<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Story;

class DonationController extends Controller
{
    public function store(Request $request, Story $story)
    {
        if ($story->user_id == auth()->id()) {
            return redirect()->route('stories.show', $story)
                ->with('error', 'Jūs negalite aukoti savo kampanijai.');
        }

        if ($story->status === 'closed') { // Only block if closed, allow donations for pending/active
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
