@extends('admin.layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Manage Events</h2>
    <a href="{{ route('admin.events.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Add Event</a>
    
    <!-- Events Table -->
    <table class="w-full mt-4 border border-gray-200">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 text-left">Name</th>
                <th class="p-2 text-left">Date</th>
                <th class="p-2 text-left">Location</th>
                <th class="p-2 text-left">Start Time</th>
                <th class="p-2 text-left">End Time</th>
                <th class="p-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr class="border-b border-gray-200 event-row" data-id="{{ $event->id }}">
                <td class="p-2">{{ $event->name }}</td>
                <td class="p-2">{{ $event->date }}</td>
                <td class="p-2">{{ $event->location }}</td>
                <td class="p-2">{{ $event->start_time }}</td>
                <td class="p-2">{{ $event->end_time }}</td>
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

    <!-- Section for showing students who logged into the selected event -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-4">Students Logged Into the Event</h3>
        <div id="student-log" class="border border-gray-200 p-4 rounded-lg bg-gray-50">
            <p class="text-gray-500">Select an event to see the logged students.</p>
        </div>
    </div>
</div>

<!-- Script to handle dynamic updates -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('.event-row');
        const studentLogContainer = document.getElementById('student-log');

        rows.forEach(row => {
            row.addEventListener('click', function () {
                const eventId = this.getAttribute('data-id');

                // Fetch students via AJAX
                fetch(`/admin/events/${eventId}/students`)
                    .then(response => response.json())
                    .then(data => {
                        // Clear the container
                        studentLogContainer.innerHTML = '';

                        if (data.students.length > 0) {
                            const table = document.createElement('table');
                            table.classList.add('w-full', 'border', 'border-gray-200');

                            // Table headers
                            const thead = document.createElement('thead');
                            thead.classList.add('bg-gray-200');
                            thead.innerHTML = `
                                <tr>
                                    <th class="p-2 text-left">Student Name</th>
                                    <th class="p-2 text-left">RFID Tapped Time</th>
                                    <th class="p-2 text-left">Status</th>
                                </tr>
                            `;
                            table.appendChild(thead);

                            // Table body
                            const tbody = document.createElement('tbody');
                            data.students.forEach(student => {
                                const row = document.createElement('tr');
                                row.classList.add('border-b', 'border-gray-200');

                                const statusClass = student.status === 'on-time'
                                    ? 'text-green-500'
                                    : (student.status === 'late' ? 'text-yellow-500' : 'text-red-500');

                                row.innerHTML = `
                                    <td class="p-2">${student.name}</td>
                                    <td class="p-2">${student.attended_at}</td>
                                    <td class="p-2 ${statusClass}">${student.status}</td>
                                `;
                                tbody.appendChild(row);
                            });
                            table.appendChild(tbody);
                            studentLogContainer.appendChild(table);
                        } else {
                            studentLogContainer.innerHTML = '<p class="text-gray-500">No students have tapped their RFID for this event.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching students:', error);
                        studentLogContainer.innerHTML = '<p class="text-red-500">An error occurred while fetching student logs.</p>';
                    });
            });
        });
    });
</script>
@endsection
