{{ $profile->owner_name }} Portfolio

New portfolio inquiry

Name: {{ $message->name }}
Email: {{ $message->email }}
Company: {{ $message->company ?: 'Not provided' }}
Subject: {{ $message->subject ?: 'Portfolio inquiry' }}

Message:
{{ $message->message }}

Replying to this email will reply directly to {{ $message->name }}.
