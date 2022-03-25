<?php

namespace App\Business\Services;
use Illuminate\Http\Request;
use EmployeeRepository;
use UserRepository;

class EmployeeService
{
    protected $repository,$userRepository;

    public function __construct()
    {
        $this->repository = new EmployeeRepository;
        $this->userRepository = new UserRepository;
    }

    public function index()
    {
        return $this->repository->index();
    }

    public function store(Request $request)
    {
        $this->repository = $this->repository->store($request);
        if(isset($this->repository['data'])){
            $request->request->add([
                'empid' => $this->repository['data']['empid'], 
            ]);
            if($this->repository['data']['hasLogin']==1){
                $returnUserRepository = $this->userRepository->store($request);
            }
        }
      
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
