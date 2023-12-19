<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <!-- <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet"> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous">
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
            
        </script>
    </head>
    <body class="flex flex-col min-h-screen">
        <main class="flex-grow">
            <header class="bg-black flex justify-between items-center">
                <h1 class="text-white"><a href="{{ url('/login') }}">AnswerMe!</a></h1>
                @if (Auth::check())
                    <a class="button" href="{{ route('questions.create') }}" onclick="event.preventDefault(); {{ Auth::user()->isBlocked() ? '' : 'window.location.href=this.href;' }}">ASK A QUESTION</a>
                    @if (Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.show') }}" class="button mr-2">Admin Page</a>
                    @endif
                    <div class="flex items-center">
                        <a class="button mr-2" href="{{ url('/logout') }}">Logout</a>
                        <a href="{{ route('profile.show')}}"><span class="text-white">{{ Auth::user()->name }}</span></a>
                    </div>
                @else
                    <div class="flex items-center">
                        <a class="button mr-2" href="{{ url('/login') }}">Login</a>
                    </div>
                @endif
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
        <footer class="bg-black justify-between items-center p-4">
            <p class="text-white">&copy; Made By lbaw2392</p>
            <a href="{{ route('faq.show') }}">Frequently Asked Questions</a>
        </footer>
    </body>
</html>