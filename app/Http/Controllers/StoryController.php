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
        // Pavadinimo unikalumo validacija - negalima sukurti daugiau nei vienos kampanijos su tuo pačiu pavadinimu
        $request->validate(
            [
                'title' => 'required|max:255|unique:stories,title',
                'short_description' => 'required',
                'full_story' => 'required',
                'goal_amount' => 'required|numeric|min:1',
                'main_image' => 'required|image',
                'gallery_images.*' => 'image', // Validacija kiekvienam galerijos paveikslėliui
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
            'status' => 'pending' // Set to pending for admin approval
            // 'status' => 'active' // Set to active immediately for simplicity
        ]);

        // Handle new tags created by user
        $tagIds = [];

        if ($request->has('tags')) {
            $tagIds = $request->tags;
        }

        if ($request->filled('new_tags')) {
            $newTagNames = explode(',', $request->new_tags);

            foreach ($newTagNames as $tagName) {
                $tagName = trim($tagName);

                if (!$tagName || strlen($tagName) < 3) {
                    continue; // Skip empty or too short tag names
                }

                $tag = Tag::firstOrCreate([
                    'name' => $tagName
                ]);

                $tagIds[] = $tag->id;
            }
        }

        // remove duplicates
        $tagIds = array_unique($tagIds);

        // attach all tags to story
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

        // return redirect('/')->with('success', 'Story created');
        return redirect()->route('stories.show', $story)->with('success', 'Kampanija sukurta sėkmingai!');
    }

    public function show(Story $story)
    {
        // $story->load('user'); // also load owner info for display
        // $story = Story::with(['comments.user', 'tags'])->findOrFail($story->id); // load comments with user info and tags
        $story->load(['comments.user', 'tags']); // load comments with user info and tags

        // Add this if you want to count total donation sum in back-end
        $raised = $story->donations->sum('amount');
        $goal = $story->goal_amount;

        $percentage = 0;

        $percentage = $goal > 0 ? min(100, ($raised / $goal) * 100) : 0;

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
            'recentDonations',
        ));

        // return view('stories.show', compact('story'));
    }

    ///////////////////////////////////////////////////////////////////////////////////

    public function index(Request $request)
    {

        $tags = Tag::all(); // Gauname visus tagus, kad galėtume rodyti juos šalia kampanijų
        $query = Story::query();
        $query->whereIn('status', ['active', 'closed']); // Rodo tik aktyvias ir uždarytas kampanijas, kad jos būtų matomos titulinio puslapio sąraše
        


        // Filtruojame pagal tagą, jei jis pasirinktas
        if ($request->tag) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // Filtruojame pagal paieškos užklausą, jei ji pateikta
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filtruojame pagal patiktukus
        // if ($request->filled('like')) {
        //     $direction = ($request->like === 'most_liked') ? 'desc' : 'asc';
        //     $query->withCount('likes')->orderBy('likes_count', $direction);
        // }

        // Filtruojame pagal patiktukus (su most ir least variantais)
        if ($request->like) {
            $query->withCount('likes');
            if ($request->like === 'most') {
            // Rikiuojame nuo daugiausiai patiktukų iki mažiausiai
            // jeigu likes_count vienodas, rikiuojame pagal datą nuo naujausios iki seniausios kampanijos
                $query->orderBy('likes_count', 'desc')->orderBy('created_at', 'desc');
            } elseif ($request->like === 'least') {
                // Rikiuojame nuo mažiausiai patiktukų iki daugiausiai
                // jeigu likes_count vienodas, rikiuojame pagal datą nuo naujausios iki seniausios kampanijos
                $query->orderBy('likes_count', 'asc')->orderBy('created_at', 'desc');
            }
        }

        // $stories = $query->withSum('donations as total_donated', 'amount')
        //     ->orderByRaw('CASE WHEN total_donated >= goal_amount THEN 1 ELSE 0 END') // Kampanijos, kurių tikslas pasiektas, pačiame gale
        //     ->orderByRaw('CASE WHEN total_donated = 0 THEN created_at END DESC') // Kampanijos, kurios dar nesurinko nė euro, rikiuojamos pagal datą
        //     ->orderByRaw('CASE WHEN total_donated > 0 THEN total_donated END ASC') // Kampanijos, kurios surinko daugiau nei 0, rikiuojamos pagal surinktą sumą
        //     ->paginate(9); // Puslapiavimas, rodo 9 kampanijas puslapyje

        // Rikiuojame kampanijas pagal tai, kiek procentų tikslo jos pasiekusios, nuo mažiausiai iki daugiausiai, o jeigu procentas pasiektas vienodas, rikiuojame pagal datą nuo naujausios iki seniausios kampanijos
        $stories = $query->withSum('donations as total_donated', 'amount')
            ->orderByRaw('CASE WHEN goal_amount > 0 THEN (total_donated / goal_amount) ELSE 0 END ASC') // Rikiuojame pagal procentą tikslo pasiekimo nuo mažiausiai iki daugiausiai
            ->orderByRaw('CASE WHEN goal_amount > 0 THEN (total_donated / goal_amount) END DESC') // Jeigu procentas pasiektas vienodas, rikiuojame pagal datą nuo naujausios iki seniausios kampanijos
            ->paginate(9); // Puslapiavimas, rodo 9 kampanijas puslapyje

        // Sėkmės žinutė, rodoma, kai yra kampanijų atitinkančių paiešką
        $successMessage = null;

        if ($request->filled('tag') || $request->filled('search') || $request->filled('like')) {
            $count = $stories->total();
            $successMessage = 'Rastų kampanijų skaičius pagal Jūsų pateiktus kriterijus: ' . $count . '.';
        }

        // Žinutė, rodoma, kai nėra kampanijų atitinkančių paiešką
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

        // if ($story->status === 'pending') {
        //     return redirect()->route('stories.show', $story)
        //         ->with('error', 'Negalima redaguoti laukiančios patvirtinimo kampanijos! Palaukite, kol administratorius ją patvirtins.');
        // }

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

        // Handle main image upload
        $imgPath = $story->main_image;

        if ($request->hasFile('main_image')){

            // Delete old image if exists
            if ($story->main_image) {
                Storage::disk('public')->delete($story->main_image);
            }

            $imgPath = $request->file('main_image')->store('stories', 'public');
        }

        // Handle gallery images upload
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

        // Handle new tags created by user (in update form)
        $tagIds = $request->tags ?? [];

        if ($request->filled('new_tags')) {
            $newTagNames = explode(',', $request->new_tags);

            foreach ($newTagNames as $tagName) {
                $tagName = trim($tagName);

                if (!$tagName || strlen($tagName) < 3) {
                    continue; // Skip empty or too short tag names
                }

                $tag = Tag::firstOrCreate([
                    'name' => $tagName
                ]);

                $tagIds[] = $tag->id;
            }
        }
        
        // remove duplicates
        $tagIds = array_unique($tagIds);

        // Sync tags
        $story->tags()->sync($tagIds); // If no tags selected, detach all

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

    // RODO KAMPANIJAS PAGAL TAGUS (sena versija, be puslapiavimo)
    // public function byTag(Tag $tag)
    // {
    //     $stories = $tag->stories()->latest()->get();

    //     return view('stories.index', compact('stories'));
    // }

    // RODO KAMPANIJAS PAGAL TAGUS (nauja versija, su puslapiavimu)
    // public function byTag(Tag $tag)
    // {
    //     $tags = Tag::all(); // Gauname visus tagus, kad galėtume rodyti juos šalia kampanijų

    //     $stories = $tag->stories()
    //         ->latest() // Rodo naujausias kampanijas pirmiausia
    //         ->where('status', 'active') // Rodo tik aktyvias kampanijas pagal tagus
    //         ->paginate(9); // Puslapiavimas, rodo 9 kampanijas puslapyje

    //     // Žinutė, rodoma, kai nėra kampanijų su pasirinktu tagu
    //     if ($stories->isEmpty()) {
    //         return view('stories.index', compact('stories', 'tag', 'tags'))
    //             ->with('info', 'Nėra kampanijų su šiuo tagu.');
    //     }

    //     // Žinutė, rodoma, kai yra kampanijų su pasirinktu tagu
    //     return view('stories.index', compact('stories', 'tag', 'tags'))
    //         ->with('success', 'Rasta ' . $stories
    //             ->total() . ' kampanija(s) su tagu "' . $tag->name . '".');
    // }

    // RODO KAMPANIJAS PAGAL TAGUS (nauja versija, su puslapiavimu ir search funkcionalumu)
    // public function byTag(Request $request, Tag $tag)
    // {
    //     $query = $tag->stories()->where('status', 'active'); // Rodo tik aktyvias kampanijas pagal tagus

    //     if ($request->search) {
    //         $query->where('title', 'like', '%' . $request->search . '%');
    //     }

    //     $stories = $query->latest()->paginate(9);

    //     return view('stories.index', compact('stories'));
    // }

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
        // Kampanijų sumavimas pagal statusą, kad adminas matytų kiek kampanijų yra kiekviename statuso etape
        $pendingCount = Story::where('status', 'pending')->count();
        $activeCount = Story::where('status', 'active')->count();
        $rejectedCount = Story::where('status', 'rejected')->count();
        $closedCount = Story::where('status', 'closed')->count();

        // Filtravimas pagal statusą, jei adminas pasirinko filtruoti pagal konkretų statusą
        $query = Story::query();
        if ($request->has('status') && in_array($request->status, ['pending', 'active', 'rejected', 'closed'])) {
            $query->where('status', $request->status);
        }

        // Rūšiavimas pagal kampanijos statusą: pirmiausia rodomos laukiančios patvirtinimo kampanijos, po jų aktyvios, po jų atmestos, o gale uždarytos kampanijos.
        // Jei statusas vienodas, rodomos naujausios kampanijos pirmiausia
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

            // $successMessage = 'Rasta ' . $stories->total() . ' kampanija(s) su statusu "' . ($statusText[$request->status] ?? $request->status) . '".';
            $successMessage = 'Rastų kampanijų skaičius pagal Jūsų pateiktus kriterijus: ' . $stories->total();
            $infoMessage = 'Tokių kampanijų nerasta';        
        }

        return view('admin.index', compact('stories', 'pendingCount', 'activeCount', 'rejectedCount', 'closedCount', 'successMessage'));
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
