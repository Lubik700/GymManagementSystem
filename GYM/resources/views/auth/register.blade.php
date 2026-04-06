<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>GymFlow - Register New Member</title>
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Google Fonts - Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; }
    .preview-img { object-fit: cover; }
  </style>
</head>
<body class="h-full bg-gray-50">

<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
  <div class="max-w-2xl w-full space-y-10">

    <!-- Header -->
    <div class="text-center">
      <div class="mx-auto h-16 w-16 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center shadow-lg">
        <span class="text-white text-3xl font-bold">GF</span>
      </div>
      <h2 class="mt-6 text-3xl font-bold text-gray-900 tracking-tight">
        Join <span class="text-emerald-600">GymFlow</span>
      </h2>
      <p class="mt-2 text-sm text-gray-600">
        Create your member account and start your fitness journey
      </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white py-10 px-10 shadow-2xl rounded-2xl border border-gray-100/80">

      <form class="space-y-8" method="POST" action="{{ route('register.send-otp') }}" enctype="multipart/form-data">
       @csrf

       <!-- Add this right after your <form> opening tag -->
@if($errors->any())
    <div style="background:#fee2e2; border:1px solid #ef4444; padding:15px; margin-bottom:15px; border-radius:8px;">
        <strong>Please fix these errors:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li style="color:#dc2626;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
           <!-- Profile Photo Upload -->
<div class="flex flex-col items-center">
    <div class="relative group">
        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl bg-gray-100">
            <img 
                id="preview" 
                src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1480&q=80" 
                alt="Profile preview" 
                class="w-full h-full object-cover"
            >
        </div>
        <!-- ✅ Label points to the real input below -->
        <label 
            for="profile_picture" 
            class="absolute bottom-0 right-0 bg-emerald-600 text-white p-2 rounded-full cursor-pointer shadow-md hover:bg-emerald-700 transition"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </label>
    </div>
    <p class="mt-3 text-sm text-gray-500">Upload your profile photo (optional)</p>
    
    <!-- ✅ File input OUTSIDE the label, but linked via id -->
    <input 
        type="file" 
        id="profile_picture" 
        name="profile_picture" 
        accept="image/*"
        class="hidden"
        onchange="previewImage(event)"
    >
</div>

        <!-- Personal Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <!-- Full Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input 
              type="text" 
              id="name" 
              name="name" 
              value="{{ old('name') }}"
              required 
              class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 placeholder-gray-400 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm shadow-sm transition"
              placeholder="Ram Bahadur Thapa"
            >
          </div>

          <!-- Contact (Phone) -->
          <div>
            <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
            <input 
              type="tel" 
              id="contact" 
              name="contact" 
              required 
              pattern="[0-9]{10}" 
              title="Please enter a valid 10-digit phone number"
              class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 placeholder-gray-400 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm shadow-sm transition"
              placeholder="98XXXXXXXX"
            >
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input 
              type="email" 
              id="email" 
              name="email" 
              required 
              class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 placeholder-gray-400 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm shadow-sm transition"
              placeholder="you@example.com"
            >
          </div>

          <!-- Date of Birth -->
          <div>
            <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
            <input 
              type="date" 
              id="dob" 
              name="dob" 
              required 
              class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm shadow-sm transition"
            >
          </div>

        </div>

        <!-- Address -->
        <div>
          <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
          <textarea 
            id="address" 
            name="address" 
            rows="3"
            required
            class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 placeholder-gray-400 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm shadow-sm transition"
            placeholder="Ward no., Tole, City, Nepal"
          ></textarea>
        </div>

        <!-- Gender -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
          <div class="flex space-x-8">
            <label class="inline-flex items-center">
              <input type="radio" name="gender" value="male" class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500" required>
              <span class="ml-2 text-sm text-gray-700">Male</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="gender" value="female" class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
              <span class="ml-2 text-sm text-gray-700">Female</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="gender" value="other" class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
              <span class="ml-2 text-sm text-gray-700">Other</span>
            </label>
          </div>
        </div>

        <!-- Password (added for completeness - usually required for registration) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input 
              type="password" 
              id="password" 
              name="password" 
              required 
              minlength="8"
              class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 placeholder-gray-400 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm shadow-sm transition"
              placeholder="••••••••••"
            >
          </div>
          <div>
            <label for="password_confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input 
              type="password" 
              id="password_confirm" 
              name="password_confirmation" 
              required 
              class="mt-1 block w-full rounded-lg border border-gray-300 px-4 py-3 placeholder-gray-400 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm shadow-sm transition"
              placeholder="••••••••••"
            >
          </div>
        </div>

        <!-- Terms -->
        <div class="flex items-center">
          <input 
            id="terms" 
            name="terms" 
            type="checkbox" 
            required 
            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
          >
          <label for="terms" class="ml-2 block text-sm text-gray-700">
            I agree to the <a href="#" class="text-emerald-600 hover:underline">Terms of Service</a> and <a href="#" class="text-emerald-600 hover:underline">Privacy Policy</a>
          </label>
        </div>

        <!-- Submit -->
        <div>
          <button 
            type="submit"
            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition duration-200 font-medium text-base"
          >
            Create Account
          </button>
        </div>

      </form>

      <p class="mt-8 text-center text-sm text-gray-600">
        Already have an account? 
        <a href="{{route('login')}}" class="font-medium text-emerald-600 hover:text-emerald-500 transition">
          Sign in
        </a>
      </p>

    </div>

  </div>
</div>

<script>
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
      const output = document.getElementById('preview');
      output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>

</body>
</html>