<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\member;
use App\Models\color;
use Illuminate\Support\Facades\DB;

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
      "
    );
    
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
      if($request->input('appear_'.$i) == "on"){
        if(!($request->input('appearOrder_'.$i))){
          $message = '表示する項目には表示順を選択してください';
          return redirect('/member')->with('message',$message);
        }

        array_push($appearOrders,$request->input('appearOrder_'.$i));
      }
    }
    $check = array_diff_assoc($appearOrders,array_unique($appearOrders));
    if(isset($check)){
      $message = '表示順が重複しています';
      return redirect('/member')->with('message',$message);
    }

    //レコード全更新
    for ($i = 0; $i < $count; $i++) {
      $fullName = $request->input('fullName_' . $i);
      $memName = $request->input('memName_' . $i);
      $memName_2 = $request->input('memName_2_' . $i);
      $color = $request->input('color_' . $i);
      $note = $request->input('note_' . $i);
      $appearOrder = $request->input('appearOrder_'.$i);

      //表示チェック判定
      $appear=$this->appearCheck($request->input('appear_' . $i));
      $color = color::where('color', $color)->first();

      //更新
      $members = new member();
      $members->where('id', $i + 1)->update([
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
}
