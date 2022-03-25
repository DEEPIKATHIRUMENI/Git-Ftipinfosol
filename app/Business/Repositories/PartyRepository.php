<?php

namespace App\Business\Repositories;

use Cache;
use Helper;
use Illuminate\Http\Request;
use PartyModel;
use Validator;

class PartyRepository
{
    protected $model;
    protected $cache;
    public function __construct()
    {
        $this->model = new PartyModel;
        $this->cache = CACHE_PARTY;
    }

    public function index()
    {
        Cache::forget($this->cache);

        if (Cache::has($this->cache)) {
            return Cache::get($this->cache);
        } else {
            return Cache::remember($this->cache, number_format(CACHE_DURATION), function () {
                return $this->model::
                selectRaw("*, 
                    (CASE
                        WHEN mobile is null THEN customerName
                        WHEN mobile is not null THEN concat(customerName,' - ',mobile)
                    END) as partyDetails")
                ->get();
            });
        }

    }

    public function show()
    {

    }

    public function store(Request $request)
    {
        //TODO: add validation
        $validator = Validator::make($request->all(),
            [
                'customerName' => 'required|unique:party',
            ],
            [
                'customerName.required' => Helper::msgEnt('Customer Name'),
                'customerName.unique' => Helper::msgAl('Customer Name'),
            ]
        );

        if ($validator->fails()) {
            return [RETURN_VALIDATION => $validator->errors()];
        }

        $input = $request->all();

        //TODO: handle party type

        $this->model = $this->model::create($input);
        Cache::forget($this->cache);
        return [RETURN_DATA => $this->model, RETURN_SUCCESS => SAVE_SUCCESS];
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
            [
                'customerName' => "required|unique:party,customerName,$id,prid",
            ],
            [
                'customerName.required' => Helper::msgEnt('Customer Name'),
                'CustomerName.unique' => Helper::msgAl('Customer Name'),
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
            return [RETURN_FAILURE => Helper::msgDl('Customer')];
        }
    }
}
