<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Tag;
use App\Models\GalleryImage;
use App\Models\Like;
use App\Models\Comment;

class StoryController extends Controller
{
    public function create()
    {
        $tags = Tag::all();

        return view('stories.create', compact('tags'));
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
            'status' => 'pending' // Set to pending for admin approval
            // 'status' => 'active' // Set to active immediately for simplicity
        ]);

        // $story->tag()->attach($request->tags);

        if ($request->has('tags')) {
            $story->tags()->attach($request->tags);
        }

        if ($request->hasFile('gallery_images')) {

            foreach ($request->file('gallery_images') as $image) {

                $path = $image->store('stories/gallery', 'public');

                GalleryImage::create([
                    'story_id' => $story->id,
                    'image_path' => $path
                ]);
            }
        }

        // return redirect('/')->with('success', 'Story created');
        return redirect()->route('stories.show', $story)->with('success', 'Kampanija sukurta sėkmingai!');
    }

    public function show(Story $story)
    {
        $story->load('user'); // also load owner info for display

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

    ///////////////////////////////////////////////////////////////////////////////////

    // Be search funkcionalumo

    // public function index(Story $story)
    // {
    //     $stories = Story::latest()->get();
    //     $raised = $story->donations->sum('amount');

    //     $query = Story::query();

    //     return view('stories.index', compact('stories', 'raised'));
    // }

    ///////////////////////////////////////////////////////////////////////////////////

    // Su search funkcionalumu
    // public function index(Request $request)
    // {
    //     $query = Story::query();

    //     if ($request->search) {
    //         $query->where('title', 'like', '%' . $request->search . '%');
    //     }

    //     $stories = $query->latest()->get();

    //     return view('stories.index', compact('stories'));
    // }

    ///////////////////////////////////////////////////////////////////////////////////

    // Su search funkcionalumu, tik aktyvios kampanijos
    public function index(Request $request)
    {
        $query = Story::query();
        $query->where('status', 'active');

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $stories = $query->latest()->paginate(9);

        return view('stories.index', compact('stories'));
    }

    public function edit(Story $story)
    {
        if ($story->user_id !== auth()->id()) {
            abort(403);
        }

        return view('stories.edit', compact('story'));
    }

    public function update(Request $request, Story $story)
    {
        if ($story->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'short_description' => 'required',
            'full_story' => 'required',
            'goal_amount' => 'required|numeric|min:1'
        ]);

        $story->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'full_story' => $request->full_story,
            'goal_amount' => $request->goal_amount
        ]);

        return redirect()->route('stories.show', $story)
            ->with('success', 'Kampanija atnaujinta sėkmingai!');
    }

    public function destroy(Story $story)
    {
        if ($story->user_id !== auth()->id()) {
            abort(403);
        }

        $story->delete();

        return redirect('/')->with('success', 'Kampanija ištrinta sėkmingai!');
    }

    public function byTag(Tag $tag)
    {
        $stories = $tag->stories()->latest()->get();

        return view('stories.index', compact('stories'));
    }

    public function toogleLike(Story $story)
    {
        $user = auth()->user();

        $existingLike = Like::where('user_id', $user->id)
            ->where('story_id', $story->id)
            ->first();

        if ($existingLike) {
            // If like exists, remove it (unlike)
            $existingLike->delete();
        } else {
            // If like doesn't exist, create it (like)
            Like::create([
                'user_id' => $user->id,
                'story_id' => $story->id
            ]);
        }

        return back();
    }

    public function storeComment(Request $request, Story $story)
    {
        $request->validate([
            'content' => 'required|max:1000',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'story_id' => $story->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentaras pridėtas sėkmingai!');
    }

    public function adminIndex()
    {
        // Pagination for admin view
        $stories = Story::latest()->paginate(10);

        return view('admin.index', compact('stories'));
    }

    public function approveAdmin(Story $story)
    {
        // If campaign is closed, we can not approve it
        if ($story->status === 'closed') {
            return back()->with('error', 'Negalima patvirtinti uždarytos kampanijos!');
        }

        // If campaign is already active, we can not approve it again
        if ($story->status === 'active') {
            return back()->with('error', 'Kampanija jau yra patvirtinta!');
        }

        $story->update(['status' => 'active']);

        return back()->with('success', 'Kampanija patvirtinta sėkmingai!');
    }

    public function destroyAdmin(Story $story)
    {
        $story->delete();

        return back()->with('success', 'Kampanija ištrinta sėkmingai!');
    }

    public function rejectAdmin(Story $story)
    {
        // If campaign is closed, we can not reject it
        if ($story->status === 'closed') {
            return back()->with('error', 'Negalima atmesti uždarytos kampanijos!');
        }

        // If campaign is already rejected, we can not reject it again
        if ($story->status === 'rejected') {
            return back()->with('error', 'Kampanija jau yra atmesta!');
        }

        $story->update(['status' => 'rejected']);

        return back()->with('success', 'Kampanija atmesta sėkmingai!');
    }
}
