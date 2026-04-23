<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - SmartAttend</title>
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
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 p-3 text-gray-500 hover:bg-gray-50 rounded-xl transition">
                    <i class="fas fa-th-large w-5"></i> Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 p-3 text-gray-500 hover:bg-gray-50 rounded-xl transition">
                    <i class="fas fa-user w-5"></i> Profile
                </a>
                <a href="{{ route('student.reports') }}" class="flex items-center gap-3 p-3 bg-indigo-50 text-indigo-600 rounded-xl font-medium">
                    <i class="fas fa-chart-bar w-5"></i> Reports
                </a>
            </nav>
            <div class="p-6 border-t border-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-red-500 font-medium w-full">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Attendance Reports</h1>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 mb-8">
                <form action="{{ route('student.reports') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('student.reports') }}" class="text-gray-400 text-sm hover:underline ml-2 pb-2">Reset</a>
                </form>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 italic">Attendance Logs</h3>
                <a href="{{ route('student.reports.pdf', request()->query()) }}" 
                  class="text-xs font-bold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-lg hover:bg-indigo-100 transition">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 text-gray-400 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-8 py-4">Date</th>
                            <th class="px-8 py-4">Subject</th> <th class="px-8 py-4">Check-in Time</th>
                            <th class="px-8 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($allAttendances as $row)
                        <tr class="text-sm text-gray-600 hover:bg-gray-50/50 transition">
                            <td class="px-8 py-4 font-medium">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                            
                            <td class="px-8 py-4 font-bold text-indigo-600">
                                {{ $row->course->name ?? 'General' }}
                            </td>

                            <td class="px-8 py-4 text-gray-400">{{ $row->check_in ?? '--:--:--' }}</td>
                            <td class="px-8 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $row->status == 'Present' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 italic">No records found for the selected dates.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <div class="p-6 bg-gray-50/30">
                    {{ $allAttendances->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

</body>
</html>