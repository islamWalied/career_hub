<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="'https://careerhubb.netlify.app/'">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
