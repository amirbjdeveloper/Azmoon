<?php

namespace Tests\API\V1\Quizzes;

use app;
use Carbon\Carbon;
use Tests\TestCase;
use App\repositories\Contracts\QuizRepositoryInterface;

class QuizzesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

    public function test_ensure_that_we_can_create_a_new_quiz()
    {
        $category = $this->createCategories()[0];

        $startDate = Carbon::now()->addDay();
        $duration = Carbon::now()->addDay();

        $quizData = [
            'category_id' => $category->getId(),
            'title' => 'Quiz Test',
            'description' => 'This is a new Quiz for test',
            'start_date' => $startDate,
            'duration' => $duration->addMinutes(60),
            'is_active' => true
        ];

        $response = $this->call('POST', 'api/v1/quizzes', $quizData);
        $responseData = json_decode($response->getContent(), true)['data'];
        $quizData['start_date'] = $quizData['start_date']->format('Y-m-d');
        $quizData['duration'] = $quizData['duration']->format('Y-m-d H:i:s');

        $this->assertEquals(201, $response->getStatusCode());
        $this->seeInDatabase('quizzes', $quizData);
        $this->assertEquals($quizData['title'], $responseData['title']);
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'category_id',
                'title',
                'description',
                'start_date',
                'duration'
            ]
        ]);

    }

    public function test_ensure_that_we_can_delete_a_quiz()
    {
        $quiz = $this->createQuiz()[0];

        $response = $this->call('DELETE', 'api/v1/quizzes', [
            'id' => $quiz->getId()
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_that_we_can_get_quizzes()
    {
        $this->createQuiz(30);
        $pagesize = 3;

        $response = $this->call('GET', 'api/v1/quizzes', [
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(), true);

        $this->assertEquals($pagesize, count($data['data']));
        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_that_we_can_get_filtered_quiz()
    {
        $this->createQuiz(30);
        $category = $this->createCategories()[0];
        $startDate = Carbon::now()->addDay();
        $duration = Carbon::now()->addDay();
        $serachKey = 'Specific Quiz';

        $this->createQuiz(data:[
            'category_id' => $category->getId(),
            'title' => $serachKey,
            'description' => 'This is a Specific Quiz for test',
            'start_date' => $startDate,
            'duration' => $duration->addMinutes(30)
        ]);

        $pagesize = 3;

        $response = $this->call('GET', 'api/v1/quizzes', [
            'page' => 1,
            'search' => $serachKey,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(), true);

        foreach ($data['data'] as $quiz) {
            $this->assertEquals($quiz['title'], $serachKey);
        }

        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_that_we_can_update_quiz()
    {
        $quiz = $this->createQuiz()[0];
        $category = $this->createCategories()[0];
        $startDate = Carbon::now()->addDay();
        $duration = Carbon::now()->addDay();

        $quizData = [
            'id' => $quiz->getId(),
            'category_id' => $category->getId(),
            'title' => 'Quiz Updated',
            'description' => 'This is a Updated Quiz for test',
            'start_date' => $startDate,
            'duration' => $duration->addMinutes(60),
            'is_active' => false
        ];


        $response = $this->call('PUT','api/v1/quizzes',$quizData);

        $data = json_decode($response->getContent(),true)['data'];

        $this->assertEquals(200,$response->getStatusCode());
        $this->assertEquals($data['title'],$quizData['title']);
        $this->assertEquals($data['description'],$quizData['description']);
        $this->assertEquals($data['is_active'],$quizData['is_active']);
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'category_id',
                'title',
                'description',
                'duration',
                'start_date',
                'is_active',
            ]
        ]);
    }

    private function createQuiz(int $count=1, array $data=[]): array
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
}
