<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPS - Official Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        
        /* Hagaajinta Daabacaadda */
        @media print { 
            .no-print { display: none !important; } 
            body { background: white !important; padding: 0 !important; }
            .receipt-card { 
                box-shadow: none !important; 
                border: 1px solid #eee !important;
                margin-top: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-[#F8FAFC] flex items-center justify-center min-h-screen p-4 font-sans text-gray-900">

    <div class="receipt-card max-w-md w-full bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 p-8 animate-fade-in relative">
        
        <div class="absolute top-6 right-6 rotate-12 opacity-[0.07] no-print">
            <i class="fas fa-check-circle text-8xl text-green-500"></i>
        </div>

        <div class="text-center mb-8 relative">
            <div class="w-16 h-16 bg-green-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg -rotate-3">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <h2 class="text-2xl font-black tracking-tight uppercase italic">Official Receipt</h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1 italic text-green-600">Payment Successfully Verified</p>
        </div>

        <div class="bg-gray-50 rounded-3xl p-6 mb-6 border border-gray-100 space-y-4">
            
            <div class="flex justify-between items-center pb-3 border-b border-dashed border-gray-200">
                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">Student Name</span>
                <span class="font-black text-gray-800 text-xs uppercase">{{ auth()->user()->name }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b border-dashed border-gray-200">
                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">Transaction ID</span>
                <span class="font-black text-indigo-600 text-xs">{{ $payment->transaction_id }}</span>
            </div>

            <div class="flex justify-between items-center pb-3 border-b border-dashed border-gray-200">
                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">Payment Method</span>
                <span class="font-black text-gray-800 text-[10px] uppercase">
                    <i class="fas {{ $payment->payment_method == 'mobile_money' ? 'fa-mobile-alt' : 'fa-credit-card' }} mr-1"></i>
                    {{ str_replace('_', ' ', $payment->payment_method) }}
                </span>
            </div>

            @if($payment->phone_number)
            <div class="flex justify-between items-center pb-3 border-b border-dashed border-gray-200">
                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">Phone Number</span>
                <span class="font-black text-gray-800 text-xs">{{ $payment->phone_number }}</span>
            </div>
            @endif

            <div class="flex justify-between items-center pt-2">
                <span class="text-gray-800 font-black text-[11px] uppercase tracking-widest">Total Amount</span>
                <div class="text-right">
                    <span class="block font-black text-green-600 text-xl">
                        {{ number_format($payment->amount) }} {{ strtoupper($payment->currency) }}
                    </span>
                    <span class="text-[8px] text-gray-400 font-bold uppercase italic">
                        {{ \Carbon\Carbon::parse($payment->created_at)->format('D, d M Y - h:i A') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="text-center space-y-4">
            <div class="inline-block p-3 bg-white border border-gray-100 rounded-2xl shadow-sm">
                <i class="fas fa-qrcode text-4xl text-gray-300"></i>
            </div>
            <p class="text-[9px] text-gray-400 leading-relaxed px-6 italic">
                This is a secure, computer-generated receipt for <b>Smart Attendance & Payment System (SAPS)</b>. No physical signature is required.
            </p>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-3 no-print">
            <button onclick="window.print()" class="bg-gray-100 text-gray-700 py-4 rounded-2xl font-black text-[10px] uppercase hover:bg-gray-200 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-print text-indigo-500"></i> Print Receipt
            </button>
            <a href="{{ route('student.dashboard') }}" class="bg-indigo-600 text-white py-4 rounded-2xl font-black text-[10px] uppercase hover:bg-indigo-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-100">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>

    </div>

</body>
</html>