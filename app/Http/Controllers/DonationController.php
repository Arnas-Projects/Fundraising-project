<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Story;

class DonationController extends Controller
{
    public function store(Request $request, Story $story)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1' 
        ]);

        Donation::create([
            'user_id' => auth()->id(),
            'story_id' => $story->id,
            'amount' => $request->amount
        ]);

        return redirect()->route('stories.show', $story)
            ->with('success', 'Ačiū už Jūsų skirtą paramą!');
    }
}
