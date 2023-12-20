@extends('layouts.app')

@section('content')
<main>
    <div class="p-5">   
        <h2>We will send you a link to your Email!</h2>
        <form method="POST" action="{{ route('reset.password.post') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}"> 
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif

            <label for="password">Enter New Password</label>
            <input id="password" type="password" name="password" required>
            @if ($errors->has('password'))
                <span class="error">
                    {{ $errors->first('password') }}
                </span>
            @endif

            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
            @if ($errors->has('password_confirmation'))
                <span class="error">
                    {{ $errors->first('password_confirmation') }}
                </span>
            @endif

            <button type="submit">
                Confirm New Password
            </button>
            @if (session('success'))
                <p class="success">
                    {{ session('success') }}
                </p>
            @endif
        </form>
    </div>
</main>
@endsection
