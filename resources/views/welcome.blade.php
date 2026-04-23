<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPS - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center font-sans antialiased">

    <div class="relative flex items-top justify-center sm:items-center py-4 sm:pt-0">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0 mb-8">
                <div class="h-16 w-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg">
                    <i class="fas fa-university"></i>
                </div>
                <div class="ml-4 flex flex-col justify-center">
                    <h1 class="text-3xl font-black text-gray-800 tracking-tighter">SAPS</h1>
                    <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-[0.2em]">Smart Attendance</p>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-xl rounded-[2rem] border border-gray-100 p-10 text-center max-w-sm mx-auto">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Welcome to SAPS</h2>
                <p class="text-sm text-gray-500 mb-8">Access your dashboard to manage attendance and payments.</p>

                <div class="space-y-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                                DASHBOARD
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full py-4 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                                LOGIN
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-full py-4 bg-white border-2 border-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">
                                    REGISTER
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>

            <div class="mt-8 text-center text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                &copy; 2026 SAPS System - All Rights Reserved
            </div>
        </div>
    </div>

</body>
</html>