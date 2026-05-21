<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\EducationEntry;
use App\Models\Experience;
use App\Models\BlogPost;
use App\Models\NewsletterSubscription;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ContactMessage::class,
            NewsletterSubscription::class,
            BlogPost::class,
            EducationEntry::class,
            Experience::class,
            Project::class,
            Publication::class,
            Service::class,
            Skill::class,
            Testimonial::class,
        ] as $model) {
            $model::query()->delete();
        }

        Profile::updateOrCreate(
            ['id' => 1],
            [
                'owner_name' => 'Alex Morgan',
                'headline' => 'Product-minded Laravel developer crafting fast, elegant web experiences.',
                'tagline' => 'I turn messy product ideas into clean interfaces, useful dashboards, and maintainable backend systems.',
                'hero_panel_label' => 'Portfolio Studio',
                'hero_panel_text' => 'hello@example.com',
                'bio' => 'I partner with founders, agencies, and growing teams to design and build portfolio sites, SaaS dashboards, content systems, and high-converting product experiences with a sharp eye for usability.',
                'portrait_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=900&q=80',
                'resume_url' => null,
                'email' => 'hello@example.com',
                'phone' => '+1 555 0199',
                'location' => 'Remote / Available worldwide',
                'availability' => 'Available for select projects',
                'seo_title' => 'Alex Morgan / Laravel Developer Portfolio',
                'seo_description' => 'A modern Laravel portfolio with editable projects, services, skills, experience, testimonials, and contact messages.',
                'github_url' => 'https://github.com',
                'linkedin_url' => 'https://linkedin.com',
                'twitter_url' => 'https://x.com',
                'dribbble_url' => 'https://dribbble.com',
                'facebook_url' => 'https://facebook.com',
                'whatsapp_url' => 'https://wa.me/15550199',
                'website_url' => 'https://example.com',
                'accent_color' => '#0f766e',
                'frontend_background_color' => '#f7f8f6',
                'backend_background_color' => '#f5f7f4',
                'font_family' => 'elegant',
                'frontend_logo_url' => null,
                'backend_logo_url' => null,
                'favicon_url' => null,
                'home_section_order' => array_keys(Profile::homeSectionOptions()),
            ],
        );

        $projects = [
            [
                'title' => 'Atlas Studio CMS',
                'slug' => 'atlas-studio-cms',
                'summary' => 'A publishing dashboard for a creative studio with case studies, inquiry tracking, and SEO controls.',
                'description' => "Designed and built a Laravel content system for a boutique studio that needed faster publishing and a calmer editorial workflow.\n\nThe project included custom content models, editorial status controls, responsive public pages, and a contact inbox for new client leads.",
                'image_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80',
                'client' => 'Atlas Studio',
                'role' => 'Full-stack developer',
                'year' => 2026,
                'tech_stack' => ['Laravel', 'Blade', 'SQLite', 'Responsive UI'],
                'live_url' => 'https://example.com',
                'repo_url' => 'https://github.com',
                'featured' => true,
                'published' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'Northline Analytics',
                'slug' => 'northline-analytics',
                'summary' => 'A metrics-heavy operations interface with client reporting and tidy data entry flows.',
                'description' => 'Built an internal reporting interface that helps the team scan account health, compare KPIs, and prepare weekly client updates without exporting spreadsheets.',
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80',
                'client' => 'Northline',
                'role' => 'UX engineer',
                'year' => 2025,
                'tech_stack' => ['Laravel', 'Dashboards', 'Charts', 'MySQL'],
                'live_url' => 'https://example.com',
                'repo_url' => null,
                'featured' => true,
                'published' => true,
                'display_order' => 2,
            ],
            [
                'title' => 'Pulse Launch Kit',
                'slug' => 'pulse-launch-kit',
                'summary' => 'A reusable launch site toolkit for solo creators with projects, services, testimonials, and lead capture.',
                'description' => 'Created a flexible portfolio foundation that lets creators publish polished pages quickly while keeping every content section editable from a secure dashboard.',
                'image_url' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1200&q=80',
                'client' => 'Pulse Collective',
                'role' => 'Product builder',
                'year' => 2025,
                'tech_stack' => ['Laravel', 'Content Design', 'Blade', 'CSS'],
                'live_url' => 'https://example.com',
                'repo_url' => 'https://github.com',
                'featured' => false,
                'published' => true,
                'display_order' => 3,
            ],
            [
                'title' => 'Harbor Care Portal',
                'slug' => 'harbor-care-portal',
                'summary' => 'A patient intake and appointment workflow for a small healthcare team moving away from paper forms.',
                'description' => "Built a secure operations portal for a clinic team that needed cleaner intake, searchable patient notes, and simple appointment handoff.\n\nThe work focused on practical form design, role-ready admin screens, and responsive layouts that staff could use from reception desks and tablets.",
                'image_url' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=1200&q=80',
                'client' => 'Harbor Care',
                'role' => 'Laravel developer',
                'year' => 2024,
                'tech_stack' => ['Laravel', 'Forms', 'Admin UX', 'SQLite'],
                'live_url' => 'https://example.com',
                'repo_url' => null,
                'featured' => false,
                'published' => true,
                'display_order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['slug' => $project['slug']], $project);
        }

        $services = [
            ['title' => 'Portfolio Systems', 'icon' => 'PS', 'description' => 'Personal sites with strong first impressions, editable content, SEO basics, and conversion-focused contact flows.', 'deliverables' => ['Public portfolio', 'Admin dashboard', 'Contact inbox'], 'display_order' => 1],
            ['title' => 'Laravel Dashboards', 'icon' => 'LD', 'description' => 'Operational backends for teams that need clean CRUD, role-ready structure, and reliable data workflows.', 'deliverables' => ['Custom models', 'Admin UI', 'Validation and seed data'], 'display_order' => 2],
            ['title' => 'UI Refreshes', 'icon' => 'UI', 'description' => 'Interface redesigns that improve hierarchy, responsiveness, readability, and everyday usability.', 'deliverables' => ['Responsive layouts', 'Design cleanup', 'Frontend implementation'], 'display_order' => 3],
            ['title' => 'Content Operations', 'icon' => 'CO', 'description' => 'Editorial workflows for teams that need repeatable publishing, organized assets, and clearer review paths.', 'deliverables' => ['Content models', 'Publishing states', 'Editor handoff'], 'display_order' => 4],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['title' => $service['title']], $service + ['active' => true]);
        }

        $skills = [
            ['name' => 'Laravel', 'category' => 'Backend', 'proficiency' => 95, 'display_order' => 1],
            ['name' => 'PHP', 'category' => 'Backend', 'proficiency' => 92, 'display_order' => 2],
            ['name' => 'Database Design', 'category' => 'Backend', 'proficiency' => 88, 'display_order' => 3],
            ['name' => 'Blade', 'category' => 'Frontend', 'proficiency' => 90, 'display_order' => 1],
            ['name' => 'Responsive CSS', 'category' => 'Frontend', 'proficiency' => 93, 'display_order' => 2],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'proficiency' => 86, 'display_order' => 3],
            ['name' => 'UX Writing', 'category' => 'Product', 'proficiency' => 84, 'display_order' => 1],
            ['name' => 'Content Modeling', 'category' => 'Product', 'proficiency' => 88, 'display_order' => 2],
            ['name' => 'Accessibility QA', 'category' => 'Product', 'proficiency' => 82, 'display_order' => 3],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name'], 'category' => $skill['category']],
                $skill + ['active' => true],
            );
        }

        $experiences = [
            [
                'role' => 'Independent Laravel Developer',
                'company' => 'Alex Morgan Studio',
                'location' => 'Remote',
                'start_date' => '2023-01-01',
                'end_date' => null,
                'is_current' => true,
                'summary' => 'Designing and shipping custom Laravel websites, dashboards, and portfolio systems for small teams.',
                'highlights' => ['Built editable content platforms', 'Improved lead capture flows', 'Delivered handoff-friendly admin screens'],
                'display_order' => 1,
            ],
            [
                'role' => 'Full-stack Engineer',
                'company' => 'BrightLayer Labs',
                'location' => 'New York, NY',
                'start_date' => '2020-04-01',
                'end_date' => '2022-12-01',
                'is_current' => false,
                'summary' => 'Led frontend and backend implementation for client portals and internal tools.',
                'highlights' => ['Reduced manual reporting work', 'Refactored legacy PHP modules', 'Partnered closely with design'],
                'display_order' => 2,
            ],
            [
                'role' => 'Frontend Developer',
                'company' => 'Signal & Type',
                'location' => 'Austin, TX',
                'start_date' => '2018-06-01',
                'end_date' => '2020-03-01',
                'is_current' => false,
                'summary' => 'Built responsive marketing sites, content pages, and reusable UI patterns for agency clients.',
                'highlights' => ['Shipped accessible landing pages', 'Created reusable Blade components', 'Improved mobile page speed'],
                'display_order' => 3,
            ],
            [
                'role' => 'Junior Web Developer',
                'company' => 'LaunchGrid',
                'location' => 'Remote',
                'start_date' => '2016-09-01',
                'end_date' => '2018-05-01',
                'is_current' => false,
                'summary' => 'Supported PHP site builds, bug fixes, content migrations, and QA for small business clients.',
                'highlights' => ['Maintained PHP websites', 'Migrated legacy content', 'Documented admin workflows'],
                'display_order' => 4,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::updateOrCreate(
                ['role' => $experience['role'], 'company' => $experience['company']],
                $experience + ['active' => true],
            );
        }

        $educationEntries = [
            [
                'degree' => 'BSc in Computer Science',
                'institution' => 'Metropolitan University',
                'location' => 'Remote / Campus',
                'start_year' => 2017,
                'end_year' => 2021,
                'summary' => 'Focused on software engineering, database systems, and practical web application development.',
                'highlights' => ['Built Laravel course projects', 'Studied data structures', 'Completed capstone system design'],
                'display_order' => 1,
            ],
            [
                'degree' => 'Professional UX Engineering Track',
                'institution' => 'Design Systems Lab',
                'location' => 'Online',
                'start_year' => 2022,
                'end_year' => 2023,
                'summary' => 'Advanced frontend polish, responsive systems, accessibility, and design handoff workflows.',
                'highlights' => ['Responsive interface systems', 'Accessibility-first forms', 'Component documentation'],
                'display_order' => 2,
            ],
            [
                'degree' => 'Advanced Laravel Architecture Certificate',
                'institution' => 'Backend Craft Academy',
                'location' => 'Online',
                'start_year' => 2023,
                'end_year' => 2024,
                'summary' => 'Covered service boundaries, validation strategies, queues, notifications, and maintainable admin workflows.',
                'highlights' => ['Queue and mail workflows', 'Testing practical apps', 'Refactoring service layers'],
                'display_order' => 3,
            ],
            [
                'degree' => 'Accessibility for Web Interfaces',
                'institution' => 'Inclusive Design Institute',
                'location' => 'Online',
                'start_year' => 2024,
                'end_year' => 2024,
                'summary' => 'Focused on semantic HTML, keyboard flows, contrast, form feedback, and content readability.',
                'highlights' => ['Keyboard-first testing', 'Accessible form states', 'WCAG fundamentals'],
                'display_order' => 4,
            ],
        ];

        foreach ($educationEntries as $educationEntry) {
            EducationEntry::updateOrCreate(
                ['degree' => $educationEntry['degree'], 'institution' => $educationEntry['institution']],
                $educationEntry + ['active' => true],
            );
        }

        $publications = [
            [
                'title' => 'Practical Patterns for Portfolio Content Management',
                'year' => 2026,
                'journal_name' => 'Journal of Applied Web Systems',
                'publisher' => 'Open Web Press',
                'article_url' => 'https://example.com',
                'icon' => 'WP',
                'display_order' => 1,
            ],
            [
                'title' => 'Admin UX Signals for Small Product Teams',
                'year' => 2025,
                'journal_name' => 'Interface Research Notes',
                'publisher' => 'Product Systems Guild',
                'article_url' => 'https://example.com',
                'icon' => 'UX',
                'display_order' => 2,
            ],
            [
                'title' => 'Responsive Case Study Layouts for Independent Builders',
                'year' => 2025,
                'journal_name' => 'Frontend Practice Quarterly',
                'publisher' => 'Craft UI Press',
                'article_url' => 'https://example.com',
                'icon' => 'CS',
                'display_order' => 3,
            ],
            [
                'title' => 'Reducing Form Friction in Client Intake Systems',
                'year' => 2024,
                'journal_name' => 'Digital Service Review',
                'publisher' => 'Service Design Forum',
                'article_url' => 'https://example.com',
                'icon' => 'DF',
                'display_order' => 4,
            ],
        ];

        foreach ($publications as $publication) {
            Publication::updateOrCreate(
                ['title' => $publication['title']],
                $publication + ['active' => true],
            );
        }

        $testimonials = [
            [
                'name' => 'Maya Chen',
                'title' => 'Founder',
                'company' => 'Atlas Studio',
                'quote' => 'Alex brought structure to a messy brief and shipped a portfolio system our team actually enjoys using.',
                'avatar_url' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80',
                'featured' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Jordan Reed',
                'title' => 'Operations Lead',
                'company' => 'Northline',
                'quote' => 'The dashboard feels fast, focused, and practical. It removed a lot of weekly admin work for our team.',
                'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
                'featured' => false,
                'display_order' => 2,
            ],
            [
                'name' => 'Priya Shah',
                'title' => 'Product Director',
                'company' => 'Pulse Collective',
                'quote' => 'The launch kit gave us a polished public site and a backend our non technical team could manage with confidence.',
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
                'featured' => false,
                'display_order' => 3,
            ],
            [
                'name' => 'Owen Brooks',
                'title' => 'Clinic Manager',
                'company' => 'Harbor Care',
                'quote' => 'Our intake flow is clearer, faster, and much easier to maintain. The team adopted it without a long training period.',
                'avatar_url' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=200&q=80',
                'featured' => false,
                'display_order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name'], 'company' => $testimonial['company']],
                $testimonial + ['active' => true],
            );
        }

        $posts = [
            [
                'title' => 'How I shape a portfolio that wins better leads',
                'slug' => 'portfolio-that-wins-better-leads',
                'excerpt' => 'A practical look at making portfolio sections clear, credible, and easier for serious clients to scan.',
                'body' => "Strong portfolios are not just pretty archives. They help visitors understand what you do, how you think, and why you are trustworthy.\n\nI start by tightening the hero, then I make the work section easy to compare. Each case study should answer the same quiet questions: what changed, what role did you play, and what can someone hire you to repeat?",
                'cover_image_url' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=80',
                'featured' => true,
                'published' => true,
                'published_at' => now()->subDays(10),
                'display_order' => 1,
            ],
            [
                'title' => 'Small admin UX details that make Laravel dashboards feel premium',
                'slug' => 'laravel-dashboard-ux-details',
                'excerpt' => 'The little backend interface choices that reduce friction for non-technical teams.',
                'body' => "A dashboard feels better when the defaults are kind. Clear empty states, predictable save buttons, fast validation feedback, and tidy table actions make a huge difference.\n\nThe backend does not need to be flashy. It needs to feel calm, legible, and forgiving when someone is updating real content under deadline pressure.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
                'featured' => false,
                'published' => true,
                'published_at' => now()->subDays(4),
                'display_order' => 2,
            ],
            [
                'title' => 'A better structure for project overview pages',
                'slug' => 'better-project-overview-pages',
                'excerpt' => 'How to keep case study pages readable on mobile while still showing enough detail for serious clients.',
                'body' => "Project pages work best when they answer the important questions quickly. The title, role, result, and links should be easy to scan before the reader reaches the long description.\n\nOn small screens, generous side gutters and short content groups matter more than decorative layout tricks. The page should feel steady, readable, and direct.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
                'featured' => false,
                'published' => true,
                'published_at' => now()->subDays(2),
                'display_order' => 3,
            ],
            [
                'title' => 'What makes a testimonial carousel feel continuous',
                'slug' => 'continuous-testimonial-carousel',
                'excerpt' => 'A short note on duplicating content tracks, reset timing, and making carousel motion feel natural.',
                'body' => "An infinite carousel should not feel like it is rewinding. The trick is to render a repeated track and reset the transform only when both positions look identical.\n\nThat keeps the movement calm and lets the content keep flowing in one direction without a visible snap.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=1200&q=80',
                'featured' => false,
                'published' => true,
                'published_at' => now()->subDay(),
                'display_order' => 4,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], $post);
        }

        $messages = [
            ['name' => 'Sample Client', 'email' => 'client@example.com', 'company' => 'Launch Co', 'subject' => 'New portfolio build', 'message' => 'I would like to discuss a portfolio and dashboard build for a new personal brand.', 'read_at' => null],
            ['name' => 'Nora Ellis', 'email' => 'nora@example.com', 'company' => 'Studio North', 'subject' => 'Case study CMS', 'message' => 'We need a lightweight CMS for publishing new work and collecting project inquiries.', 'read_at' => now()->subDays(2)],
            ['name' => 'Marcus Lee', 'email' => 'marcus@example.com', 'company' => 'Metric Lane', 'subject' => 'Dashboard refresh', 'message' => 'Our internal dashboard needs better layout, filters, and mobile-friendly tables.', 'read_at' => null],
            ['name' => 'Elena Cruz', 'email' => 'elena@example.com', 'company' => 'CarePath', 'subject' => 'Client portal planning', 'message' => 'Can you help scope a secure portal for intake forms and status updates?', 'read_at' => now()->subDay()],
        ];

        foreach ($messages as $message) {
            ContactMessage::create($message);
        }

        $subscribers = [
            ['name' => 'Avery Reader', 'email' => 'avery@example.com', 'source' => 'blog', 'subscribed_at' => now()->subDays(12)],
            ['name' => 'Rina Patel', 'email' => 'rina@example.com', 'source' => 'portfolio', 'subscribed_at' => now()->subDays(8)],
            ['name' => 'Leo Grant', 'email' => 'leo@example.com', 'source' => 'case-study', 'subscribed_at' => now()->subDays(5)],
            ['name' => 'Mina Torres', 'email' => 'mina@example.com', 'source' => 'newsletter', 'subscribed_at' => now()->subDays(1)],
        ];

        foreach ($subscribers as $subscriber) {
            NewsletterSubscription::create($subscriber);
        }
    }
}
