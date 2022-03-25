<?php

namespace App\Business\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Helper;
use Carbon;
use UserModel;
use SettingModel;
use ThemeModel;


class AuthController extends Controller
{

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|exists:users,email',
                'password' =>'required'
            ],
            [
                'email.required' => 'Please enter Email',
                'email.exists' => 'Email is not available. Please contact Administrator',
                'email.email' => 'Please enter valid Email',
                'password.required' => 'Please enter Password',
            ]);
      
        if ($validator->fails()) {
            return response([RETURN_VALIDATION => $validator->errors()],422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember == 1 ? true : false)) {
            return response([RETURN_SUCCESS => RESULT_SUCCESS]);
        } else {
            return response([RETURN_VALIDATION => ['password' => ['Please enter valid Password']]], 422);
        }
    }

    public function displayLoginPage()
    {
        $id = Auth::id();
        $user = UserModel::where('id',$id)->with('setting','branch')->first();
        $user['company'] =SettingModel::first();
        $user['theme'] =ThemeModel::first();
        $user['loginBg'] = "https://api.sf3.in/ftipbox/radiant/v1/images/loginBg".rand(1, 54).".jpg";

        $user['tabcolor']=json_decode($user['theme']['themes'])[$user['theme']['themeID']]->primaryDark; 
        if(!isset($id)){
            $login=0;
            $user['loginYN']=0;
            return view('layout.login', compact('user','login'));
        }else{
            $login=1;
            $user['loginYN']=1;
            $user['menu'] = Helper::sidebarMenu();
            $user['poweredBy'] = Carbon::now()->format('Y').'. FTip infosol';

            $diff = Auth::user()->updated_at->diffInDays(Carbon::now());
            if($diff>=1 && Helper::getBrowser()!='Chrome'){
                UserModel::where('id',$id)->update(['notification'=>1]);
                $user['notification'] = 1; 
            }else{
                $user['notification'] = 0; 
            }

            return view('layout.app', compact('user','login'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


}
