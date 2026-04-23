@extends('layouts.admin')

@section('content')
<div class="mb-8 flex items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.students.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-100 transition border border-gray-100">
            <i class="fas fa-arrow-left text-gray-400"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black text-gray-800">{{ $student->name }}</h1>
            <p class="text-gray-500 font-medium italic text-sm">Attendance record for the last 30 days</p>
        </div>
    </div>
    
    <a href="{{ route('admin.student.report', $student->id) }}" 
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-[1.5rem] flex items-center gap-2 font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 transition-all hover:scale-105 active:scale-95">
        <i class="fas fa-file-pdf text-lg"></i> 
        Download Report
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 text-center">
            <div class="w-24 h-24 bg-indigo-50 text-indigo-600 rounded-[2rem] flex items-center justify-center text-4xl font-black mx-auto mb-6">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <h3 class="text-xl font-bold text-gray-800">{{ $student->name }}</h3>
            <p class="text-gray-400 mb-6 font-medium text-sm">{{ $student->email }}</p>
            
            <div class="pt-6 border-t border-gray-50 grid grid-cols-2 gap-4">
                <div class="text-center">
                    <p class="text-[10px] uppercase tracking-widest font-black text-gray-300">Student ID</p>
                    <p class="font-bold text-gray-700">#{{ $student->id }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] uppercase tracking-widest font-black text-gray-300">Joined</p>
                    <p class="font-bold text-gray-700">{{ $student->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden text-sm">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h3 class="font-bold text-lg text-gray-800">Attendance Log</h3>
                <span class="px-4 py-1 bg-indigo-600 text-white text-[10px] font-black rounded-full uppercase">Recent First</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-[0.2em] font-black">
                        <tr>
                            <th class="px-8 py-5">Date</th>
                            <th class="px-8 py-5 text-center">Check-in</th>
                            <th class="px-8 py-5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($attendances as $record)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-5 font-bold text-gray-700">
                                {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-5 text-center text-gray-400 font-medium italic">
                                {{ $record->check_in ?? '--:--' }}
                            </td>
                            <td class="px-8 py-5 text-right">
                                @php
                                    $statusClasses = [
                                        'Present' => 'bg-green-100 text-green-600',
                                        'Late'    => 'bg-orange-100 text-orange-600',
                                        'Absent'  => 'bg-red-100 text-red-600',
                                    ];
                                    $class = $statusClasses[$record->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase {{ $class }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center">
                                <i class="fas fa-calendar-times text-4xl text-gray-100 mb-4 block"></i>
                                <p class="text-gray-400 italic">No attendance records found for this student.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection9