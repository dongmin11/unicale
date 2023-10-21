<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
  <script>
    var categories = <?php echo json_encode($categories); ?>;
  </script>
</head>

<body>
  <h2 style="text-align: center; margin: 50px 0 50px 0;"><a style="opacity:60%; text-decoration: none; color:black" href="/unical/unicale/public/showCalender">E-CAL</a></h2>
  <div class="addForm">
    <form action="/unical/unicale/public/showCalender?year={{$year}}&month={{$month}}" method="post">
      @csrf
      <div class="addItemTop">
        <div class="addItem" style="display: inline;">
          <select name="year" id="" class="Item">
            @for($i = 2019; $i <= 2030 ;$i++) <option <?php if ($year == $i) {
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
                                                        if (($day == $i) && ($month == date('m')) && ($year == date('Y'))) {
                                                          echo 'selected';
                                                        } ?> value="{{$i}}">{{$i}}</option>
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

        <input type="submit" value="追加" class="Item">
      </div>

      <div class="addItemMiddle">
        <div class="addItem" style="display: inline;">
          時刻
          <select name="start_time" id="start_time" class="Item">
            <option value="未定">未定</option>
            <option value="終日">終日</option>
            <option value="午前">午前</option>
            <option value="午後">午後</option>
            @foreach($formattedTimes as $formattedTime)
            <option value="{{$formattedTime}}">{{$formattedTime}}</option>
            @endforeach
          </select>
          ～
          <select name="end_time" id="end_time" class="Item">
            <option value="未定">未定</option>
            <option value="終日">終日</option>
            <option value="午前">午前</option>
            <option value="午後">午後</option>
            @foreach($formattedTimes as $formattedTime)
            <option value="{{$formattedTime}}">{{$formattedTime}}</option>
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
          @foreach($members as $member)
          <label style="background-color:<?php echo $member->color; ?>;border-radius:5px; color:white; font-weight:bold" ; for="" class="Item">
            <input type="checkbox" value="{{$member->id}}" name="memberID[]">{{$member->memName}}
          </label>
          @endforeach
        </div>
      </div>

    </form>
  </div>


  <form class="dayPoint" action="{{route('preCal')}}" method="get">
    <input type="hidden" value="{{$month}}" name="month">
    <input type="hidden" value="{{$year}}" name="year">
    <input type="submit" value="前月" class="dayButton">
  </form>

  <form class="dayPoint" action="{{route('showCal')}}" method="get">
    <input type="submit" value="今月" class="dayButton">
  </form>

  <form class="dayPoint" action="{{route('nextCal')}}" method="get">
    <input type="hidden" value="{{$month}}" name="month">
    <input type="hidden" value="{{$year}}" name="year">
    <input type="submit" value="来月" class="dayButton">
  </form>

  <form class="tranceDate" action="">
    <select name="year" id="" class="Item">
      @for($i = 2019; $i <= 2030 ;$i++) <option <?php if ($year == $i) {
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

    <input type="submit" value="適応">
  </form>

  <div>
    <table style="">
      <h2>{{$year}}年{{$month}}月</h2>
      <tr>
        @foreach($daysOfWeek as $day)
        <th>{{$day}}</th>
        @endforeach
      </tr>
      <tr>
        @for( $i=0 ; $i<count($thisMonth) ; $i++) <td style="width: 171px;" <?php
                                                                            if ($i < $countLastDay) {
                                                                              echo "class='opacity';";
                                                                            } elseif ($i >= count($thisMonth) - $countNextDay) {
                                                                              echo "class='opacity';";
                                                                            }
                                                                            ?>>
          <div value="{{$thisMonth[$i]}}" <?php
                                          if ($i < $countLastDay) {
                                            foreach ($lastMonthHoliday as $day) {
                                              if (substr($day, 8, 2) == $thisMonth[$i]) {
                                                echo "class='saturday'";
                                              }
                                            }
                                          }

                                          if ($i >= $countLastDay && $i < $countLastDay + $countThisMonth) {
                                            foreach ($thisMonthHoliday as $day) {
                                              if (substr($day, 8, 2) == $thisMonth[$i]) {
                                                echo "class='saturday'";
                                              }
                                            }
                                          }

                                          if ($i >= $countLastDay + $countThisMonth) {
                                            foreach ($nextMonthHoliday as $day) {
                                              if (substr($day, 8, 2) == $thisMonth[$i]) {
                                                echo "class='saturday'";
                                              }
                                            }
                                          }


                                          if ($firstOfWeek == 0) {
                                            echo "class='saturday'";
                                          } elseif ($firstOfWeek == 6) {
                                            echo "class='sunday'";
                                          } else {
                                            echo "class='calDay'";
                                          } ?>><a href="/unical/unicale/public/schedule_day?year={{$year}}&month={{$month}}&day={{$thisMonth[$i]}}&countThisMonth={{$countThisMonth}}" class="scheduleDate">{{$thisMonth[$i]}}</a>
          </div>

          @if($i<$countLastDay) @foreach($showHolidays as $date=> $holiday)
            @if(substr($date,5,2) == str_pad($lastMonth,2,0,STR_PAD_LEFT) && (substr($date,8,2) == str_pad($thisMonth[$i],2,0,STR_PAD_LEFT)))
            <p style="color:red; font-weight:bold;">{{$holiday}}</p>
            @endif
            @endforeach

            @foreach($scheduleInfos as $scheduleInfo)
            @if($scheduleInfo->day == $thisMonth[$i] &&($lastMonth == $scheduleInfo->month))
            <a style=" color:black; text-decoration:none;" href="scheduleDetail?<?php echo 'id=' . $scheduleInfo->scheduleID . '&year=' . $year . '&month=' . $month . '&day=' . $thisMonth[$i] . '&countThisMonth=' . count($thisMonth); ?>">
              <div class="scheduleBody" style="background-color:<?php if (isset($scheduleInfo->color)) {
                                                                  echo $scheduleInfo->color;
                                                                } ?>;">
                <p style="font-weight: bold; text-decoration: underline;">
                  {{$scheduleInfo->start_time}}～
                  {{$scheduleInfo->end_time}}
                </p>
                <p style="text-decoration: underline;">
                  {{$scheduleInfo->schedule}}({{$scheduleInfo->place}})
                </p>
                <span <?php if (isset($scheduleInfo->member1_memName2)) {
                        echo "class='memberName_2'";
                      }
                      echo "style=background-color:" . $scheduleInfo->member1_color . ";"; ?>>
                  {{$scheduleInfo->member1_memName2}}
                </span>
                <span <?php if (isset($scheduleInfo->member2_memName2)) {
                        echo "class='memberName_2'";
                      }
                      echo "style=background-color:" . $scheduleInfo->member2_color . ";"; ?>>
                  {{$scheduleInfo->member2_memName2}}
                </span>
                <span <?php if (isset($scheduleInfo->member3_memName2)) {
                        echo "class='memberName_2'";
                      }
                      echo "style=background-color:" . $scheduleInfo->member3_color . ";"; ?>>
                  {{$scheduleInfo->member3_memName2}}
                </span>
                <span <?php if (isset($scheduleInfo->member4_memName2)) {
                        echo "class='memberName_2'";
                      }
                      echo "style=background-color:" . $scheduleInfo->member4_color . ";"; ?>>
                  {{$scheduleInfo->member4_memName2}}
                </span>
                <span <?php if (isset($scheduleInfo->member5_memName2)) {
                        echo "class='memberName_2'";
                      }
                      echo "style=background-color:" . $scheduleInfo->member5_color . ";"; ?>>
                  {{$scheduleInfo->member5_memName2}}
                </span>
              </div>
            </a>
            @endif
            @endforeach
            @endif

            @if($i>=$countLastDay && $i < $countLastDay+$countThisMonth) @foreach($showHolidays as $date=> $holiday)
              @if(substr($date,5,2) == str_pad($month,2,0,STR_PAD_LEFT) && (substr($date,8,2) == str_pad($thisMonth[$i],2,0,STR_PAD_LEFT)))
              <p style="color:red; font-weight:bold;">{{$holiday}}</p>
              @endif
              @endforeach

              @foreach($scheduleInfos as $scheduleInfo)
              @if($scheduleInfo->day == $thisMonth[$i] &&($month == $scheduleInfo->month))
              <a style=" color:black; text-decoration:none;" href="scheduleDetail?<?php echo 'id=' . $scheduleInfo->scheduleID . '&year=' . $year . '&month=' . $month . '&day=' . $thisMonth[$i] . '&countThisMonth=' . count($thisMonth); ?>">
                <div class="scheduleBody" style="background-color:<?php if (isset($scheduleInfo->color)) {
                                                                    echo $scheduleInfo->color;
                                                                  } ?>;">
                  <p style="font-weight: bold; text-decoration: underline;">
                    {{$scheduleInfo->start_time}}～
                    {{$scheduleInfo->end_time}}
                  </p>
                  <p style="text-decoration: underline;">
                    {{$scheduleInfo->schedule}}({{$scheduleInfo->place}})
                  </p>
                  <span <?php if (isset($scheduleInfo->member1_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member1_color . ";"; ?>>
                    {{$scheduleInfo->member1_memName2}}
                  </span>
                  <span <?php if (isset($scheduleInfo->member2_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member2_color . ";"; ?>>
                    {{$scheduleInfo->member2_memName2}}
                  </span>
                  <span <?php if (isset($scheduleInfo->member3_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member3_color . ";"; ?>>
                    {{$scheduleInfo->member3_memName2}}
                  </span>
                  <span <?php if (isset($scheduleInfo->member4_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member4_color . ";"; ?>>
                    {{$scheduleInfo->member4_memName2}}
                  </span>
                  <span <?php if (isset($scheduleInfo->member5_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member5_color . ";"; ?>>
                    {{$scheduleInfo->member5_memName2}}
                  </span>
                </div>
              </a>
              @endif
              @endforeach
              @endif
              <!-- "2023-01-01" => "元日"
              "2023-01-02" => "休日 元日" -->
              @if($i>=$countLastDay + $countThisMonth)
              @foreach($showHolidays as $date => $holiday)
              @if(substr($date,5,2) == str_pad($nextMonth,2,0,STR_PAD_LEFT) && (substr($date,8,2) == str_pad($thisMonth[$i],2,0,STR_PAD_LEFT)))
              <p style="color:red; font-weight:bold;">{{$holiday}}</p>
              @endif
              @endforeach

              @foreach($scheduleInfos as $scheduleInfo)
              @if($scheduleInfo->day == $thisMonth[$i] &&($nextMonth == $scheduleInfo->month))
              <a style=" color:black; text-decoration:none;" href="scheduleDetail?<?php echo 'id=' . $scheduleInfo->scheduleID . '&year=' . $year . '&month=' . $month . '&day=' . $thisMonth[$i] . '&countThisMonth=' . count($thisMonth); ?>">
                <div class="scheduleBody" style="background-color:<?php if (isset($scheduleInfo->color)) {
                                                                    echo $scheduleInfo->color;
                                                                  } ?>;">
                  <p style="font-weight: bold; text-decoration: underline;">
                    {{$scheduleInfo->start_time}}～
                    {{$scheduleInfo->end_time}}
                  </p>
                  <p style="text-decoration: underline;">
                    {{$scheduleInfo->schedule}}({{$scheduleInfo->place}})
                  </p>
                  <span <?php if (isset($scheduleInfo->member1_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member1_color . ";"; ?>>
                    {{$scheduleInfo->member1_memName2}}
                  </span>
                  <span <?php if (isset($scheduleInfo->member2_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member2_color . ";"; ?>>
                    {{$scheduleInfo->member2_memName2}}
                  </span>
                  <span <?php if (isset($scheduleInfo->member3_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member3_color . ";"; ?>>
                    {{$scheduleInfo->member3_memName2}}
                  </span>
                  <span <?php if (isset($scheduleInfo->member4_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member4_color . ";"; ?>>
                    {{$scheduleInfo->member4_memName2}}
                  </span>
                  <span <?php if (isset($scheduleInfo->member5_memName2)) {
                          echo "class='memberName_2'";
                        }
                        echo "style=background-color:" . $scheduleInfo->member5_color . ";"; ?>>
                    {{$scheduleInfo->member5_memName2}}
                  </span>
                </div>
              </a>
              @endif
              @endforeach
              @endif

              </td>
              <?php
              if ($firstOfWeek == 6) {
                echo "</tr><tr>";
                $firstOfWeek = 0;
              } else {
                $firstOfWeek++;
              }
              ?>
              @endfor
      </tr>
    </table>
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

    const dayHead = document.querySelectorAll('calDay');

    // 開始時刻の<select>要素を取得
    var startTimeSelect = document.getElementById('start_time');
    // 終了時刻の<select>要素を取得
    var endTimeSelect = document.getElementById('end_time');

    // 開始時刻が変更されたときに呼び出される関数
    startTimeSelect.addEventListener('change', function() {
      // 選択された開始時刻の値を取得
      var selectedStartTime = startTimeSelect.value;

      if (selectedStartTime == "終日") {
        endTimeSelect.value = "終日";
      }

      // 選択された開始時刻以前の終了時刻を無効化
      for (var i = 0; i < endTimeSelect.options.length; i++) {
        var endTimeOption = endTimeSelect.options[i];
        if (endTimeOption.value <= selectedStartTime) {
          endTimeOption.disabled = true;
          endTimeOption.style.backgroundColor = "gray";
          if(selectedStartTime == "終日"){
            endTimeSelect.options[1].disabled = false;
            endTimeSelect.options[1].backgroundColor = "white";
          }
        } else {
          endTimeOption.disabled = false;
          endTimeOption.style.backgroundColor = "white";
        }
      }

      if (selectedStartTime === "午前") {
        for (var i = 0; i < endTimeSelect.options.length; i++) {
          var endTimeOption = endTimeSelect.options[i];
          if (endTimeOption.value <= "12:00") {
            endTimeOption.disabled = true;
            endTimeOption.style.backgroundColor = "gray";
          } else {
            endTimeOption.disabled = false;
            endTimeOption.style.backgroundColor = "white";

          }
        }
      }

      if (selectedStartTime === "午後") {
        for (var i = 0; i < endTimeSelect.options.length; i++) {
          var endTimeOption = endTimeSelect.options[i];
          if (endTimeOption.value <= "12:00" || endTimeOption.value == "午前" ) {
            endTimeOption.disabled = true;
            endTimeOption.style.backgroundColor = "gray";
          } else {
            endTimeOption.disabled = false;
            endTimeOption.style.backgroundColor = "white";

          }
        }
      }
      
    });
  </script>
</body>
@extends('layout.footer')

</html>