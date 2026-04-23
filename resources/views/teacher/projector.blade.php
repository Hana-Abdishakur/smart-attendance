<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projector Mode - {{ $course->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#0F172A] text-white min-h-screen flex items-center justify-center p-8">

    <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        
        <div class="text-center bg-white p-12 rounded-[3rem] shadow-2xl shadow-indigo-500/20">
            <h2 class="text-indigo-600 text-xl font-bold mb-6 uppercase tracking-widest">Scan to Mark Attendance</h2>
            <div class="inline-block p-4 bg-white border-8 border-indigo-50 rounded-3xl">
                {!! $qrCode !!}
            </div>
            <p class="mt-8 text-gray-400 font-medium">Course ID: <span class="text-indigo-600">#{{ $course->id }}</span></p>
        </div>

        <div class="space-y-8">
            <div>
                <h1 class="text-5xl font-black mb-2 text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">
                    {{ $course->name }}
                </h1>
                <p class="text-gray-400 text-lg italic">Instructor: {{ auth()->user()->name }}</p>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-xl p-8 rounded-3xl border border-slate-700">
                <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
                    <span class="flex h-3 w-3 rounded-full bg-green-500 animate-pulse"></span>
                    Recent Check-ins
                </h3>
                
                <div class="space-y-4">
                    @forelse($recentAttendance as $attend)
                        <div class="flex items-center justify-between bg-slate-700/30 p-4 rounded-2xl border border-slate-600/50">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ $attend->user->name }}&background=4e73df&color=fff" class="w-10 h-10 rounded-full">
                                <span class="font-bold">{{ $attend->user->name }}</span>
                            </div>
                            <span class="text-xs text-indigo-400 font-mono">{{ $attend->check_in }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 italic text-center py-4">Waiting for students to scan...</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => { location.reload(); }, 5000); // Wuxuu is-cusboonaysiinayaa 10-kii ilbiriqsi kasta
    </script>
</body>
</html>