<x-mail::message>

    this email is for resetting your password and this is your pin {{$pin}} to verify that this is your email

<x-mail::button :url="'https://careerhubb.netlify.app/'">
reset password
</x-mail::button>

Thanks, <br>
{{ config('app.name') }}
</x-mail::message>
