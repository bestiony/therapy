<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\AffiliateHistory;
use App\Models\BookingHistory;
use App\Models\Bundle;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\Order_item;
use App\Models\RankingLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $data['title'] = 'Dashboard';
        $data['parent'] = $user->certified_parent;
        $data['total_conversations'] = $user->conversations->count();
       
        return view('certified_parent.dashboard', $data);
    }

    public function rankingLevelList()
    {
        $data['title'] = 'Dashboard';
        $data['navDashboardActiveClass'] = 'active';
        $data['levels'] = RankingLevel::orderBy('serial_no', 'asc')->where('type', RANKING_LEVEL_EARNING)->get();
        return view('instructor.ranking-badge-list', $data);
    }
}
