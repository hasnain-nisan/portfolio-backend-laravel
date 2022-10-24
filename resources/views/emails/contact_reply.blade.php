<x-mail::message>
# Hello
{{ $contact->name }}

This is the reply of your contact message.

{{ $reply }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
