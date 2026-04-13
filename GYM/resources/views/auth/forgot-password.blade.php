<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">

<div class="max-w-md w-full">
    <!-- Logo -->
    <div class="text-center mb-8">
        <div class="mx-auto h-16 w-16 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center shadow-lg">
            <span class="text-white text-3xl font-bold">G</span>
        </div>
        <h2 class="mt-6 text-3xl font-bold text-gray-900">Forgot Password</h2>
        <p class="mt-2 text-sm text-gray-600">Enter your email to receive an OTP</p>
    </div>

    <div class="bg-white py-8 px-8 shadow-2xl rounded-2xl border border-gray-100">

        @if($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.send-otp') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    placeholder="your@email.com"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                Send OTP
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Remember your password?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign in</a>
        </p>
    </div>
</div>

</body>
</html>