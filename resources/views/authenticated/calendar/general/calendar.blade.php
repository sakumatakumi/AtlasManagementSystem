@extends('layouts.sidebar')

@section('content')
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
      </div>

      <div class="modal"><!-- モーダルウィンドウ本体の囲み -->
        <div class="modal__bg">
          <div class="modal-content"><!-- コンテンツエリア -->
            <p class="txt">予約日：<span id="modal-reservation-date"></span></p>
            <p class="txt">予約時間:<span id="modal-reservation-time"></span></p>
            <p class="txt">上記の予約をキャンセルしてもよろしいですか？</p>
          </div>
          <div class="btn-area">
            <button type="button" class="modal-close">閉じる</button><!-- 閉じるボタン -->
            <button type="button" class="modal-cancel">キャンセル</button><!-- キャンセルボタン -->
          </div>
        </div>
      </div>
    </div>

  </div>
  <div class="text-right w-75 m-auto">
    <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
  </div>
</div>
</div>
@endsection
