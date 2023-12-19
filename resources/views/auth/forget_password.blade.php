@extends('layouts.app')

@section('content')
<main>
    <div class="p-5">   
        <h2>We will send you a link to your Email! Use the link to change the Password.</h2>
        <form method="POST" action="{{ route('forget.password.post') }}">
            {{ csrf_field() }}


            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="error">
                {{ $errors->first('email') }}
                </span>
            @endif

            <button type="submit">
                Reset Password
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