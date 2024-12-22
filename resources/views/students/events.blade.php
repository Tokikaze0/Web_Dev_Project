@extends('layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Student Events</h2>

    <!-- FullCalendar container -->
    <div id="calendar"></div>
</div>

<!-- FullCalendar initialization -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/dist/fullcalendar.min.js"></script>
<script>
    $(document).ready(function() {
        // Check if events and attendance logs are passed correctly
        console.log("Events:", @json($events));
        console.log("Attendance Logs:", @json($attendanceLogs));

        var events = @json($events);  // This will properly encode your PHP $events array as JSON
        var attendanceLogs = @json($attendanceLogs); // Pass the PHP attendance logs to JS

        $('#calendar').fullCalendar({
            events: events.map(function(event) {
                return {
                    title: event.name,
                    start: event.start_time,
                    end: event.end_time,
                    eventId: event.id // This should be directly added, no `data` property here
                };
            })
        });

        // Function to determine the color based on attendance
        function getEventColor(eventId) {
            var status = getAttendanceStatus(eventId);
            if (status === 'attended') {
                return 'green'; // Green for attended
            } else if (status === 'late') {
                return 'yellow'; // Yellow for late
            } else {
                return 'red'; // Red for absent
            }
        }

        // Function to determine the attendance status of a student for the given event
        function getAttendanceStatus(eventId) {
            var status = 'absent'; // Default status

            // Loop through the attendance logs and check the attendance status for the eventId
            attendanceLogs.forEach(function(log) {
                if (log.event_id === eventId) {
                    var eventStartTime = moment(log.event.start_time);  // Use Moment.js to handle the time
                    var attendedAt = moment(log.attended_at);

                    // If the attendedAt time is before the event start time, mark as attended
                    if (attendedAt.isBefore(eventStartTime)) {
                        status = 'attended';
                    } 
                    // If the student attended within 30 minutes of the start time, mark as late
                    else if (attendedAt.isBetween(eventStartTime, eventStartTime.clone().add(30, 'minutes'))) {
                        status = 'late';
                    }
                }
            });

            return status;
        }
    });
</script>
@endsection
