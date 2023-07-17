<?php

namespace App\Entities\AnswerSheet;

use Carbon\Carbon;
use App\Models\AnswerSheet;
use App\Entities\AnswerSheet\AnswerSheetEntity;

class AnswerSheetEloquentEntity implements AnswerSheetEntity
{
    private $answerSheet;

    public function __construct(AnswerSheet $answerSheet)
    {
        $this->answerSheet = $answerSheet;
    }

    public function getId(): int
    {
        return $this->answerSheet->id;
    }

    public function getQuizId(): int
    {
        return $this->answerSheet->quiz_id;
    }

    public function getAnswers(): array
    {
        return json_decode($this->answerSheet->answers,true);
    }

    public function getStatus(): int
    {
        return $this->answerSheet->status;
    }

    public function getScore(): float|null
    {
        return $this->answerSheet?->score;
    }

    public function getFinishedAt(): Carbon
    {
        return Carbon::parse($this->answerSheet->finished_at);
    }
}