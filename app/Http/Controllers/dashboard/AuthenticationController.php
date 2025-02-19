<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    //

    public function authentication(Request $request){

        #echo "<pre>";print_r($_POST);die();

        $rules = [

            'email' => 'required|min:6,email',
            'password' => 'required|min:5'
        ];

        $validator = validator::make($request->all(),$rules);        
        
        if($validator->fails())
        { //echo "inn";die();
            return redirect()->route('login')->withInput()->withErrors($validator);

        }else{ 

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password ])) 
            { 
                if (Auth::user() && (Auth::user()->role == 'staff' || Auth::user()->role == 'admin') ) 
                {
                    return redirect()->route('new'); 

                }else{
                    Auth::logout();
                    return redirect()->route('login')->with('error','Invalid User Role selected');
    
                }

            }else{

                return redirect()->route('login')->with('error','Invalid Credentials')->withInput()->withErrors($validator);;

            }

        }


    }

    public function staff_logout()
    {

        Auth::logout();
        return redirect()->route('login');
    }
}
