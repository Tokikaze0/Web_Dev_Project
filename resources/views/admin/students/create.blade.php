@extends('admin.layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Add Student</h2>
    
    <form action="{{ route('admin.students.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
    </div>

    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
    </div>
    
    <div class="mb-4">
        <label for="rfid" class="block text-sm font-medium text-gray-700">RFID</label>
        <input type="text" name="rfid" id="rfid" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Add Student</button>
</form>
</div>
@endsection
