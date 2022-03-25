<?php

namespace App\Business\Repositories;
use UserModel;
use Validator;
use Cache;
use Helper;

class UserRepository
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new UserModel;
        $this->cache = CACHE_USER;
    }

    public function index()
    {
        if(Cache::has($this->cache)){
            return Cache::get($this->cache);
        }else{
            return Cache::remember($this->cache, number_format(CACHE_DURATION), function () {
                return $this->model::with('role','branch','setting')->get();
            });
        }
    }

    public function store($request)
    {

            $validator = Validator::make($request->all(),
            [
                'name' => 'required', 
                'role' => 'required', 
                'email' => 'required|unique:users',
                'New'=>'required|min:6|same:Retype'
            ],
            [
                'name.required'=>'User Name Required',
                'role.required'=>'Please Select Role',
                'email.unique'=>'Email Already Exist',
                'email.required'=>'Email Required',
                'New.min'=>'Minimum 6 characters',
                'New.same'=>'Passwords not matched'
            ]);
            if ($validator->fails()) {
                return [ RETURN_VALIDATION => $validator->errors()];
            }

            $input = $request->all();
       
        $input['roid']=$input['role']['roid'];
        $input['password'] = Helper::passwordEncode($request->New);
        $this->model = $this->model::create($input);
        $this->model = $this->model::with('role','branch','setting')->find($this->model->id);
            
        Cache::forget($this->cache);
        return [RETURN_DATA => $this->model, RETURN_SUCCESS => SAVE_SUCCESS];
    }

    public function update($request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(),
            [
                'name' => 'required', 
                'email' => "required|unique:users,email,$id,id",
                'New'=>'required|min:6|same:Retype'
            ],
            [
                'name.required'=>'User Name Required',
                'email.unique'=>'Email Already Exist',
                'email.required'=>'Email Required',
                'New.min'=>'Minimum 6 characters',
                'New.same'=>'Passwords not matched'
            ]
        );

        if ($validator->fails()) {
            return [ RETURN_VALIDATION => $validator->errors()];
        }

        $this->model = $this->model::find($id);
        if (empty($this->model)) {
            return [ RETURN_FAILURE => UPDATE_NO_RECORD];
        }
        $input['roid']=$input['role']['roid'];
        $input['password'] = Helper::passwordEncode($request->New);
        $this->model->update($input);
        Cache::forget($this->cache);
        $this->model = $this->model::with('role','branch','setting')->find($id);

        return [RETURN_DATA => $this->model, RETURN_SUCCESS => UPDATE_SUCCESS];
    }

    public function destroy($id)
    {
        $this->model = $this->model::find($id);
        if (empty($this->model)) {
            return [ RETURN_FAILURE => DELETE_NO_RECORD];
        }


        if ($this->model::deleteValidation($id)==0) {
            $this->model->delete();
            Cache::forget($this->cache);
            return [RETURN_SUCCESS =>  DELETE_SUCCESS];
        }else{
            return [ RETURN_FAILURE => DELETE_REFUSE . 'User already exist in ______'];
        }

    }
}