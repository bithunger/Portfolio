{{ $profile->owner_name }} Portfolio

Thank you, {{ $message->name }}.

Your message has been received. I will review the details and contact you soon at {{ $message->email }}.

Subject: {{ $message->subject ?: 'Portfolio inquiry' }}

Thank you for reaching out. If you need to add anything, reply to this email or send a note to {{ $profile->email }}.
