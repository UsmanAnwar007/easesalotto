<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Response;
use Illuminate\Support\Facades\Hash;
class AccountController extends Controller
{
    public function signup(Request $request){
        $emailchk=User::where('email',$request->email)->first();
        $usernamechk=User::where('username',$request->username)->first();
        $phonechk=User::where('phonenumber',$request->phonenumber)->first();
        if ($emailchk)
        {
            return Response::json(['success' => '0','validation'=>'0','message' => 'Email already exist']);
        }
        elseif($usernamechk){
            return Response::json(['success' => '0','validation'=>'0','message' => 'Usernaem already exist']);
        }
        elseif($phonechk){
            return Response::json(['success' => '0','validation'=>'0','message' => 'Phonenumber already exist']);
        }
        elseif($request->password != $request->confirm_password){
            return Response::json(['success'=>'0','message'=>'Password does not match']);
        }else{
            $obj=new User();
            $obj->username=$request->username;
            $obj->email=$request->email;
            $obj->phonenumber=$request->phonenumber;
            $obj->dob=$request->dob;
            $obj->gender=$request->gender;
            $obj->verifyToken=Hash::make(uniqid());
            $obj->password=Hash::make($request->password);
            if($obj->save()){
                return Response::json(['success'=>'1','message'=>'Account Successfully Created']);
            }
            else{ 
                return Response::json(['success'=>'0','message'=>'Something is wrong! Please try again']);
            }
        }
        
    }
    public function login(Request $request){
        $user=User::where('email',$request->email)->first();
        if($user){
            if($user->status=='1'){
                if(Hash::check($request->password,$user->password)){
                    return Response::json(['success' => '1','message' => 'Logedin successfully','data'=>$user]);
                }
                else{
                    return Response::json(['success'=>'0','message'=>'Invalid email or password!']);

                }
            }
            elseif($user->status=='0'){
                return Response::json(['success' => '0','message' => 'Your Account is blocked by admin!']);
            }
            else{
                return Response::json(['success'=>'0','message'=>'Invalid email or password!']);
            }
        }
        else{
            return Response::json(['success'=>'0','message'=>'Invalid email or password!']);
        }
    }

    public function sendcode(){
        
    }
}
