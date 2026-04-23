@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Academic Courses</h1>
            <p class="text-sm text-gray-500">Define subjects and assign them to instructors.</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-4">Create New Course</h3>
        <form action="{{ route('admin.courses.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <input type="text" name="course_name" placeholder="Course Title (e.g. Advanced PHP)" class="bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 outline-none md:col-span-1" required>
            <input type="text" name="course_code" placeholder="Code (e.g. CS301)" class="bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 outline-none" required>
            <select name="teacher_id" class="bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 outline-none" required>
                <option value="">Select Instructor</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
            <button class="bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">Create Course</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($courses as $course)
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition group">
            <div class="flex justify-between items-start mb-4">
                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-black px-3 py-1 rounded-full uppercase">{{ $course->course_code }}</span>
                <i class="fas fa-ellipsis-h text-gray-300 group-hover:text-gray-500 transition cursor-pointer"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-1">{{ $course->course_name }}</h2>
            <p class="text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-user-circle"></i> {{ $course->teacher->name }}
            </p>
            <div class="mt-6 pt-6 border-t border-gray-50 flex justify-between items-center">
                <span class="text-xs text-green-500 font-bold flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Active
                </span>
                <span class="text-[10px] text-gray-400">AY 2026/2027</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection