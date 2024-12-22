<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-500 p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('MinSULogo.png') }}" alt="Logo" class="w-8 h-8">
            <span class="text-white text-xl font-semibold">Dashboard</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-white text-lg">Logout</button>
        </form>
    </nav>

    <!-- Main Content -->
    <div class="p-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Event and RFID Attendance</h2>


            <!-- Event Selection -->
            <div class="mb-6">
                <label for="event" class="block text-lg font-medium mb-2">Select Event:</label>
                <select id="event" name="event" class="w-full p-3 border border-gray-300 rounded-lg">
                    <option value="" disabled selected>Select an event</option>
                    <!-- Assuming you have events in your database -->
                    @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- RFID Input -->
            <div class="mb-6">
                <label for="rfid" class="block text-lg font-medium mb-2">Scan RFID:</label>
                <input id="rfid" type="text" class="w-full p-3 border border-gray-300 rounded-lg" placeholder="Scan RFID" autofocus>
            </div>

            <!-- Message -->
            <div id="message" class="text-green-500 text-lg"></div>
        </div>
        <!-- Students Logged Into the Event -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Students Logged Into the Event</h3>
            <div id="student-log" class="border border-gray-200 p-4 rounded-lg bg-gray-50">
                <p class="text-gray-500">Select an event to see the logged students.</p>
            </div>
        </div>
    </div>
    <script>
        const eventDropdown = document.getElementById('event');
        const studentLogContainer = document.getElementById('student-log');

        // Fetch attendance logs for the selected event
        async function fetchAttendanceLogs(eventId) {
            try {
                const response = await fetch('/get-attendance-logs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        event_id: eventId
                    }),
                });

                const logs = await response.json();

                if (logs.length > 0) {
                    studentLogContainer.innerHTML = logs.map(log => `
                    <div class="p-2 border-b border-gray-200">
                        <p class="font-medium">${log.student.name}</p>
                        <p class="text-sm text-gray-500">Logged at: ${new Date(log.attended_at).toLocaleString()}</p>
                    </div>
                `).join('');
                } else {
                    studentLogContainer.innerHTML = '<p class="text-gray-500">No students logged for this event.</p>';
                }
            } catch (error) {
                studentLogContainer.innerHTML = '<p class="text-red-500">Failed to fetch attendance logs.</p>';
                console.error(error);
            }
        }

        // Update logs when an event is selected
        eventDropdown.addEventListener('change', function() {
            const eventId = this.value;
            if (eventId) {
                fetchAttendanceLogs(eventId);
            }
        });

        // Update logs after recording attendance
        async function updateAttendanceLogs(eventId) {
            if (eventId) {
                await fetchAttendanceLogs(eventId);
            }
        }

        // Handle RFID submission
        document.getElementById('rfid').addEventListener('keydown', async function(e) {
            if (e.key === 'Enter') {
                const rfid = e.target.value;
                const eventId = eventDropdown.value;

                if (!eventId) {
                    document.getElementById('message').innerText = 'Select an Event First';
                    document.getElementById('rfid').value = '';
                    return;
                }

                try {
                    const response = await fetch('/check-rfid', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            rfid,
                            event_id: eventId
                        }),
                    });

                    const data = await response.json();

                    if (data.exists) {
                        const saveResponse = await fetch('/save-attendance', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                student_id: data.student_id,
                                event_id: eventId
                            }),
                        });

                        const saveData = await saveResponse.json();

                        if (saveData.status === 'success') {
                            document.getElementById('message').innerText = `Attendance recorded for ${data.student_name}.`;
                            updateAttendanceLogs(eventId); // Refresh logs
                        } else {
                            document.getElementById('message').innerText = saveData.message;
                        }
                    } else {
                        document.getElementById('message').innerText = 'RFID does not exist';
                    }
                } catch (error) {
                    document.getElementById('message').innerText = 'An error occurred. Please try again.';
                    console.error(error);
                } finally {
                    document.getElementById('rfid').value = '';
                }
            }
        });
    </script>
</body>

</html>