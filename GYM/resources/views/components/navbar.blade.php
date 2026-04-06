<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gym Dashboard - Responsive Navbar</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
  </style>
</head>
<body class="bg-gray-50">

@php
    $client = session('client_id') ? App\Models\Client::find(session('client_id')) : null;
@endphp

<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">

      <!-- Left: Logo + Gym Name -->
      <div class="flex items-center space-x-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center shadow-md">
          <span class="text-white font-bold text-xl">G</span>
        </div>
        <span class="text-xl font-bold text-gray-900 tracking-tight">
          Gurkhas<span class="text-indigo-600">Fitness</span>
        </span>
      </div>

      <!-- Desktop Navigation + Right Section -->
      <div class="hidden md:flex items-center space-x-10">

        <!-- Desktop links -->
        <div class="flex space-x-8">
          <a href="{{route('home')}}" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition px-1 py-2 border-b-2 border-transparent hover:border-indigo-600">
            Home
          </a>
          <a href="{{route('membership')}}" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition px-1 py-2 border-b-2 border-transparent hover:border-indigo-600">
            Membership
          </a>
          <a href="{{route('plans')}}" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition px-1 py-2 border-b-2 border-transparent hover:border-indigo-600">
            Workout Plan
          </a>
          <a href="{{route('equipment')}}" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition px-1 py-2 border-b-2 border-transparent hover:border-indigo-600">
            Equipment
          </a>
          <a href="{{route('feedback')}}" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition px-1 py-2 border-b-2 border-transparent hover:border-indigo-600">
            Feedback
          </a>
        </div>

        <!-- Profile + Logout (desktop) -->
        <div class="flex items-center space-x-4">
          <div class="relative group">
            <button class="flex items-center focus:outline-none">
              <!-- ✅ Dynamic profile picture -->
              @if($client && $client->profile_picture)
                <img 
                  class="h-10 w-10 rounded-full object-cover border-2 border-indigo-100 shadow-sm"
                  src="{{ asset('storage/' . $client->profile_picture) }}"
                  alt="{{ $client->name }}"
                />
              @else
                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center border-2 border-indigo-100 shadow-sm">
                  <span class="text-white font-bold text-lg">
                    {{ $client ? strtoupper(substr($client->name, 0, 1)) : 'G' }}
                  </span>
                </div>
              @endif

              <!-- ✅ Dynamic name -->
              <span class="ml-2 text-sm font-medium text-gray-700 hidden lg:block group-hover:text-indigo-600 transition">
                {{ $client ? $client->name : 'Guest' }}
              </span>
            </button>

            <!-- Dropdown -->
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150">
              <div class="px-4 py-2 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-900">{{ $client ? $client->name : '' }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $client ? $client->email : '' }}</p>
              </div>
              <div class="border-t border-gray-100 my-1"></div>
              <!-- ✅ Logout form -->
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                  Logout
                </button>
              </form>
            </div>
          </div>

          <!-- ✅ Logout button -->
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
              Logout
            </button>
          </form>
        </div>
      </div>

      <!-- Mobile Hamburger Button -->
      <div class="md:hidden">
        <button id="mobile-menu-btn" class="text-gray-700 focus:outline-none">
          <svg id="menu-open" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg id="menu-close" class="h-8 w-8 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

    </div>
  </div>

  <!-- Mobile Menu Panel -->
  <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
    <div class="px-2 pt-2 pb-3 space-y-1">
      <a href="{{route('home')}}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">
        Home
      </a>
      <a href="{{route('membership')}}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">
        Membership
      </a>
      <a href="{{route('plans')}}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">
        Workout Plan
      </a>
      <a href="{{route('equipment')}}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">
        Equipment
      </a>
      <a href="{{route('feedback')}}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50">
        Feedback
      </a>
    </div>

    <!-- Mobile Profile Section -->
    <div class="pt-4 pb-3 border-t border-gray-200">
      <div class="flex items-center px-4">
        <div class="flex-shrink-0">
          <!-- ✅ Dynamic mobile profile picture -->
          @if($client && $client->profile_picture)
            <img 
              class="h-10 w-10 rounded-full object-cover"
              src="{{ asset('storage/' . $client->profile_picture) }}"
              alt="{{ $client->name }}"
            />
          @else
            <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
              <span class="text-white font-bold">
                {{ $client ? strtoupper(substr($client->name, 0, 1)) : 'G' }}
              </span>
            </div>
          @endif
        </div>
        <div class="ml-3">
          <!-- ✅ Dynamic mobile name and email -->
          <div class="text-base font-medium text-gray-800">{{ $client ? $client->name : 'Guest' }}</div>
          <div class="text-sm font-medium text-gray-500">{{ $client ? $client->email : '' }}</div>
        </div>
      </div>
      <div class="mt-3 space-y-1 px-2">
        <!-- ✅ Mobile logout -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-700 hover:bg-red-50">
            Logout
          </button>
        </form>
      </div>
    </div>
  </div>
</nav>

<script src="{{ asset('js/myscript.js') }}"></script>

</body>
</html>