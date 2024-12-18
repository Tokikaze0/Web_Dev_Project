<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-500 p-4 flex justify-between items-center">
        <div>
            <img src="{{ asset('MinSULogo.png') }}" alt="Logo" class="w-10 h-10">
        </div>
        <!-- Logo -->

        <div>
            <div class="flex space-x-6 text-white text-lg">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
                <a href="{{ route('admin.students.index') }}" class="hover:underline">Students</a>
                <a href="{{ route('admin.events.index') }}" class="hover:underline">Events</a>
            </div>
        </div>
        <!-- Center Links -->

        <!-- Profile & Logout -->
        <div class="flex space-x-4">
            <a href="{{ route('admin.profile') }}" class="text-white hover:underline">Profile</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-white hover:underline">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="p-8">
        @yield('content')
    </div>
</body>

</html>