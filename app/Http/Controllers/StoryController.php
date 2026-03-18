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
        // Add this if you want to count total donation sum in back-end
        $raised = $story->donations->sum('amount');
        $goal = $story->goal_amount;

        $percentage = 0;

        if ($goal > 0) {
            $percentage = min(100, ($raised / $goal) * 100);
        }

        $recentDonations = $story->donations()
            ->with('user') // also load donor info
            ->latest() // newest donations first
            ->take(5) // only 5 donors
            ->get();

        return view('stories.show', compact(
            'story',
            'raised',
            'goal',
            'percentage',
            'recentDonations'
        ));

        // return view('stories.show', compact('story'));
    }

    public function index(Story $story)
    {
        $stories = Story::latest()->get();
        $raised = $story->donations->sum('amount');

        return view('stories.index', compact('stories', 'raised'));
    }
}
