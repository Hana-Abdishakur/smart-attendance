<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - SmartAttend</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FE]">

    <div class="flex min-h-screen">
        <div class="w-64 bg-white border-r border-gray-100 flex flex-col fixed h-full">
            <div class="p-6 text-2xl font-bold text-indigo-600 flex items-center gap-2">
                <i class="fas fa-university"></i> SmartAttend
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 p-3 text-gray-500 hover:bg-gray-50 rounded-xl transition font-medium">
                    <i class="fas fa-th-large w-5"></i> Dashboard
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3 bg-indigo-50 text-indigo-600 rounded-xl font-medium">
                    <i class="fas fa-user w-5"></i> Profile
                </a>
                <a href="{{ route('student.reports') }}" class="flex items-center gap-3 p-3 text-gray-500 hover:bg-gray-50 rounded-xl transition font-medium">
                    <i class="fas fa-chart-bar w-5"></i> Reports
                </a>
            </nav>
            <div class="p-6 border-t border-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-red-500 font-medium w-full hover:bg-red-50 p-2 rounded-lg transition">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 ml-64 p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Account Profile</h1>
                <p class="text-gray-500 text-sm">Halkan ka maamul macluumaadkaaga gaarka ah.</p>
            </div>

            <div class="max-w-4xl space-y-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-50">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 border-l-4 border-l-red-500">
                    <div class="max-w-xl text-gray-400">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>