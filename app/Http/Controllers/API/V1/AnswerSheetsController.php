<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\contracts\ApiController;
use App\repositories\Contracts\AnswerSheetRepositoryInterface;
use App\repositories\Contracts\QuizRepositoryInterface;

class AnswerSheetsController extends ApiController
{
    public function __construct(private AnswerSheetRepositoryInterface $answerSheetRepository,private QuizRepositoryInterface $quizRepository)
    {

    }

    public function index(Request $request)
    {
        $this->validate($request,[
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'pagesize' => 'nullable|numeric'
        ]);

        $answerSheets = $this->answerSheetRepository->paginate($request->search,$request->page,$request->pagesize??0,['quiz_id','answers','status','score','finished_at']);

        return $this->respondSuccess('پاسخ نامه ها',$answerSheets);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'quiz_id' => 'required|numeric',
            'answers' => 'required|json',
            'status' => 'required|numeric',
            'score' => 'required|numeric',
            'finished_at' => 'required|date'
        ]);

        if (!$this->quizRepository->find($request->quiz_id)) {
            return $this->respondNotFound('آزمون یافت نشد');
        }

        $answerSheet = $this->answerSheetRepository->create([
            'quiz_id' => $request->quiz_id,
            'answers' => $request->answers,
            'status' => $request->status,
            'score' => $request->score,
            'finished_at' => $request->finished_at
        ]);

        return $this->respondCreated('پاسخ نامه ایجاد شد',[
            'quiz_id' => $answerSheet->getQuizId(),
            'answers' => json_encode($answerSheet->getAnswers()),
            'status' => $answerSheet->getStatus(),
            'score' => $answerSheet->getScore(),
            'finished_at' => $answerSheet->getFinishedAt()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request,[
            'id' => 'required|numeric'
        ]);

        if (!$this->answerSheetRepository->find($request->id)) {
           return $this->respondNotFound('پاسخ نامه یافت نشد');
        }

        if (!$this->answerSheetRepository->delete($request->id)) {
            return $this->respondInternalError('پاسخ نامه حذف نشد');
        }

        return $this->respondSuccess('پاسخ نامه حذف شد');
    }
}
