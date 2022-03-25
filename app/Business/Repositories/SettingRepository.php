<?php

namespace App\Business\Repositories;
use Illuminate\Http\Request;
use SettingModel;
use UserModel;
use Validator;
use Cache;
use Auth;
use Input;
use Str;
use Image;
use File;
use Hash;

class SettingRepository
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new SettingModel;
        $this->cache = CACHE_SETTING;
    }
    public function index()
    {
        return $this->model::where('userId',Auth::id())->first();
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required', 
                'mobile1' => 'required',
                'stateCode' => 'required',
                'addressLine1' => 'required',
                'city' => 'required',
                'gstin' => 'required',
                'receiptSize' => 'required',
                'invoiceStartFrom' => 'required',
            ], 
            [
                'name.required' => 'Please Enter Company Name', 
                'mobile1.required' => 'Please Enter Mobile',
                'stateCode.required' => 'Please Enter State Code',
                'gstin.required' => 'Please Enter GSTIN',
                'addressLine1.required' => 'Please Enter Address Line 1',
                'city.required' => 'Please Enter City',
                'receiptSize.required' => 'Please Enter Receipt Size',
                'invoiceStartFrom.required' => 'Please Enter Invoice Start From',
            ]);

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $input = $request->all();
        $this->model = $this->model::create($input);
        Cache::forget($this->cache);
        return [RETURN_DATA => $this->model, RETURN_SUCCESS => SAVE_SUCCESS];
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => 'required', 
            'mobile1' => 'required',
            'stateCode' => 'required',
            'addressLine1' => 'required',
            'city' => 'required',
            'gstin' => 'required',
            'receiptSize' => 'required',
            'invoiceStartFrom' => 'required',
        ], 
        [
            'name.required' => 'Please Enter Company Name', 
            'mobile1.required' => 'Please Enter Mobile',
            'stateCode.required' => 'Please Enter State Code',
            'gstin.required' => 'Please Enter GSTIN',
            'addressLine1.required' => 'Please Enter Address Line 1',
            'city.required' => 'Please Enter City',
            'receiptSize.required' => 'Please Enter Receipt Size',
            'invoiceStartFrom.required' => 'Please Enter Invoice Start From',
        ]);

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }
        $this->model = $this->model::find($id);
      
        if (empty($this->model)) {
            return [RETURN_FAILURE => UPDATE_NO_RECORD];
        }

        $input = $request->all();
        if(isset($input['logo'])&&$input['logo']!=$this->model->logo){
            $destinationPath = public_path('/images/logo/');
            if(isset($this->model->logo)){
                if (file_exists($destinationPath.$this->model->logo)) {
                    unlink($destinationPath.$this->model->logo);
                }
            }
            $data = $input['logo'];
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            file_put_contents($destinationPath.'dummy.jpg', $data);
            $filename = Str::random(8).'.jpg';
            $imgFile = Image::make($destinationPath.'dummy.jpg');
            $imgFile->save($destinationPath.$filename, $this->model->imageQuality);
            $input['logo']=$filename;
            if (file_exists($destinationPath.'dummy.jpg')) {
                unlink($destinationPath.'dummy.jpg');
            }
        }

        $this->model->update($input);
        Cache::forget($this->cache);
        $this->model = $this->model::find($id);

        return [RETURN_DATA => $this->model, RETURN_SUCCESS => UPDATE_SUCCESS];
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
            return [ RETURN_FAILURE => DELETE_REFUSE . 'Setting already exist in ______'];
        }

    }

   

}
