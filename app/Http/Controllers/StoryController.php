<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;

class StoryController extends Controller
{
    public function create()
    {
        return view('stories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'short_description' => 'required',
            'full_story' => 'required',
            'goal_amount' => 'required|numeric|min:1',
            'main_image' => 'image'
        ]);

        $path = null;

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('stories', 'public');
        }

        $story = Story::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'short_description' => $request->short_description,
            'full_story' => $request->full_story,
            'goal_amount' => $request->goal_amount,
            'main_image' => $path,
            'status' => 'pending'
        ]);

        // return redirect('/')->with('success', 'Story created');
        return redirect()->route('stories.show', $story);
    }

    public function show(Story $story)
    {
        return view('stories.show', compact('story'));
    }

    public function index()
    {
        $stories = Story::latest()->get();

        return view('stories.index', compact('stories'));
    }
}
