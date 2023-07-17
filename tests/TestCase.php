<?php

namespace Tests;

use Carbon\Carbon;
use App\Consts\QuestionStatus;
use App\Consts\AnswerSheetsStatus;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\repositories\Contracts\QuizRepositoryInterface;
use App\repositories\Contracts\CategoryRepositoryInterface;
use App\repositories\Contracts\QuestionRepositoryInterface;
use App\repositories\Contracts\AnswerSheetRepositoryInterface;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function createCategories(int $count=1): array
    {
        $categoryRepository = $this->app->make(CategoryRepositoryInterface::class);

        $newCategory = [
            'name' => 'Category Test',
            'slug' => 'Category-Test'
        ];

        $categories = [];

        foreach (range(0,$count) as $item) {
           $categories[] = $categoryRepository->create($newCategory);
        }

        return $categories;
    }

    protected function createQuiz(int $count=1, array $data=[]): array
    {
        $quizRepository = $this->app->make(QuizRepositoryInterface::class);

        $category = $this->createCategories()[0];

        $startDate = Carbon::now()->addDay();
        $duration = Carbon::now()->addDay();


        $quizData = empty($data) ? [
            'category_id' => $category->getId(),
            'title' => 'Quiz Test',
            'description' => 'This is a new Quiz for test',
            'start_date' => $startDate,
            'duration' => $duration->addMinutes(30)
        ] : $data;

        $quizzes = [];

        foreach (range(0, $count) as $item) {
            $quizzes[] = $quizRepository->create($quizData);
        }

        return $quizzes;
    }

    protected function createQuestion(int $count=1,array $data=[]):array
    {
        $questionRepository = $this->app->make(QuestionRepositoryInterface::class);

        $quiz = $this->createQuiz()[0];

        $questionData = empty($data) ? [
            'quiz_id' => $quiz->getId(),
            'title' => 'What is PHP?',
            'options' => json_encode([
                1 => ['text'=> 'PHP is Car.','is_correct'=>0],
                2 => ['text'=> 'PHP is programming language.','is_correct'=>1],
                3 => ['text'=> 'PHP is animal.','is_correct'=>0],
                4 => ['text'=> 'PHP is toy.','is_correct'=>0],
            ]),
            'is_active' => QuestionStatus::ACTIVE,
            'score' => 10,
        ] : $data;

        $questions = [];

        foreach (range(0, $count) as $item) {
            $questions[] = $questionRepository->create($questionData);
        }

        return $questions;
    }

    protected function createAnswerSheet(int $count=1,array $data=[]):array
    {
        $answerSheetRepository = $this->app->make(AnswerSheetRepositoryInterface::class);

        $quiz = $this->createQuiz()[0];

        $answerSheetData = empty($data) ? [
            'quiz_id' => $quiz->getId(),
            'answers' => json_encode([
                1 => 3,
                2 => 3,
                3 => 4
            ]),
            'status' => AnswerSheetsStatus::PASSED,
            'score' => 10,
            'finished_at' => Carbon::now()
        ] : $data;

        $answerSheets = [];

        foreach (range(0, $count) as $item) {
            $answerSheets[] = $answerSheetRepository->create($answerSheetData);
        }

        return $answerSheets;
    }

}
