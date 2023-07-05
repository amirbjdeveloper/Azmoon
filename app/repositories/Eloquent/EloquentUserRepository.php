<?php

namespace App\repositories\Eloquent;

use App\Entities\User\UserEloquentEntity;
use App\Entities\User\UserEntity;
use App\Models\User;
use App\repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    protected $model = User::class;

    public function create(array $data)
    {
        $createdUser = parent::create($data);
        return new UserEloquentEntity($createdUser);
    }

    public function update(int $id, array $data): UserEntity
    {
        if (!parent::update($id,$data)) {
           throw new \Exception('کاربر بروزرسانی نشد');
        }

        return new UserEloquentEntity(parent::find($id));
    }
}