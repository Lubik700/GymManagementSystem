<x-layout>
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

  <!-- Header -->
  <div class="text-center mb-10">
    <h1 class="text-3xl font-bold text-gray-900">Share Your Feedback</h1>
    <p class="text-gray-600 mt-3">Help us improve your gym experience</p>
  </div>

  <!-- Success Message -->
  @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-2xl text-green-700 font-medium text-center">
        {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl text-red-700">
        {{ $errors->first() }}
    </div>
  @endif

  <div class="bg-white rounded-3xl shadow-xl p-8 md:p-10">

    <form method="POST" action="{{ route('feedback.store') }}">
      @csrf

      <!-- Rating -->
      <div class="mb-8">
        <label class="block text-sm font-medium text-gray-700 mb-3">
          How would you rate your overall experience?
        </label>
        <div class="flex gap-2" id="rating-stars">
          <button type="button" onclick="setRating(1)" class="text-4xl hover:scale-110 transition">⭐</button>
          <button type="button" onclick="setRating(2)" class="text-4xl hover:scale-110 transition">⭐</button>
          <button type="button" onclick="setRating(3)" class="text-4xl hover:scale-110 transition">⭐</button>
          <button type="button" onclick="setRating(4)" class="text-4xl hover:scale-110 transition">⭐</button>
          <button type="button" onclick="setRating(5)" class="text-4xl hover:scale-110 transition">⭐</button>
        </div>
        <input type="hidden" id="rating" name="rating" value="{{ old('rating', 0) }}">
      </div>

      <!-- Feedback Category -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Feedback Category</label>
        <select name="category"
                class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500">
          <option value="">Select Category</option>
          <option value="gym_facility"  {{ old('category') == 'gym_facility'  ? 'selected' : '' }}>Gym Facility & Cleanliness</option>
          <option value="equipment"     {{ old('category') == 'equipment'     ? 'selected' : '' }}>Equipment Quality</option>
          <option value="staff"         {{ old('category') == 'staff'         ? 'selected' : '' }}>Staff Behavior</option>
          <option value="workout_area"  {{ old('category') == 'workout_area'  ? 'selected' : '' }}>Workout Area</option>
          <option value="classes"       {{ old('category') == 'classes'       ? 'selected' : '' }}>Group Classes</option>
          <option value="other"         {{ old('category') == 'other'         ? 'selected' : '' }}>Other</option>
        </select>
      </div>

      <!-- Title -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Title / Subject</label>
        <input 
          type="text" 
          name="title"
          value="{{ old('title') }}"
          placeholder="e.g. Excellent Treadmill Experience" 
          class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500"
          required
        >
      </div>

      <!-- Feedback Message -->
      <div class="mb-8">
        <label class="block text-sm font-medium text-gray-700 mb-2">Your Feedback</label>
        <textarea 
          name="message"
          rows="6"
          placeholder="Please share your honest feedback, suggestions, or complaints..."
          class="w-full px-5 py-4 border border-gray-300 rounded-3xl focus:outline-none focus:border-indigo-500 resize-y"
          required
        >{{ old('message') }}</textarea>
      </div>

      <!-- Suggestions -->
      <div class="mb-8">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Any suggestions for improvement? (Optional)
        </label>
        <textarea 
          name="suggestion"
          rows="3"
          placeholder="e.g. Add more yoga mats, extend gym hours..."
          class="w-full px-5 py-4 border border-gray-300 rounded-3xl focus:outline-none focus:border-indigo-500 resize-y"
        >{{ old('suggestion') }}</textarea>
      </div>

      <!-- Submit Button -->
      <button 
        type="submit"
        id="submit-btn"
        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl transition duration-200 flex items-center justify-center gap-2">
        <span>Submit Feedback</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7M5 12h14" />
        </svg>
      </button>

      <p class="text-center text-xs text-gray-500 mt-6">
        Your feedback helps us improve the gym for everyone.
      </p>
    </form>
  </div>

</section>

<script>
let currentRating = {{ old('rating', 0) }};

// Restore rating on page reload
if (currentRating > 0) setRating(currentRating);

function setRating(rating) {
    currentRating = rating;
    document.getElementById('rating').value = rating;

    const stars = document.querySelectorAll('#rating-stars button');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.style.filter = 'none';
            star.style.transform = 'scale(1.15)';
        } else {
            star.style.filter = 'grayscale(100%)';
            star.style.transform = 'scale(1)';
        }
    });
}

// Validate rating before submit
document.getElementById('submit-btn').closest('form').addEventListener('submit', function(e) {
    if (currentRating == 0) {
        e.preventDefault();
        alert('Please select a rating (1-5 stars)');
        return;
    }
    if (!document.querySelector('select[name="category"]').value) {
        e.preventDefault();
        alert('Please select a feedback category');
        return;
    }
});
</script>
</x-layout>