@extends('admin.layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Manage Events</h2>
    <a href="{{ route('admin.events.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Add Event</a>
    <table class="w-full mt-4 border border-gray-200">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">Name</th>
                <th class="p-2">Date</th>
                <th class="p-2">Location</th>
                <th class="p-2">Start Time</th>
                <th class="p-2">End Time</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr class="border-b border-gray-200">
                <td class="p-2">{{ $event->name }}</td>
                <td class="p-2">{{ $event->date }}</td>
                <td class="p-2 flex space-x-2">
                    <a href="{{ route('admin.events.edit', $event->id) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
