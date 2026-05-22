<?php

namespace App\Support;

class VersionedAsset
{
    public static function url(string $path): string
    {
        $path = ltrim($path, '/\\');
        $url = asset($path);
        $absolutePath = self::resolvePath($path);

        if (! $absolutePath) {
            return $url;
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.'v='.filemtime($absolutePath);
    }

    private static function resolvePath(string $path): ?string
    {
        foreach (self::candidatePaths($path) as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Support both standard Laravel public/ installs and cPanel-style public_html/ deploys.
     *
     * @return array<int, string>
     */
    private static function candidatePaths(string $path): array
    {
        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? null;
        $candidates = [
            public_path($path),
            base_path('public'.DIRECTORY_SEPARATOR.$path),
            base_path('public_html'.DIRECTORY_SEPARATOR.$path),
            base_path('..'.DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR.$path),
        ];

        if (is_string($documentRoot) && $documentRoot !== '') {
            array_unshift($candidates, rtrim($documentRoot, '/\\').DIRECTORY_SEPARATOR.$path);
        }

        return array_values(array_unique($candidates));
    }
}
