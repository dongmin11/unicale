<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use Illuminate\Support\Facades\DB;

class categoryController extends Controller
{
    //カテゴリ管理画面
  public function category()
  {
    //カテゴリ一覧取得
    $categoryInfos = db::select(
      "SELECT
      c.id,
      c.category,
      c.color,
      c.appear
      FROM categories AS c
      "
    );

    //カテゴリ別背景色設定
    $colors = [
      'aliceblue',
      'bisque',
      'darkkhaki',
      'slategrey',
      'salmon',
      'darkseagreen',
      'khaki'
    ];

    return view('category', compact('categoryInfos', 'colors'));
  }

  public function addCategory(Request $request)
  {
    //表示チェック判定
    $appear=$this->appearCheck($request->appear);

    //カテゴリ追加
    category::create([
      'category' => $request->category,
      'color' => $request->color,
      'appear' => $appear
    ]);

    return redirect('/category');
  }

  public function updateCategory(Request $request)
  {
    $categories = category::all();
    $count = count($categories);

    //カテゴリレコード全更新
    for ($i = 0; $i < $count; $i++) {
      $category = $request->input('category_' . $i);
      $color = $request->input('color_' . $i);

      //表示チェック判定
      $appear=$this->appearCheck($request->input('appear_' . $i));

      //更新
      $categories = new category();
      $categories->where('id', $i + 1)->update([
        'category' => $category,
        'color' => $color,
        'appear' => $appear,
      ]);
    }

    return redirect('/category');
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
