<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartAttend - Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FE]">
    <div class="flex min-h-screen">
        <div class="w-64 bg-[#1A1D23] text-gray-400 flex flex-col">
            <div class="p-8 text-xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-shield-alt text-indigo-500"></i> SmartAttend
            </div>

            <!-- Navigation Links(QYPTA SIDEBAR KA) -->
            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg' : 'hover:bg-gray-800' }} rounded-xl transition">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 p-3 {{ request()->routeIs('admin.students.*') ? 'bg-indigo-600 text-white shadow-lg' : 'hover:bg-gray-800' }} rounded-xl transition">
                    <i class="fas fa-user-graduate"></i> Students
                </a>
                <a href="{{ route('admin.teachers') }}" class="flex items-center gap-3 p-3 {{ request()->routeIs('admin.teachers') ? 'bg-indigo-600 text-white shadow-lg' : 'hover:bg-gray-800' }} rounded-xl transition">
                    <i class="fas fa-chalkboard-teacher"></i> Teachers
                </a>
                <a href="{{ route('admin.courses') }}" class="flex items-center gap-3 p-3 {{ request()->routeIs('admin.courses') ? 'bg-indigo-600 text-white shadow-lg' : 'hover:bg-gray-800' }} rounded-xl transition">
                    <i class="fas fa-book"></i> Courses
                </a>
                <a href="#" class="flex items-center gap-3 p-3 hover:bg-gray-800 rounded-xl transition">
                    <i class="fas fa-calendar-check"></i> Attendance
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 p-3 {{ request()->routeIs('admin.reports') ? 'bg-indigo-600 text-white shadow-lg' : 'hover:bg-gray-800' }} rounded-xl transition">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </nav>

            <div class="p-6 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-red-400 font-medium w-full">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </div>
    </div>
</body>
</html>