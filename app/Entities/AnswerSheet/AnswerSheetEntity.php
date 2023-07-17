<?php

namespace App\Entities\AnswerSheet;

use Carbon\Carbon;

interface AnswerSheetEntity
{
    public function getId(): int;

    public function getQuizId(): int;

    public function getAnswers(): array;

    public function getStatus(): int;

    public function getScore(): float|null;

    public function getFinishedAt(): Carbon;
}