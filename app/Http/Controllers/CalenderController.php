<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\schedule;
use Illuminate\Support\Facades\DB;
use App\Models\category;

class CalenderController extends Controller
{

  public $counLastDay = "";

  public $countNextDay = "";

  public $countThisMonth = "";

  //GoogleカレンダーAPIから祝日を取得
  function getHolidays($year)
  {

    $api_key = 'AIzaSyAU2Lyxe_5aVlwDn3T28lwIKq-vZghM7XY';
    $holidays = array();
    $holidays_id = 'japanese__ja@holiday.calendar.google.com'; // Google 公式版日本語
    //$holidays_id = 'japanese@holiday.calendar.google.com'; // Google 公式版英語
    //$holidays_id = 'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com'; // mozilla.org版 ←2017年5月時点で文字化け発生中（山の日）
    $url = sprintf(
      'https://www.googleapis.com/calendar/v3/calendars/%s/events?' .
        'key=%s&timeMin=%s&timeMax=%s&maxResults=%d&orderBy=startTime&singleEvents=true',
      $holidays_id,
      $api_key,
      $year . '-01-01T00:00:00Z', // 取得開始日
      $year . '-12-31T00:00:00Z', // 取得終了日
      150 // 最大取得数
    );

    if ($results = file_get_contents($url, true)) {
      //JSON形式で取得した情報を配列に格納
      $results = json_decode($results);
      //年月日をキー、祝日名を配列に格納
      foreach ($results->items as $item) {
        $date = strtotime((string) $item->start->date);
        $title = (string) $item->summary;
        $holidays[date('Y-m-d', $date)] = $title;
      }
      //祝日の配列を並び替え
      ksort($holidays);
    }
    return $holidays;
  }


  public function makeSql($year, $month)
  {
    $sql = "SELECT
  s.id as scheduleID
  , s.memberID
  , s.year
  , s.month
  , s.day
  , s.schedule
  , s.place
  , s.start_time
  , s.end_time
  , m1.memName_2 AS member1_memName2
  , c1.color AS member1_color
  , m2.memName_2 AS member2_memName2
  , c2.color AS member2_color
  , m3.memName_2 AS member3_memName2
  , c3.color AS member3_color
  , m4.memName_2 AS member4_memName2
  , c4.color AS member4_color
  , m5.memName_2 AS member5_memName2
  , c5.color AS member5_color
  , ca.category
  , ca.color
FROM
  schedules AS s 
  LEFT JOIN ( 
      SELECT
          ID
          , memName_2
          , colorID 
      FROM
          members
  ) AS m1 
      ON s.memberID = m1.ID 
  LEFT JOIN (SELECT ID, color FROM colors) AS c1 
      ON m1.colorID = c1.ID
      LEFT JOIN ( 
      SELECT
          ID
          , memName_2
          , colorID 
      FROM
          members
  ) AS m2
      ON s.memberID2 = m2.ID 
  LEFT JOIN (SELECT ID, color FROM colors) AS c2 
      ON m2.colorID = c2.ID
          LEFT JOIN ( 
      SELECT
          ID
          , memName_2
          , colorID 
      FROM
          members
  ) AS m3
      ON s.memberID3 = m3.ID 
  LEFT JOIN (SELECT ID, color FROM colors) AS c3 
      ON m3.colorID = c3.ID
       LEFT JOIN ( 
      SELECT
          ID
          , memName_2
          , colorID 
      FROM
          members
  ) AS m4
      ON s.memberID4 = m4.ID 
  LEFT JOIN (SELECT ID, color FROM colors) AS c4 
      ON m4.colorID = c4.ID
               LEFT JOIN ( 
      SELECT
          ID
          , memName_2
          , colorID 
      FROM
          members
  ) AS m5
      ON s.memberID5 = m5.ID 
  LEFT JOIN (SELECT ID, color FROM colors) AS c5 
      ON m5.colorID = c5.ID
  LEFT JOIN categories AS ca
      ON s.categoryID = ca.id
WHERE
  s.year = $year
  AND s.month = $month
  OR s.month = $month + 1
  OR s.month = $month - 1
  AND ca.appear = 1
";
    return $sql;
  }


