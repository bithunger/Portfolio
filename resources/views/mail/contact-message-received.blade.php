<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New portfolio inquiry</title>
</head>
<body style="margin:0; background:#f5f7f6; color:#15201b; font-family:Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7f6; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; background:#ffffff; border:1px solid #dce5e0; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 10px; color:#5f6f68; font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">{{ $profile->owner_name }} Portfolio</p>
                            <h1 style="margin:0 0 14px; font-size:24px; line-height:1.25;">New portfolio inquiry</h1>
                            <p style="margin:0 0 18px; color:#4d5d56; font-size:15px; line-height:1.6;">
                                {{ $message->name }} sent a message from the contact form.
                            </p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px; border:1px solid #dce5e0; border-radius:8px;">
                                <tr>
                                    <td style="padding:12px 14px; color:#5f6f68; font-size:13px; width:120px;">Email</td>
                                    <td style="padding:12px 14px; font-size:14px;"><a href="mailto:{{ $message->email }}" style="color:#0f766e;">{{ $message->email }}</a></td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 14px; color:#5f6f68; font-size:13px;">Company</td>
                                    <td style="padding:12px 14px; font-size:14px;">{{ $message->company ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 14px; color:#5f6f68; font-size:13px;">Subject</td>
                                    <td style="padding:12px 14px; font-size:14px;">{{ $message->subject ?: 'Portfolio inquiry' }}</td>
                                </tr>
                            </table>
                            <div style="padding:16px 18px; border:1px solid #dce5e0; border-radius:8px; background:#f7fbf9; color:#25322d; font-size:15px; line-height:1.65;">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                            <p style="margin:20px 0 0; color:#7b8782; font-size:13px; line-height:1.5;">
                                Replying to this email will reply directly to {{ $message->name }}.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
