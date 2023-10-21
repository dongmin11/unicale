<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="category.css">
  <script>
        var categories = <?php echo json_encode($categoryInfos); ?>;
    </script>
</head>

<body>
  <h2 style="text-align: center; margin: 50px 0 50px 0;"><a style="opacity:60%; text-decoration: none; color:black" href="/unical/unicale/public/showCalender">E-CAL</a></h2>
  <h3 class="title">カテゴリ管理</h3>
  <div class="updateMember">
    <form action="{{route('updateCategory')}}" method="post">
      @csrf
      <table class="memberIndex">
        <tr>
          <th class="memberHead">カテゴリ</th>
          <th class="memberHead">アイコン色</th>
          <th class="memberHead" style="width:70px;">表示する</th>
        </tr>
        @foreach($categoryInfos as $key=>$categoryInfo)
        <tr>
          <input type="hidden" value="{{$categoryInfo->id}}" name="ID_{{$key}}">
          <td><input name="category_{{$key}}" type="text" style="width:50%;" value="{{$categoryInfo->category}}"></td>
          <td><select style="padding:5px; color:black; background-color:<?php echo $categoryInfo->color; ?>;" name="color_{{$key}}">
              <option>選択してください</option>
              @foreach($colors as $color)
              <option style="background-color:<?php echo $color;?>;"
              <?php if($categoryInfo->color == $color){echo 'selected';} ?>
              >{{$color}}</option>
              @endforeach

            </select></td>
          <td><input type="checkbox" name="appear_{{$key}}"
          <?php if($categoryInfo->appear == 1){echo 'checked';} ?>
          ></td>
        </tr>
        @endforeach
      </table>
      <div class="updateButtonItem">
        <input class="updateButton" type="submit" value="更新">
      </div>

  </div>
  </form>

  <div class="addMember">
    <form action="{{route('addCategory')}}" method="post">
      @csrf
      <table class="memberIndex">
        <tr>
          <th class="memberHead">カテゴリ</th>
          <th class="memberHead">アイコン色</th>
          <th class="memberHead" style="width:70px;">表示する</th>
        </tr>

        <tr>
          <td><input name="category" typstyle="width:50%;" type="text"></td>
          <td>
            <select style="padding:5px" name="color" id="addColor">
            <option>選択してください</option>
              <option style="background-color:aliceblue;">aliceblue</option>
              <option style="background-color:bisque;">bisque</option>
              <option style="background-color:darkkhaki;">darkkhaki</option>
              <option style="background-color:slategrey;">slategrey</option>
              <option style="background-color:salmon;">salmon</option>
              <option style="background-color:darkseagreen;">darkseagreen</option>
              <option style="background-color:khaki;">khaki</option>

            </select>
          </td>
          <td><input type="checkbox" name="appear" checked></td>
        </tr>
      </table>
      <div class="addButtonItem">
        <input class="addButton" type="submit" value="追加">
      </div>
    </form>
  </div>

  <script>
    // 各<select>要素のchangeイベントを監視
    var selectElements = document.querySelectorAll('select');

    selectElements.forEach(function(selectElement) {
      selectElement.addEventListener('change', function() {
        var selectedColor = selectElement.value; // 選択された<option>の値を取得
        selectElement.style.backgroundColor = selectedColor; // 背景色を変更
      });
    });

    // <select>要素のIDを取得
    var selectElement = document.getElementById('addColor');

    // <select>要素のchangeイベントを監視
    selectElement.addEventListener('change', function() {
      // 選択された<option>の値を取得
      var selectedColor = selectElement.value;

      // <select>要素の背景色を選択された色に設定
      selectElement.style.backgroundColor = selectedColor;
    });
  </script>
</body>

@extends('layout.footer')

</html>