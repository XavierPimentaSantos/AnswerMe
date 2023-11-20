<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Answer::latest()->paginate(10);
        return view();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $answer = Answer::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            // Add any other fields you may need
        ]);

        // You may want to associate the question with the currently authenticated user
        // $question->user()->associate(auth()->user())->save();

        return redirect()->route('questions.show', $answer->id)
            ->with('success', 'Answer created successfully');
    }
}
