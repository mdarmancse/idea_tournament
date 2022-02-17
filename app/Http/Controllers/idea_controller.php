<?php

namespace App\Http\Controllers;

use App\AdminModel;
use App\IdeaModel;
use Illuminate\Http\Request;

class idea_controller extends Controller
{
    function idea(){
        $user=session('user');

        $user_type=AdminModel::where('email',$user)->first()->user_type;



        $data=[
            'user_type' =>$user_type,
        ];

        return view('Idea',$data);
    }

    function getIdeaData()
    {
//        $users = IdeaModel::with('user')->get();

        $result = IdeaModel::with('user')->orderBy('id','desc')->get();
        return $result;
    }
    function ideaAdd(Request $request)
    {
        $idea=$request->input('idea');
        $user=session('user');

        $user_id=AdminModel::where('email',$user)->first()->id;
        $date=date('Y-m-d');


//
        $result = IdeaModel::insert(['idea'=>$idea,'user_id'=>$user_id,'date'=>$date]);
        if ($result == true) {
            return redirect('/idea')->withSuccess('Idea Add Successfully!!');
        } else {
            return redirect()->back()->withError('Idea add Failed!!');
        }

    }

}
