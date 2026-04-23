@extends('layouts.admin')

@section('content')
<div class="p-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">System Overview</h1>
            <p class="text-gray-500 font-medium">Monitoring activity for <span class="text-indigo-600">{{ now()->format('l, M d, Y') }}</span></p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.location.reload()" class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm text-gray-500 hover:text-indigo-600 transition">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="{{ route('admin.reports') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                <i class="fas fa-file-download mr-2"></i> Export Report
            </a>
        </div>
    </div>

    {{-- Quick Stats Grid --}}
    {{-- Tier 1: System Overview (Total Counts) --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    {{-- Total Students --}}
    <div class="bg-indigo-600 p-6 rounded-[2.5rem] shadow-lg shadow-indigo-100 flex items-center justify-between group">
        <div>
            <p class="text-indigo-200 text-[10px] font-black uppercase tracking-widest">Total Students</p>
            <p class="text-3xl font-black text-white">{{ $totalStudents ?? 0 }}</p>
        </div>
        <div class="text-indigo-400 text-4xl opacity-50"><i class="fas fa-user-graduate"></i></div>
    </div>

    {{-- Total Teachers --}}
    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:border-indigo-200 transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Teachers</p>
            <p class="text-3xl font-black text-gray-800">{{ $totalTeachers ?? 0 }}</p>
        </div>
        <div class="text-indigo-100 text-4xl group-hover:text-indigo-500 transition-colors"><i class="fas fa-chalkboard-teacher"></i></div>
    </div>

    {{-- Total Courses --}}
    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:border-indigo-200 transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Courses</p>
            <p class="text-3xl font-black text-gray-800">{{ $totalCourses ?? 0 }}</p>
        </div>
        <div class="text-indigo-100 text-4xl group-hover:text-indigo-500 transition-colors"><i class="fas fa-book-open"></i></div>
    </div>
</div>
{{-- Tier 1.5: Finance Overview (New Section) --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    {{-- Total Revenue --}}
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:border-green-200 transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Revenue</p>
            <p class="text-2xl font-black text-gray-800">${{ number_format($totalRevenue ?? 0, 2) }}</p>
        </div>
        <div class="text-green-100 text-3xl group-hover:text-green-500 transition-colors"><i class="fas fa-hand-holding-usd"></i></div>
    </div>

    {{-- Pending Verifications --}}
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:border-yellow-200 transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Pending Approval</p>
            <p class="text-2xl font-black text-gray-800">{{ $pendingPayments ?? 0 }}</p>
        </div>
        <div class="text-yellow-100 text-3xl group-hover:text-yellow-500 transition-colors"><i class="fas fa-history"></i></div>
    </div>

    {{-- Verified Students --}}
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:border-indigo-200 transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Paid Students</p>
            <p class="text-2xl font-black text-gray-800">{{ $paidStudentsCount ?? 0 }}</p>
        </div>
        <div class="text-indigo-100 text-3xl group-hover:text-indigo-500 transition-colors"><i class="fas fa-user-check"></i></div>
    </div>

    {{-- Manage Payments Button --}}
    <a href="{{ route('admin.payments') }}" class="bg-gray-900 p-6 rounded-[2rem] shadow-lg flex items-center justify-between group hover:bg-gray-800 transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Finance Center</p>
            <p class="text-lg font-bold text-white tracking-tight">View All Payments <i class="fas fa-arrow-right ml-2 text-xs"></i></p>
        </div>
        <div class="text-gray-600 text-3xl"><i class="fas fa-file-invoice-dollar"></i></div>
    </a>
</div>

{{-- Tier 2: Today's Live Attendance (Status) --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    {{-- Present Today --}}
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border-l-8 border-green-500 flex items-center justify-between group hover:shadow-md transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Present Today</p>
            <p class="text-3xl font-black text-green-600">{{ $todayPresent ?? 0 }}</p>
        </div>
        <div class="text-green-100 text-4xl group-hover:text-green-500 transition-colors"><i class="fas fa-check-circle"></i></div>
    </div>

    {{-- Late Today --}}
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border-l-8 border-orange-500 flex items-center justify-between group hover:shadow-md transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Late Today</p>
            <p class="text-3xl font-black text-orange-500">{{ $todayLate ?? 0 }}</p>
        </div>
        <div class="text-orange-100 text-4xl group-hover:text-orange-500 transition-colors"><i class="fas fa-clock"></i></div>
    </div>

    {{-- Absent Today --}}
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border-l-8 border-red-500 flex items-center justify-between group hover:shadow-md transition">
        <div>
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Absent Today</p>
            <p class="text-3xl font-black text-red-600">{{ $todayAbsent ?? 0 }}</p>
        </div>
        <div class="text-red-100 text-4xl group-hover:text-red-500 transition-colors"><i class="fas fa-user-times"></i></div>
    </div>
</div>

    {{-- Main Analytics Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Attendance Mix --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50 flex flex-col items-center justify-center">
            <h3 class="font-bold text-gray-800 mb-6 uppercase text-xs tracking-widest">Today's Attendance Mix</h3>
            <div class="relative w-[220px] h-[220px]">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        {{-- Weekly Trend --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50">
            <h3 class="font-bold text-gray-800 mb-6 uppercase text-xs tracking-widest">Weekly Attendance Trend</h3>
            <div class="h-[250px]">
                <canvas id="weeklyTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
    <h3 class="font-bold text-gray-800 mb-4">Today's Schedule</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($todayClasses as $class)
            <div class="p-4 border-l-4 border-indigo-500 bg-indigo-50/30 rounded-r-xl">
                <p class="font-bold text-indigo-900">{{ $class->name }}</p>
                <p class="text-xs text-gray-500">{{ $class->start_time }} - {{ $class->end_time }}</p>
                <p class="text-xs font-medium text-indigo-600">Teacher: {{ $class->teacher->name }}</p>
            </div>
        @endforeach
    </div>
</div>

    {{-- Live Feed & Alerts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-xl text-gray-800">Live Activity Feed</h3>
                <span class="px-4 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase animate-pulse">Live Now</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase tracking-widest font-bold">
                        <tr>
                            <th class="px-8 py-5">Student Info</th>
                            <th class="px-8 py-5">Course</th>
                            <th class="px-8 py-5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentAttendances ?? [] as $attendance)
                        <tr class="hover:bg-gray-50/80 transition group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                        {{ substr($attendance->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-700 group-hover:text-indigo-600 transition">{{ $attendance->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-gray-500 text-sm font-medium">{{ $attendance->course->name ?? 'N/A' }}</td>
                            <td class="px-8 py-5 text-right">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase {{ ($attendance->status ?? '') == 'Present' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                                    {{ $attendance->status ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-12 text-center text-gray-400 font-medium">No activity recorded yet today.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-red-50 flex flex-col">
            <h3 class="font-bold text-red-600 mb-6 uppercase text-xs tracking-widest flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i> Low Attendance Alerts
            </h3>
            <div class="space-y-4">
                @forelse($lowAttendanceStudents ?? [] as $student)
                <div class="flex items-center justify-between p-4 bg-red-50/50 rounded-2xl border border-red-100">
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $student->name }}</p>
                        <p class="text-[10px] text-gray-400 uppercase">Rate: {{ $student->attendance_rate ?? 0 }}%</p>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-red-300"></i>
                </div>
                @empty
                <div class="text-center py-4 text-gray-400 text-xs italic">No critical alerts.</div>
                @endforelse
            </div>
            <div class="mt-auto pt-6">
                <div class="bg-indigo-600 p-6 rounded-3xl text-white shadow-lg shadow-indigo-100">
                    <p class="text-xs text-indigo-100 font-bold uppercase mb-1">System Health</p>
                    <p class="text-3xl font-black mb-2">{{ number_format($avgRate ?? 0, 1) }}%</p>
                    <div class="w-full bg-indigo-500 rounded-full h-1.5">
                        <div class="bg-white h-1.5 rounded-full" style="width: {{ $avgRate ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Doughnut Chart
        const ctxMix = document.getElementById('attendanceChart');
        if (ctxMix) {
            new Chart(ctxMix, {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Late', 'Absent'],
                    datasets: [{
                        data: [
                            {{ ($todayPresent ?? 0) - ($todayLate ?? 0) }},
                            {{ $todayLate ?? 0 }},
                            {{ $todayAbsent ?? 0 }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: { cutout: '80%', plugins: { legend: { display: false } } }
            });
        }

        // Line Chart
        const ctxTrend = document.getElementById('weeklyTrendChart');
        if (ctxTrend) {
            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($days ?? []) !!},
                    datasets: [{
                        label: 'Attendance',
                        data: {!! json_encode($weeklyData ?? []) !!},
                        borderColor: '#4f46e5',
                        borderWidth: 4,
                        fill: true,
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
                }
            });
        }

        setTimeout(() => { window.location.reload(); }, 120000);
    });
</script>
@endsection