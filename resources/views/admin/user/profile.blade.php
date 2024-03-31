@extends('layouts.main-layout')

@section('title', 'User Profile')

@section('content')
    @include('partials.admin.header')
    <main>
        <h2>User Profile</h2>
        <table cellpadding="4">
            <thead>
            <tr>
                <th>Username</th>
                <th>Created Timestamp</th>
                <th>Last Login</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->updated_at }}</td>
                </tr>
            </tbody>
        </table>
    </main>
@endsection
