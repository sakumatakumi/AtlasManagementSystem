$(function () {
  $('.delete-modal-open').on('click', function () {
    // モーダルを表示
    $('.js-modal').fadeIn();

    // クリックされた要素から値を取得
    var days = $(this).attr('days');
    var reservePart = $(this).attr('reservePart');

    // 予約日を表示
    $('.reserve_date_display').text(days);
    $('.reserve_date').val(days);

    // 予約時間の部を設定
    var reservePartWord = "";
    if (reservePart == 1) {
      reservePartWord = "リモ1部";
    } else if (reservePart == 2) {
      reservePartWord = "リモ2部";
    } else if (reservePart == 3) {
      reservePartWord = "リモ3部";
    }

    // モーダル内に予約時間を表示
    $('.reserve_part_text').text(reservePartWord);
     // 隠しフィールドに予約の部の値を設定
    $('.reserve_part').val(reservePart);

    return false;
  });

  // モーダルを閉じる処理
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });
});