  public function showCalender()
  {
    $countNextDay = "";
    $counLastDay = "";

    //メンバーレコード取得
    $members = db::select(
      "SELECT
      members.id,
      fullName,
      memName,
      memName_2,
      note,
      colors.color
      FROM members
      LEFT JOIN colors
      ON members.colorID = colors.ID
      WHERE deleteFlg = 0
      AND appear = 1;
      "
    );

    //開始時刻から30分ごとの時刻を取得
    $formattedTimes = $this->makeFormattedTime();

    //カレンダー表示位置のデフォルトは現在日時基準
    if (isset($_GET['month']) && isset($_GET['year'])) {
      $month = $_GET['month'];
      $year = $_GET['year'];
      $day = date($year . "-" . $month . "-d");
    } else {
      $day = date('Y-m-d');
      $month = date("m");
      $year = date("Y");
    }

    $Holidays = $this->getHolidays($year);
    $showHolidays = [];

    if($month-1 == 0){
      $lastMonth = 12;
    }else{
      $lastMonth = $month -1;
    }

    if($month + 1 == 13){
      $nextMonth = 1;
    }else{
      $nextMonth = $month+1;
    }

    foreach ($Holidays as $date => $Holiday) {
      if (substr($date, 5, 2) == str_pad($month, 2, 0, STR_PAD_LEFT) or substr($date, 5, 2) == str_pad($lastMonth, 2, 0, STR_PAD_LEFT) or substr($date, 5, 2) == str_pad($nextMonth, 2, 0, STR_PAD_LEFT)) {
        $showHolidays[$date] = $Holiday;
      }
    }

    $firstOfWeek = 0;

    $thisMonth = $this->makeCal($day);
    $countThisMonth = $this->countThisMonth;

    $daysOfWeek = ["日", "月", "火", "水", "木", "金", "土"];

    $countLastDay = $this->counLastDay;

    $countNextDay = $this->countNextDay;

    $scheduleInfos = DB::select(
      $this->makeSql($year, $month)
    );

    $categories = category::all();

    $lastMonthHoliday = [];
    $thisMonthHoliday = [];
    $nextMonthHoliday = [];

    // dd($thisMonth);
    // "2024-04-29" => "昭和の日"
    foreach ($showHolidays as $date => $holiday) {
      foreach ($thisMonth as $day) {
        for($i=0; $i<$countLastDay;$i++)
        {
        //月初の数は大きいので$countLastDayより小さくなることは無いためこの部分の処理が通ってない
        if (substr($date, 8, 2) == str_pad($day, 2, 0, STR_PAD_LEFT) && substr($date, 5, 2) == str_pad($lastMonth, 2, 0, STR_PAD_LEFT)) { 
          array_unshift($lastMonthHoliday, $date);
          $lastMonthHoliday = array_unique($lastMonthHoliday);
        }
        }

        if ($day >= $countLastDay && $day <= $countLastDay + $countThisMonth && substr($date, 8, 2) == str_pad($day, 2, 0, STR_PAD_LEFT) && substr($date, 5, 2) == str_pad($month, 2, 0, STR_PAD_LEFT)) {
          array_unshift($thisMonthHoliday, $date);
        }

        //月初の数は小さいので$countLastDay + $countThisMonthより大きくなることは無いためこの部分の処理が通ってない
        for($i=0; $i<$countLastDay + $countThisMonth;$i++)
        {
          if (substr($date, 8, 2) == str_pad($day, 2, 0, STR_PAD_LEFT) && substr($date, 5, 2) == str_pad($nextMonth, 2, 0, STR_PAD_LEFT)) {
            array_unshift($nextMonthHoliday,$date);
            $nextMonthHoliday=array_unique($nextMonthHoliday);
          }
        }

      }
    }

    return view('calender', compact('thisMonth', 'daysOfWeek', 'firstOfWeek', 'year', 'month', 'countNextDay', 'countLastDay', 'formattedTimes', 'members', 'countThisMonth', 'scheduleInfos', 'categories', 'showHolidays','thisMonthHoliday','nextMonthHoliday','lastMonthHoliday','nextMonth','lastMonth'));
  }

