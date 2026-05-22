{{ config('app.name') }} password reset code

Hello {{ $user->name }},

Use this one-time code to reset your portfolio admin password:

{{ $code }}

This code expires in {{ $expiresInMinutes }} minutes.

If you did not request a password reset, you can ignore this email. Never share this code with anyone.
