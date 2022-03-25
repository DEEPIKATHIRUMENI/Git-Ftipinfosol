<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use SettingRepository;
use BranchRepository;
use ThemeRepository;

class SettingService
{
    protected $repository,$branchRepository, $themeRepository;

    public function __construct()
    {
        $this->repository = new SettingRepository;
        $this->branchRepository = new BranchRepository;
        $this->themeRepository = new ThemeRepository;
    }

    public function index($request)
    {
        return $this->repository->index();
    }

    public function store(Request $request)
    {
        $this->repository = $this->repository->store($request);
        $this->branchRepository->store($request);
        $this->themeRepository->store();
        return $this->repository;
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