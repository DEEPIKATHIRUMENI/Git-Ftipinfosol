<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use PermissionRepository;

class PermissionService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new PermissionRepository;
    }
    
    public function index($id)
    {
        return $this->repository->index($id);
    }

    public function update($request, $id)
    {
        $this->repository->destroy($id);
        return $this->repository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
