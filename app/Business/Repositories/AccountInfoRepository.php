<?php

namespace App\Business\Repositories;
use Illuminate\Http\Request;
use UserModel;
use Validator;
use Cache;
use Auth;
use Input;
use Str;
use Image;
use File;
use Hash;

class AccountInfoRepository
{
    public function __construct()
    {
    }
    public function index()
    {
       return UserModel::find(Auth::id());
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::find($id);
        $input = $request->all();

        if(!isset($input['logo'])||$input['logo']==$user->logo){
            if ((!Hash::check($request->currentPassword, $user->password))) {
                return [RETURN_VALIDATION => ['currentPassword' => ['Current password not matched']]];
            }
            $validator = Validator::make($request->all(),
                ['password' => 'required|min:6',
                'confirmPassword' => 'required|same:password'],
                ['password.min' => 'Minimum 6 characters', 'confirmPassword.same' => 'Passwords not matched']);

            if ($validator->fails()) {
                return [RETURN_VALIDATION => $validator->errors()];
            }
            
            $input['password'] = Hash::make($request->password);
        }

        if(isset($input['logo'])&&$input['logo']!=$user->logo){
            $destinationPath = public_path('/images/logo/');
            $data = $input['logo'];
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            file_put_contents($destinationPath.'dummy.jpg', $data);
            $filename = Str::random(8).'.jpg';
            $imgFile = Image::make($destinationPath.'dummy.jpg');
            $imgFile->save($destinationPath.$filename, $user->imageQuality);
            $input['logo']=$filename;
            if (file_exists($destinationPath.'dummy.jpg')) {
                unlink($destinationPath.'dummy.jpg');
            }
        }


        $user->update($input);
        $user = UserModel::find($id);
        return [RETURN_DATA => $user , RETURN_SUCCESS => UPDATE_SUCCESS];
    }

}
