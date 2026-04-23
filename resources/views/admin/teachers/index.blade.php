@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center px-2">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Teacher Management</h1>
            <p class="text-sm text-gray-500 font-medium">Manage your faculty and instructors.</p>
        </div>
        <button onclick="toggleModal('teacherModal')" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New Teacher
        </button>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl font-bold mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Teachers Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50 border-b border-gray-50">
                <tr>
                    <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">Instructor</th>
                    <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">Email Address</th>
                    <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Courses</th>
                    <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($teachers as $teacher)
                <tr class="hover:bg-indigo-50/20 transition group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-lg">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="block font-bold text-gray-800">{{ $teacher->name }}</span>
                                <span class="text-[10px] text-indigo-500 font-black uppercase tracking-tighter">Verified Faculty</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 font-medium text-gray-600">{{ $teacher->email }}</td>
                    <td class="px-8 py-5 text-center">
                        <span class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-xl text-xs font-black border border-indigo-100">
                            {{ $teacher->courses_count ?? 0 }} Classes
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            {{-- Edit Button --}}
                            <button onclick="openEditModal({{ $teacher }})" class="p-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                            {{-- Delete Button --}}
                            <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Ma hubtaa inaad tirtirto macallinkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL: Add Teacher --}}
<div id="teacherModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="p-8 border-b border-gray-50 bg-gray-50/30 text-center">
            <h3 class="text-2xl font-black text-gray-800">New Instructor</h3>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">Onboarding Form</p>
        </div>
        <form action="{{ route('admin.teachers.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Full Name</label>
                <input type="text" name="name" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 outline-none font-medium" placeholder="Dr. Liban Yusuf" required>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Email Address</label>
                <input type="email" name="email" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 outline-none font-medium" placeholder="liban@gmail.com" required>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Access Password</label>
                <input type="password" name="password" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 outline-none font-medium" required>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="toggleModal('teacherModal')" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-sm hover:bg-gray-200 transition">Cancel</button>
                <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition">Save Data</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: Edit Teacher (Cusub) --}}
<div id="editTeacherModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-indigo-50/30 text-center">
            <h3 class="text-2xl font-black text-gray-800">Edit Faculty</h3>
            <p class="text-indigo-400 text-xs font-bold uppercase tracking-widest mt-1">Update Information</p>
        </div>
        <form id="editTeacherForm" method="POST" class="p-8 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Full Name</label>
                <input type="text" name="name" id="edit_name" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 outline-none font-medium" required>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Email Address</label>
                <input type="email" name="email" id="edit_email" class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 outline-none font-medium" required>
            </div>
            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                <p class="text-[10px] text-amber-600 font-black leading-tight">PASSWORD NOTE: Leave blank if you don't want to change the password.</p>
                <input type="password" name="password" class="w-full bg-white mt-2 border-none rounded-xl p-3 text-sm" placeholder="New password (optional)">
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="toggleModal('editTeacherModal')" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-sm">Close</button>
                <button type="submit" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-black text-sm shadow-xl shadow-blue-100 hover:bg-blue-700 transition">Update Faculty</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function openEditModal(teacher) {
        // Buuxi form-ka xogta macallinka la riixay
        document.getElementById('edit_name').value = teacher.name;
        document.getElementById('edit_email').value = teacher.email;
        
        // Dynamic Route Setting (Update Route)
        let form = document.getElementById('edit_teacher_form');
        // Hubi in route-kan uu jiro web.php-gaaga
        document.getElementById('editTeacherForm').action = `/admin/teachers/${teacher.id}`;
        
        toggleModal('editTeacherModal');
    }
</script>
@endsection