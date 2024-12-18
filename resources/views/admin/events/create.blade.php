@extends('admin.layout')

@section('content')

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

<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Create Event</h2>
    <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-lg font-medium">Event Name:</label>
            <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-lg" required>
        </div>

        <div>
            <label for="date" class="block text-lg font-medium">Event Date:</label>
            <input type="date" id="date" name="date" class="w-full p-3 border border-gray-300 rounded-lg" required>
        </div>

        <div>
            <label for="start_time" class="block text-lg font-medium">Start Time:</label>
            <input type="time" id="start_time" name="start_time" class="w-full p-3 border border-gray-300 rounded-lg" required>
        </div>

        <div>
            <label for="end_time" class="block text-lg font-medium">End Time:</label>
            <input type="time" id="end_time" name="end_time" class="w-full p-3 border border-gray-300 rounded-lg" required>
        </div>

        <div>
            <label for="location" class="block text-lg font-medium">Event Location:</label>
            <input type="text" id="location" name="location" class="w-full p-3 border border-gray-300 rounded-lg" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Create Event</button>
    </form>
</div>
@endsection