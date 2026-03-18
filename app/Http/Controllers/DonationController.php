<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Story;

class DonationController extends Controller
{
    public function store(Request $request, Story $story)
    {
        if ($story->status !== 'active') {
            return redirect()->route('stories.show', $story)
                ->with('error', 'Šita kampanija nepriima daugiau aukų.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        Donation::create([
            'user_id' => auth()->id(),
            'story_id' => $story->id,
            'amount' => $request->amount
        ]);

        $raised = $story->donations()->sum('amount');
        
        if ($raised >= $story->goal_amount) {
            $story->update([
                'status' => 'closed'
            ]);
        }

        return redirect()->route('stories.show', $story)
            ->with('success', 'Ačiū už Jūsų skirtą paramą!');
    }
}
