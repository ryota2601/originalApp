<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassroomChat;
use App\Models\Lesson;
use App\Models\Mylesson;
use Illuminate\Support\Facades\Auth;

class ChatroomController extends Controller
{
    public function showChatroom($lesson_id)
    {
        $chats_=ClassroomChat::where('lesson_id', $lesson_id)->get();
        $lesson=Lesson::find($lesson_id);
        $lesson_name=$lesson->name;

        $mylessons=Mylesson::where('user_id', Auth::id())->get();
        $mylesson_ids=array();
        foreach($mylessons as $mylesson){
            $mylesson_ids[] = $mylesson->lesson_id;
        }

        $lessons = array();
        foreach($mylesson_ids as $mylesson_id){
            $lessons[]=Lesson::where('id', $mylesson_id)->get();
        }

        $lesson_list=array();

        foreach($lessons as $lesson_){
            foreach($lesson_ as $lesson){
                $lesson_list[] = [$lesson->id, $lesson->name];
            }
        }

        $chats = array();
        foreach($chats_ as $chat){
            $chats[] = [$chat->user_id, $chat->text, $chat->file,$chat->id];
        }

        return view('chat', compact('lesson_name', 'chats', 'lesson_id', 'lesson_list'));
    }

    public function addText($lesson_id, Request $request)
    {
        $text=$request->request->get("text");
        $chatroom = new ClassroomChat;
        $chatroom->lesson_id=$lesson_id;
        $chatroom->user_id=Auth::id();
        $chatroom->text=$text;
        $chatroom->save();

        $pass = '/chat_room/' . $lesson_id;

        return redirect($pass);
    }
    public function getMessage($lesson_id,$chat_id){
        $chats=ClassroomChat::where('lesson_id', $lesson_id)
                            ->where('id','>',$chat_id)->get();
        return $chats;
    }
}
