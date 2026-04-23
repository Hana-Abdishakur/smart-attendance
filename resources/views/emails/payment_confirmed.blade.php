<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - SmartAttend</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="5;url={{ route('student.dashboard') }}">
</head>
<body class="bg-[#F8F9FE] min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 text-center animate-fade-in">
        
        <div class="w-20 h-20 bg-green-50 text-green-600 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
            <i class="fas fa-check-double"></i>
        </div>

        <h2 class="text-2xl font-black text-gray-800 mb-2">Payment Successful!</h2>
        <p class="text-gray-400 text-sm mb-8 font-medium">
            Thank you, <strong>{{ $userName }}</strong>. Your registration payment of <strong>$50.00</strong> has been confirmed.
        </p>

        <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100 text-left mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Transaction ID</span>
                <span class="text-xs font-mono font-bold text-indigo-600">{{ $transactionId }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Access Status</span>
                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded-full font-bold">FULL ACCESS UNLOCKED</span>
            </div>
        </div>

        <a href="{{ route('student.dashboard') }}" 
           class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 group">
            Go to My Dashboard
            <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
        </a>

        <p class="mt-6 text-[10px] text-gray-400">
            You will be automatically redirected in <span class="font-bold text-indigo-500">5 seconds</span>.
        </p>
    </div>

</body>
</html>