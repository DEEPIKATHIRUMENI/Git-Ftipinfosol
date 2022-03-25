<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use AccountInfoRepository;
use BranchRepository;
use ThemeRepository;

class AccountInfoService
{
    protected $repository,$branchRepository, $themeRepository;

    public function __construct()
    {
        $this->repository = new AccountInfoRepository;
        $this->branchRepository = new BranchRepository;
        $this->themeRepository = new ThemeRepository;
    }

    public function index($request)
    {
        return $this->repository->index();
    }

    public function update($request, $id)
    {
        return $this->repository->update($request, $id);
    }
}