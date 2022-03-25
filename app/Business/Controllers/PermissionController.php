<?php

namespace App\Business\Controllers;
use Illuminate\Http\Request;
use Controller;
use PermissionService;
use DB;

class PermissionController extends Controller
{

    protected $service;
    public function __construct()
    {
        $this->service = new PermissionService;
    }

    public function index(Request $request)
    {
        return $this->service->index($request->roid);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $this->service = $this->service->update($request, $id);

        if (!empty($this->service)) {
            if (!empty($this->service[RETURN_VALIDATION] )) {
                return response($this->service, 422);
            }
            DB::commit();
            return response($this->service);
        }
    }

}
