@extends('layouts.app')

@section('content')
<h2>Login</h2>
<form action="/login" method="POST">
    @csrf

    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>

    @if ($errors->any())
        <p style="color:red;">{{ $errors->first() }}</p>
    @endif

    <p>Belum punya akun? <a href="/register">Daftar</a></p>
</form>
@endsection
