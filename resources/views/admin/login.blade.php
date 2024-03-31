@extends('layouts.main-layout')

@section('title', 'Admin - Login')

@section('content')
    <form action="" class="d-flex flex-column gap-5 p-5" method="post">
        @csrf
        @method('POST')
        <input autocomplete="username" type="text" name="username" placeholder="Type your username...">
        @error('username')
            <p class="text-danger">{{ $message }}</p>
        @enderror
        <input autocomplete="current-password" type="password" name="password" placeholder="Type your password...">
        @error('password')
            <p class="text-danger">{{ $message }}</p>
        @enderror
        <button type="submit" class="btn btn-primary">Login</button>
        @error('errorMsg')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </form>
@endsection
