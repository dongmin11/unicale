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
      note,
      c.color
      FROM members AS m
      LEFT JOIN colors AS c
      ON m.colorID = c.ID
      "
    );
    
    $colors = color::all();

    return view('member', compact('memberInfos', 'colors'));
  }

  //メンバー更新
  public function updateMember(Request $request)
  {
    $members = member::all();
    $count = count($members);

    //レコード全更新
    for ($i = 0; $i < $count; $i++) {
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
        'fullName' => $fullName,
        'colorID' => $color->id,
        'memName' => $memName,
        'memName_2' => $memName_2,
        'appear' => $appear,
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
