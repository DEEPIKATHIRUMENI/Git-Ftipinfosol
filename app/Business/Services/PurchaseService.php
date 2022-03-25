<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use PurchaseRepository;
use PurchaseDetailRepository;

class PurchaseService
{
    protected $repositoryMaster;
    protected $repositoryDetail;

    public function __construct()
    {
        $this->repositoryMaster = new PurchaseRepository;
        $this->repositoryDetail = new PurchaseDetailRepository;
    }

    public function index($request)
    {
        return $this->repositoryMaster->index();
    }

    public function store(Request $request)
    {
        $this->repositoryMaster = $this->repositoryMaster->store($request);
        if (!empty($this->repositoryMaster[RETURN_VALIDATION])) {
            return $this->repositoryMaster;
        }
        $this->repositoryMaster = $this->repositoryDetail->store($request,$this->repositoryMaster['data']);
        return $this->repositoryMaster;
    }

    public function update($request, $id)
    {
        $this->repositoryMaster = $this->repositoryMaster->update($request, $id);
        if (!empty($this->repositoryMaster[RETURN_VALIDATION])) {
            return $this->repositoryMaster;
        }
        $this->repositoryDetail->destroy($this->repositoryMaster['data']['purid']);
        $this->repositoryMaster = $this->repositoryDetail->store($request,$this->repositoryMaster['data']);

        return $this->repositoryMaster;
    }

    public function edit($id)
    {
        return $this->repositoryMaster->edit($id);
    }
    public function show($id)
    {
        return $this->repositoryMaster->show($id);
    }
    public function print($id)
    {
        return $this->repositoryMaster->print($id);
    }

    public function destroy($id)
    {
        $this->repositoryMaster = $this->repositoryMaster->destroy($id);
        if (!empty($this->repositoryMaster[RETURN_VALIDATION])) {
            return $this->repositoryMaster;
        }
        $this->repositoryDetail->destroy($id);
        return $this->repositoryMaster;
    }

    

}
