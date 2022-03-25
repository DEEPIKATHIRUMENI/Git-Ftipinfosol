<?php

namespace App\Business\Repositories;
use BankModel;
use Validator;
use Cache;
use Auth;

class BankRepository
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new BankModel;
        $this->cache = $this->cache;
    }

    public function index()
    {
        if(Cache::has($this->cache)){
            return Cache::get($this->cache);
        }else{
            return Cache::remember($this->cache, number_format(CACHE_DURATION), function () {
                return $this->model::where('userId',Auth::id())->get();
            });
        }
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => 'required', 
            'ifsc' => 'required',
            'branch' => 'required',
            'accountNo' => 'required',
            'accountName' => 'required',
        ], 
        [
            'name.required' => 'Please Enter Bank Name', 
            'ifsc.required' => 'Please Enter IFSC', 
            'branch.required' => 'Please Enter Branch',
            'accountNo.required' => 'Please Enter Account No',
            'accountName.required' => 'Please Enter Account Name'
        ]);

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $input = $request->all();
        $this->model = $this->model::create($input);
        Cache::forget($this->cache);

        return [ RETURN_DATA => $this->model, RETURN_SUCCESS =>  SAVE_SUCCESS];
    }

    public function update($request, $baid)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => 'required', 
            'ifsc' => 'required',
            'branch' => 'required',
            'accountNo' => 'required',
            'accountName' => 'required',
        ], 
        [
            'name.required' => 'Please Enter Bank Name', 
            'ifsc.required' => 'Please Enter IFSC', 
            'branch.required' => 'Please Enter Branch',
            'accountNo.required' => 'Please Enter Account No',
            'accountName.required' => 'Please Enter Account Name'
        ]);
        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $this->model = $this->model::find($baid);
        if (empty($this->model)) {
            return [RETURN_FAILURE => UPDATE_NO_RECORD];
        }

        $input = $request->all();

        $this->model->update($input);
        Cache::forget($this->cache);
        $this->model = $this->model::find($baid);

        return [ RETURN_DATA => $this->model, RETURN_SUCCESS =>  UPDATE_SUCCESS];
    }

    public function defaultBank($id)
    {
        DB::statement("UPDATE bank set defaultBank=0 where baid!='$id'");
        Cache::forget($this->cache);

        $this->model = $this->model::find($id);

        return [ RETURN_DATA => $this->model, RETURN_SUCCESS =>  UPDATE_SUCCESS];
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
            return [ RETURN_FAILURE => Helper::msgDl('Bank')];
        }

       
    }
}
