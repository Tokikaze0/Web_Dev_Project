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
            <div id="message" class="text-red-500 text-lg"></div>
        </div>
    </div>

    <script>
        document.getElementById('rfid').addEventListener('keydown', async function(e) {
            // Check if RFID exists in the students table
            if (e.key === 'Enter') {
                const rfid = e.target.value;
                const eventId = document.getElementById('event').value;

                // Check if an event is selected
                if (!eventId) {
                    document.getElementById('message').innerText = 'Select an Event First';
                    // Clear the RFID input after showing the message
                    document.getElementById('rfid').value = '';
                    return;
                }

                // Send RFID to the server to check if it exists
                const response = await fetch('/check-rfid', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        rfid: rfid
                    })
                });

                const data = await response.json();

                if (data.exists) {
                    // Save the attendance record in 'attendancelogs'
                    await fetch('/save-attendance', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            student_id: data.student_id,
                            event_id: eventId
                        })
                    });

                    alert('Attendance recorded successfully!');
                    // Clear the RFID input and message
                    document.getElementById('rfid').value = '';
                    document.getElementById('message').innerText = ''; // Clear any previous message
                } else {
                    document.getElementById('message').innerText = 'RFID does not exist';
                    // Clear the RFID input after showing the message
                    document.getElementById('rfid').value = '';
                }
            }
        });
    </script>

</body>

</html>