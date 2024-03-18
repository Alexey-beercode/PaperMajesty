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

    public function createUser($login,$password,$name,$email)
    {
        $this->userRepository->create($login,$password,$name,$email);
        return $this->userRepository->getUserByUsername($name);
    }
    public function authenticate($login, $password) {
        // Получаем пользователя по имени пользователя из базы данных
        $user = $this->userRepository->getUserByLogin($login);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Проверяем, найден ли пользователь и совпадает ли пароль
        if ($user && password_verify($hashedPassword, $user['passwordHash'])) {
            // Пользователь найден и пароль совпадает, возвращаем данные пользователя
            return $user;
        } else {
            // Пользователь не найден или пароль не совпадает, возвращаем null
            return null;
        }
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
