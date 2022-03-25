<?php

namespace App\Business\Repositories;
use PurchaseModel;
use SettingModel;
use Validator;
use Carbon;
use Helper;
use Auth;
use PDF;
use Cache;

class PurchaseRepository 
{

    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new PurchaseModel;
        $this->cache = CACHE_PURCHASE;
    }

    public function index()
    {
        Cache::forget($this->cache);
        if(Cache::has($this->cache)){
            return Cache::get($this->cache);
        }else{
            return Cache::remember($this->cache, number_format(CACHE_DURATION), function () {
                return $this->model::selectRaw("*, 
                date_format(Date(date),'%d-%m-%Y') as formatedDate")->with('party','details')->get();
            });
        }
    }

    public function show($id)
    {
        return $this->model::selectRaw("*")->where('prid',$id)->where('balanceAmount','>',0)->whereIn('type',[2,3])->get();
    }
    

    public function edit($id)
    {
        return PurchaseModel::with('party','details')->find($id);
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(),
        [
            'details' => 'required',
        ], 
        [
            'details.required' => Helper::msgEnt('Details'),
        ]);
      
        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $input = $request->all();
        $input['no']=Helper::setFinancialYear($input['date'],'PurchaseModel','');
        $this->model = $this->model::create($input);
        Cache::forget($this->cache);
        return [ RETURN_DATA => $this->model, RETURN_SUCCESS =>  SAVE_SUCCESS];
    }

    public function update($request, $id)
    {
    
        $validator = Validator::make($request->all(),
        [
            'details' => 'required',
        ], 
        [
            'details.required' => Helper::msgEnt('Details'),
        ]);
      

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $this->model  = $this->model::find($id);
        if (empty($this->model)) {
            return [RETURN_VALIDATION => UPDATE_NO_RECORD];
        }
        $input = $request->all();

        $this->model->update($input);
        Cache::forget($this->cache);
        $this->model  = $this->model::find($id);
        return [ RETURN_DATA => $this->model, RETURN_SUCCESS =>  UPDATE_SUCCESS];
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
            return [RETURN_FAILURE => Helper::msgDl('Purchase')];
        }
    }

    public function print($id)
    {
        $data = $this->model::selectRaw("*,date_format(date, '%d-%m-%Y') as date")->with('details','party')->find($id);
        $data['height']=Helper::printFormatHeight(count($data->details));
        $data['title']='PurchaseOrder-'.$data->party->companyName.'-'.$data->no.'.pdf';
        $data['amountToWord']=Helper::numberToText($data->totalAmount);
        $data['company']=SettingModel::find(Auth::user()->userId);
        return PDF::loadView('purchase.print',compact('data'))->stream($data['title']);
    }

  
}