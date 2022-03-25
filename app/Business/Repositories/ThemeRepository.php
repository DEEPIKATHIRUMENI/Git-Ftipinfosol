<?php

namespace App\Business\Repositories;
use ThemeModel;
use Validator;
use Cache;
use Auth;
use Input;
use Str;

class ThemeRepository
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new ThemeModel;
    $this->cache = CACHE_THEME;
    }
    public function index()
    {
        return $this->model::where('userId',Auth::id())->first();
    }

    public function store()
    {
        $input['headerFixed']=1;
        $input['asideFixed']=1;
        $input['asideFolded']=0;
        $input['asideDock']=0;
        $input['container']=0;
        $input['customTheme']=0;
        $input['customPrimaryDark']="#1359a0";
        $input['customPrimary']="#1976D2";
        $input['customAccent']="#f50057";
        $input['themeID']=0;
        $input['themes']="[{ primary: '#1976D2', primaryDark: '#1359a0', accent: '#f50057' }, { primary: '#FF0080', primaryDark: '#e6006b', accent: '#3d5afe' }, { primary: '#ff5722', primaryDark: '#e63600', accent: '#034A75' }, { primary: '#077130', primaryDark: '#044f21', accent: '#ff1744' }, { primary: '#555555', primaryDark: '#595959', accent: '#f50057' }, { primary: '#00897b', primaryDark: '#004d45', accent: '#ff3d00' }, { primary: '#e53935', primaryDark: '#b51a17', accent: '#3d5afe' }, { primary: '#5e35b1', primaryDark: '#3f2376', accent: '#f50057' }, { primary: '#3949ab', primaryDark: '#263073', accent: '#f50057' }, { primary: '#1e88e5', primaryDark: '#156bb7', accent: '#f50057' }]";
        $input['sid']=SettingModel::where('userId',Auth::id())->first()->sid;
        $this->model = $this->model::create($input);
        Cache::forget($this->cache);
        return [RETURN_DATA => $this->model, RETURN_SUCCESS => SAVE_SUCCESS];
    }

    public function update($request, $id)
    {
        $this->model = $this->model::find($id);
        $input = $request->all();
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
            return [ RETURN_FAILURE => DELETE_REFUSE . 'Theme already exist in ______'];
        }

    }
}
