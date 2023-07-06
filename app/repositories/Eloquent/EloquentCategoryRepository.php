<?php

namespace App\repositories\Eloquent;

use App\Entities\Category\CategoryEloquentEntity;
use App\Entities\Category\CategoryEntity;
use App\Models\Category;
use App\repositories\Eloquent\EloquentBaseRepository;
use App\repositories\Contracts\CategoryRepositoryInterface;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepositoryInterface
{
    protected $model = Category::class;

    public function create(array $data)
    {
        $createdCategory = parent::create($data);
        return new CategoryEloquentEntity($createdCategory);
    }

    public function update(int $id, array $data): CategoryEntity
    {
        if (!parent::update($id,$data)) {
           throw new \Exception('دسته بندی بروزرسانی نشد');
        }

        return new CategoryEloquentEntity(parent::find($id));
    }
}