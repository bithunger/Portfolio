<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }}</title>
</head>
<body style="margin:0; background:#f5f7f6; color:#15201b; font-family:Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7f6; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; background:#ffffff; border:1px solid #dce5e0; border-radius:8px; overflow:hidden;">
                    @if ($post->cover_image_url)
                        <tr>
                            <td>
                                <img src="{{ url($post->cover_image_url) }}" alt="{{ $post->title }}" style="display:block; width:100%; max-height:300px; object-fit:cover;">
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 10px; color:#5f6f68; font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">{{ $profile->owner_name }} Portfolio</p>
                            <h1 style="margin:0 0 12px; font-size:25px; line-height:1.25;">{{ $post->title }}</h1>
                            <p style="margin:0 0 18px; color:#4d5d56; font-size:15px; line-height:1.6;">{{ $post->excerpt }}</p>
                            <p style="margin:0 0 20px;">
                                <a href="{{ $blogUrl }}" style="display:inline-block; padding:12px 16px; border-radius:8px; background:#0f766e; color:#ffffff; font-size:14px; font-weight:700; text-decoration:none;">Read the article</a>
                            </p>
                            <p style="margin:0; color:#7b8782; font-size:13px; line-height:1.5;">
                                You are receiving this because you subscribed to {{ $profile->owner_name }} updates. <a href="{{ $unsubscribeUrl }}" style="color:#0f766e;">Unsubscribe anytime</a>.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
