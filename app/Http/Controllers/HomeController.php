<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\AdminModel;
use Illuminate\Support\Facades\Hash;
use App\Mail\TestUserMail;
use Mail;
class HomeController extends Controller
{
       //ADMIN
    function HomeIndexAdmin(){
        $user=session('user');

        $user_type=AdminModel::where('email',$user)->first()->user_type;

        $data=[
          'user_type' =>$user_type
        ];

        return view('Home',$data);
    }



    function userAdd(Request $request)
    {
        $name=$request->input('name');
        $email=$request->input('email');
        $pass=md5($request->input('pass'));
        $user_type=$request->input('user_type');
;
//

        $result = AdminModel::insert(['name'=>$name,'email'=>$email,'user_type'=>$user_type,'password'=>$pass]);
        if ($result == true) {
            return redirect('/')->withSuccess('User Add Successfully!!');
        } else {
            return redirect()->back()->withError('User add Failed!!');
        }

    }

    function getUserData()
    {
        $result = AdminModel::orderBy('id','desc')->get();
        return $result;
    }
    public function sendMail(Request $request)
    {

       $data= $request->data;

        foreach ($data as $dt){

           $e= AdminModel::where('id',$dt['user_id'])->get();

            foreach($e as $key => $value){


                if (!empty($value['email'])) {
                    $details = [
                        'subject' => 'Idea Tournament',
                        'phase' => $request->phase,
                    ];

                    Mail::to($value['email'])->send(new TestUserMail($details));
                }
            }

        }







        return response()->json(['done']);
    }


}
