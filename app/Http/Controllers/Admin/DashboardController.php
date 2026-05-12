<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\EducationEntry;
use App\Models\Experience;
use App\Models\NewsletterSubscription;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'Projects' => Project::count(),
                'Services' => Service::count(),
                'Skills' => Skill::count(),
                'Experience' => Experience::count(),
                'Education' => EducationEntry::count(),
                'Publications' => Publication::count(),
                'Testimonials' => Testimonial::count(),
                'Blog' => BlogPost::count(),
                'Newsletter' => NewsletterSubscription::count(),
                'Users' => User::count(),
                'Unread' => ContactMessage::whereNull('read_at')->count(),
            ],
            'recentMessages' => ContactMessage::latest()->limit(5)->get(),
            'featuredProjects' => Project::orderByDesc('featured')->orderBy('display_order')->limit(5)->get(),
        ]);
    }
}
