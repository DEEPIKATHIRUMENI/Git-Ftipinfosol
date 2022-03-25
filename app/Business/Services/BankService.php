<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use BankRepository;

class BankService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BankRepository;
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function store(Request $request)
    {
        $this->repository = $this->repository->store($request);
        if($this->repository['data']['defaultbank']==1){
            $this->repository = $this->repository->defaultBank($this->repository['data']['baid']);
        }
        return $this->repository;
    }

    public function update($request, $id)
    {
        $this->repository = $this->repository->update($request, $id);
        if($this->repository['data']['defaultbank']==1){
            $this->repository = $this->repository->defaultBank($this->repository['data']['baid']);
        }
        return $this->repository;
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

}
