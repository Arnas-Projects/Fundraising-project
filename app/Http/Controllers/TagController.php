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
            'name' => 'required|unique:tags|alpha_dash|max:50',
        ],
        [
            'name.alpha_dash' => 'Tagas gali būti sudarytas tik iš raidžių, skaičių, brūkšnelių ir pabraukimų.',
            'name.unique' => 'Toks tagas jau egzistuoja.',
        ]);

        // $request->validate([
        //     'name' => 'required|max:50',
        // ]);

        Tag::create($request->only('name'));

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
            'name' => 'required|unique:tags,name,' . $tag->id . '|alpha_dash|max:50',
        ],
        [
            'name.alpha_dash' => 'Tagas gali būti sudarytas tik iš raidžių, skaičių, brūkšnelių ir pabraukimų.',
            'name.unique' => 'Toks tagas jau egzistuoja.',
        ]);

        $tag->update($request->only('name'));

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
