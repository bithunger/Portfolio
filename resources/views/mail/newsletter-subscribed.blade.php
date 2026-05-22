<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Newsletter subscription confirmed</title>
</head>
<body style="margin:0; background:#f5f7f6; color:#15201b; font-family:Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7f6; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px; background:#ffffff; border:1px solid #dce5e0; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 10px; color:#5f6f68; font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">{{ $profile->owner_name }} Portfolio</p>
                            <h1 style="margin:0 0 12px; font-size:24px; line-height:1.25;">You are subscribed</h1>
                            <p style="margin:0 0 18px; color:#4d5d56; font-size:15px; line-height:1.6;">
                                Thanks{{ $subscription->name ? ', '.$subscription->name : '' }}. You will receive practical notes from {{ $profile->owner_name }} at {{ $subscription->email }}.
                            </p>
                            <p style="margin:0 0 18px; color:#4d5d56; font-size:15px; line-height:1.6;">
                                You can leave the list at any time using the unsubscribe link below.
                            </p>
                            <p style="margin:0 0 18px;">
                                <a href="{{ $unsubscribeUrl }}" style="display:inline-block; padding:12px 16px; border-radius:8px; background:#0f766e; color:#ffffff; font-size:14px; font-weight:700; text-decoration:none;">Unsubscribe</a>
                            </p>
                            <p style="margin:0; color:#7b8782; font-size:13px; line-height:1.5;">
                                If the button does not work, open this link: <a href="{{ $unsubscribeUrl }}" style="color:#0f766e;">{{ $unsubscribeUrl }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
