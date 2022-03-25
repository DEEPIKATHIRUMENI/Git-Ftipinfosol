<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use ThemeRepository;

class ThemeService
{
    protected $repository,$branchRepository;

    public function __construct()
    {
        $this->repository = new ThemeRepository;
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

}