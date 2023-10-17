 <?php

  function makeCal()
  {

    // 今日の日付を取得
    $today = date("Y-m-d");

    //今月の日付
    $monthDay = [];

    //先月の日付
    $lastDay = [];

    //来月の日付
    $nextDay = [];

    // カレンダーの表示月を設定（デフォルトは今月）
    if (isset($_GET['month']) && isset($_GET['year'])) {
      $month = $_GET['month'];
      $year = $_GET['year'];
    } else {
      $month = date("m");
      $year = date("Y");
    }

    //今月の日付取得
    // 月初めの日付を取得
    $firstDayOfMonth = date("Y-m-01", strtotime("$year-$month-01"));
    // 月末の日付を取得
    $lastDayOfMonth = date("Y-m-t", strtotime($firstDayOfMonth));
    // 月末の日を取得
    $lastDayOfMonth = date("d", strtotime($lastDayOfMonth));
    $monthDay = [];
    for ($i = 1; $i <= $lastDayOfMonth; $i++) {
      array_push($monthDay, $i);
    }

    //先月の日付取得
    //先月の最後の日
    $firstDayOfLastMonth = date("Y-m-01", strtotime("-1 month", strtotime($today)));
    //先月の最初の日
    $lastDayOfLastMonth = date("t", strtotime("-1 month", strtotime($today)));
    //最後の曜日の開始点
    $dayOfWeek = date("w", strtotime($firstDayOfLastMonth));
    //先月の日付取得
    for ($i = 1; $i <= $lastDayOfLastMonth; $i++) {
      array_push($lastDay, $i);
    }
    //7で区切れるように隙間を埋める
    for ($i = 0; $i < $dayOfWeek; $i++) {
      array_unshift($lastDay, 0);
    }
    //7で区切る
    for ($i = 0; $i < count($lastDay) / 7; $i++) {
      $lastDay = array_chunk($lastDay, 7);
    }
    //区切った最後の週を取得
    $lastDay = array_pop($lastDay);

    //来月の日付取得
    //来月の最後の日
    $firstDayOfNextMonth = date("Y-m-01", strtotime("+1 month", strtotime($today)));
    //来月の最初の日
    $lastDayOfNextMonth = date("t", strtotime("+1 month", strtotime($today)));
    //最初の曜日の開始点
    $dayOfWeek = date("w", strtotime($firstDayOfNextMonth));
    //来月の日付取得
    for ($i = 1; $i <= $lastDayOfNextMonth; $i++) {
      array_push($nextDay, $i);
    }
    //7で区切れるように隙間を埋める
    for ($i = 0; $i < $dayOfWeek; $i++) {
      array_unshift($nextDay, 0);
    }
    //7で区切る
    for ($i = 0; $i < count($nextDay) / 7; $i++) {
      $nextDay = array_chunk($nextDay, 7);
    }
    //区切った最後の週を取得
    $nextDay = array_pop($nextDay);

    $thisMonth=array_merge($lastDay,$monthDay,$nextDay);

    return $thisMonth;
  }

