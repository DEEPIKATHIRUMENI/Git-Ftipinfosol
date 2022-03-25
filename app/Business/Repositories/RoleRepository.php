<?php

namespace App\Business\Repositories;
use RoleModel;
use Validator;
use Cache;
use Illuminate\Http\Request;

class RoleRepository
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new RoleModel;
        $this->cache = CACHE_ROLE;
    }

    public function index()
    {
        if(Cache::has($this->cache)){
            return Cache::get($this->cache);
        }else{
            return Cache::remember($this->cache, number_format(CACHE_DURATION), function () {
                return $this->model::get();
            });
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),['name' => 'required'],['name.required' => 'Please Enter Role Name']);

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $input = $request->all();
        $this->model = $this->model::create($input);
        Cache::forget($this->cache);
        return [ RETURN_DATA => $this->model, RETURN_SUCCESS =>  SAVE_SUCCESS];
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),['name' => 'required'],['name.required' => 'Please Enter Role Name']);

        if ($validator->fails()) {
            return [ RETURN_VALIDATION => $validator->errors()];
        }

        $this->model = $this->model::find($id);
        if (empty($this->model)) {
            return [RETURN_FAILURE => UPDATE_NO_RECORD];
        }

        $input = $request->all();

        $this->model->update($input);
        Cache::forget($this->cache);
        $this->model = $this->model::find($id);

        return [ RETURN_DATA => $this->model, RETURN_SUCCESS =>  UPDATE_SUCCESS];
    }

    public function destroy($id)
    {
        $this->model = $this->model::find($id);
        if (empty($this->model)) {
            return [RETURN_FAILURE => DELETE_NO_RECORD];
        }

        if ($this->model::deleteValidation($id)==0) {
            $this->model->delete();
            Cache::forget($this->cache);
            return [RETURN_SUCCESS =>  DELETE_SUCCESS];
        }else{
            return [ RETURN_FAILURE => Helper::msgDl('Role')];
        }

    }
}
