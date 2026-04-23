@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-black text-gray-800">Student Directory</h1>
        <p class="text-gray-500 font-medium">Manage and monitor student performance.</p>
    </div>

    <div class="flex items-center gap-3">
        <form action="{{ route('admin.students.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="pl-12 pr-4 py-3 rounded-2xl border-none shadow-sm focus:ring-2 focus:ring-indigo-500 w-64 md:w-80" 
                   placeholder="Search name or email...">
            <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
        </form>

        <button onclick="openAddModal()" class="bg-indigo-600 text-white px-5 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Add Student</span>
        </button>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-2xl font-bold">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50/50 text-gray-400 text-xs uppercase tracking-widest font-bold">
            <tr>
                <th class="px-8 py-6">Student</th>
                <th class="px-8 py-6">Email</th>
                <th class="px-8 py-6 text-center">Attendance Rate</th>
                <th class="px-8 py-6 text-center">Status</th>
                <th class="px-8 py-6 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($students as $student)
            <tr class="hover:bg-indigo-50/30 transition group">
                <td class="px-8 py-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div>
                        <span class="block font-bold text-gray-800">{{ $student->name }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">ID: #{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </td>
                <td class="px-8 py-5 text-gray-500 font-medium">{{ $student->email }}</td>
                
                <td class="px-8 py-5 text-center">
                    @php
                        $total = $student->attendances->count();
                        $present = $student->attendances->whereIn('status', ['Present', 'Late'])->count();
                        $rate = $total > 0 ? round(($present / $total) * 100) : 0;
                    @endphp
                    <div class="flex flex-col items-center gap-1">
                        <span class="font-black {{ $rate >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $rate }}%</span>
                        <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $rate >= 75 ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ $rate }}%"></div>
                        </div>
                    </div>
                </td>

                <td class="px-8 py-5 text-center">
                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase {{ $rate >= 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $rate >= 75 ? 'Active' : 'At Risk' }}
                    </span>
                </td>

                <td class="px-8 py-5 text-right">
                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition">
                        {{-- BADHANKA MAHADNAQA: Kaliya haddii uu yahay 90% ama ka badan --}}
                        @if($rate >= 90)
                        <a href="{{ route('admin.students.appreciate', $student->id) }}" 
                        class="p-2.5 bg-green-50 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition"
                        title="Send Appreciation Email">
                            <i class="fas fa-medal"></i>
                        </a>
                        @endif

                        {{-- BADHANKA DIGNIINTA: Kaliya haddii uu yahay ka hooseeyo 75% --}}
                        @if($rate < 75)
                        <a href="{{ route('admin.students.warning', $student->id) }}" 
                        class="p-2.5 bg-orange-50 text-orange-600 rounded-xl hover:bg-orange-600 hover:text-white transition"
                        title="Send Warning Email">
                            <i class="fas fa-paper-plane"></i>
                        </a>
                        @endif
                        <a href="{{ route('admin.students.show', $student->id) }}" class="p-2.5 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Ma hubtaa?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-20 text-center text-gray-400 italic">No students found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MODAL Halkan ku dar --}}
<div id="studentModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl">
        <h2 class="text-2xl font-black text-gray-800 mb-6">Add New Student</h2>
        <form action="{{ route('admin.teachers.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <input type="text" name="name" placeholder="Full Name" required class="w-full px-5 py-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-indigo-500">
                <input type="email" name="email" placeholder="Email Address" required class="w-full px-5 py-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-indigo-500">
                <input type="password" name="password" placeholder="Password" required class="w-full px-5 py-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex gap-3 mt-8">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 font-bold text-gray-500 hover:bg-gray-50 rounded-2xl transition">Cancel</button>
                <button type="submit" class="flex-1 py-4 font-bold bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">Save Student</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() { document.getElementById('studentModal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('studentModal').classList.add('hidden'); }
</script>
@endsection