<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 shadow-md flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <!-- Logo -->
            <img src="{{ asset('MinSULogo.png') }}" alt="Logo" class="w-10 h-10">
            <span class="text-white text-2xl font-semibold">Admin Panel</span>
        </div>

        <!-- Navbar Links -->
        <div class="hidden md:flex space-x-8 text-white text-lg">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-200 transition duration-200">Dashboard</a>
            <a href="{{ route('admin.students.index') }}" class="hover:text-blue-200 transition duration-200">Students</a>
            <a href="{{ route('admin.events.index') }}" class="hover:text-blue-200 transition duration-200">Events</a>
        </div>

        <!-- Profile & Logout -->
        <div class="flex items-center space-x-6 text-white text-lg">
            <a href="{{ route('admin.profile') }}" class="hover:text-blue-200 transition duration-200">Profile</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-white hover:text-blue-200 transition duration-200">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="p-8">
        @yield('content')
    </div>

</body>

</html>
