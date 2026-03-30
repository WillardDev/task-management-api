<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #F8FAFC; font-family: 'Inter', sans-serif; }
        .sidebar-icon { @apply text-gray-400 hover:text-blue-600 transition-colors text-xl; }
    </style>
</head>
<body class="flex min-h-screen">
    <aside class="w-20 bg-white border-r border-gray-100 flex flex-col items-center py-8 space-y-10">
        <div class="text-blue-600 text-2xl"><i class="fas fa-th-large"></i></div>
        <nav class="flex flex-col space-y-8">
            <a href="{{ route('tasks.index') }}" class="sidebar-icon text-blue-600"><i class="fas fa-home"></i></a>
            {{-- <a href="#" class="sidebar-icon"><i class="fas fa-envelope"></i></a>
            <a href="#" class="sidebar-icon"><i class="fas fa-calendar-alt"></i></a>
            <a href="#" class="sidebar-icon"><i class="fas fa-chart-bar"></i></a> --}}
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="h-16 bg-white border-b border-gray-50 flex items-center justify-between px-10">
            <div class="text-sm text-gray-400 font-medium">Tasks &gt; <span class="text-gray-900">Today</span></div>
            <div class="flex items-center space-x-6">
                <a href="#" class="text-sm font-semibold text-blue-600 border-b-2 border-blue-600 pb-1">Board</a>
                {{-- <a href="#" class="text-sm font-semibold text-gray-400">Activity</a> --}}
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-400 to-purple-500 shadow-sm"></div>
            </div>
        </header>

        <main class="p-10">
            @yield('content')
        </main>
    </div>

    <script>const token = document.querySelector('meta[name="csrf-token"]').content;</script>
    @stack('scripts')
</body>
</html>
