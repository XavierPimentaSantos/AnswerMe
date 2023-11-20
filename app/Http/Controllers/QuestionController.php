<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Creates a new Question.
     */
    public function create(Request $request)
    {
        $question = new Question;

        $this->authorize('create', $question);

        $question->title = $request->input('question_title');
        $question->body = $request->input('question_body');
        $question->creation_date = time();
        $question->edit_date = time();
        $question->edited = false;
        $question->user_id = Auth::user->id;

        $question->save();
        return response()->json($question);
    }

    /**
     * Creates and stores a Question in storage.
     */
    /* public function store(Request $request)
    {
        $question = new Question();

        $this->authorize('create', $question);

        $question->title = $request->input('title');
        $question->body = $request->input('body');
        $question->score = 0;
        $question->post_id = $post->id;
    } */

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
