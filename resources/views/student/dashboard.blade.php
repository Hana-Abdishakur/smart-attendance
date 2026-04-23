<!DOCTYPE html>
<html lang="en" x-data="{ mobileMenuOpen: false }"> {{-- Ku dar x-data halkan --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartAttend - Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Ku dar Alpine.js si uu menu-ga u furmo --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .tab-active { border-bottom: 3px solid #4f46e5; color: #4f46e5; }
        /* Hide scrollbar for clean mobile look */
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-[#F8F9FE]">

    <div class="flex min-h-screen relative overflow-x-hidden">
        
        {{-- Sidebar - Modified for Responsive --}}
        <div 
            :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="w-64 bg-white border-r border-gray-100 flex flex-col p-6 fixed h-full z-50 transition-transform duration-300 ease-in-out shadow-xl md:shadow-none">
            
            <div class="flex justify-between items-center mb-10">
                <div class="text-2xl font-bold text-indigo-600 flex items-center gap-2">
                    <i class="fas fa-university"></i> SmartAttend
                </div>
                {{-- Badhanka menu-ga lagu xirayo (Mobile Only) --}}
                <button @click="mobileMenuOpen = false" class="md:hidden text-gray-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="space-y-2 flex-1">
                <a href="#" class="flex items-center gap-3 p-3 bg-indigo-50 text-indigo-600 rounded-xl font-medium">
                    <i class="fas fa-th-large w-5"></i> Dashboard
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3 text-gray-500 hover:bg-gray-50 rounded-xl transition">
                    <i class="fas fa-user w-5"></i> Profile
                </a>
                <a href="{{ route('student.reports') }}" class="flex items-center gap-3 p-3 text-gray-500 hover:bg-gray-50 rounded-xl transition">
                    <i class="fas fa-chart-bar w-5"></i> Reports
                </a>

                <hr class="my-4 border-gray-50">

                <div class="pt-2">
    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-3">Account Status</p>
@php
    // Waxaan soo qaadaynaa xogta lacagta ee u dambaysay (ha noqoto completed ama pending)
    $latestPayment = \DB::table('payments')
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->first();
@endphp

<div class="mx-2 space-y-2">
    @if($latestPayment && $latestPayment->status == 'completed')
        <div class="flex items-center gap-3 p-3 bg-green-50 text-green-600 rounded-xl text-[11px] font-bold border border-green-100">
            <i class="fas fa-check-circle text-sm"></i>
            <span>Verified (Paid)</span>
        </div>
        <a href="{{ route('student.receipt', $latestPayment->transaction_id) }}" 
           class="flex items-center justify-center gap-2 w-full py-3 bg-white border border-gray-200 text-gray-700 text-[10px] rounded-xl font-bold hover:bg-gray-50 shadow-sm transition group">
            <i class="fas fa-file-invoice text-indigo-500 group-hover:scale-110 transition"></i>
            VIEW OFFICIAL RECEIPT
        </a>

    @elseif($latestPayment && $latestPayment->status == 'pending')
        <div class="p-3 bg-yellow-50 rounded-xl border border-yellow-100 shadow-sm">
            <div class="flex items-center gap-2 text-yellow-700 text-[11px] font-bold">
                <i class="fas fa-hourglass-half animate-pulse"></i>
                <span>Payment Pending...</span>
            </div>
            <p class="text-[9px] text-yellow-600 mt-1 italic">Sug inta Admin-ku xaqiijinayo.</p>
        </div>

    @else
        <div class="p-3 bg-red-50 rounded-xl border border-red-100">
            <div class="flex items-center gap-2 text-red-600 text-[11px] font-bold mb-2">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Fee Pending</span>
            </div>
            <a href="{{ route('student.checkout') }}" class="block w-full py-2 bg-red-500 text-white text-[10px] text-center rounded-lg font-bold hover:bg-red-600 shadow-sm transition uppercase">
                PAY $50 NOW
            </a>
        </div>
    @endif
</div>
            </nav>

            <div class="pt-6 border-t border-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-red-500 font-medium w-full p-3 hover:bg-red-50 rounded-xl transition">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- Dark Overlay markuu Menu-ga Mobile-ka furan yahay --}}
        <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/20 z-40 md:hidden transition-opacity"></div>

        {{-- Main Content - Modified for Responsive Margin --}}
        <div class="flex-1 ml-0 md:ml-64 p-4 md:p-8 transition-all duration-300 w-full">
            
            {{-- Mobile Header - Only shows on mobile --}}
            <div class="flex md:hidden justify-between items-center mb-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-50">
                <button @click="mobileMenuOpen = true" class="p-2 text-indigo-600 bg-indigo-50 rounded-lg">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="font-bold text-gray-800">SmartAttend</div>
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-[10px] font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>

            {{-- Header --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-500 text-xs md:text-sm">Managing your attendance for <b>{{ now()->format('l') }}</b></p>
                </div>
                <div class="hidden md:flex items-center gap-4 bg-white p-2 pr-6 rounded-full shadow-sm border border-gray-50">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center border border-indigo-100 shadow-sm text-indigo-600 font-bold uppercase">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">ID: #{{ auth()->user()->id }}</p>
                    </div>
                </div>
            </div>

            {{-- Live Clock --}}
            <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] p-6 md:p-10 shadow-sm border border-gray-50 text-center mb-8">
                <h2 class="text-4xl md:text-6xl font-black text-gray-800 mb-2" id="liveClock">00:00:00 AM</h2>
                <p class="text-gray-400 font-medium mb-8 uppercase tracking-widest text-[10px] md:text-xs">{{ now()->format('l, F d, Y') }}</p>

                <div id="scanner-container" class="hidden flex flex-col items-center animate-fade-in">
                    <div id="reader" class="w-full max-w-[320px] rounded-3xl overflow-hidden border-4 border-indigo-100 shadow-2xl mb-4"></div>
                    <p id="scan-label" class="text-indigo-600 font-bold mb-4 text-sm"></p>
                    <button onclick="stopScanner()" class="text-red-500 font-medium underline text-sm">Cancel Scanning</button>
                </div>
            </div>

            {{-- Tabs Navigation --}}
            <div class="flex gap-4 md:gap-8 border-b border-gray-100 mb-8 no-scrollbar overflow-x-auto">
                <button onclick="switchTab('today')" id="btn-today" class="pb-4 text-[11px] md:text-sm font-black uppercase tracking-widest tab-active transition-all whitespace-nowrap">
                    Today's Focus
                </button>
                <button onclick="switchTab('weekly')" id="btn-weekly" class="pb-4 text-[11px] md:text-sm font-black uppercase tracking-widest border-b-3 border-transparent text-gray-400 hover:text-gray-600 transition-all whitespace-nowrap">
                    Weekly Schedule
                </button>
            </div>

            {{-- Tab Content: Today --}}
            <div id="tab-today" class="tab-content animate-fade-in">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-12">
                    @forelse($courses as $course)
                        @php 
                            $attendance = $todayAttendances->get($course->id); 
                            $isFinished = now()->toTimeString() > $course->end_time;
                        @endphp
                        <div class="bg-white p-5 md:p-6 rounded-3xl border 
                            {{ $attendance ? 'border-green-200 bg-green-50/5' : ($isFinished ? 'border-red-100 bg-red-50/5' : 'border-gray-50 shadow-sm') }} transition-all">
                            
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-10 h-10 md:w-12 md:h-12 
                                    {{ $attendance ? 'bg-green-100 text-green-600' : ($isFinished ? 'bg-red-100 text-red-600' : 'bg-indigo-50 text-indigo-600') }} 
                                    rounded-2xl flex items-center justify-center text-lg md:text-xl">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase 
                                    @if($attendance)
                                        bg-green-100 text-green-600"> {{ $attendance->status }}
                                    @elseif($isFinished)
                                        bg-red-100 text-red-600"> Absent
                                    @else
                                        bg-gray-100 text-gray-400"> Not Scanned
                                    @endif
                                </span>
                            </div>

                            <h4 class="font-bold text-gray-800 text-md md:text-lg leading-tight">{{ $course->name }}</h4>
                            <p class="text-indigo-500 text-[10px] font-bold mt-1">
                                <i class="fas fa-user-tie mr-1"></i> {{ $course->teacher->name ?? 'Instructor' }}
                            </p>
                            <p class="text-gray-400 text-[9px] mt-3 mb-4">
                                <i class="far fa-clock mr-1"></i> 
                                {{ \Carbon\Carbon::parse($course->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($course->end_time)->format('h:i A') }}
                            </p>

                            @if($attendance)
                                <div class="text-center py-3 bg-green-50 text-green-600 rounded-2xl text-[10px] font-bold border border-green-100">
                                    <i class="fas fa-check-circle mr-1"></i> Checked-in at {{ \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') }}
                                </div>
                            @elseif($isFinished)
                                <div class="text-center py-3 bg-red-50 text-red-600 rounded-2xl text-[10px] font-bold border border-red-100 uppercase">
                                    <i class="fas fa-times-circle mr-1"></i> Session Ended
                                </div>
                            @else
                                <button onclick="startScanner({{ $course->id }}, '{{ $course->name }}')" class="w-full bg-indigo-600 text-white py-3 rounded-2xl font-bold text-xs md:text-sm hover:bg-indigo-700 transition shadow-lg">
                                    <i class="fas fa-qrcode mr-2"></i> Mark Attendance
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full py-12 bg-white rounded-[2rem] border-2 border-dashed border-gray-100 text-center">
                            <p class="text-gray-400 font-bold text-sm">No classes scheduled for today.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Tab Content: Weekly Schedule --}}
            <div id="tab-weekly" class="tab-content hidden animate-fade-in">
                <div class="space-y-6 mb-12">
                    @php $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']; @endphp
                    @foreach($days as $day)
                        @php $dayCourses = $weeklySchedule->where('class_day', $day); @endphp
                        <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] border border-gray-50 shadow-sm overflow-hidden">
                            <div class="px-6 py-3 md:px-8 md:py-4 bg-gray-50/50 border-b border-gray-50 flex justify-between items-center">
                                <h4 class="font-black text-[10px] md:text-xs uppercase tracking-widest {{ now()->format('l') == $day ? 'text-indigo-600' : 'text-gray-500' }}">
                                    {{ $day }}
                                </h4>
                                @if(now()->format('l') == $day)
                                    <span class="bg-indigo-600 text-white text-[8px] md:text-[9px] px-3 py-1 rounded-full font-bold uppercase">Today</span>
                                @endif
                            </div>
                            <div class="p-2">
                                @forelse($dayCourses as $wCourse)
                                    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition-colors border-b last:border-0 border-gray-50">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 h-8 md:w-10 md:h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm text-xs">
                                                <i class="fas fa-book-open"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs md:text-sm font-black text-gray-800">{{ $wCourse->name }}</p>
                                                <p class="text-[10px] md:text-[11px] text-indigo-500 font-bold">
                                                    <i class="fas fa-user-tie mr-1"></i> {{ $wCourse->teacher->name ?? 'Instructor' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-3 md:mt-0 flex items-center gap-6 md:gap-8">
                                            <div class="text-left md:text-right">
                                                <p class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase">Duration</p>
                                                <p class="text-[10px] md:text-[11px] font-black text-gray-700">
                                                    {{ \Carbon\Carbon::parse($wCourse->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($wCourse->end_time)->format('h:i A') }}
                                                </p>
                                            </div>
                                            <div class="text-left md:text-right border-l pl-6 md:pl-8 border-gray-100">
                                                <p class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase">Code</p>
                                                <p class="text-[10px] md:text-[11px] font-black text-gray-700">{{ $wCourse->code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[9px] text-gray-300 italic p-4 text-center">No classes scheduled.</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Activity --}}
            <h3 class="text-md md:text-lg font-bold text-gray-800 mb-6">Recent Activity</h3>
            <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] shadow-sm border border-gray-50 overflow-x-auto mb-12 no-scrollbar">
                <table class="w-full text-left min-w-[500px]">
                    <tbody class="divide-y divide-gray-50">
                        @foreach($attendances as $row)
                        <tr class="hover:bg-gray-50/50 transition text-[11px] md:text-sm">
                            <td class="px-6 py-4 md:px-8 md:py-6">
                                <p class="font-bold text-gray-800">{{ $row->course->name ?? 'Unknown' }}</p>
                                <p class="text-[9px] text-gray-400">{{ $row->course->teacher->name ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4 md:px-8 md:py-6 text-gray-500">
                                <i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 md:px-8 md:py-6 text-indigo-600 font-bold">
                                <i class="far fa-clock mr-1 text-[9px]"></i> 
                                {{ $row->check_in ? \Carbon\Carbon::parse($row->check_in)->format('h:i A') : '--:--' }}
                            </td>
                            <td class="px-6 py-4 md:px-8 md:py-6 text-right">
                                <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase 
                                    {{ $row->status == 'Present' ? 'bg-green-100 text-green-600' : 
                                      ($row->status == 'Late' ? 'bg-orange-100 text-orange-600' : 'bg-red-100 text-red-600') }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('liveClock').textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});
        }
        setInterval(updateClock, 1000); updateClock();

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('tab-' + tabName).classList.remove('hidden');

            const btnToday = document.getElementById('btn-today');
            const btnWeekly = document.getElementById('btn-weekly');

            if(tabName === 'today') {
                btnToday.classList.add('tab-active'); btnToday.classList.remove('text-gray-400');
                btnWeekly.classList.remove('tab-active'); btnWeekly.classList.add('text-gray-400');
            } else {
                btnWeekly.classList.add('tab-active'); btnWeekly.classList.remove('text-gray-400');
                btnToday.classList.remove('tab-active'); btnToday.classList.add('text-gray-400');
            }
        }

        const html5QrCode = new Html5Qrcode("reader");
        let selectedCourseId = null;

        function startScanner(id, name) {
            selectedCourseId = id;
            document.getElementById('scanner-container').classList.remove('hidden');
            document.getElementById('scan-label').textContent = "Scanning for: " + name;

            html5QrCode.start(
                { facingMode: "environment" }, 
                { fps: 20, qrbox: { width: 250, height: 250 } }, 
                (decodedText) => {
                    html5QrCode.stop().then(() => {
                        sendCheckInData(decodedText);
                    });
                }
            ).catch(err => alert("Camera Error!"));
        }

        function sendCheckInData(token) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/student/checkin`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ course_id: selectedCourseId, qr_token: token })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) { alert("✅ Success!"); window.location.reload(); }
                else { alert("❌ Error: " + data.message); document.getElementById('scanner-container').classList.add('hidden'); }
            });
        }

        function stopScanner() {
            html5QrCode.stop().then(() => {
                document.getElementById('scanner-container').classList.add('hidden');
            });
        }
    </script>
</body>
</html>