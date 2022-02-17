<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdminModel;
use App\Http\Middleware\LoginCheckMiddleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;


class LoginController extends Controller
{
    function LoginIndex(){
        return view('Login');

    }
    function onLogout(Request $request){
        $request->session()->flush();
        return redirect('/LoginIndex');


    }


    function onLogin(Request $request){

        $userr= $request->input('userName');


        $user = AdminModel::where([
            'email' => $request->input('userName'),
            'password' => md5($request->input('userPassword'))
        ])->first();

        //dd($user);
        if ($user) {
            $request->session()->put('user',$userr);
            return Redirect::to("/");
        }else{
            return redirect()->back()->withError('Login Fail!! Try again!!');
        }





    }
}
