<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\BlogPost;
use App\Models\EducationEntry;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $profile = Profile::site();
        $projects = Project::published()
            ->orderByDesc('featured')
            ->orderBy('display_order')
            ->latest()
            ->get();

        return view('portfolio.index', [
            'profile' => $profile,
            'projects' => $projects,
            'services' => Service::active()->orderBy('display_order')->get(),
            'skillsByCategory' => Skill::active()
                ->orderBy('category')
                ->orderBy('display_order')
                ->get()
                ->groupBy('category'),
            'experiences' => Experience::active()
                ->orderBy('display_order')
                ->orderByDesc('start_date')
                ->get(),
            'educationEntries' => EducationEntry::active()
                ->orderBy('display_order')
                ->orderByDesc('end_year')
                ->get(),
            'publications' => Publication::active()
                ->orderBy('display_order')
                ->orderByDesc('year')
                ->get(),
            'testimonials' => Testimonial::active()
                ->orderByDesc('featured')
                ->orderBy('display_order')
                ->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->published, 404);

        return view('portfolio.project', [
            'profile' => Profile::site(),
            'project' => $project,
            'relatedProjects' => Project::published()
                ->whereKeyNot($project->getKey())
                ->orderByDesc('featured')
                ->orderBy('display_order')
                ->limit(3)
                ->get(),
        ]);
    }

    public function blogIndex(): View
    {
        return view('portfolio.blog-index', [
            'profile' => Profile::site(),
            'posts' => BlogPost::published()
                ->orderByDesc('featured')
                ->orderBy('display_order')
                ->orderByDesc('published_at')
                ->get(),
        ]);
    }

    public function showBlog(BlogPost $blogPost): View
    {
        abort_unless($blogPost->published && (! $blogPost->published_at || $blogPost->published_at->isPast()), 404);

        return view('portfolio.blog', [
            'profile' => Profile::site(),
            'post' => $blogPost,
            'relatedPosts' => BlogPost::published()
                ->whereKeyNot($blogPost->getKey())
                ->orderByDesc('featured')
                ->orderBy('display_order')
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
