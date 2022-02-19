<?php

namespace App\Http\Controllers;

use App\AdminModel;
use App\IdeaModel;
use App\TourModel;
use Illuminate\Http\Request;

class idea_controller extends Controller
{
    function idea(){
        $user=session('user');

        $user_type=AdminModel::where('email',$user)->first()->user_type;

        $latest_tour = TourModel::where('status',1)->latest()->first();

        $data=[
            'user_type' =>$user_type,
            'latest_tour' =>$latest_tour,
        ];

        return view('Idea',$data);
    }

    function getIdeaData()
    {

        $result = IdeaModel::with('user')->orderBy('id','desc')->get();
        return $result;
    }

    function get_end_time($lm,$tour_id)
    {

        $result = IdeaModel::where(['tour_id'=>$tour_id,'status'=>2])->inRandomOrder()->limit($lm)->get();
        return $result;
    }

    function change_status($id)
    {

        $result = TourModel::where('tour_id',$id)->update(['status'=>2]);
        return $result;
    }

    function ideaCount()
    {

        $result = IdeaModel::where('status',1)->count();
        return $result;
    }
    function ideaAdd(Request $request)
    {
        date_default_timezone_set("Asia/Dhaka");
        $current_time=date("h:i:sa");

        $endTime = strtotime("+15 minutes", strtotime($current_time));

//     echo date('h:i:s', $endTime);
//     die();

        $idea=$request->input('idea');
        $endT=$request->time;
        $user=session('user');

        $user_id=AdminModel::where('email',$user)->first()->id;
        $date=date('Y-m-d');


//
        IdeaModel::insert(['idea'=>$idea,'user_id'=>$user_id,'status' => 1,'date'=>$date]);

        $count=$this->ideaCount();

        $data['count']=$count;

        if ($count == 8){
            $tour_id=date('Ymdhs');
            $data['tour_id']=$tour_id;
            $data['end_time']=$endT;


            TourModel::insert(['tour_id'=>$tour_id,'status' => 1,'end_time'=>$endT]);
            IdeaModel::where('status',1)->update(['status'=>2,'tour_id'=>$tour_id]);




        }





        return $data;

    }

}
