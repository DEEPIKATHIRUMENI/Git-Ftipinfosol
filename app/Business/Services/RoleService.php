<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use RoleRepository;

class RoleService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new RoleRepository;
    }

    public function index($request)
    {
        return $this->repository->index();
    }

    public function store(Request $request)
    {
        return $this->repository->store($request);
    }

    public function update($request, $id)
    {
        return $this->repository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    

}