<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use ItemRepository;

class ItemService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new ItemRepository;
    }
   
    public function index()
    {
        return $this->repository->index();
    }

    public function store(Request $request)
    {
        return $this->repository->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->repository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
    
}
