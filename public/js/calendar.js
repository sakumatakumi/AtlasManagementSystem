$(function(){

   // 変数に要素を入れる
  var close = $('.modal-close'),
  cancel = $('.modal-cancel'),
  container = $('.modal-container');

  //読み込んで5秒後にモーダルウィンドウを表示
  setTimeout(() => {
    container.addClass('active');
    return false;
  },5000);
  //閉じるボタンをクリックしたらモーダルウィンドウを閉じる
  close.on('click',function(){
    container.removeClass('active');
  });

  //キャンセルボタンを押したら予約が削除される


  //モーダルウィンドウの外側をクリックしたらモーダルウィンドウを閉じる
  $(document).on('click',function(e) {
    if(!$(e.target).closest('.modal-body').length) {
      container.removeClass('active');
    }
  });

});
