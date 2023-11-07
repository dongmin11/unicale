<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="detail.css">
</head>

<body>
<h2 style="text-align: center; margin: 50px 0 50px 0;"><a style="opacity:60%; text-decoration: none; color:black" href="/unicale/public/showCalender">E-CAL</a></h2>

<form action="{{route('search')}}">
    <input style="width: 350px; height:30px;" type="text">
    <input style="width: 50ox; height:35px; font-weight:bold; font-size:larger;" type="submit" value="検索">
    @csrf
</form>

<p>{{}}</p>

<table>
    <tr>
        <th>日付</th>
        <th>開始時刻</th>
        <th>カテゴリ</th>
        <th>タイトル</th>
        <th>詳細</th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>

</body>
@extends('layout.footer')

</html>