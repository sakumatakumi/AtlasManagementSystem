<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request)
    {
        DB::beginTransaction();
        try {
            $getPart = $request->getPart;
            $getDate = $request->getData;

            // dd($getDate, $getPart);

            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach ($reserveDays as $key => $value) {
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();

                // dd($reserve_settings);

                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            // 送信されたデータを取得
            $getDate = $request->input('date');
            $getPart = $request->input('part');

            // 予約設定を取得
            $reserve_settings = ReserveSettings::where('setting_reserve', $getDate)
                ->where('setting_part', $getPart)
                ->first();

            if ($reserve_settings) {
                // ユーザーの予約を削除
                $reserve_settings->users()->detach(Auth::id());

                // 定員数を1増やす
                $reserve_settings->increment('limit_users');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // エラーハンドリング（必要に応じてエラーメッセージを設定）
        }

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
