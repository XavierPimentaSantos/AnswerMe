<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Question;
use App\Models\User;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30', // valor sujeito a alteração
        ]);

        $tag = Tag::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('questions.index') // se calhar enviar para outro sítio
            ->with('success', 'Tag was added successfully.');
    }

    public function delete(Request $request, $tag_id)
    {
        $tag = Tag::findOrFail($tag_id);

        if(!Auth::user()->isModerator()) { // assumindo que o código dos admins é 3
            abort(403, 'Unauthorized'); // apesar de só os admins conseguirem invocar esta função, é melhor verificar se é mesmo um admin a fazê-lo
        }

        $tag->delete();

        return redirect()->route('admin.show') // se calhar enviar para outro sítio
            ->with('success', 'Tag was deleted successfully.');
    }

    public function edit(Request $request, $tag_id)
    {
        $tag = Tag::findOrFail($tag_id);

        if(Auth::user()->type!=3) { // assumindo que o código dos admins é 3
            abort(403, 'Unauthorized'); // apesar de só os admins conseguirem invocar esta função, é melhor verificar se é mesmo um admin a fazê-lo
        }
        
        $request->validate([
            'name' => 'required|max:30', // valor sujeito a alteração
        ]);

        $tag->name = $request->input('name');

        $tag->save();

        return redirect()->route('questions.index') // se calhar enviar para outro sítio
            ->with('success', 'Tag was deleted successfully.');
    }

    public function updateTags(Request $request)
    {
        $selectedTags = $request->input('selectedTags', []);

        return view('partials.selected_tags', ['tags' => $selectedTags])->render();
    }
}