  //先月のカレンダー表示
  public function preCal(Request $request)
  {
    $year = $request->year;
    $month = $request->month;
    $month--;
    if ($month == 0) {
      $year = $year - 1;
      $month = 12;
      $param = "year={$year}&month={$month}";
    } else {
      $param = "year={$year}&month={$month}";
    }
    return redirect("/showCalender?{$param}");
  }

  //来月のカレンダー表示
  public function nextCal(Request $request)
  {
    $year = $request->year;
    $month = $request->month;
    $month++;
    if ($month == 13) {
      $year = $year + 1;
      $month = 1;
      $param = "year={$year}&month={$month}";
    } else {
      $param = "year={$year}&month={$month}";
    }
    return redirect("/showCalender?{$param}");
  }

  //スケジュール追加
  public function addSchedule(Request $request)
  {
    //送られてきたメンバー取得（最大5つ）
    $memberID = [];
    for ($i = 0; $i < 5; $i++) {
      if (!(isset($request->memberID[$i]))) {
        array_push($memberID, "");
      } else {
        array_push($memberID, $request->memberID[$i]);
      }
    }

    //該当カテゴリ取得
    $category = category::where('category', $request->category)->first();

    if($category){
    //レコード追加
    schedule::create([
      'memberID' => $memberID[0],
      'memberID2' => $memberID[1],
      'memberID3' => $memberID[2],
      'memberID4' => $memberID[3],
      'memberID5' => $memberID[4],
      'categoryID' => $category->id,
      'year' => $request->year,
      'month' => $request->month,
      'day' => $request->day,
      'schedule' => $request->schedule,
      'place' => $request->place,
      'start_time' => $request->start_time,
      'end_time' => $request->end_time,
    ]);
    }else{
          //レコード追加
    schedule::create([
      'memberID' => $memberID[0],
      'memberID2' => $memberID[1],
      'memberID3' => $memberID[2],
      'memberID4' => $memberID[3],
      'memberID5' => $memberID[4],
      'year' => $request->year,
      'month' => $request->month,
      'day' => $request->day,
      'schedule' => $request->schedule,
      'place' => $request->place,
      'start_time' => $request->start_time,
      'end_time' => $request->end_time,
    ]);
    }


    return redirect("/showCalender?year=".$_GET['year']."&month=".$_GET['month']);
  }

  public function updateSchedule(Request $request)
  {
    //送られてきたメンバー取得（最大５つ）
    $memberID = [];
    for ($i = 0; $i < 5; $i++) {
      if (!(isset($request->memberID[$i]))) {
        array_push($memberID, "");
      } else {
        array_push($memberID, $request->memberID[$i]);
      }
    }

    
    $category = category::where('category', $request->category)->first();

    if($category)
    {
    //レコード更新
    schedule::find($request->id)->update([
      'year' => $request->year,
      'month' => $request->month,
      'day' => $request->day,
      'schedule' => $request->schedule,
      'start_time' => $request->start_time,
      'end_time' => $request->end_time,
      'memberID' => $memberID[0],
      'memberID2' => $memberID[1],
      'memberID3' => $memberID[2],
      'memberID4' => $memberID[3],
      'memberID5' => $memberID[4],
      'categoryID' => $category->id,
      'detail' => $request->detail
    ]);
    }else{
          //レコード更新
    schedule::find($request->id)->update([
      'year' => $request->year,
      'month' => $request->month,
      'day' => $request->day,
      'schedule' => $request->schedule,
      'start_time' => $request->start_time,
      'end_time' => $request->end_time,
      'memberID' => $memberID[0],
      'memberID2' => $memberID[1],
      'memberID3' => $memberID[2],
      'memberID4' => $memberID[3],
      'memberID5' => $memberID[4],
      'detail' => $request->detail
    ]);
    }

    return redirect("/showCalender?year=".$_GET['year']."&month=".$_GET['month']);
  }


