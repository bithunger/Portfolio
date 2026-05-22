<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password reset code</title>
</head>
<body style="margin:0; background:#f5f7f6; color:#15201b; font-family:Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7f6; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px; background:#ffffff; border:1px solid #dce5e0; border-radius:8px; overflow:hidden;">
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 10px; color:#5f6f68; font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">{{ config('app.name') }}</p>
                            <h1 style="margin:0 0 12px; font-size:24px; line-height:1.25;">Password reset code</h1>
                            <p style="margin:0 0 18px; color:#4d5d56; font-size:15px; line-height:1.6;">
                                Hello {{ $user->name }}, use this one-time code to reset your portfolio admin password.
                            </p>
                            <p style="margin:0 0 20px; padding:16px 18px; border:1px solid #dce5e0; border-radius:8px; background:#f7fbf9; color:#0f766e; font-size:32px; font-weight:800; letter-spacing:.18em; text-align:center;">
                                {{ $code }}
                            </p>
                            <p style="margin:0 0 12px; color:#4d5d56; font-size:14px; line-height:1.6;">
                                This code expires in {{ $expiresInMinutes }} minutes. If you did not request a password reset, you can ignore this email.
                            </p>
                            <p style="margin:0; color:#7b8782; font-size:13px; line-height:1.5;">
                                For your security, never share this code with anyone.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
