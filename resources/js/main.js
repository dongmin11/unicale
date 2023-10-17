
// 各<select>要素のchangeイベントを監視
var selectElements = document.querySelectorAll('select');

selectElements.forEach(function (selectElement) {
  selectElement.addEventListener('change', function () {
    var selectedColor = selectElement.value; // 選択された<option>の値を取得
    selectElement.style.backgroundColor = selectedColor; // 背景色を変更
  });
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