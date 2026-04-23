@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight text-shadow-sm">Attendance Reports</h1>
        <nav class="text-sm font-bold text-indigo-500 flex gap-2 uppercase tracking-tighter">
            <span>Dashboard</span> <span class="text-gray-300">›</span> <span class="text-gray-400">Reports</span>
        </nav>
    </div>
    
    <div class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.reports') }}" method="GET" id="reportForm" class="flex gap-2">
            <select name="month" class="border-none bg-gray-50 rounded-xl font-bold text-gray-600 text-sm focus:ring-2 focus:ring-indigo-500">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                Update View
            </button>

            <a href="{{ route('admin.export.pdf', ['month' => request('month', now()->month)]) }}" 
               target="_blank" 
               class="bg-gray-900 text-white px-4 py-2 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-black transition">
                <i class="fas fa-file-pdf"></i>
            </a>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Avg. Attendance Rate</p>
            <h2 class="text-3xl font-black text-indigo-600">{{ round($avgRate, 1) }}%</h2>
        </div>
        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500 text-xl">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Late Arrivals</p>
            <h2 class="text-3xl font-black text-orange-500">{{ $totalLate }}</h2>
        </div>
        <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 text-xl">
            <i class="fas fa-clock"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Alerts</p>
            <h2 class="text-lg font-black text-gray-800 italic uppercase">Review Absents</h2>
        </div>
        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-500">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
    </div>
</div>

<div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-black text-gray-800 text-xl italic tracking-tight underline decoration-indigo-200 underline-offset-8">Attendance Trend</h3>
        <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Monthly Participation</span>
    </div>
    <div class="h-[250px]">
        <canvas id="attendanceChart"></canvas>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden mb-12">
    <div class="p-8 border-b border-gray-50 bg-gray-50/20">
        <h3 class="font-black text-gray-800 text-xl italic">Detailed Records</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                <tr>
                    <th class="px-8 py-5">Student Information</th>
                    <th class="px-8 py-5 text-center">Present</th>
                    <th class="px-8 py-5 text-center">Late</th>
                    <th class="px-8 py-5 text-center">Absent</th>
                    <th class="px-8 py-5 text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 font-medium text-gray-600">
                @foreach($reportData as $data)
                <tr class="hover:bg-gray-50/30 transition group">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-black border border-indigo-100">
                                {{ substr($data['name'], 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 group-hover:text-indigo-600 transition">{{ $data['name'] }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $data['email'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center font-black text-green-600">{{ $data['present'] }}</td>
                    <td class="px-8 py-6 text-center font-black text-orange-500">{{ $data['late'] }}</td>
                    <td class="px-8 py-6 text-center font-black text-red-500">{{ $data['absent'] }}</td>
                    <td class="px-8 py-6 text-right">
                        @php 
                            $total = $data['present'] + $data['late'] + $data['absent'];
                            $rate = $total > 0 ? round((($data['present'] + $data['late']) / $total) * 100) : 0;
                        @endphp
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black {{ $rate > 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $rate }}% RATE
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                data: [82, 95, 88, 98],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.05)',
                fill: true,
                tension: 0.4,
                borderWidth: 5,
                pointRadius: 6,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 100, 
                    grid: { color: '#f3f4f6' },
                    ticks: { font: { weight: 'bold' } }
                },
                x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
            }
        }
    });
</script>
@endsection