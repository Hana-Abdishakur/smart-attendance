<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPS - Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 font-sans">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg rotate-3">
                    <i class="fas fa-university text-xl"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter text-slate-800 uppercase">SAPS</span>
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-10 border border-slate-100">
            <div class="mb-8 text-center md:text-left">
                <h2 class="text-2xl font-bold text-slate-800">Create Account</h2>
                <p class="text-sm text-slate-400 mt-1">Join SAPS to manage your attendance today.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Full Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400">
                            <i class="fas fa-user text-xs"></i>
                        </span>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe"
                               class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 text-sm">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com"
                               class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 text-sm">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input id="password" type="password" name="password" required placeholder="••••••••"
                               class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 text-sm">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-1 italic">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400">
                            <i class="fas fa-check-circle text-xs"></i>
                        </span>
                        <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••"
                               class="w-full pl-12 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 text-sm">
                    </div>
                </div>

                <div class="flex justify-center py-2">
                    <div class="cf-turnstile" 
                         data-sitekey="0x4AAAAAACzXR2vy1ukfywn_" 
                         data-theme="light">
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black shadow-xl shadow-indigo-100 hover:bg-indigo-700 active:scale-[0.98] transition-all uppercase text-xs tracking-widest flex items-center justify-center gap-2">
                    <span>Create Account</span>
                    <i class="fas fa-user-plus text-[10px]"></i>
                </button>
            </form>
        </div>

        <p class="mt-8 text-center text-sm font-bold text-slate-400 italic">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-indigo-600 font-black hover:underline not-italic ml-2 uppercase tracking-wide">Sign In</a>
        </p>
    </div>

</body>
</html>