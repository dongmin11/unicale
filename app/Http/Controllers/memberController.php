<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\member;
use App\Models\color;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Logger\ConsoleLogger;

class memberController extends Controller
{
    //メンバー管理画面
  public function member()
  {
    //メンバー一覧取得
    $memberInfos = db::select(
      "SELECT
      m.ID,
      fullName,
      memName,
      memName_2,
      appear,
      appearOrder,
      note,
      c.color
      FROM members AS m
      LEFT JOIN colors AS c
      ON m.colorID = c.ID
      ORDER BY appearOrder ASC
      "
    );

    $appearOrders = [];
    for($i=0;$i<count($memberInfos);$i++){
      if($memberInfos[$i]->appearOrder != null){
        array_push($appearOrders,$memberInfos[$i]->appearOrder);
      }
    }
    $check = array_diff_assoc($appearOrders,array_unique($appearOrders));
    // dd($check);
    
    $appearOrders = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
    $colors = color::all();

    return view('member', compact('memberInfos', 'colors','appearOrders'));
  }

  
  //メンバー更新
  public function updateMember(Request $request)
  {
    $members = member::all();
    $count = count($members);

    $appearOrders = [];

    for($i = 0;$i < $count;$i++){
      //表示する項目に表示順を設定してない場合
      if($request->input('appear_'.$i) == "on"){
        if(!($request->input('appearOrder_'.$i))){
          $message = '表示する項目には表示順を選択してください';
          return redirect('/member')->with('message',$message);
        }

        array_push($appearOrders,$request->input('appearOrder_'.$i));
      }
    }
    sort($appearOrders);
    $check = array_diff_assoc($appearOrders,array_unique($appearOrders));

    if(count($check) != 0){
      $message = '表示順が重複しています';
      return redirect('/member')->with('message',$message);
    }

    //表示順の入力が連番でない場合
    $notSequentialNum = false;
    for ($i = 0; $i < max($appearOrders); $i++) {
      if(isset($appearOrders[$i])){
        if ($appearOrders[$i] != $i+1) {
          $notSequentialNum = true;
          break;
        }
      }else{
        break;
      }
  }

    if($notSequentialNum == true){
      $message = '表示順を正しく入力してください';
      return redirect('/member')->with('message',$message);
    }

    //レコード全更新
    for ($i = 0; $i < $count; $i++) {
      $appearOrder = $request->input('appearOrder_'.$i);
      $fullName = $request->input('fullName_' . $i);
      $memName = $request->input('memName_' . $i);
      $memName_2 = $request->input('memName_2_' . $i);
      $color = $request->input('color_' . $i);
      $note = $request->input('note_' . $i);  

      //表示チェック判定
      $appear=$this->appearCheck($request->input('appear_' . $i));
      $color = color::where('color', $color)->first();

      //更新
      $members = new member();
      $members->where('id', $i + 1)->update([
        'appearOrder' => $appearOrder,
        'fullName' => $fullName,
        'colorID' => $color->id,
        'memName' => $memName,
        'memName_2' => $memName_2,
        'appear' => $appear,
        'appearOrder' => $appearOrder,
        'note' => $note
      ]);
    }

    return redirect('/member');
  }

  public function addMember(Request $request)
  {
    $color = color::where('color', $request->color)->first();

    //表示チェック判定
    $appear=$this->appearCheck($request->appear);



    //メンバーレコード追加
    member::create([
      'fullName' => $request->fullName,
      'colorID' => $color->id,
      'memName' => $request->memName,
      'memName_2' => $request->memName_2,
      'appearOrder' => $request->appearOrder,
      'appear' => $appear,
      'note' => $request->note
    ]);

    return redirect('/member');
  }

    //表示チェック判定
    public function appearCheck($appear)
    {
      if ($appear == 'on') {
        $appear = 1;
      } else {
        $appear = 0;
      }
  
      return $appear;
    }

    public function updateMemberAppear(Request $request)
    {
      $checked = $request->input('checked');
      $memberID = $request->input('memberID');
      member::where('id',$memberID)
      ->update([
        'appear' => $checked,
      ]);
      return response()->json(['message' => 'updated']);
    }

    public function updateAppearOrder(Request $request)
    {
      $memberID = $request->input('memberID');
      $updateAppearOrder = member::where('id',$memberID)->value('appearOrder');
      member::where('id',$memberID)->update([
        'appearOrder' => $updateAppearOrder+1
      ]);
      return response()->json(['message' => 'updated']);
    }
}
