<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\contracts\ApiController;
use App\repositories\Contracts\QuestionRepositoryInterface;
use App\repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Http\Request;

class QuestionsController extends ApiController
{
    public function __construct(private QuestionRepositoryInterface $questionRepository,private QuizRepositoryInterface $quizRepository)
    {

    }

    public function index(Request $request)
    {
        $this->validate($request,[
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'pagesize' => 'nullable|numeric'
        ]);

        $questions = $this->questionRepository->paginate($request->search,$request->page,$request->pagesize,['title','score','is_active','options','quiz_id']);

        return $this->respondSuccess('سوالات',$questions);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'score' => 'required|numeric',
            'is_active' => 'required|numeric',
            'quiz_id' => 'required|numeric',
            'options' => 'required|json',
        ]);

        if (!$this->quizRepository->find($request->quiz_id)) {
           return $this->respondNotFound('آزمون وجود ندارد');
        }

        $question = $this->questionRepository->create([
            'title' => $request->title,
            'score' => $request->score,
            'is_active' => $request->is_active,
            'quiz_id' => $request->quiz_id,
            'options' => $request->options
        ]);

        return $this->respondCreated('سوال ایجاد شد', [
            'title' => $question->getTitle(),
            'score' => $question->getScore(),
            'is_active' => $question->getIsActive(),
            'quiz_id' => $question->getQuizId(),
            'options' => $question->getOptions()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request,[
            'id'=>'required|numeric'
        ]);

        if (!$this->questionRepository->find($request->id)) {
            return $this->respondNotFound('سوال وجود ندارد');
        }

        if (!$this->questionRepository->delete($request->id)) {
           return $this->respondInternalError('سوال حذف نشد');
        }

        return $this->respondSuccess('سوال حذف شد');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'title' => 'required|string',
            'score' => 'required|numeric',
            'is_active' => 'required|numeric',
            'quiz_id' => 'required|numeric',
            'options' => 'required|json',
        ]);

        if (!$this->questionRepository->find($request->id)) {
            return $this->respondNotFound('سوال وجود ندارد');
        }

        try {
            $updatedQuestion = $this->questionRepository->update($request->id,[
                'title' => $request->title,
                'score' => $request->score,
                'is_active' => $request->is_active,
                'quiz_id' => $request->quiz_id,
                'options' => $request->options
            ]);
    
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }

      
        return $this->respondSuccess('سوال بروزرسانی شد',[
            'title' => $updatedQuestion->getTitle(),
            'score' => $updatedQuestion->getScore(),
            'is_active' => $updatedQuestion->getIsActive(),
            'quiz_id' => $updatedQuestion->getQuizId(),
            'options' => $updatedQuestion->getOptions()
        ]);
    }
}
