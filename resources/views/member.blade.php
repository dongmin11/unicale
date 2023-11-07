<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="member.css">
</head>

<body>
  <h2 style="text-align: center; margin: 50px 0 50px 0;"><a style="opacity:60%; text-decoration: none; color:black" href="/unical/unicale/public/showCalender">E-CAL</a></h2>
  <h3 class="title">メンバー管理</h3>
  <div class="updateMember">
    <form action="{{route('updateMember')}}" method="post">
      @csrf
      <table class="memberIndex">
        <tr>
          <th class="memberHead">表示順</th>
          <th class="memberHead">フルネーム</th>
          <th class="memberHead">短縮名１<br>（２文字程度）</th>
          <th class="memberHead">短縮名２<br>（１文字程度）</th>
          <th class="memberHead">アイコン色</th>
          <th class="memberHead" style="width:70px;">表示する</th>
          <th class="memberHead">備考</th>
        </tr>
  
        @if(session()->has('message'))
            <p style="font-weight: bold; color:red; text-align:center;">{{session('message')}}</p>
        @endif
        @foreach($memberInfos as $key=>$memberInfo)
        </p>

        <tr>
          <input type="hidden" value="{{$memberInfo->ID}}" name="ID_{{$key}}">
          <td><select memberID="{{$memberInfo->ID}}" class="appearOrder" name="appearOrder_{{$key}}">
            <option value="">-</option>
            @foreach($appearOrders as $appearOrder)
            <option value="{{$appearOrder}}" <?php if($memberInfo->appearOrder == $appearOrder){echo "selected";} ?>>{{$appearOrder}}</option>

            @endforeach
          </select></td>
          <td><input name="fullName_{{$key}}" type="text" value="{{$memberInfo->fullName}}" style="width:50%;"></td>
          <td><input name="memName_{{$key}}" style="width:50%;" type="text" value="{{$memberInfo->memName}}"></td>
          <td><input name="memName_2_{{$key}}" style="width:50%;" type="text" value="{{$memberInfo->memName_2}}"></td>
          <td><select class="member_colors" style="padding:5px; color:white; background-color:<?php echo $memberInfo->color; ?>;" name="color_{{$key}}">

              @foreach($colors as $color)
              <option  style="color:white; background-color: <?php echo $color->color; ?>;" value="{{$color->color}}" <?php if ($color->color == $memberInfo->color) {
                                          echo 'selected';
                                          } ?>>{{$color->color}}</option>
              @endforeach
            </select></td>
          <td><input class="appear" appearOrder="{{$memberInfo->appearOrder}}" type="checkbox" name="appear_{{$key}}" 
          <?php if($memberInfo->appear == 1){echo 'checked';} ?>></td>
          <td><input name="note_{{$key}}" style="width:90%;" type="text" value="{{$memberInfo->note}}"></td>
        </tr>
        @endforeach
      </table>
      <div class="updateButtonItem">
        <input class="updateButton" type="submit" value="更新">
      </div>

  </div>
  </form>

  <div class="addMember">
    <form action="{{route('addMember')}}" method="post">
      @csrf
      <table class="memberIndex">
        <tr>
          <th class="memberHead">表示順</th>
          <th class="memberHead">フルネーム</th>
          <th class="memberHead">短縮名１<br>（２文字程度）</th>
          <th class="memberHead">短縮名２<br>（１文字程度）</th>
          <th class="memberHead">アイコン色</th>
          <th class="memberHead" style="width:70px;">表示する</th>
          <th class="memberHead">備考</th>
        </tr>

        <tr>
          <td><select id="addAppearOrder" name="appearOrder" style="width: 50%;">
          <option value="">-</option>
          @foreach($appearOrders as $appearOrder)      
          <option value="{{$appearOrder}}">{{$appearOrder}}</option>
          @endforeach
        </select></td>
          <td><input name="fullName" typstyle="width:50%;" type="text" required></td>
          <td><input name="memName" style="width:50%;" type="text" required></td>
          <td><input name="memName_2" style="width:50%;" type="text" required></td>
          <td>
            <select  style="padding:5px; color:white;" name="color" id="addColor" required>
            <option  value=""></option>
              @foreach($colors as $color)
              <option style="color:white; background-color: <?php echo $color->color; ?>;" value="{{$color->color}}">{{$color->color}}</option>
              @endforeach
            </select>
          </td>
          <td><input id="addAppearCheck" type="checkbox" name="appear" checked></td>
          <td><input name="note" style="width:90%;" type="text" value=""></td>
        </tr>
      </table>
      <div class="addButtonItem">
        <input class="addButton" type="submit" value="追加">
      </div>
    </form>
  </div>

<script>
const memberColors = document.querySelector('.member_colors');
memberColors.addEventListener('change',function(){
    const selectOption = memberColors.options[memberColors.selectedIndex];
    memberColors.style.backgroundColor = selectOption.value;
});

  // <select>要素のIDを取得
  var selectElement = document.getElementById('addColor');

  // <select>要素のchangeイベントを監視
  selectElement.addEventListener('change', function () {
    // 選択された<option>の値を取得
    var selectedColor = selectElement.value;

    // <select>要素の背景色を選択された色に設定
    selectElement.style.backgroundColor = selectedColor;
  });


  //表示順の最大選択値を取得
  const appearOrders = document.querySelectorAll('.appearOrder');
  const maxAppear = appearOrders.length;//5
  const appears = document.querySelectorAll('.appear');
  const appearOrderArray = [];

  appearOrders.forEach(function(appearOrder){
    appearOrderArray.push(appearOrder.selectedIndex);
  })
  maxAppearOrder = Math.max(...appearOrderArray);

  for(i=0;i<appearOrders.length;i++)
  {
    //表示するがfalseの場合は表示順変更不可
    if(appears[i].checked == false){
      appearOrders[i].disabled = true;
    }
    for(j=0;j<appearOrders[0].length;j++)
    {
      if(appearOrders[i].options[j].value > maxAppearOrder+1)
      {
        appearOrders[i].options[j].disabled = true;
        appearOrders[i].options[j].style.backgroundColor ="gray";
      }
    }
  }


  //「表示する」のチェック切り替えの度にmembers.appear更新、appearOrderのdisabled変更
  appears.forEach(function(appear){
    appear.addEventListener('change',()=>{

      var appearorder = appear.getAttribute('appearorder');
      if(appear.checked == true)
      {
        appearOrders[appearorder].disabled = false;
      }else{
        appearOrders[appearorder].disabled = true;
        appearOrders[appearorder].selectedIndex = 0;
      }
    });
  });

  //追加の表示順処理
  addAppearOrder = document.getElementById('addAppearOrder');
  addAppearCheck = document.getElementById('addAppearCheck');
  
  addAppearCheck.addEventListener('change',()=>{
    if(addAppearCheck.checked == false)
    {
      addAppearOrder.disabled = true;
      addAppearOrder.selectedIndex = 0;
    }else{
      addAppearOrder.disabled = false;
    }
  })

  for(i=0;i<addAppearOrder.length;i++)
  {
    if(addAppearOrder[i].value > maxAppear)
    {
      addAppearOrder[i].disabled = true;
      addAppearOrder[i].style.backgroundColor = "gray";
    }
  }




</script>
</body>

@extends('layout.footer')

</html>