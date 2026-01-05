<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>
    <style>
        /* Reset some defaults */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Overlay background (optional dark layer) */
        .alert-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* হালকা কালো ব্যাকগ্রাউন্ড */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* Alert box */
        .alert {
            position: relative;
            min-width: 300px;
            padding: 15px 20px;
            border-radius: 6px;
            font-size: 16px;
            animation: fadeIn 0.4s ease;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }


        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .d-none {
            display: none;
        }

        .m-0 {
            margin: 0px !important;
        }

        .p-0 {
            margin: 0px !important;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Base button style */
        .btn {
            display: inline-block;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            color: white;
        }

        .btn-center {
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .btn-lg {
            padding: 15px;
        }

        /* Small buttons */
        .btn-sm {
            padding: 4px 10px !important;
            font-size: 12px;
        }

        /* Primary - Blue */
        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Success - Green */
        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #1e7e34;
        }

        /* Warning - Orange */
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            color: #212529;
        }

        /* Danger - Red */
        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #bd2130;
        }

        /* Navbar */       
        header {
            padding: 0;
        }
        .header {
            padding: 15px 20px;
            margin-left: auto;
            margin-right: auto;
        }
        .header {
            width: 1200px;
        }
        header,
        .header {
            background-color: #222;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 20px;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        header nav .logout-link, aside .logout-link {
            background: transparent;
            border: none;
            color: #ff4e4e;
            font-size: 16px;
        }

        header nav .logout-link {
            margin-left: 15px;
        }

        aside .logout-link {
            padding: 10px 20px;
        }
        
        button,
        .logout-link {
            cursor: pointer;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        .navbar-top {
            margin-bottom: 20px;
        }

        /* Layout wrapper */
        .wrapper {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 60px); /* Adjust for header */
        }

        /* Sidebar */
        aside {
            width: 220px;
            background-color: #333;
            color: white;
            padding-top: 20px;
            flex-shrink: 0;
        }

        aside ul {
            list-style: none;
        }

        aside ul li {
            margin-bottom: 10px;
        }

        aside ul li a {
            display: block;
        }

        aside ul li a, aside ul li button {
            color: #c4cac5;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
        }

        aside ul li a.active, aside ul li a:hover, aside ul li button:hover {
            color: white;
        }

        aside ul li button {
            background-color: transparent;
            border: none;
        }

        /* Main content */
        main {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
        }

        /* Card Styles */
        .card {
            width: 100%;
            border: none;
        }

        .shadow {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Card Header */
        .card-header {
            background-color: #4e73df;
            color: #fff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }

        .card-header h4 {
            font-size: 22px;
            font-weight: bold;
        }

        /* Card Body */
        .card-body {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }

        /* Success Message */
        .alert-success {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        /* Form Styles */
        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        .form-control,
        .form-select {
            width: 100%;
        }

        .form-control {
            border: 1px solid #d1d3e2;
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
            background-color: #f8f9fc;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        /* Select */
        .form-select {
            border: 1px solid #d1d3e2;
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
            background-color: #f8f9fc;
        }

        .form-select:focus {
            border-color: #4e73df;
        }

        /* Input File & Image Preview */
        input[type="file"] {
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }

        #imagePreviewWrapper {
            max-width: 100%;
            position: relative;
        }

        #imagePreview {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
        }

        #imagePreviewWrapper button {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            padding: 5px 10px;
            cursor: pointer;
        }

        #imagePreviewWrapper button:hover {
            background-color: #ff4e4e;
        }

        /* Modal Overlay */
        .modal-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Ensure modal is on top */
        }

        /* Modal Container */
        .modal-container {
            background-color: #fff;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            transition: all 0.3s ease;
        }

        /* Modal Header */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: bold;
        }

        /* Close Button */
        .close-btn {
            font-size: 24px;
            cursor: pointer;
            border: none;
            background: transparent;
        }

        /* Modal Body */
        .modal-body {
            font-size: 16px;
        }

        /* Modal Footer */
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-footer button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        /* Responsive: Collapse sidebar on small screens */
        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }
            aside {
                width: 100%;
            }

            .card-body {
                padding: 20px;
            }

            .modal-container {
                width: 90%;
            }

            .btn {
                width: 100%;
            }
        }

    
    </style>
    <!-- Optional custom CSS -->
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <header>
        <div class="header">
            <h1>LW Settings</h1>
            <nav class="d-flex">
                @auth
                    Hi, {{ auth()->user()->name }}
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="logout-link" type="submit">Logout</button>
                        </form>
                @endauth
                @guest
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                @endguest
            </nav>
        </div>
    </header>


    <!-- Layout Wrapper -->
    <div class="wrapper">
        <!-- Sidebar -->
        <aside>
            <ul class="sidebar">
                <li class="link"><a href="{{ route('site.settings') }}">All Settings</a>
                {{-- <li class="link"><a href="{{ route('site.settings.create') }}">Add New Settings</a> --}}
                <li class="link"><a href="{{ route('site-settings.preference') }}">Preference</a></li>
                <li class="link">
                    <button type="button"
                            onclick="window.open('{{ $documentation }}', '_blank')">
                        Documentation
                    </button>
                </li>

            </ul>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="logout-link" type="submit">Logout</button>
                </form>
            @endauth
        </aside>

        <!-- Main Content -->
        <main>

            {{-- ===== Flash Messages ===== --}}
            @if(session('success'))
                <div class="alert-overlay" id="success-alert">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-overlay" id="error-alert">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @yield('content')

        </main>
    </div>
    @stack('scripts')

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(function () {
                    successAlert.style.display = 'none';
                }, 2000);
            }

            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                setTimeout(function () {
                    errorAlert.style.display = 'none';
                }, 2000);
            }
        });
    </script>
</body>
</html>