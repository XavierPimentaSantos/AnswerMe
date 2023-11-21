<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Answer;
use App\Models\Question;  

use Illuminate\Support\Facades\Auth;

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
        $answer = Answer::findOrFail($answer_id);

        if ($answer->question->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized');
        }
    

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $answer->title = $request->input('title');
        $answer->content = $request->input('content');

        $answer->save();
    }

    public function delete(Request $request, $question_id, $answer_id)
    {
        
        $answer = Answer::findOrFail($answer_id);

        if ($answer->question->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized');
        }
    

        $answer->delete();

        return redirect()->route('questions.show', ['id' => $question_id]);
    }
}

