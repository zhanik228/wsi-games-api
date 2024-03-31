@extends('layouts.main-layout')

@section('title', 'User List')

@section('content')
    @include('partials.admin.header')
    <main>
        <h2>User List</h2>
        <table cellpadding="4">
            <thead>
            <tr>
                <th>Username</th>
                <th>Created Timestamp</th>
                <th>Last Login</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="position-relative">
                    <td><a class="text-dark" href="{{ route('admin.user-profile', $user->id) }}">{{ $user->username }}</a></td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->updated_at }}</td>
                    <td>
                        @if(!$user->deleted_at)
                            <form action="{{ route('admin.user-block', $user->id) }}" method="POST">
                                @csrf
                                <select name="block_reason" id="">
                                    <option value="suspend">Suspend</option>
                                    <option value="spamming">Spamming</option>
                                    <option value="cheating">Cheating</option>
                                </select>
                                <button type="submit" class="btn btn-danger">Block</button>
                            </form>
                            @else
                            <form action="{{ route('admin.user-unblock', $user->id) }}" method="POST">
                                @csrf
                                <span class="text-bg-warning rounded p-1">{{ $user->delete_reason }}</span>
                                <button type="submit" class="btn btn-primary">Unblock</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </main>
@endsection
