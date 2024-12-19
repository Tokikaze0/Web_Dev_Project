<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Center the clock */
        .clock {
            font-size: 100px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-top: 200px;
        }

        /* Style the navbar */
        .navbar {
            background-color: #343a40;
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
        }

        /* Additional styles for centering the clock and making it responsive */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="" alt="No Icon" class="w-8 h-8"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Centered Content with Clock -->
    <div class="container">
        <div class="clock" id="clock"></div>
    </div>

    <script>
        // Function to update the clock every second
        function updateClock() {
            const clockElement = document.getElementById('clock');
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Update clock every second
        setInterval(updateClock, 1000);

        // Initialize the clock on page load
        updateClock();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>