@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center py-10" style="background: linear-gradient(135deg, #133E87 0%, #608BC1 100%);">

    <div class="w-full max-w-2xl bg-white/95 backdrop-blur-md rounded-2xl shadow-xl p-8">

        <h2 class="text-2xl font-bold text-center mb-6" style="color:#133E87;">
            ✨ Edit Profile
        </h2>

        {{-- Success Message --}}
        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg text-white text-center" style="background-color:#133E87;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            {{-- Name --}}
            <div class="mb-4">
                <label class="block font-medium mb-2" style="color:#133E87;">Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full p-3 rounded-lg border focus:outline-none focus:ring"
                       style="border-color:#608BC1;">
                @error('name')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block font-medium mb-2" style="color:#133E87;">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                       class="w-full p-3 rounded-lg border focus:outline-none focus:ring"
                       style="border-color:#608BC1;">
                @error('email')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            {{-- Password Change Section --}}
            <h3 class="text-lg font-semibold mt-6 mb-3" style="color:#133E87;">Change Password</h3>

            <div class="mb-4">
                <label class="block font-medium mb-2" style="color:#133E87;">Current Password</label>
                <input type="password" name="current_password"
                       class="w-full p-3 rounded-lg border focus:outline-none focus:ring"
                       style="border-color:#608BC1;">
                @error('current_password')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-2" style="color:#133E87;">New Password</label>
                <input type="password" name="password"
                       class="w-full p-3 rounded-lg border focus:outline-none focus:ring"
                       style="border-color:#608BC1;">
                @error('password')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-2" style="color:#133E87;">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full p-3 rounded-lg border focus:outline-none focus:ring"
                       style="border-color:#608BC1;">
            </div>

            {{-- Buttons --}}
            <div class="flex justify-between mt-6">
                <button type="submit" class="py-2 px-6 rounded-lg font-semibold shadow-lg"
                        style="background-color:#133E87; color:white;">
                    💾 Save Changes
                </button>
                <a href="{{ route('dashboard') }}" class="py-2 px-6 rounded-lg font-semibold shadow-lg"
                   style="background-color:#608BC1; color:white;">
                    ← Back
                </a>
            </div>
        </form>

        <hr class="my-8 border-gray-300">

        {{-- Delete Account --}}
        <h3 class="text-lg font-semibold mb-3" style="color:#133E87;">Danger Zone</h3>
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure?')">
            @csrf
            @method('delete')
            <button class="py-2 px-6 rounded-lg font-semibold shadow-lg bg-red-600 text-white">
                ⚠️ Delete Account
            </button>
        </form>
    </div>
</div>
@endsection
