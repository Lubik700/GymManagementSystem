<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gym Management System - Login</title>
  
  <!-- Tailwind CSS CDN (for quick prototyping) -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Optional: better font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; }
  </style>
</head>
<body class="h-full bg-gray-50">

<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">

    <!-- Logo + Title -->
    <div class="text-center">
      <div class="mx-auto h-16 w-16 rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center shadow-lg">
        <span class="text-white text-3xl font-bold tracking-tight">G</span>
      </div>
      <h2 class="mt-6 text-3xl font-bold text-gray-900 tracking-tight">
        Gurkhas<span class="text-indigo-600">Fitness</span>
      </h2>
      <!-- <p class="mt-2 text-sm text-gray-600">
        Sign in to manage members, classes & payments
      </p> -->
    </div>

    <!-- Card -->
    <div class="mt-8 bg-white py-10 px-10 shadow-2xl rounded-2xl border border-gray-100/80">

      <!-- Error message example (you can show/hide with JS or backend) -->
      <!--
      <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
        <p class="text-sm text-red-700">Invalid credentials. Please try again.</p>
      </div>
      -->

      <form class="space-y-6" action="{{ route('login.submit') }}" method="POST">
    @csrf
    @if($errors->any())
    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
        <p class="text-sm text-red-700">{{ $errors->first() }}</p>
    </div>
@endif
        <!-- Email / Username -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">
            Email or Username
          </label>
          <div class="mt-1.5">
            <input
              id="email"
    name="email"
    type="text"
    value="{{ old('email') }}"
    autocomplete="email username"
    required
    class="block w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 sm:text-sm transition duration-150 shadow-sm"
    placeholder="member@gymflow.com"
            />
          </div>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">
            Password
          </label>
          <div class="mt-1.5">
            <input
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="block w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 sm:text-sm transition duration-150 shadow-sm"
              placeholder="••••••••••"
            />
          </div>
        </div>

        <!-- Remember + Forgot -->
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember"
              name="remember"
              type="checkbox"
              class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
            />
            <label for="remember" class="ml-2 block text-sm text-gray-600 select-none">
              Remember me
            </label>
          </div>

          <div class="text-sm">
            <a href="{{ route('password.forgot') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition">
              Forgot password?
            </a>
          </div>
        </div>

        <!-- Login Button -->
        <div>
          <button
            type="submit"
            class="group relative flex w-full justify-center rounded-lg bg-indigo-600 px-4 py-3.5 text-sm font-semibold text-white shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200"
          >
            <span class="absolute left-0 inset-y pl-4 flex items-center">
              <svg class="h-5 w-5 text-indigo-400 group-hover:text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
              </svg>
            </span>
            Sign in
          </button>
        </div>

      </form>

      <!-- Register link -->
      <p class="mt-8 text-center text-sm text-gray-600">
        New to the gym? 
        <a href="{{route('register')}}" class="font-medium text-indigo-600 hover:text-indigo-500 transition">
          Register
        </a>
      </p>

    </div>

    <p class="mt-8 text-center text-xs text-gray-500">
      © 2025 GymFlow • All rights reserved
    </p>

  </div>
</div>
@if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl text-green-700 text-sm">
        {{ session('success') }}
    </div>
@endif
</body>
</html>