<?php

namespace App\repositories\Eloquent;

use App\Entities\AnswerSheet\AnswerSheetEloquentEntity;
use App\Models\AnswerSheet;
use App\repositories\Eloquent\EloquentBaseRepository;
use App\repositories\Contracts\AnswerSheetRepositoryInterface;


class EloquentAnswerSheetRepository extends EloquentBaseRepository implements AnswerSheetRepositoryInterface
{
    protected $model = AnswerSheet::class;

    public function create(array $data)
    {
        $createdAnswerSheet = parent::create($data);

        return new AnswerSheetEloquentEntity($createdAnswerSheet);
    }
} 