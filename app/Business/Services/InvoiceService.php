<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use InvoiceRepository;
use InvoiceDetailRepository;

class InvoiceService
{
    protected $repositoryMaster;
    protected $repositoryDetail;

    public function __construct()
    {
        $this->repositoryMaster = new InvoiceRepository;
        $this->repositoryDetail = new InvoiceDetailRepository;
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
        $this->repositoryDetail->destroy($this->repositoryMaster['data']['invid']);
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

    public function printCopy($request,$id)
    {
        return $this->repositoryMaster->printCopy($request,$id);
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