  public function scheduleDetail(Request $request)
  {
    //開始時刻から30分ごとの時刻を取得
    $formattedTimes = $this->makeFormattedTime();

    //押下されたスケジュールレコード取得
    $schedule = schedule::where('id', $_GET['id'])->first();

    //押下されたメンバー情報取得
    $memberID = $schedule->memberID;
    $memberInfos = db::select(
      "SELECT
      m.ID,
      fullName,
      memName,
      memName_2,
      note,
      c.color
      FROM members AS m
      LEFT JOIN colors AS c
      ON m.colorID = c.ID
      WHERE appear = 1
      "
    );

    //該当月の日数取得
    $countThisMonth = $_GET['countThisMonth'];

    $year = $_GET['year'];

    $month = $_GET['month'];

    $categories = category::where('appear', 1)->get();

    return view('scheduleDetail', compact('schedule', 'memberInfos', 'countThisMonth', 'formattedTimes', 'year', 'month', 'categories'));
  }

  public function schedule_day(Request $request)
  {
    $year=$request->year;
    $month=$request->month;
    //開始時刻から30分ごとの時刻を取得
    $formattedTimes = $this->makeFormattedTime();

    $memberInfos = db::select(
      "SELECT
      m.ID,
      fullName,
      memName,
      memName_2,
      note,
      c.color
      FROM members AS m
      LEFT JOIN colors AS c
      ON m.colorID = c.ID
      WHERE appear =1
      "
    );

    $categories = category::where('appear', 1)->get();

    return view('schedule_day', compact('memberInfos', 'formattedTimes', 'categories','year','month'));
  }





  //各月のカレンダー作成
  public function makeCal($day)
  {
    //今月の日付
    $monthDay = [];

    //先月の日付
    $lastDay = [];

    //来月の日付
    $nextDay = [];

    //今月の最後の日
    $firstDayOfMonth = date("Y-m-01", strtotime($day));
    //今月の最初の日
    $lastDayOfMonth = date("t", strtotime($day));
    //最初の曜日の開始点
    $dayOfWeek = date("w", strtotime($firstDayOfMonth));
    //今月の日付取得
    for ($i = 1; $i <= $lastDayOfMonth; $i++) {
      array_push($monthDay, $i);
    }

    $this->countThisMonth = count($monthDay);

    //先月の日付取得
    //先月の最後の日
    $firstDayOfLastMonth = date("t", strtotime('last month'));

    for ($i = 0; $i < $dayOfWeek; $i++) {
      array_unshift($lastDay, $firstDayOfLastMonth - $i);
    }

    $this->counLastDay = count($lastDay);

    //来月の日付取得
    //来月の最後の日
    $firstDayOfNextMonth = date("Y-m-01", strtotime("+1 month", strtotime($day)));

    //最初の曜日の開始点
    $dayOfWeek = date("w", strtotime($firstDayOfNextMonth));

    if (!($dayOfWeek == 0)) {
      $dayOfWeek = 7 - $dayOfWeek;
      for ($i = 1; $i <= $dayOfWeek; $i++) {
        array_push($nextDay, $i);
      }
    }
    $this->countNextDay = count($nextDay);

    $thisMonth = array_merge($lastDay, $monthDay, $nextDay);

    return $thisMonth;
  }

  //スケジュールの開始、終了時間項目
  public function makeFormattedTime()
  {
    //開始、終了時間
    $startTime = strtotime('09:00');
    $endTime = strtotime('24:00');

    //開始時刻から30分ごとの時刻を取得
    $formattedTimes = [];
    for ($time = $startTime; $time <= $endTime; $time += 1800) {
      array_push($formattedTimes, date('H:i', $time));
    }
    return $formattedTimes;
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
