<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Tag;
use App\Models\GalleryImage;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function create()
    {
        $tags = Tag::all();

        return view('stories.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|max:255|unique:stories,title',
                'short_description' => 'required',
                'full_story' => 'required',
                'goal_amount' => 'required|numeric|min:1',
                'main_image' => 'required|image',
                'gallery_images.*' => 'image',
            ],
            [
                'title.required' => 'Pavadinimas yra privalomas.',
                'title.unique' => 'Kampanija su tokiu pavadinimu jau egzistuoja.',
                'title.max' => 'Pavadinimas negali būti ilgesnis nei 255 simboliai.',
                'short_description.required' => 'Trumpas aprašymas yra privalomas.',
                'full_story.required' => 'Pilnas aprašymas yra privalomas.',
                'goal_amount.required' => 'Tikslas yra privalomas.',
                'goal_amount.numeric' => 'Tikslas turi būti skaičius.',
                'goal_amount.min' => 'Tikslas turi būti bent 1.',
                'main_image.required' => 'Pagrindinis paveikslėlis yra privalomas.',
                'main_image.image' => 'Pagrindinis paveikslėlis turi būti vaizdo failas.',
                'gallery_images.*.image' => 'Kiekvienas galerijos paveikslėlis turi būti vaizdo failas.',
            ]
        );

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

        $tagIds = [];

        if ($request->has('tags')) {
            $tagIds = $request->tags;
        }

        if ($request->filled('new_tags')) {
            $newTagNames = explode(',', $request->new_tags);

            foreach ($newTagNames as $tagName) {
                $tagName = trim($tagName);

                if (!$tagName || strlen($tagName) < 3) {
                    continue;
                }

                $tag = Tag::firstOrCreate([
                    'name' => $tagName
                ]);

                $tagIds[] = $tag->id;
            }
        }

        $tagIds = array_unique($tagIds);

        if (!empty($tagIds)) {
            $story->tags()->attach($tagIds);
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

        return redirect()->route('stories.show', $story)->with('success', 'Kampanija sukurta sėkmingai!');
    }

    public function show(Story $story)
    {
        $story->load(['comments.user', 'tags']);

        $raised = $story->donations->sum('amount');
        $goal = $story->goal_amount;

        $percentage = 0;

        $percentage = $goal > 0 ? min(100, ($raised / $goal) * 100) : 0;

        $recentDonations = $story->donations()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('stories.show', compact(
            'story',
            'raised',
            'goal',
            'percentage',
            'recentDonations',
        ));
    }

    public function index(Request $request)
    {

        $tags = Tag::all();
        $query = Story::query();
        $query->whereIn('status', ['active', 'closed']);
        


        if ($request->tag) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->like) {
            $query->withCount('likes');
            if ($request->like === 'most') {
                $query->orderBy('likes_count', 'desc')->orderBy('created_at', 'desc');
            } elseif ($request->like === 'least') {
                $query->orderBy('likes_count', 'asc')->orderBy('created_at', 'desc');
            }
        }

        $stories = $query->with(['galleryImages', 'likes', 'tags']) 
            ->withSum('donations as total_donated', 'amount')
            ->orderByRaw('CASE WHEN goal_amount > 0 THEN (total_donated / goal_amount) ELSE 0 END ASC') 
            ->orderByRaw('CASE WHEN goal_amount > 0 THEN (total_donated / goal_amount) END DESC')
            ->paginate(9);

        $successMessage = null;

        if ($request->filled('tag') || $request->filled('search') || $request->filled('like')) {
            $count = $stories->total();
            $successMessage = 'Rastų kampanijų skaičius pagal Jūsų pateiktus kriterijus: ' . $count . '.';
        }

        if ($stories->isEmpty()) {
            return view('stories.index', compact('stories', 'tags'))
                ->with('info', 'Nėra kampanijų atitinkančių jūsų paiešką.');
        }

        return view('stories.index', compact('stories', 'tags',  'successMessage'));
    }

    public function edit(Story $story)
    {
        $tags = Tag::all();

        if ($story->user_id !== auth()->id()) {
            abort(403);
        }

        return view('stories.edit', compact('story', 'tags'));
    }

    public function update(Request $request, Story $story)
    {
        if ($story->user_id !== auth()->id()) {
            abort(403);
        }

        if ($story->status === 'closed') {
            return redirect()->route('stories.show', $story)
                ->with('error', 'Negalima redaguoti uždarytos kampanijos!');
        }

        if ($story->status === 'rejected') {
            return redirect()->route('stories.show', $story)
                ->with('error', 'Negalima redaguoti atmestos kampanijos! Jei norite, galite sukurti naują kampaniją su tais pačiais duomenimis, bet su kitu pavadinimu.');
        }

        if ($story->status === 'active') {
            return redirect()->route('stories.show', $story)
                ->with('error', 'Negalima redaguoti aktyvios kampanijos! Jei norite, galite sukurti naują kampaniją su tais pačiais duomenimis, bet su kitu pavadinimu.');
        }

        $request->validate([
            'title' => 'required|max:255|unique:stories,title,' . $story->id,
            'short_description' => 'required',
            'full_story' => 'required',
            'goal_amount' => 'required|numeric|min:1',
            'tags' => 'nullable|array',
            'new_tags' => 'nullable|string|max:255',
            'main_image' => 'nullable|image',
            'gallery_images.*' => 'image',
        ],
        [
            'title.required' => 'Pavadinimas yra privalomas.',
            'title.unique' => 'Kampanija su tokiu pavadinimu jau egzistuoja.',
            'title.max' => 'Pavadinimas negali būti ilgesnis nei 255 simboliai.',
            'short_description.required' => 'Trumpas aprašymas yra privalomas.',
            'full_story.required' => 'Pilnas aprašymas yra privalomas.',
            'goal_amount.required' => 'Tikslas yra privalomas.',
            'goal_amount.numeric' => 'Tikslas turi būti skaičius.',
            'goal_amount.min' => 'Tikslas turi būti bent 1.',
            'main_image.image' => 'Pagrindinis paveikslėlis turi būti vaizdo failas.',
            'gallery_images.*.image' => 'Kiekvienas galerijos paveikslėlis turi būti vaizdo failas.',
        ]);

        $imgPath = $story->main_image;

        if ($request->hasFile('main_image')){

            if ($story->main_image) {
                Storage::disk('public')->delete($story->main_image);
            }

            $imgPath = $request->file('main_image')->store('stories', 'public');
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

        $story->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'full_story' => $request->full_story,
            'goal_amount' => $request->goal_amount,
            'main_image' => $imgPath,
        ]);

        $tagIds = $request->tags ?? [];

        if ($request->filled('new_tags')) {
            $newTagNames = explode(',', $request->new_tags);

            foreach ($newTagNames as $tagName) {
                $tagName = trim($tagName);

                if (!$tagName || strlen($tagName) < 3) {
                    continue;
                }

                $tag = Tag::firstOrCreate([
                    'name' => $tagName
                ]);

                $tagIds[] = $tag->id;
            }
        }
        
        $tagIds = array_unique($tagIds);

        $story->tags()->sync($tagIds);

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

    public function toggleLike(Story $story)
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

    public function deleteMainImage(Story $story)
    {
        if ($story->user_id !== auth()->id()) {
            abort(403);
        }

        if ($story->main_image) {
            Storage::disk('public')->delete($story->main_image);
            $story->update(['main_image' => null]);
        }

        return back()->with('success', 'Pagrindinis paveikslėlis ištrintas sėkmingai!');
    }

    public function adminIndex(Request $request)
    {
        $pendingCount = Story::where('status', 'pending')->count();
        $activeCount = Story::where('status', 'active')->count();
        $rejectedCount = Story::where('status', 'rejected')->count();
        $closedCount = Story::where('status', 'closed')->count();

        $query = Story::query();
        if ($request->has('status') && in_array($request->status, ['pending', 'active', 'rejected', 'closed'])) {
            $query->where('status', $request->status);
        }

        $stories = $query->with('user')
            ->orderByRaw("FIELD(status, 'pending', 'active', 'rejected', 'closed')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $successMessage = null;
        if ($request->has('status')) {
            $statusText = [
                'pending' => 'laukiančios patvirtinimo',
                'active' => 'aktyvios',
                'rejected' => 'atmestos',
                'closed' => 'uždarytos'
            ];

            $successMessage = 'Rastų kampanijų skaičius pagal Jūsų pateiktus kriterijus: ' . $stories->total();
            $infoMessage = 'Tokių kampanijų nerasta';        
        }

        return view('admin.index', compact('stories', 'pendingCount', 'activeCount', 'rejectedCount', 'closedCount', 'successMessage'));
    }

    public function approveAdmin(Story $story)
    {
        if ($story->status === 'closed') {
            return back()->with('error', 'Negalima patvirtinti uždarytos kampanijos!');
        }

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
        if ($story->status === 'closed') {
            return back()->with('error', 'Negalima atmesti uždarytos kampanijos!');
        }

        if ($story->status === 'rejected') {
            return back()->with('error', 'Kampanija jau yra atmesta!');
        }

        $story->update(['status' => 'rejected']);

        return back()->with('success', 'Kampanija atmesta sėkmingai!');
    }
}
