<?php

namespace App\repositories\Json;

use App\repositories\Contracts\RepositoryInterface;

class JsonBaseRepository implements RepositoryInterface
{
    public function create(array $data)
    {
        $users = [];
        if (file_exists('users.json')) {
            $fileContents = file_get_contents('users.json');
            $users = !empty($fileContents) ? json_decode($fileContents, true) : [];
        }
        $data['id'] = empty($users) ? 1 : $users[count($users) - 1]['id'] + 1;
        $index = array_search('full_name', array_keys($data));
        $data = array_merge(array_slice($data, 0, $index), ['id' => $data['id']], array_slice($data, $index));
        array_push($users, $data);
        file_put_contents('users.json', json_encode($users));
    }

    public function update(int $id, array $data)
    {

    }

    public function all(array $where)
    {

    }

    public function delete(array $where)
    {

    }

    public function find(int $id)
    {

    }
}
