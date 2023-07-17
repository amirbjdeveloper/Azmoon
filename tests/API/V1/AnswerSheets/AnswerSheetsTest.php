<?php

namespace Tests\API\V1\AnswerSheets;

use App\Consts\AnswerSheetsStatus;
use Carbon\Carbon;
use Tests\TestCase;

class AnswerSheetsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_ensure_we_can_create_a_answer_sheet()
    {
        $quiz = $this->createQuiz()[0];

        $answerSheetData = [
            'quiz_id' => $quiz->getId(),
            'answers' => json_encode([
                1 => 3,
                2 => 3,
                3 => 4
            ]),
            'status' => AnswerSheetsStatus::PASSED,
            'score' => 10,
            'finished_at' => Carbon::now()
        ];

        $response = $this->call('POST','api/v1/answer-sheets',$answerSheetData);

        $responseData = json_decode($response->getContent(),true)['data'];

        $responseData['finished_at'] = Carbon::parse($responseData['finished_at'])->format('Y-m-d H:i:s');
        $answerSheetData['finished_at'] = $answerSheetData['finished_at']->format('Y-m-d H:i:s');
        
        $this->assertJson($responseData['answers']);
        $this->assertEquals(201,$response->getStatusCode());

        $this->seeInDatabase('answer_sheets',$answerSheetData);
        $this->assertEquals($answerSheetData['quiz_id'],$responseData['quiz_id']);
        $this->assertEquals($answerSheetData['answers'],$responseData['answers']);
        $this->assertEquals($answerSheetData['status'],$responseData['status']);
        $this->assertEquals($answerSheetData['score'],$responseData['score']);
        $this->assertEquals($answerSheetData['finished_at'],$responseData['finished_at']);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'quiz_id',
                'answers',
                'status',
                'score',
                'finished_at'
            ]
        ]);
    }

    public function test_ensure_we_can_delete_a_answer_sheet()
    {
        $answerSheet = $this->createAnswerSheet()[0];

        $response = $this->call('DELETE','api/v1/answer-sheets',[
            'id' => $answerSheet->getId(),
        ]);

        $this->assertEquals(200,$response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_that_we_can_get_answer_sheet()
    {
        $this->createAnswerSheet(30);
        $pagesize = 3;

        $response = $this->call('GET', 'api/v1/answer-sheets', [
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

    public function test_ensure_that_we_can_get_filtered_answer_sheet()
    {
        $quiz = $this->createQuiz()[0];
        $serachKey = 50;
        $pagesize = 3;

        $this->createAnswerSheet(data:[
            'quiz_id' => $quiz->getId(),
            'answers' => json_encode([
                1 => 3,
                2 => 3,
                3 => 4
            ]),
            'status' => AnswerSheetsStatus::PASSED,
            'score' => $serachKey,
            'finished_at' => Carbon::now()
        ]);

        $response = $this->call('GET', 'api/v1/answer-sheets', [
            'page' => 1,
            'search' => (string) $serachKey,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(), true);

        foreach ($data['data'] as $answerSheet) {
            $this->assertEquals($answerSheet['score'], $serachKey);
        }

        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }
}