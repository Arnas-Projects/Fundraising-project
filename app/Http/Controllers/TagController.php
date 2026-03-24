<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::oldest()->get(); // Pabaigoje bus naujausi tagai
        $tagsAmount = Tag::withCount('stories')->get(); // Gauname kiekvieno tago istorijų skaičių
        
        $activeTagsAmount = Tag::withCount(['stories' => function ($query) {
            $query->where('status', 'active');
        }])->get(); // Gauname kiekvieno tago aktyvių istorijų skaičių

        return view('admin.tags.index', compact('tags', 'tagsAmount', 'activeTagsAmount'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tags|max:25',
        ],
        [
            'name.unique' => 'Toks tagas jau egzistuoja.',
            'name.required' => 'Pavadinimas yra privalomas.',
            'name.max' => 'Pavadinimas negali būti ilgesnis nei 25 simboliai.',
        ]);

        // $request->validate([
        //     'name' => 'required|max:25',
        // ]);

        Tag::create(['name' => $request->name]);

        return redirect()->route('admin.tags.index')->with('success', 'Tagas sukurtas sėkmingai.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return back()->with('success', 'Tagas ištrintas sėkmingai.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|unique:tags,name,' . $tag->id . '|max:25',
        ],
        [
            'name.unique' => 'Toks tagas jau egzistuoja.',
            'name.required' => 'Pavadinimas yra privalomas.',
            'name.max' => 'Pavadinimas negali būti ilgesnis nei 25 simboliai.',
        ]);

        $tag->update(['name' => $request->name]);

        return redirect()->route('admin.tags.index')->with('success', 'Tagas atnaujintas sėkmingai.');
    }

    // public function update(Request $request, Tag $tag)
    // {
    //     $request->validate([
    //         'name' => 'required|max:25',
    //     ]);

    //     $tag->update([
    //         'name' => $request->name,
    //     ]);

    //     return redirect()->route('admin.tags.index')->with('success', 'Tagas atnaujintas sėkmingai.');
    // }
}
