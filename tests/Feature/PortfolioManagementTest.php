<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\EducationEntry;
use App\Models\NewsletterSubscription;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Publication;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PortfolioManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_the_portfolio(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Your Name')
            ->assertSee('Start a project');
    }

    public function test_contact_form_stores_a_message(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('Tell me what you are building');

        $this->post('/contact', [
            'name' => 'Taylor Client',
            'email' => 'taylor@example.com',
            'subject' => 'Portfolio project',
            'message' => 'Can we talk about a portfolio build?',
        ])->assertSessionHas('status');

        $this->assertDatabaseHas(ContactMessage::class, [
            'email' => 'taylor@example.com',
            'subject' => 'Portfolio project',
        ]);
    }

    public function test_homepage_renders_education_and_publications(): void
    {
        EducationEntry::create([
            'degree' => 'BSc in Computer Science',
            'institution' => 'Example University',
            'start_year' => 2020,
            'end_year' => 2024,
            'active' => true,
            'display_order' => 1,
        ]);

        Publication::create([
            'title' => 'Researching Cleaner Portfolio Systems',
            'year' => 2026,
            'journal_name' => 'Web Systems Review',
            'publisher' => 'Open Web Press',
            'article_url' => 'https://example.com/paper',
            'active' => true,
            'display_order' => 1,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Academic qualifications')
            ->assertSee('BSc in Computer Science')
            ->assertSee('Research & Papers', false)
            ->assertSee('Researching Cleaner Portfolio Systems');
    }

    public function test_admin_dashboard_requires_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_owner_account_can_be_created_when_no_owner_exists(): void
    {
        $this->get(route('admin.login'))
            ->assertRedirect(route('admin.setup'));

        $this->post(route('admin.setup.store'), [
            'name' => 'First Owner',
            'email' => 'owner@example.com',
            'contact' => '+1 555 0100',
            'password' => 'first-password',
            'password_confirmation' => 'first-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas(User::class, [
            'email' => 'owner@example.com',
            'contact' => '+1 555 0100',
            'is_owner' => true,
        ]);
    }

    public function test_setup_is_not_available_after_owner_exists(): void
    {
        User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'contact' => '+1 555 0100',
            'password' => 'password',
            'email_verified_at' => now(),
            'is_owner' => true,
        ]);

        $this->get(route('admin.setup'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_login_and_view_dashboard(): void
    {
        User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'contact' => '+1 555 0100',
            'password' => 'password',
            'email_verified_at' => now(),
            'is_owner' => true,
        ]);

        $this->post('/admin/login', [
            'email' => 'owner@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Portfolio control room');
    }

    public function test_admin_can_open_reset_page_after_matching_email_and_change_password(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->post(route('admin.password.email'), [
            'email' => 'admin@example.com',
        ]);

        $response->assertRedirect();

        $location = $response->headers->get('Location');

        preg_match('#/admin/reset-password/([^?]+)#', $location, $matches);
        $token = $matches[1] ?? null;

        $this->assertNotNull($token);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'admin@example.com',
        ]);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'admin@example.com',
            'password' => 'changed-password',
            'password_confirmation' => 'changed-password',
        ])->assertRedirect(route('admin.login'))
            ->assertSessionHas('status');

        $user->refresh();

        $this->assertTrue(Hash::check('changed-password', $user->password));
    }

    public function test_admin_can_manage_newsletter_subscribers(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        NewsletterSubscription::create([
            'name' => 'Reader',
            'email' => 'reader@example.com',
            'source' => 'blog',
            'subscribed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('admin.newsletter.index'))
            ->assertOk()
            ->assertSee('reader@example.com');
    }

    public function test_admin_can_manage_users_and_change_passwords(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Editor',
                'email' => 'editor@example.com',
                'password' => 'secret-pass',
                'password_confirmation' => 'secret-pass',
                'email_verified' => '1',
            ])
            ->assertRedirect(route('admin.users.index'));

        $editor = User::where('email', 'editor@example.com')->firstOrFail();

        $this->assertTrue(Hash::check('secret-pass', $editor->password));
        $this->assertNotNull($editor->email_verified_at);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $editor), [
                'name' => 'Editor Updated',
                'email' => 'editor.updated@example.com',
                'password' => 'new-secret-pass',
                'password_confirmation' => 'new-secret-pass',
            ])
            ->assertRedirect(route('admin.users.index'));

        $editor->refresh();

        $this->assertSame('Editor Updated', $editor->name);
        $this->assertTrue(Hash::check('new-secret-pass', $editor->password));
        $this->assertNull($editor->email_verified_at);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHasErrors();

        $this->assertDatabaseHas(User::class, [
            'email' => 'admin@example.com',
        ]);
    }

    public function test_owner_account_cannot_be_deleted_by_another_admin(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'contact' => '+1 555 0100',
            'password' => 'password',
            'email_verified_at' => now(),
            'is_owner' => true,
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $owner))
            ->assertSessionHasErrors();

        $this->assertDatabaseHas(User::class, [
            'email' => 'owner@example.com',
            'is_owner' => true,
        ]);
    }

    public function test_admin_can_update_profile_with_cropped_portrait(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $profile = Profile::site();
        $image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII=';

        $response = $this->actingAs($user)->post(route('admin.profile.update'), [
            '_method' => 'PUT',
            'owner_name' => $profile->owner_name,
            'headline' => $profile->headline,
            'tagline' => $profile->tagline,
            'hero_panel_label' => 'Now booking',
            'hero_panel_text' => 'Laravel portfolio builds',
            'bio' => $profile->bio,
            'portrait_crop' => $image,
            'resume_file' => UploadedFile::fake()->create('resume.pdf', 120, 'application/pdf'),
            'frontend_logo_file' => UploadedFile::fake()->create('front-logo.png', 12, 'image/png'),
            'backend_logo_file' => UploadedFile::fake()->create('admin-logo.png', 12, 'image/png'),
            'favicon_file' => UploadedFile::fake()->create('favicon.png', 8, 'image/png'),
            'email' => $profile->email,
            'phone' => $profile->phone,
            'location' => $profile->location,
            'availability' => $profile->availability,
            'seo_title' => $profile->seo_title,
            'seo_description' => $profile->seo_description,
            'github_url' => $profile->github_url,
            'linkedin_url' => $profile->linkedin_url,
            'twitter_url' => $profile->twitter_url,
            'dribbble_url' => $profile->dribbble_url,
            'website_url' => $profile->website_url,
            'accent_color' => $profile->accent_color ?: '#0f766e',
            'frontend_background_color' => '#ffffff',
            'backend_background_color' => '#f2f5f3',
            'font_family' => 'manrope',
        ]);

        $response->assertRedirect();

        $profile->refresh();

        $this->assertStringStartsWith('/uploads/profiles/portrait-', $profile->portrait_url);
        $this->assertStringStartsWith('/uploads/resumes/resume-', $profile->resume_url);
        $this->assertStringStartsWith('/uploads/branding/frontend-logo-', $profile->frontend_logo_url);
        $this->assertStringStartsWith('/uploads/branding/backend-logo-', $profile->backend_logo_url);
        $this->assertStringStartsWith('/uploads/branding/favicon-', $profile->favicon_url);
        $this->assertSame('#ffffff', $profile->frontend_background_color);
        $this->assertSame('#f2f5f3', $profile->backend_background_color);
        $this->assertSame('manrope', $profile->font_family);
        $this->assertSame('Now booking', $profile->hero_panel_label);
        $this->assertSame('Laravel portfolio builds', $profile->hero_panel_text);
        $this->assertFileExists(public_path(ltrim($profile->portrait_url, '/')));
        $this->assertFileExists(public_path(ltrim($profile->resume_url, '/')));
        $this->assertFileExists(public_path(ltrim($profile->frontend_logo_url, '/')));
        $this->assertFileExists(public_path(ltrim($profile->backend_logo_url, '/')));
        $this->assertFileExists(public_path(ltrim($profile->favicon_url, '/')));

        unlink(public_path(ltrim($profile->portrait_url, '/')));
        unlink(public_path(ltrim($profile->resume_url, '/')));
        unlink(public_path(ltrim($profile->frontend_logo_url, '/')));
        unlink(public_path(ltrim($profile->backend_logo_url, '/')));
        unlink(public_path(ltrim($profile->favicon_url, '/')));
    }

    public function test_blog_posts_render_on_blog_listing_and_detail_page(): void
    {
        $post = BlogPost::create([
            'title' => 'Designing useful dashboards',
            'slug' => 'designing-useful-dashboards',
            'excerpt' => 'What makes a backend feel focused and easy to manage.',
            'body' => 'A useful dashboard gets the everyday workflows out of the way.',
            'published' => true,
            'featured' => true,
            'published_at' => now(),
            'display_order' => 1,
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Designing useful dashboards');

        $this->get(route('portfolio.blog.show', $post))
            ->assertOk()
            ->assertSee('prism-core.min.js', false)
            ->assertSee('A useful dashboard gets the everyday workflows out of the way.');
    }

    public function test_admin_can_create_blog_post_with_uploaded_cover_and_rich_body(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->post(route('admin.blog.store'), [
            'title' => 'Writing with better structure',
            'slug' => 'writing-with-better-structure',
            'excerpt' => 'A quick note about sharper article structure.',
            'body' => '<h2>Start clear</h2><p>Keep the article focused. <a href="https://example.com/docs">Read docs</a></p><figure><img src="https://example.com/editor.jpg" alt="Editor image"><figcaption>Editor image</figcaption></figure><script>alert("no")</script>',
            'cover_image_file' => UploadedFile::fake()->create('cover.jpg', 120, 'image/jpeg'),
            'published' => '1',
            'featured' => '1',
            'published_at' => now()->format('Y-m-d H:i:s'),
            'display_order' => 1,
        ]);

        $response->assertRedirect(route('admin.blog.index'));

        $post = BlogPost::where('slug', 'writing-with-better-structure')->firstOrFail();

        $this->assertStringStartsWith('/uploads/blog/blog-cover-', $post->cover_image_url);
        $this->assertStringContainsString('<h2>Start clear</h2>', $post->body);
        $this->assertStringContainsString('<a href="https://example.com/docs" target="_blank" rel="noopener noreferrer">Read docs</a>', $post->body);
        $this->assertStringContainsString('<img src="https://example.com/editor.jpg" alt="Editor image">', $post->body);
        $this->assertStringNotContainsString('<script>', $post->body);
        $this->assertFileExists(public_path(ltrim($post->cover_image_url, '/')));

        unlink(public_path(ltrim($post->cover_image_url, '/')));
    }

    public function test_admin_can_create_project_with_uploaded_image(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->post(route('admin.projects.store'), [
            'title' => 'Uploaded Project',
            'summary' => 'A project with a local uploaded image.',
            'description' => 'Project description',
            'project_image_file' => UploadedFile::fake()->create('project.jpg', 120, 'image/jpeg'),
            'published' => '1',
            'featured' => '1',
            'display_order' => 1,
        ]);

        $response->assertRedirect(route('admin.projects.index'));

        $project = Project::where('slug', 'uploaded-project')->firstOrFail();

        $this->assertStringStartsWith('/uploads/projects/project-', $project->image_url);
        $this->assertFileExists(public_path(ltrim($project->image_url, '/')));

        unlink(public_path(ltrim($project->image_url, '/')));
    }

    public function test_project_tech_stack_is_limited_to_four_items(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->post(route('admin.projects.store'), [
                'title' => 'Overloaded Project',
                'summary' => 'A project with too many stack items.',
                'tech_stack' => "Laravel\nBlade\nCSS\nMySQL\nRedis",
                'published' => '1',
                'display_order' => 1,
            ])
            ->assertSessionHasErrors('tech_stack');

        $this->assertDatabaseMissing(Project::class, [
            'title' => 'Overloaded Project',
        ]);
    }

    public function test_service_deliverables_are_limited_to_four_items(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->post(route('admin.services.store'), [
                'title' => 'Overloaded Service',
                'icon' => 'OS',
                'description' => 'A service with too many deliverables.',
                'deliverables' => "Strategy\nDesign\nBuild\nQA\nLaunch",
                'active' => '1',
                'display_order' => 1,
            ])
            ->assertSessionHasErrors('deliverables');

        $this->assertDatabaseMissing(Service::class, [
            'title' => 'Overloaded Service',
        ]);
    }

    public function test_admin_can_create_testimonial_with_uploaded_avatar(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->actingAs($user)->post(route('admin.testimonials.store'), [
            'name' => 'Happy Client',
            'title' => 'Founder',
            'company' => 'Launch Co',
            'quote' => 'The work felt polished and easy to manage.',
            'avatar_file' => UploadedFile::fake()->create('avatar.jpg', 80, 'image/jpeg'),
            'active' => '1',
            'featured' => '1',
            'display_order' => 1,
        ]);

        $response->assertRedirect(route('admin.testimonials.index'));

        $testimonial = Testimonial::where('name', 'Happy Client')->firstOrFail();

        $this->assertStringStartsWith('/uploads/testimonials/avatar-', $testimonial->avatar_url);
        $this->assertFileExists(public_path(ltrim($testimonial->avatar_url, '/')));

        unlink(public_path(ltrim($testimonial->avatar_url, '/')));
    }

    public function test_newsletter_form_stores_subscription(): void
    {
        $this->post('/newsletter', [
            'name' => 'Reader',
            'email' => 'reader@example.com',
            'source' => 'blog',
        ])->assertSessionHas('newsletter_status');

        $this->assertDatabaseHas(NewsletterSubscription::class, [
            'email' => 'reader@example.com',
            'source' => 'blog',
        ]);
    }
}
