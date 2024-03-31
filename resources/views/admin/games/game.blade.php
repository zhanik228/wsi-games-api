@extends('layouts.main-layout')

@section('title', 'Game Page')

@section('content')
    @include('partials.admin.header')
    <main>
        <h2>Game</h2>
        <table cellpadding="4">
            <thead>
            <tr>
                <th><img width="64" src="{{ route('game.file', [$game->slug, 'thumbnail.png']) }}" alt=""></th>
                <th>Title</th>
                <th>Description</th>
                <th>Author</th>
            </tr>
            </thead>
            <tbody>
                <tr class="position-relative">
                    <td></td>
                    <td>{{ $game->title }}</td>
                    <td>{{ $game->description }}</td>
                    <td>{{ $game->author->username }}</td>
                </tr>
            </tbody>
        </table>
        <h2>Scores</h2>
        <form action="{{ route('admin.score.delete', $game->id) }}" method="POST">
            @csrf
            <input type="hidden" name="delete_method" value="game">
            <button class="btn btn-danger">Delete All Scores</button>
        </form>
        <table cellpadding="4">
            <thead>
            <tr>
                <th>Version</th>
                <th>User</th>
                <th>Score</th>
            </tr>
            </thead>
            <tbody>
{{--            {{ $game->gameVersionDeleted->scores }}--}}
            @foreach($game->gameVersions as $version)
                @if(count($version->scores))
            <tr class="position-relative">
                <td>{{ $version->version }}</td>
            </tr>
                @endif
            @foreach($version->scores as $score)
                <tr>
                    <td></td>
                <td>{{ $score->user->username }}</td>
                <td>{{ $score->score }}</td>
                    <td>
                        <form action="{{ route('admin.score.delete', $score->id) }}" method="POST">
                            @csrf
                            <select name="delete_method" id="">
                                <option value="all">Delete All scores for the user</option>
                                <option value="one">Delete Single Score</option>
                            </select>
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @endforeach
            </tbody>
        </table>
    </main>
@endsection
