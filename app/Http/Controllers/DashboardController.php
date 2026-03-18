<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\Donation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myStories = Story::where('user_id', $user->id)->latest()->get();

        $myDonations = Donation::with('story')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        
        return view('dashboard.index', compact('myStories', 'myDonations'));
    }
}
