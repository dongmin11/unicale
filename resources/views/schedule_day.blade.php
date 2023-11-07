<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="detail.css">
  <script>
        var categories = <?php echo json_encode($categories); ?>;
    </script>
</head>

<body>

  <h2 style="text-align: center; margin: 50px 0 50px 0;"><a style="opacity:60%; text-decoration: none; color:black" href="/unicale/public/showCalender">E-CAL</a></h2>

  <div class="addForm">
    <form action="/unicale/public/showCalender?year={{$year}}&month={{$month}}" method="post">
      @csrf
      <div class="addItemTop">
        <div class="addItem" style="display: inline;">
          <select name="year" id="" class="Item">
            @for($i = 2019; $i <= 2030 ;$i++) <option <?php
                                                      if ($_GET['year'] == $i) {
                                                        echo 'selected';
                                                      } ?> value="{{$i}}">{{$i}}</option>
              @endfor

          </select>
          年

          <select name="month" id="" class="Item">
            @for($i=1;$i<=12;$i++) <option <?php if ($_GET['month'] == $i) {
                                              echo 'selected';
                                            } ?> value="{{$i}}">{{$i}}</option>
              @endfor
          </select>
          月

          <select name="day" id="" class="Item">
            @for($i=1;$i<=$_GET['countThisMonth'];$i++) <option <?php $day = date('d');
                                                        if ($_GET['day'] == $i)  {
                                                          echo 'selected';
                                                        } ?> value="{{$i}}" <?php if ($i == $_GET['day']) {
                                                                              echo 'selected';
                                                                            } ?>>{{$i}}</option>
              @endfor
          </select>
          日
        </div>

        <div class="addItem" style="display: inline;">
          用事
          <input type="text" required max="100" class="Item" style="width: 300px;" name="schedule">
        </div>

        <div class="addItem" style="display: inline;">
          場所
          <input type="text" required max="30" class="Item" name="place">
        </div>

        <input type="submit" value="追加" class="Item" style="color:black;">
      </div>

      <div class="addItemMiddle">
        <div class="addItem" style="display: inline;">
          時刻
          <select name="start_time" id="" class="Item">
            <option value="未定">未定</option>
            <option  value="終日">終日</option>
            <option  value="午前">午前</option>
            <option  value="午後">午後</option>
            @foreach($formattedTimes as $formattedTime)
            <option  value="{{$formattedTime}}">{{$formattedTime}}</option>
            @endforeach
          </select>
          ～
          <select name="end_time" id="" class="Item">
            <option  value="未定">未定</option>
            <option  value="終日">終日</option>
            <option  value="午前">午前</option>
            <option  value="午後">午後</option>
            @foreach($formattedTimes as $formattedTime)
            <option  value="{{$formattedTime}}">{{$formattedTime}}</option>
            @endforeach
          </select>

        </div>
        カテゴリ
          <select class="Item" name="category" id="addColor">
          <option>選択してください</option>
            @foreach($categories as $category)
            <option style="background-color: <?php echo $category->color; ?>;" value="{{$category->category}}">{{$category->category}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="addItemBottom">
        <div class="addItem">
          @foreach($memberInfos as $memberInfo)
          <label for="" class="Item" style="background-color:{{$memberInfo->color}}; color:white;">
            <input style="color:white; font-weight:bold;" type="checkbox" value="{{$memberInfo->ID}}" name="memberID[]" >{{$memberInfo->memName}}
          </label>
          @endforeach
        </div>
      </div>
      <p>詳細:</p>
      <textarea name="detail" id="" cols="30" rows="10" style="width: 70%;"></textarea>
    </form>
  </div>

  <script>
  // <select>要素のIDを取得
  var selectElement = document.getElementById('addColor');

  // <select>要素のchangeイベントを監視
  selectElement.addEventListener('change', function () {
    // 選択された<option>の値を取得
    var selectedCategory = selectElement.value;
    
    // 選択されたカテゴリをJavaScriptオブジェクトから検索
    var selectedColor = '';
    for (var i = 0; i < categories.length; i++) {
      if (categories[i].category === selectedCategory) {
        selectedColor = categories[i].color;
        break;
      }
    }

    // <select>要素の背景色を選択された色に設定
    selectElement.style.backgroundColor = selectedColor;
  });
</script>
</body>
@extends('layout.footer')

</html>