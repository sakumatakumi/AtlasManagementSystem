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
        dd($request);
        $setting_reserve = $request->input('date');
        $setting_part = $request->input('part');

        $setting_reserve = ReserveSettings::where('setting_reserve', $setting_reserve)->where('setting_part', $setting_part)->first();
        // dd($setting_reserve);
        $setting_reserve->increment('limit_users');
        $setting_reserve->users()->detach(Auth::id());

        // インクリメント(+5)。第二引数がない場合+１になる
        // $setting_reserve->increment('limit_users', 5);

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
