<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - SmartAttend</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FE]">

    <div class="flex min-h-screen">
        <div class="w-64 bg-white border-r border-gray-100 flex flex-col">
            <div class="p-6 text-2xl font-bold text-indigo-600 flex items-center gap-2">
                <i class="fas fa-university"></i> SmartAttend
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a href="#" class="flex items-center gap-3 p-3 bg-indigo-50 text-indigo-600 rounded-xl font-medium">
                    <i class="fas fa-chalkboard-teacher w-5"></i> Teacher Panel
                </a>
                <div class="mb-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 p-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-colors">
                <i class="fas fa-sign-out-alt w-5"></i> Logout
            </button>
        </form>
    </div>
            </nav>
        </div>

        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Instructor Dashboard</h1>
                    <p class="text-gray-500 text-sm">Select a course to start the QR attendance session.</p>
                </div>
                <div class="bg-indigo-600 text-white px-6 py-2 rounded-full font-bold shadow-lg shadow-indigo-100">
                    Today's Attendance: {{ $todayTotal }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($courses as $course)
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50 hover:shadow-xl transition-all group">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->name }}</h3>
                    <p class="text-gray-400 text-sm mb-6 font-mono">{{ $course->code }}</p>
                    
                    <a href="{{ route('teacher.projector', $course->id) }}" target="_blank" class="block text-center bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                        <i class="fas fa-qrcode mr-2"></i> Launch Projector
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>