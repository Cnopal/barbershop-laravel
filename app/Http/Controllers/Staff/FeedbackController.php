<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::where('barber_id', auth()->id())
            ->with('customer')
            ->latest()
            ->paginate(20);

        $averageRating = Feedback::where('barber_id', auth()->id())
            ->whereNotNull('rating')
            ->avg('rating');

        $totalFeedbacks = Feedback::where('barber_id', auth()->id())->count();

        return view('staff.feedbacks.index', compact('feedbacks', 'averageRating', 'totalFeedbacks'));
    }
}