@extends('layouts.app')

@section('title', 'Cards')

@section('content')

@auth
    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}">Go to Admin Dashboard</a>
    @endif
@endauth

<section id="cards">
    @each('partials.card', $cards, 'card')
    <article class="card">
        <form class="new_card">
            <input type="text" name="name" placeholder="new card">
        </form>
    </article>
</section>

@endsection