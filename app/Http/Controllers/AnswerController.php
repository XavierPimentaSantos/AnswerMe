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
        
        $answer->edited = true;
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

    public function inc_score(Request $request)
    {
        $answer = Answer::findOrFail($request->input('answer_id'));
        
        if(Auth::user()->answerUpVotes()->where('answer_id', $answer->id)->exists()) {
            Auth::user()->answerUpVotes()->detach($answer->id);
            $score = $answer->score;
            $answer->score = $score - 1;
            $answer->save();
        }
        else {
            if(Auth::user()->answerDownVotes()->where('answer_id', $answer->id)->exists()) {
                Auth::user()->answerDownVotes()->detach($answer->id);
                Auth::user()->answerUpVotes()->attach($answer->id);
                $score = $answer->score;
                $answer->score = $score + 2;
                $answer->save();
            }
            else {
                Auth::user()->answerUpVotes()->attach($answer->id);
                $score = $answer->score;
                $answer->score = $score + 1;
                $answer->save();
            }
        }

        return view('partials.answer_score', ['answer_id' => $answer->id])->render();
    }

    public function dec_score(Request $request)
    {
        $answer = answer::findOrFail($request->input('answer_id'));

        if(Auth::user()->answerDownVotes()->where('answer_id', $answer->id)->exists()) {
            Auth::user()->answerDownVotes()->detach($answer->id);
            $score = $answer->score;
            $answer->score = $score + 1;
            $answer->save();
        }
        else {
            if(Auth::user()->answerUpVotes()->where('answer_id', $answer->id)->exists()) {
                Auth::user()->answerUpVotes()->detach($answer->id);
                Auth::user()->answerDownVotes()->attach($answer->id);
                $score = $answer->score;
                $answer->score = $score - 2;
                $answer->save();
            }
            else {
                Auth::user()->answerDownVotes()->attach($answer->id);
                $score = $answer->score;
                $answer->score = $score - 1;
                $answer->save();
            }
        }

        return view('partials.answer_score', ['answer_id' => $answer->id])->render();
    }
}
