@extends('layouts.admin')

@section('content')
<div class="p-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Payment Management</h1>
            <p class="text-gray-500 font-medium">Verify and manage student tuition payments</p>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('admin.payments') }}" method="GET" class="relative">
                <input type="text" name="search" placeholder="Search ID or Student..." 
                       class="pl-10 pr-4 py-2 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none w-64 shadow-sm">
                <i class="fas fa-search absolute left-4 top-3 text-gray-400 text-sm"></i>
            </form>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-widest font-black">
                <tr>
                    <th class="px-8 py-6">Student Information</th>
                    <th class="px-8 py-6">Transaction Details</th>
                    <th class="px-8 py-6">Amount</th>
                    <th class="px-8 py-6">Status</th>
                    <th class="px-8 py-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50/50 transition group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm uppercase">
                                {{ substr($payment->student_name, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 group-hover:text-indigo-600 transition">{{ $payment->student_name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $payment->student_email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <span class="font-mono text-xs text-indigo-600 bg-indigo-50/50 px-2 py-1 rounded-md tracking-tighter">
                            #{{ $payment->transaction_id }}
                        </span>
                        <p class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y - h:i A') }}</p>
                    </td>
                    <td class="px-8 py-5">
                        <p class="font-black text-gray-800 text-lg">${{ number_format($payment->amount, 2) }}</p>
                    </td>
                    <td class="px-8 py-5">
                        @if($payment->status == 'pending')
                            <span class="px-4 py-1.5 bg-yellow-50 text-yellow-600 rounded-full text-[10px] font-black uppercase ring-1 ring-yellow-100">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @else
                            <span class="px-4 py-1.5 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase ring-1 ring-green-100">
                                <i class="fas fa-check-double mr-1"></i> Verified
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-center">
                        @if($payment->status == 'pending')
                            <form action="{{ route('admin.approve.payment', $payment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-xs font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition transform hover:-translate-y-0.5">
                                    Approve Payment
                                </button>
                            </form>
                        @else
                            <button class="bg-gray-50 text-gray-400 px-5 py-2 rounded-xl text-xs font-bold cursor-not-allowed opacity-60" disabled>
                                Already Verified
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-receipt text-5xl text-gray-100 mb-4"></i>
                            <p class="text-gray-400 font-medium">No payment records found at the moment.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="p-6 bg-gray-50/30 border-t border-gray-50">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection