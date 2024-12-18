@extends('admin.layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-3xl font-semibold mb-4">Update Profile</h2>

    {{-- Success and Error Messages --}}
    @if (session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
        @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Profile Update Form --}}
    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-lg font-medium">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-lg" value="{{ auth()->user()->name }}">
        </div>

        <div>
            <label for="email" class="block text-lg font-medium">Email:</label>
            <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg" value="{{ auth()->user()->email }}">
        </div>

        <div>
            <label for="current_password" class="block text-lg font-medium">Current Password:</label>
            <input type="password" id="current_password" name="current_password" class="w-full p-3 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label for="password" class="block text-lg font-medium">New Password:</label>
            <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg">
        </div>

        <div>
            <label for="password_confirmation" class="block text-lg font-medium">Confirm New Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-3 border border-gray-300 rounded-lg">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Update Profile</button>
    </form>
</div>
@endsection