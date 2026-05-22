<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\BlogNewsletterNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function __construct(private readonly BlogNewsletterNotifier $blogNewsletterNotifier)
    {
        //
    }

    public function index(): View
    {
        return view('admin.blog.index', [
            'posts' => BlogPost::orderBy('display_order')
                ->orderByDesc('published_at')
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog.create', [
            'post' => new BlogPost([
                'published' => true,
                'featured' => false,
                'published_at' => now(config('app.timezone')),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $post = BlogPost::create($this->validated($request));
        $this->blogNewsletterNotifier->sendIfNeeded($post);

        return redirect()->route('admin.blog.index')->with('status', 'Blog post created.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.blog.edit', [
            'post' => $blogPost,
        ]);
    }

    public function update(Request $request, BlogPost $blogPost): RedirectResponse
    {
        $blogPost->update($this->validated($request, $blogPost));
        $this->blogNewsletterNotifier->sendIfNeeded($blogPost);

        return redirect()->route('admin.blog.index')->with('status', 'Blog post updated.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $this->deletePreviousLocalCover($blogPost);
        $blogPost->delete();

        return redirect()->route('admin.blog.index')->with('status', 'Blog post deleted.');
    }

    private function validated(Request $request, ?BlogPost $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200'],
            'excerpt' => ['required', 'string', 'max:360'],
            'body' => ['required', 'string', 'max:20000'],
            'cover_image_file' => ['nullable', 'image', 'max:5120'],
            'published_at' => ['nullable', 'date'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        unset($data['cover_image_file']);

        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? '') ?: $data['title'], $post);
        $data['body'] = $this->cleanBody($data['body']);
        $data['featured'] = $request->boolean('featured');
        $data['published'] = $request->boolean('published');
        $data['display_order'] = $data['display_order'] ?? 0;
        $data['published_at'] = $data['published_at'] ?: null;

        if ($coverImagePath = $this->storeCoverImage($request, $post)) {
            $data['cover_image_url'] = $coverImagePath;
        }

        return $data;
    }

    private function uniqueSlug(string $value, ?BlogPost $post = null): string
    {
        $base = Str::slug($value) ?: 'blog-post';
        $slug = $base;
        $count = 2;

        while (BlogPost::where('slug', $slug)
            ->when($post, fn ($query) => $query->whereKeyNot($post->getKey()))
            ->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }

    private function storeCoverImage(Request $request, ?BlogPost $post = null): ?string
    {
        if (! $request->hasFile('cover_image_file')) {
            return null;
        }

        $directory = public_path('uploads/blog');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = $request->file('cover_image_file');
        $filename = 'blog-cover-'.Str::uuid().'.'.$file->extension();

        $file->move($directory, $filename);
        $this->deletePreviousLocalCover($post);

        return '/uploads/blog/'.$filename;
    }

    private function deletePreviousLocalCover(?BlogPost $post): void
    {
        if (! $post?->cover_image_url || ! Str::startsWith($post->cover_image_url, '/uploads/blog/')) {
            return;
        }

        $path = public_path(ltrim($post->cover_image_url, '/'));

        if (is_file($path)) {
            unlink($path);
        }
    }

    private function cleanBody(string $body): string
    {
        $body = preg_replace('/<(script|style|iframe|object|embed)\b[^>]*>.*?<\/\1>/is', '', $body) ?? '';
        $body = strip_tags($body, '<p><br><strong><b><em><i><u><s><del><sub><sup><span><div><ul><ol><li><a><h2><h3><h4><blockquote><pre><code><img><figure><figcaption><hr><table><thead><tbody><tfoot><tr><th><td>');
        $body = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $body) ?? '';
        $body = preg_replace('/(href\s*=\s*["\'])\s*javascript:[^"\']*(["\'])/i', '$1#$2', $body) ?? '';
        $body = preg_replace('/(src\s*=\s*["\'])\s*javascript:[^"\']*(["\'])/i', '$1#$2', $body) ?? '';
        $body = preg_replace('/(style\s*=\s*["\'][^"\']*)javascript:/i', '$1#', $body) ?? '';
        $body = preg_replace('/(style\s*=\s*["\'][^"\']*)expression\s*\(/i', '$1blocked(', $body) ?? '';
        $body = $this->forceBodyLinksToNewTabs($body);

        return trim($body);
    }

    private function forceBodyLinksToNewTabs(string $body): string
    {
        return preg_replace_callback('/<a\b([^>]*)>/i', function (array $matches): string {
            $attributes = $matches[1];

            if (! preg_match('/\s+target\s*=/i', $attributes)) {
                $attributes .= ' target="_blank"';
            } else {
                $attributes = preg_replace('/\s+target\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', ' target="_blank"', $attributes, 1) ?? $attributes;
            }

            if (! preg_match('/\s+rel\s*=\s*([\'"])(.*?)\1/i', $attributes, $relMatch)) {
                $attributes .= ' rel="noopener noreferrer"';

                return '<a'.$attributes.'>';
            }

            $quote = $relMatch[1];
            $existing = preg_split('/\s+/', trim($relMatch[2])) ?: [];
            $lowerExisting = array_map('strtolower', $existing);

            foreach (['noopener', 'noreferrer'] as $requiredRel) {
                if (! in_array($requiredRel, $lowerExisting, true)) {
                    $existing[] = $requiredRel;
                    $lowerExisting[] = $requiredRel;
                }
            }

            $rel = ' rel='.$quote.trim(implode(' ', $existing)).$quote;
            $attributes = preg_replace('/\s+rel\s*=\s*([\'"]).*?\1/i', $rel, $attributes, 1) ?? $attributes;

            return '<a'.$attributes.'>';
        }, $body) ?? $body;
    }
}
