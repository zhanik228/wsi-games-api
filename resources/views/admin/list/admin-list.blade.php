@extends('layouts.main-layout')

@section('title', 'Admin List')

@section('content')
    @include('partials.admin.header')
    <main>
        <h2>Admin List</h2>
        <table cellpadding="4">
            <thead>
            <tr>
                <th>Username</th>
                <th>Created Timestamp</th>
                <th>Last Login</th>
            </tr>
            </thead>
            <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->username }}</td>
                    <td>{{ $admin->created_at }}</td>
                    <td>{{ $admin->updated_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </main>
@endsection
