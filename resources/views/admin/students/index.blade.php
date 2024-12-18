@extends('admin.layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Manage Students</h2>
    <a href="{{ route('admin.students.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Add Student</a>
    <table class="w-full mt-4 border border-gray-200">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">Name</th>
                <th class="p-2">School ID</th>
                <th class="p-2">Role</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr class="border-b border-gray-200">
                <td class="p-2">{{ $student->name }}</td>
                <td class="p-2">{{ $student->school_id }}</td>
                <td class="p-2">{{ $student->role }}</td>
                <td class="p-2 flex space-x-2">
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                    <form action="{{ route('admin.students.toggleRole', $student->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-green-500 hover:underline">
                            {{ $student->role == 'representative' ? 'Demote' : 'Promote' }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
