@extends('layouts.app')

@section('content')
<h2>Profil Pengguna</h2>

<p><strong>Nama:</strong> {{ $user['name'] }}</p>
<p><strong>Email:</strong> {{ $user['email'] }}</p>
<p><strong>Terdaftar sejak:</strong> {{ $user['created_at'] }}</p>

<a href="/logout">Logout</a>
@endsection
