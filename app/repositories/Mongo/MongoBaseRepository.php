<?php

namespace App\repositories\Mongo;

use App\repositories\Contracts\RepositoryInterface;

class MongoBaseRepository implements RepositoryInterface
{
    public function create(array $data)
    {

    }

    public function paginate(string $search=null,int $page,int $pagesize=20)
    {
        
    }

    public function update(int $id,array $data)
    {

    }

    public function all(array $where)
    {

    }

    public function delete(int $id)
    {

    }

    public function find(int $id)
    {
        
    }

    public function deleteBy(array $where)
    {

    }
}