<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Answer;
use App\Models\Question;  
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Answer::latest()->paginate(10);
        return view();
    }

    public function store(Request $request, $question_id)
    {

        $question = Question::findOrFail($question_id);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $answer = Answer::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'correct' => false,
            'score' => 0,
            'edited' => false,
            'question_id' => $question_id,
            'user_id' => Auth::user()->id,
        ]);

        $answer->save();

        // You may want to associate the question with the currently authenticated user
        // $question->user()->associate(auth()->user())->save();

        return redirect()->route('questions.show', $question->id)
            ->with('success', 'Answer created successfully');
    }


    public function edit(Request $request, $question_id, $answer_id)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

<<<<<<< HEAD
        if ($validator->fails()) {
            return redirect()
                ->route('pages.question', $question_id)
                ->withErrors($validator)
                ->withInput();
        }

        $answer = Answer::findOrFail($answer_id);

        if ($answer->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized');
        }


        $answer->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);
        
=======
        $answer->title = $request->input('title');
        $answer->content = $request->input('content');
        $answer->edited = true;
>>>>>>> feature/validate-answers
        $answer->save();

        return redirect()->route('questions.show', $question_id)->with('success', 'Question updated successfully');
    }

    public function delete(Request $request, $question_id, $answer_id)
    {
        
        $answer = Answer::findOrFail($answer_id);

        if ($answer->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized');
        }
    

        $answer->delete();

        return redirect()->route('questions.show', ['id' => $question_id]);
    }

    public function validate_answer(Request $request)
    {
        $answer = Answer::findOrFail($request->input('answer_id'));
        $answer->correct = true;
        $answer->save();
    }
}

