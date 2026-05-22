<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message received</title>
</head>
<body style="margin:0; background:#f5f7f6; color:#15201b; font-family:Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7f6; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px; background:#ffffff; border:1px solid #dce5e0; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 10px; color:#5f6f68; font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">{{ $profile->owner_name }} Portfolio</p>
                            <h1 style="margin:0 0 12px; font-size:24px; line-height:1.25;">Thank you, {{ $message->name }}</h1>
                            <p style="margin:0 0 16px; color:#4d5d56; font-size:15px; line-height:1.6;">
                                Your message has been received. I will review the details and contact you soon at {{ $message->email }}.
                            </p>
                            <div style="padding:16px 18px; border:1px solid #dce5e0; border-radius:8px; background:#f7fbf9;">
                                <p style="margin:0 0 6px; color:#5f6f68; font-size:13px;">Subject</p>
                                <p style="margin:0; color:#25322d; font-size:15px; font-weight:700;">{{ $message->subject ?: 'Portfolio inquiry' }}</p>
                            </div>
                            <p style="margin:18px 0 0; color:#7b8782; font-size:13px; line-height:1.5;">
                                Thank you for reaching out. If you need to add anything, reply to this email or send a note to {{ $profile->email }}.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
