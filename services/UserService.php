<?php

namespace services;

use Exception;
use repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser($user)
    {
        $this->userRepository->create($user);
    }

    public function getUser($id)
    {
        $user=$this->userRepository->find($id);
        if ($user==null)
            throw new Exception("No User");
        return $user;
    }

    public function updateUser($user)
    {

        $this->userRepository->update($user);
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    public function deleteUser($id)
    {
        $this->userRepository->delete($id);
    }
}
