<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPS - Payment Gateway</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        /* Spinner Animation */
        .loader { border-top-color: transparent; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-[#F8FAFC] flex items-center justify-center min-h-screen p-4 font-sans text-gray-900">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 p-8 animate-fade-in">
        
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl shadow-lg rotate-3">
                <i class="fas fa-university"></i>
            </div>
            <h2 class="text-2xl font-black tracking-tight uppercase">Saps Payment</h2>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1 italic text-indigo-500">Uganda Testing Mode</p>
        </div>

        <div class="bg-gray-50 rounded-3xl p-6 mb-6 border border-gray-100 relative overflow-hidden">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-500 font-bold text-[10px] uppercase tracking-widest">Total Fee</span>
                <span class="text-2xl font-black text-gray-800">$50.00</span>
            </div>
            <div class="flex justify-between items-center pt-4 border-t border-dashed border-gray-200">
                <span class="text-gray-400 font-bold text-[10px] uppercase tracking-widest italic">In Local UGX</span>
                <div class="text-right">
                    <span class="block font-black text-indigo-600 text-lg">190,000 UGX</span>
                    <span class="text-[8px] text-gray-400 font-bold uppercase">Rate: 3,800 UGX</span>
                </div>
            </div>
        </div>

        <div class="flex p-1 bg-gray-100 rounded-2xl mb-6">
            <button type="button" onclick="switchMethod('mobile')" id="btn-mobile" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase bg-white shadow-sm text-indigo-600 transition-all">
                <i class="fas fa-mobile-alt mr-1"></i> Mobile Money
            </button>
            <button type="button" onclick="switchMethod('card')" id="btn-card" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase text-gray-500 transition-all">
                <i class="fas fa-credit-card mr-1"></i> Visa / Card
            </button>
        </div>

        <form action="{{ route('student.pay') }}" method="POST" id="payment-form">
            @csrf
            <input type="hidden" name="payment_method" id="selected-method" value="mobile_money">

            <div id="section-mobile" class="space-y-4">
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-100 text-center">
                        <img src="https://raw.githubusercontent.com/the-muda-designer/logos/main/mtn-logo.png" class="h-6 mx-auto mb-1" alt="MTN">
                        <p class="text-[9px] font-black text-yellow-700 italic underline">ID: 123456</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-100 text-center">
                        <img src="https://raw.githubusercontent.com/the-muda-designer/logos/main/airtel-logo.png" class="h-6 mx-auto mb-1" alt="Airtel">
                        <p class="text-[9px] font-black text-red-700 italic underline">ID: 654321</p>
                    </div>
                </div>

                <div class="space-y-4 text-left">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Phone Number</label>
                        <input type="text" name="phone_number" placeholder="07xxxxxxxx" required
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2 tracking-widest">Transaction Reference</label>
                        <input type="text" name="transaction_id" placeholder="Enter ID from SMS" required
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-indigo-600">
                    </div>
                </div>
            </div>

            <div id="section-card" class="hidden space-y-4 animate-fade-in text-left">
                <div class="space-y-3">
                    <input type="text" placeholder="Card Number" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold">
                    <div class="flex gap-3">
                        <input type="text" placeholder="MM/YY" class="w-1/2 px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold">
                        <input type="text" placeholder="CVC" class="w-1/2 px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold">
                    </div>
                </div>
            </div>

            <button type="submit" id="submit-btn" class="w-full mt-8 bg-indigo-600 text-white py-5 rounded-3xl font-black shadow-xl hover:bg-indigo-700 transition-all uppercase text-[11px] tracking-widest flex items-center justify-center gap-2">
                <span id="btn-text">Confirm Payment</span>
                <i id="btn-icon" class="fas fa-check-circle"></i>
                <div id="btn-spinner" class="hidden h-5 w-5 border-2 border-white rounded-full loader"></div>
            </button>
        </form>

        <div class="mt-6 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-gray-400 text-[10px] font-bold uppercase hover:text-red-500 transition underline tracking-tighter">Sign out and pay later</button>
            </form>
        </div>
    </div>

    <script>
        // Form Loading Logic
        const paymentForm = document.getElementById('payment-form');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnIcon = document.getElementById('btn-icon');
        const btnSpinner = document.getElementById('btn-spinner');

        paymentForm.addEventListener('submit', function() {
            // Disable button to prevent double submit
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            
            // Show loading state
            btnText.innerText = "Processing...";
            btnIcon.classList.add('hidden');
            btnSpinner.classList.remove('hidden');
        });

        // Tab Switching Logic
        function switchMethod(method) {
            const btnMobile = document.getElementById('btn-mobile');
            const btnCard = document.getElementById('btn-card');
            const sectionMobile = document.getElementById('section-mobile');
            const sectionCard = document.getElementById('section-card');
            const methodInput = document.getElementById('selected-method');
            const mobileInputs = sectionMobile.querySelectorAll('input');

            if(method === 'mobile') {
                methodInput.value = 'mobile_money';
                mobileInputs.forEach(i => i.required = true);
                btnMobile.className = "flex-1 py-3 rounded-xl font-black text-[10px] uppercase bg-white shadow-sm text-indigo-600 transition-all";
                btnCard.className = "flex-1 py-3 rounded-xl font-black text-[10px] uppercase text-gray-500 transition-all";
                sectionMobile.classList.remove('hidden');
                sectionCard.classList.add('hidden');
            } else {
                methodInput.value = 'card';
                mobileInputs.forEach(i => i.required = false);
                btnCard.className = "flex-1 py-3 rounded-xl font-black text-[10px] uppercase bg-white shadow-sm text-indigo-600 transition-all";
                btnMobile.className = "flex-1 py-3 rounded-xl font-black text-[10px] uppercase text-gray-500 transition-all";
                sectionCard.classList.remove('hidden');
                sectionMobile.classList.add('hidden');
            }
        }
    </script>
</body>
</html>