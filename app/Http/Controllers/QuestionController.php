<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question; // Assuming you have a Question model

class QuestionController extends Controller
{
    /**
     * Display a listing of the questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::latest()->paginate(10); // Assuming you want to display 10 questions per page
        return view('home', compact('questions'));
    }

    /**
     * Show the form for creating a new question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.questions');
    }

    /**
     * Store a newly created question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $question = Question::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            // Add any other fields you may need
        ]);

        // You may want to associate the question with the currently authenticated user
        // $question->user()->associate(auth()->user())->save();

        return redirect()->route('questions.show', $question->id)
            ->with('success', 'Question created successfully');
    }

    /**
     * Display the specified question.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return view('questions.show', compact('question'));
    }

    // Add other methods like edit, update, destroy as needed
}
