<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question; 
use Illuminate\Support\Facades\View;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Tag;

class QuestionController extends Controller
{

    public function index()
    {
        $questions = Question::latest()->paginate(10); // Assuming you want to display 10 questions per page
        return view('pages.home', compact('questions'));
    }


    public function create()
    {
        return view('pages.questions');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $question = Question::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'score' => 0,
            'edited' => 0,
            'user_id' => Auth::user()->id,
        ]);

        $tags = $request->input('sel_tags', []);

        foreach($tags as $tag) {
            $tagID = Tag::where('name', $tag)->first();
            $actual_tag = $tagID->id;
            $question->tags()->attach($actual_tag);
        }

        return redirect()->route('questions.show', $question->id)
            ->with('success', 'Question created successfully');
    }

    public function show($id){
        $question = Question::findOrFail($id);
        return View::make('pages.question', [
            'question' => $question
        ]);
    }


    public function delete(Request $request, $question_id)
    {
        
        $question = Question::findOrFail($question_id);

        if ($question->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized');
        }
    

        $question->delete();

        return redirect(url('/'))->withSuccess('You have successfully deleted your question!');
    }
    /*
    public function edit(Request $request, $question_id)
    {
        $question = Question::findOrFail($question_id);

        if ($question->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized');
        }
        
        $tags = $request->input('sel_tags', []);

        $question->tags()->detach();

        foreach($tags as $tag) {
            $tagID = Tag::where('name', $tag)->first();
            $actual_tag = $tagID->id;
            $question->tags()->attach($actual_tag);
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $question->title = $request->input('title');
        $question->content = $request->input('content');
        $question->edited = 1;

        $question->save();
    }*/

    public function edit(Request $request, $question_id)
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

        $question = Question::findOrFail($question_id);

        if ($question->user_id !== $user->id) {
            return redirect()->route('questions.show', $question_id)->with('error', 'You are not authorized to edit this question');
        }

        $question->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        $question->edited = 1;

        $question->save();

        return redirect()->route('questions.show', $question_id)->with('success', 'Question updated successfully');
    }
}
public function attach_tag() {
        
}