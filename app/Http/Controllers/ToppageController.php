<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class ToppageController extends Controller
{
    //
    public function showTimetable()
    {
        $lessons=Lesson::where('user_id', Auth::id())->get();

        $lessonArray=array();

        foreach($lessons as $lesson){
            $lessonArray[$lesson->day_id][$lesson->time_id] = $lesson;
        }


        return view('timetable.list',array("lessons"=>$lessonArray));
    }

    public function addForm()
    {
        return view('timetable.addForm');
    }

    public function registerForm(Request $request)
    {
        $time=$request->request->get("time");
        $name=$request->request->get("name");
        $day=$request->request->get("day");
        $lesson = Lesson::firstOrNew(["user_id"=>Auth::id(), "time_id"=>$time,  "day_id"=>$day]);
        $lesson->university_id=1;
        $lesson->department_id=1;
        $lesson->user_id=Auth::id();
        $lesson->name=$name;
        $lesson->day_id=$day;
        $lesson->time_id=$time;
        $lesson->start_time=new Carbon('2021-04-01');
        $lesson->end_time=new Carbon('2021-07-31');
        $lesson->save();
        \Session::flash('eer_msg', '授業を登録しました');
        return redirect()->route("top_page");
    }

    public function lessonDelete($day_id, $time_id)
    {
        $lesson = Lesson::where(["user_id"=>Auth::id(), "time_id"=>$time_id,  "day_id"=>$day_id])->first();
        $lesson->delete();
        return redirect()->route("top_page");
    }
}
