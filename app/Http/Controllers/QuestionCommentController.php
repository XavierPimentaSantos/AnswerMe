<?php

namespace App\Http\Controllers;

use App\Models\QuestionComment;
use App\Models\Question;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuestionCommentController extends Controller
{
    public function store(Request $request, $question_id)
    {
        // $question_id = $request->input('question_id');
        $question_comment_body = $request->input('question_comment_body');
        $question = Question::findOrFail($question_id);

        $request->validate([
            'question_comment_body' => 'required|max:100',
        ]);

        $questionComment = QuestionComment::create([
            'body' => $question_comment_body,
            'question_id' => $question_id,
            'edited' => 0,
            'user_id' => Auth::user()->id,
        ]);

        $questionComment->save();

        return view('partials.comment_section', ['questioncomments' => $question->comments()->get()])->render();
    }

    public function edit(Request $request, $question_id, $comment_id)
    {
        $questionComment = QuestionComment::findOrFail($comment_id);

        $request->validate([
            'question_comment_body' => 'required|max:100',
        ]);
        
        $questionComment->body = $request->input('question_comment_body');
        $questionComment->edited = true;

        $questionComment->save();

        return $request->input('question_comment_body');
    }

    public function delete(Request $request)
    {
        $questionComment = QuestionComment::findOrFail($request->input('question_comment_id'));

        if($questionComment->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        $questionComment->delete();

        return redirect()->route('questions.show', $request->input('question_id'))
            ->with('success', 'Comment created successfully');
    }
}
