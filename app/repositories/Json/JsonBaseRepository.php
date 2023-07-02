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
        $data['id'] = empty($users) ? 1 : $users[count($users)-1]['id'] + 1;
        $index = array_search('full_name', array_keys($data));
        $data = array_merge(array_slice($data, 0, $index), ['id' => $data['id']], array_slice($data, $index));
        array_push($users, $data);
        file_put_contents('users.json', json_encode($users));
    }

    public function update(int $id, array $data)
    {
        $users = json_decode(file_get_contents('users.json'), true);
        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                $user['full_name'] = $data['full_name'] ?? $user['full_name'];
                $user['email'] = $data['email'] ?? $user['email'];
                $user['mobile'] = $data['mobile'] ?? $user['mobile'];
                $user['password'] = $data['password'] ?? $user['password'];
                break;
            }
        }
        file_put_contents('users.json', json_encode($users));
    }

    public function delete(int $id)
    {
        $users = json_decode(file_get_contents('users.json'), true);
        foreach ($users as $key => $user) {
            if ($user['id'] == $id) {
                unset($users[$key]);
                break;
            }
        }
        file_put_contents('users.json', json_encode(array_values($users)));
    }

    public function paginate(string $search=null,int $page,int $pagesize=20)
    {
        $users = json_decode(file_get_contents(base_path().'/users.json'),true);

        if (!is_null($search)) {
            foreach ($users as $key => $user) {
                if (array_search($search,$user)) {
                    return $users[$key];
                }
            }
        }

        $totalRecords = count($users);
        $totalPages = ceil($totalRecords/$pagesize);

        if ($page>$totalPages) {
            $page = $totalPages;
        }

        if ($page<1) {
            $page=1;
        }

        $offset = ($page-1) * $pagesize;
        
        return array_slice($users,$offset,$pagesize);
    }

    public function deleteBy(array $where)
    {

    }

    public function all(array $where)
    {

    }

    public function find(int $id)
    {

    }
}
