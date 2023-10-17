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

  <h2 style="text-align: center; margin: 50px 0 50px 0;"><a style="opacity:60%; text-decoration: none; color:black" href="/laravelapp/public/showCalender">E-CAL</a></h2>

  <div class="addForm">
    <form action="/laravelapp/public/scheduleDetail?year={{$year}}&month={{$month}}" method="post">
      <input type="hidden" value="{{$_GET['id']}}" name="id">
      @csrf
      <div class="addItemTop">
        <div class="addItem" style="display: inline;">
          <select name="year" id="" class="Item">
            @for($i = 2019; $i <= 2030 ;$i++) <option <?php
                                                      if ($year == $i) {
                                                        echo 'selected';
                                                      } ?> value="{{$i}}">{{$i}}</option>
              @endfor

          </select>
          年

          <select name="month" id="" class="Item">
            @for($i=1;$i<=12;$i++) <option <?php if ($month == $i) {
                                              echo 'selected';
                                            } ?> value="{{$i}}">{{$i}}</option>
              @endfor
          </select>
          月

          <select name="day" id="" class="Item">
            @for($i=1;$i<=$countThisMonth;$i++) <option <?php $day = date('d');
                                                        if ($_GET['day'] == $i) {
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
          <input type="text" required max="100" class="Item" style="width: 300px;" name="schedule" value="{{$schedule->schedule}}">
        </div>

        <div class="addItem" style="display: inline;">
          場所
          <input type="text" required max="30" class="Item" name="place" value="{{$schedule->place}}">
        </div>

        <input type="submit" value="更新" class="Item" style="color:black;">
      </div>

      <div class="addItemMiddle">
        <div class="addItem" style="display: inline;">
          時刻
          <select name="start_time" id="" class="Item">
            <option <?php if ($schedule->start_time == '未定') {
                      echo 'selected';
                    } ?> value="未定">未定</option>
            <option <?php if ($schedule->start_time == '未定') {
                      echo 'selected';
                    } ?> value="終日">終日</option>
            <option <?php if ($schedule->start_time == '午前') {
                      echo 'selected';
                    } ?> value="午前">午前</option>
            <option <?php if ($schedule->start_time == '午後') {
                      echo 'selected';
                    } ?> value="午後">午後</option>
            @foreach($formattedTimes as $formattedTime)
            <option <?php if ($schedule->start_time == $formattedTime) {
                      echo "selected";
                    } ?> value="{{$formattedTime}}">{{$formattedTime}}</option>
            @endforeach
          </select>
          ～
          <select name="end_time" id="" class="Item">
            <option <?php if ($schedule->end_time == '未定') {
                      echo 'selected';
                    } ?> value="未定">未定</option>
            <option <?php if ($schedule->end_time == '未定') {
                      echo 'selected';
                    } ?> value="終日">終日</option>
            <option <?php if ($schedule->end_time == '午前') {
                      echo 'selected';
                    } ?> value="午前">午前</option>
            <option <?php if ($schedule->end_time == '午後') {
                      echo 'selected';
                    } ?> value="午後">午後</option>
            @foreach($formattedTimes as $formattedTime)
            <option <?php if ($schedule->end_time == $formattedTime) {
                      echo "selected";
                    } ?> value="{{$formattedTime}}">{{$formattedTime}}</option>
            @endforeach
          </select>

        </div>
        <div class="addItem" style="display: inline;">
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
            <input style="font-weight: bold; color:white;" type="checkbox" value="{{$memberInfo->ID}}" name="memberID[]" <?php if($schedule->memberID == $memberInfo->ID) {echo 'checked';}
            if($schedule->memberID2 == $memberInfo->ID) {echo 'checked';}
            if($schedule->memberID3 == $memberInfo->ID) {echo 'checked';}
            if($schedule->memberID4 == $memberInfo->ID) {echo 'checked';}
            if($schedule->memberID5 == $memberInfo->ID) {echo 'checked';}
            ?>>{{$memberInfo->memName}}
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
    selectElement.addEventListener('change', function() {
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

  </script>
</body>
@extends('layout.footer')

</html>