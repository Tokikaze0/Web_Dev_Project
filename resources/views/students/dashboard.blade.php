@extends('student.layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Student Events</h2>

    <div id="calendar"></div> <!-- FullCalendar container -->
</div>

<!-- FullCalendar initialization -->
<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            events: [
                @foreach($events as $event)
                {
                    title: '{{ $event->name }}',
                    start: '{{ $event->start_time }}',
                    end: '{{ $event->end_time }}',
                    // Custom data for attendance
                    data: {
                        eventId: {{ $event->id }},
                    },
                    color: getEventColor({{ $event->id }}) // Get color based on attendance
                },
                @endforeach
            ],
        });

        // Function to determine the color based on attendance
        function getEventColor(eventId) {
            var status = getAttendanceStatus(eventId); // Call the PHP function to get the attendance status
            if (status === 'attended') {
                return 'green'; // Green for attended
            } else if (status === 'late') {
                return 'yellow'; // Yellow for late
            } else {
                return 'red'; // Red for absent
            }
        }

        // PHP logic to determine attendance status for the event
        function getAttendanceStatus(eventId) {
            var attendanceLogs = @json($attendanceLogs); // Pass the PHP data to JavaScript
            var status = 'absent'; // Default is absent

            // Check if the student attended or was late
            attendanceLogs.forEach(function(log) {
                if (log.event_id === eventId) {
                    var eventStartTime = moment(log.event.start_time);
                    var attendedAt = moment(log.attended_at);

                    if (attendedAt.isBefore(eventStartTime)) {
                        status = 'attended'; // Attended on time
                    } else if (attendedAt.isAfter(eventStartTime) && attendedAt.isBefore(eventStartTime.add(30, 'minutes'))) {
                        status = 'late'; // Attended but late
                    }
                }
            });

            return status;
        }
    });
</script>
@endsection
