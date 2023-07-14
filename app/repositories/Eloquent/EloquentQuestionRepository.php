<?php

namespace App\repositories\Eloquent;

use App\Entities\Question\QuestionEloquentEntity;
use App\Models\Question;
use App\repositories\Eloquent\EloquentBaseRepository;
use App\repositories\Contracts\QuestionRepositoryInterface;

class EloquentQuestionRepository extends EloquentBaseRepository implements QuestionRepositoryInterface
{
    protected $model = Question::class;

    public function create(array $data)
    {
        $createdQuestion = parent::create($data);

        return new QuestionEloquentEntity($createdQuestion);
    }
}