<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = new Post();

        $question = new Question();

        $this->authorize('create', $question);

        $question->title = $request->input('title');
        $question->body = $request->input('body');
        $question->post_id = $post->id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $question = Question::find($id);

        $this->authorize('edit', $question);

        $question->title = $request->input('title');
        $question->body = $request->input('body');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::find($id);

        $this->authorize('delete', $question);

        $question->delete();
        return response()->json($question);
    }
}
