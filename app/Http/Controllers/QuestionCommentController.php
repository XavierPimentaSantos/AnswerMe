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
        $question = Question::findOrFail($question_id);

        $request->validate([
            'question_comment_body' => 'required|max:100',
        ]);

        $questionComment = QuestionComment::create([
            'body' => $request->input('question_comment_body'),
            'question_id' => $question_id,
            'edited' => 0,
            'user_id' => Auth::user()->id,
        ]);

        $questionComment->save();

        return redirect()->route('questions.show', $question_id)
            ->with('success', 'Comment created successfully');
    }


}
