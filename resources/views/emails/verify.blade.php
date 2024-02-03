<x-mail::message>

    Thank you for signing up.
    Your six-digit code is {{$pin}}

<x-mail::button :url="'https://careerhubb.netlify.app/'">
Verify your email
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
