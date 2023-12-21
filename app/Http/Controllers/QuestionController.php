<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question; 
use App\Models\QuestionImage;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Events\UserRegister;
use App\Events\UpvoteQuestion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Tag;

class QuestionController extends Controller
{

    public function index()
    {
        if(Auth::user()) {
            $followedQuestionIDs = Auth::user()->followedQuestions()->pluck('question_id');

            $actualFollowedQuestions = Question::whereIn('id', $followedQuestionIDs)->get();
            $otherQuestions = Question::whereNotIn('id', $followedQuestionIDs)->get();

            $questions = $actualFollowedQuestions->merge($otherQuestions);
        }
        else {
            $questions = Question::orderByDesc('score')->orderBy('created_at', 'desc')->paginate(10);
        }

        $perPage = 6;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $questions->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $questions = new LengthAwarePaginator($currentPageItems, $questions->count(), $perPage, $currentPage);

        return view('pages.home', compact('questions'));
    }


    public function create()
    {
        event(new UserRegister('lololol'));
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
        
        $questionImagePath = 'question_images/' . $question->id . '/';
        foreach ($request->file('images') as $index => $image) {
            $format = $index + 1; 
            $uploadedPath = $questionImagePath . $format . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($questionImagePath), $format . '.' . $image->getClientOriginalExtension());
            QuestionImage::create([
                'format' => $format, 
                'picture_path' => $uploadedPath,
                'question_id' => $question->id,
            ]);
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

    public function updateImages(Request $request)
    {
        
        $questionImagePath = 'question_images/' . $question->id . '/';

        dd($request->file('images'));
    
        foreach ($request->file('images') as $index => $image) {
            $format = $index + 1; 
                if ($image) {
                $uploadedPath = $questionImagePath . $format . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($questionImagePath), $format . '.' . $image->getClientOriginalExtension());
    
                if ($existingImage = $question->images()->where('format', $format)->first()) {
                    $existingImage->update([
                        'picture_path' => $uploadedPath,
                    ]);
                } else {
                    QuestionImage::create([
                        'format' => $format, 
                        'picture_path' => $uploadedPath,
                        'question_id' => $question->id,
                        'format' => $format,
                    ]);
                }
            }
        }
    }
    

    public function edit(Request $request, $question_id)
    {
        $user = Auth::user();

        $question = Question::findOrFail($question_id);

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

        $question->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        $tags = $request->input('sel_tags', []);

        $question->tags()->detach();

        foreach($tags as $tag) {
            $tagID = Tag::where('name', $tag)->first();
            $actual_tag = $tagID->id;
            $question->tags()->attach($actual_tag);
        }
        
       if ($request->has('images')) {

            $questionImagePath = 'question_images/' . $question->id . '/';
        
            foreach ($request->file('images') as $index => $image) {
                $format = $index + 1; 
        
                if ($image) {
                    $uploadedPath = $questionImagePath . $format . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path($questionImagePath), $format . '.' . $image->getClientOriginalExtension());
        
                    if ($existingImage = $question->images()->where('format', $format)->first()) {
                        $existingImage->update([
                            'picture_path' => $uploadedPath,
                        ]);
                    } else {
                        QuestionImage::create([
                            'picture_path' => $uploadedPath,
                            'question_id' => $question->id,
                            'format' => $format,
                        ]);
                    }
                }
            }
        }


        if ($question->user_id !== $user->id) {
            return redirect()->route('questions.show', $question_id)->with('error', 'You are not authorized to edit this question');
        }

        $question->edited = 1;

        $question->save();

        return redirect()->route('questions.show', $question_id)->with('success', 'Question updated successfully');
    }
    
    public function attach_tag() {
        
    }

    public function inc_score(Request $request)
    {
        $question = Question::findOrFail($request->input('question_id'));
        
        if ($question->user_id === Auth::user()->id) {
            return view('partials.question_score', ['question_id' => $question->id])->render();            
        }

        if(Auth::user()->questionUpVotes()->where('question_id', $question->id)->exists()) {
            Auth::user()->questionUpVotes()->detach($question->id);
            $score = $question->score;
            $question->score = $score - 1;
            $question->save();
        }
        else {
            if(Auth::user()->questionDownVotes()->where('question_id', $question->id)->exists()) {
                Auth::user()->questionDownVotes()->detach($question->id);
                Auth::user()->questionUpVotes()->attach($question->id);
                $score = $question->score;
                $question->score = $score + 2;
                $question->save();
            }
            else {
                Auth::user()->questionUpVotes()->attach($question->id);
                $score = $question->score;
                $question->score = $score + 1;
                $question->save();
            }
        }

        return view('partials.question_score', ['question_id' => $question->id])->render();
    }

    public function dec_score(Request $request)
    {
        $question = Question::findOrFail($request->input('question_id'));

        if ($question->user_id === Auth::user()->id) {
            return view('partials.question_score', ['question_id' => $question->id])->render();            
        }

        if(Auth::user()->questionDownVotes()->where('question_id', $question->id)->exists()) {
            Auth::user()->questionDownVotes()->detach($question->id);
            $score = $question->score;
            $question->score = $score + 1;
            $question->save();
        }
        else {
            if(Auth::user()->questionUpVotes()->where('question_id', $question->id)->exists()) {
                Auth::user()->questionUpVotes()->detach($question->id);
                Auth::user()->questionDownVotes()->attach($question->id);
                $score = $question->score;
                $question->score = $score - 2;
                $question->save();
            }
            else {
                Auth::user()->questionDownVotes()->attach($question->id);
                $score = $question->score;
                $question->score = $score - 1;
                $question->save();
            }
        }

        event(new UserRegister('lololol'));

        return view('partials.question_score', ['question_id' => $question->id])->render();
    }

    public function toggleFollow ($question_id)
    {
        if(Auth::user()->followedQuestions()->where('question_id', $question_id)->exists()) {
            Auth::user()->followedQuestions()->detach($question_id);
            return response()->json(['color' => 'black']);
        }
        else {
            Auth::user()->followedQuestions()->attach($question_id);
            return response()->json(['color' => 'green']);
        }
        // return response()->json(['message' => 'Toggled follow status successfully.']);
    }
}
