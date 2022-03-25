<?php

namespace App\Business\Repositories;
use InvoiceModel;
use InvoiceDetailModel;
use Cache;

class InvoiceDetailRepository 
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->modelMaster = new InvoiceModel;
        $this->modelDetail = new InvoiceDetailModel;
        $this->cache = CACHE_INVOICE;
    }
    public function store($request,$returnMaster)
    {
        $details = $request->details;
        foreach($details as $detail){
            if(isset($detail['item'])>0){
                $detail['invid']=$returnMaster->invid;
                $detail['itid']=$detail['item']['itid'];
                // $detail['uid']=$detail['unit']['uid'];
                $this->modelDetail::create($detail);
            }
        }
        $this->modelMaster=$this->modelMaster::selectRaw("*, 
        date_format(Date(date),'%d-%m-%Y') as formatedDate")->with('details','party')->find($returnMaster->invid);
        Cache::forget($this->cache);
        return [ RETURN_DATA => $this->modelMaster, RETURN_SUCCESS =>  SAVE_SUCCESS];
    }

    public function destroy($id)
    {
        $this->modelDetail::where('invid', $id)->delete();
        Cache::forget($this->cache);
        return [RETURN_SUCCESS => SAVE_SUCCESS];
    }

   
}