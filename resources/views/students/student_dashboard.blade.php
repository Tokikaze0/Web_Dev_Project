@extends('layout')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Student Event Attendance</h2>

    <!-- Debugging output to check data -->

    <!-- Event Attendance Table -->
    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr>
                <th class="border-b p-2 text-left">Event Name</th>
                <th class="border-b p-2 text-left">Event Date & Time</th>
                <th class="border-b p-2 text-left">Attendance Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td class="border-b p-2">{{ $event->name }}</td>
                    <td class="border-b p-2">{{ \Carbon\Carbon::parse($event->start_time)->format('F j, Y, g:i A') }}</td>
                    <td class="border-b p-2">
                        @php
                            $attendance = $attendanceLogs->firstWhere('event_id', $event->id);
                            $status = $attendance ? ($attendance->attended_at ? 'Attended' : 'Not Attended') : 'Not Attended';
                        @endphp
                        {{ $status }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
