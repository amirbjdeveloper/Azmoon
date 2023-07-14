<?php

namespace App\Entities\Question;

interface QuestionEntity
{
    public function getId(): int;

    public function getTitle(): string;

    public function getOptions(): array;

    public function getIsActive(): int;

    public function getScore(): float;

    public function getQuizId(): int;
}