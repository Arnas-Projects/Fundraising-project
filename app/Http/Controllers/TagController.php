<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::oldest()->get(); // Pabaigoje bus naujausi tagai

        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tags|max:50',
        ],
        [
            'name.unique' => 'Toks tagas jau egzistuoja.',
        ]);

        // $request->validate([
        //     'name' => 'required|max:50',
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
            'name' => 'required|unique:tags,name,' . $tag->id . '|max:50',
        ],
        [
            'name.unique' => 'Toks tagas jau egzistuoja.',
        ]);

        $tag->update(['name' => $request->name]);

        return redirect()->route('admin.tags.index')->with('success', 'Tagas atnaujintas sėkmingai.');
    }

    // public function update(Request $request, Tag $tag)
    // {
    //     $request->validate([
    //         'name' => 'required|max:50',
    //     ]);

    //     $tag->update([
    //         'name' => $request->name,
    //     ]);

    //     return redirect()->route('admin.tags.index')->with('success', 'Tagas atnaujintas sėkmingai.');
    // }
}
