<?php

namespace App\Business\Repositories;
use PurchaseModel;
use PurchaseDetailModel;
use Cache;

class PurchaseDetailRepository 
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->modelMaster = new PurchaseModel;
        $this->modelDetail = new PurchaseDetailModel;
        $this->cache = CACHE_PURCHASE;
    }
    public function store($request,$returnMaster)
    {
        $details = $request->details;
        foreach($details as $detail){
            if(isset($detail['item'])&&$detail['quantity']>0){
                $detail['purid']=$returnMaster->purid;
                if(isset($detail['item'])) $detail['itid']=$detail['item']['itid'];
                $this->modelDetail::create($detail);
            }
        }
        $this->modelMaster=$this->modelMaster::selectRaw("*, 
        date_format(Date(date),'%d-%m-%Y') as formatedDate,
        DATEDIFF(DATE(dueDate),CURDATE()) AS dueDays")->with('details','party')->find($returnMaster->purid);
        Cache::forget($this->cache);
        return [ RETURN_DATA => $this->modelMaster, RETURN_SUCCESS =>  SAVE_SUCCESS];
    }

    public function destroy($id)
    {
        $this->modelDetail::where('purid', $id)->delete();
        Cache::forget($this->cache);
        return [RETURN_SUCCESS => SAVE_SUCCESS];
    }

   
}