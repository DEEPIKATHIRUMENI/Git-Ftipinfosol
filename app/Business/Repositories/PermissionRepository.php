<?php

namespace App\Business\Repositories;
use PermissionModel;
use Validator;
use Cache;
use Illuminate\Http\Request;

class PermissionRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new PermissionModel;
    }
    
    public function index($id)
    {
        $menu=$this->model::where('roid',$id)->where('type',1)->get();
        $menu['action']=$this->model::where('roid',$id)->where('type',2)->get();
        return $menu;
    }

    public function update(Request $request,$id)
    {
		$input=$request->all();

        foreach ($input['menuPermission'] as $detailMenu) {
            if($detailMenu!=null){
                $dm=$this->model::where('roid',$id)->where('type',1)->where('typeId',$detailMenu)->first();
                if(!isset($dm)){
                    $menuDetail['roid']=$id;
                    $menuDetail['type']=1;
                    $menuDetail['typeId']=$detailMenu;
                   $this->model::create($menuDetail);
                }

            }
		}

        foreach ($input['actionPermission'] as $detailAction) {
            if($detailAction!=null){
                $da=$this->model::where('roid',$id)->where('type',2)->where('typeId',$detailAction)->first();
                if(!isset($da)){
                    $actionDetail['roid']=$id;
                    $actionDetail['type']=2;
                    $actionDetail['typeId']=$detailAction;
                $this->model::create($actionDetail);
                }
            }
		}
		$data=$this->model::where('roid',$id)->get();

        return [ RETURN_DATA => $data, RETURN_SUCCESS =>  SAVE_SUCCESS];
    }

    public function destroy($id)
    {
        $this->model::where('roid',$id)->delete();
        return [RETURN_SUCCESS =>  DELETE_SUCCESS];
    }

}
