<?php

namespace App\repositories\Json;

use App\Entities\User\UserEntity;
use App\Entities\User\UserJsonEntity;
use App\repositories\Contracts\UserRepositoryInterface;
use App\repositories\Json\JsonBaseRepository;

class JsonUserRepository extends JsonBaseRepository implements UserRepositoryInterface
{
    protected $jsonModel = 'users.json';

    public function create(array $data) : UserEntity
    {
        $newUser = parent::create($data);
        return new UserJsonEntity($newUser);
    }

    public function find(int $id): UserEntity
    {
        $user = parent::find($id);
        return new UserJsonEntity($user);
    }
}