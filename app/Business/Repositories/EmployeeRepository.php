<?php

namespace App\Business\Repositories;

use Cache;
use EmployeeModel;
use Helper;
use Illuminate\Http\Request;
use Validator;

class EmployeeRepository
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new EmployeeModel;
        $this->cache = CACHE_EMPLOYEE;
    }

    public function index()
    {
        if (Cache::has($this->cache)) {
            return Cache::get($this->cache);
        } else {
            return Cache::remember($this->cache, number_format(CACHE_DURATION), function () {
                return $this->model::with('role')->get();
            });
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'role' => 'required',
                'name' => 'required|unique:employee',
                'mobile' => 'required|unique:employee',
            ],
            [
                'role.required' => Helper::msgSel('Role'),
                'name.required' => Helper::msgEnt('Employee'),
                'name.unique' => Helper::msgAl('Employee'),
                'mobile.required' => Helper::msgEnt('Mobile Number'),
                'mobile.unique' => Helper::msgAl('Mobile Number'),
            ]
        );

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $input = $request->all();
        $input['roid']=$input['role']['roid'];
        $this->model = $this->model::create($input);
        Cache::forget($this->cache);
        return [RETURN_DATA => $this->model, RETURN_SUCCESS => SAVE_SUCCESS];
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
            [
                'role' => 'required',
                'name' => "required|unique:employee,name,$id,empid",
                'mobile' => "required|unique:employee,mobile,$id,empid",
            ],
            [
                'role.required' => Helper::msgSel('Role'),
                'name.required' => Helper::msgEnt('Employee'),
                'name.unique' => Helper::msgAl('Employee'),
                'mobile.required' => Helper::msgEnt('Mobile Number'),
                'mobile.unique' => Helper::msgAl('Mobile Number'),
            ]
        );

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $this->model = $this->model::find($id);
        if (empty($this->model)) {
            return [RETURN_VALIDATION => UPDATE_NO_RECORD];
        }
        $input = $request->all();
        $input['roid']=$input['role']['roid'];
        $this->model->update($input);
        Cache::forget($this->cache);

        return [RETURN_DATA => $this->model, RETURN_SUCCESS => UPDATE_SUCCESS];
    }

    public function destroy($id)
    {
        $this->model = $this->model::find($id);
        if (empty($this->model)) {
            return [RETURN_VALIDATION => DELETE_NO_RECORD];
        }

        if ($this->model::deleteValidation($id) == 0) {
            $this->model->delete();
            Cache::forget($this->cache);
            return [RETURN_SUCCESS => DELETE_SUCCESS];
        } else {
            return [RETURN_FAILURE => Helper::msgDl('Employee')];
        }
    }
}
