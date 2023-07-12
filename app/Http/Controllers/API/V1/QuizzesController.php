<?php

namespace App\Http\Controllers\API\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\API\contracts\ApiController;
use App\repositories\Contracts\QuizRepositoryInterface;

class QuizzesController extends ApiController
{
    public function __construct(private QuizRepositoryInterface $quizRepository)
    {
    }

    public function store(Request $request)
    {
        
        $this->validate($request,[
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'duration' => 'required|date'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $duration = Carbon::parse($request->duration);

        if ($duration->timestamp < $startDate->timestamp) {
           return $this->respondInvalidValidation('تاریخ شروع باید از زمان آزمون بزرگتر باشد');
        }
        
        $createdQuiz = $this->quizRepository->create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $startDate->format('Y-m-d'),
            'duration' => $duration
        ]);

        return $this->respondCreated('آزمون ساخته شد',[
            'category_id' => $createdQuiz->getCategoryId(),
            'title' => $createdQuiz->getTitle(),
            'description' => $createdQuiz->getDescription(),
            'start_date' => $createdQuiz->getStartDate(),
            'duration' => Carbon::parse($createdQuiz->getDuration())->timestamp
        ]);
    }
}