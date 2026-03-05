{{--
    resources/views/layouts/_font_include.blade.php
    
    Is file ko apne main layout (app.blade.php) ke <head> section mein include karo:
    
        @include('layouts._font_include')
    
    Ya directly <head> mein yeh paste karo:
--}}

{{-- Local font CSS --}}
<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">

{{--
    YA agar tum Vite use kar rahe ho (resources/css/app.css):
    app.css ke top pe yeh line add karo:
    
        @import './fonts.css';
    
    Aur layout mein:
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    Fonts automatically bundle ho jayenge.
--}}

{{--
    IMPORTANT: font_heading / font_body database settings ko
    CSS mein inject NAHI karna — fonts hardcoded hain yahan.
    Admin dashboard mein Typography section show nahi hoga.
--}}
