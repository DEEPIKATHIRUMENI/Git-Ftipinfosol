<?php

namespace App\Business\Repositories;

use Cache;
use Helper;
use Illuminate\Http\Request;
use ItemModel;
use Validator;

class ItemRepository
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new ItemModel;
        $this->cache = CACHE_ITEM;
    }

    public function index()
    {
        if (Cache::has($this->cache)) {
            return Cache::get($this->cache);
        } else {
            return Cache::remember($this->cache, number_format(CACHE_DURATION), function () {
                return $this->model::get();
            });
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required|unique:item',
            ],
            [
                'name.required' => Helper::msgEnt('Item Name'),
                'name.unique' => Helper::msgAl('Item Name'),
            ]
        );

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $input = $request->all();

        $this->model = $this->model::create($input);
        Cache::forget($this->cache);
        return [RETURN_DATA => $this->model, RETURN_SUCCESS => SAVE_SUCCESS];
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
            [
                'name' => "required|unique:item,name,$id,itid",
            ],
            [
                'name.required' => Helper::msgEnt('Item Name'),
                'name.unique' => Helper::msgAl('Item Name'),
            ]
        );

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $this->model = $this->model::find($id);
        if (empty($this->model)) {
            return [RETURN_VALIDATION => UPDATE_NO_RECORD];
        }
        $input=$request->all();
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
            return [RETURN_FAILURE => Helper::msgDl('Item')];
        }
    }
}
