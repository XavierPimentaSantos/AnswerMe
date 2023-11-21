<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question; 
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Auth;



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
            'user_id' => Auth::user()->id,
        ]);


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

    public function edit(Request $request, $question_id)
    {
        $question = Question::findOrFail($question_id);

        if ($question->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized');
        }
    

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $question->title = $request->input('title');
        $question->content = $request->input('content');

        $question->save();
    }
}
