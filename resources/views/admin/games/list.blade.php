@extends('layouts.main-layout')

@section('title', 'Game List')

@section('content')
    @include('partials.admin.header')
    <main>
        <form action="{{ route('admin.game-list') }}">
            <input name="query" type="text" placeholder="Search for a game...">
            <button>Search</button>
        </form>
        <h2>Game List</h2>
        <table cellpadding="4">
            <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Description</th>
                <th>Author</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($games as $game)
                <tr class="position-relative">
                    <td><img width="64" src="{{ route('game.file', [$game->slug, 'thumbnail.png']) }}" alt="thumbnail"></td>
                    <td><a href="{{ route("game", $game) }}">{{ $game->title }}</a></td>
                    <td>{{ $game->description }}</td>
                    <td>{{ $game->author->username }}</td>
                    @if($game->deleted_at)
                        <td>deleted</td>
                        @else
                        <td>
                            <form action="{{ route('admin.game-delete', $game->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </main>
@endsection
