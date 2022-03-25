<?php

namespace App\Business\Controllers;

use Controller;
use DB;
use EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new EmployeeService;
    }

    public function index()
    {
        return response($this->service->index());
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

    public function destroy($id)
    {
        DB::beginTransaction();
        $this->service = $this->service->destroy($id);

        if (!empty($this->service)) {
            if (!empty($this->service[RETURN_FAILURE])) {
                return response($this->service, 422);
            }

            DB::commit();
            return response($this->service);
        }
    }
}
