<?php

namespace App\Http\Controllers;

use App\Models\AnswerImage;


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

        if($question->user_id === Auth::user()->id) {
            return view('partials.answer', ['answers' => $question->answers()->get()])->render();
        }

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

        

        $answerImagePath = 'answer_images/' . $answer->id . '/';
        $images = $request->file('images');

        if ($images) {
            foreach ($images as $index => $image) {
                $format = $index + 1; // 1.format, 2.format, etc.
                $uploadedPath = $answerImagePath . $format . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($answerImagePath), $format . '.' . $image->getClientOriginalExtension());
                AnswerImage::create([
                    'format' => $format, // '1', '2', '3
                    'picture_path' => $uploadedPath,
                    'answer_id' => $answer->id,
                ]);
            }
        }

        $answer->save();

        /* return redirect()->route('questions.show', $question->id)
            ->with('success', 'Answer created successfully'); */
        
        return view('partials.answer', ['answers' => $question->answers()->get()])->render();
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
        dd($request->all());

        if ($request->has('images')) {
            $answerImagePath = 'answer_images/' . $answer->id . '/';
            
            // Add new images
            $images = $request->file('images');
            if ($images) {
                foreach ($images as $index => $image) {
                    $format = $index + 1; // 1.format, 2.format, etc.
            
                    // If an image is provided, update or add it
                    if ($image) {
                        $uploadedPath = $answerImagePath . $format . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path($answerImagePath), $format . '.' . $image->getClientOriginalExtension());
            
                        // Update existing image if it exists, otherwise create a new one
                        if ($existingImage = $answer->images()->where('format', $format)->first()) {
                            $existingImage->update([
                                'picture_path' => $uploadedPath,
                            ]);
                        } else {
                            AnswerImage::create([
                                'picture_path' => $uploadedPath,
                                'answer_id' => $answer->id,
                                'format' => $format,
                            ]);
                        }
                    }
                }
            }
        }

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
        
        if ($answer->user_id === Auth::user()->id) {
            return view('partials.answer_score', ['answer_id' => $answer->id])->render();            
        }

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

        if ($answer->user_id === Auth::user()->id) {
            return view('partials.answer_score', ['answer_id' => $answer->id])->render();            
        }

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
