<?php

namespace App\Business\Controllers;
use Illuminate\Http\Request;
use Controller;
use ThemeService;
use DB;

class ThemeController extends Controller
{

    protected $service;
    public function __construct()
    {
        $this->service = new ThemeService;
    }

    public function index(Request $request)
    {
        return $this->service->Index($request);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $this->service = $this->service->store($request);
        if (!empty($this->service)) {
            if (!empty($this->service[RETURN_VALIDATION])) {
                return response($this->service, 422);
            }
            DB::commit();
            return response($this->service);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $this->service = $this->service->update($request, $id);

        if (!empty($this->service)) {
            if (!empty($this->service[RETURN_VALIDATION])) {
                return response($this->service, 422);
            }
            DB::commit();
            return response($this->service);
        }
    }

}
