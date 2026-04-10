<x-layout>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">Equipment Details</h1>
      <p class="text-gray-600 mt-1">Browse available gym equipment and their specifications</p>
    </div>
    <div class="text-sm text-gray-500">
      Total Equipment: <span class="font-semibold text-gray-900">{{ $equipments->flatten()->count() }}</span>
    </div>
  </div>

  <!-- Search & Filter -->
  <div class="flex flex-col md:flex-row gap-4 mb-8">
    <div class="flex-1 relative">
      <input 
        type="text" 
        id="search-input"
        placeholder="Search equipment (e.g. treadmill, dumbbell...)" 
        onkeyup="filterEquipment()"
        class="w-full px-5 py-4 border border-gray-300 rounded-3xl focus:outline-none focus:border-indigo-500 pl-12 text-sm"
      >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 absolute left-5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 01-14 0 7 7 0 0114 0z" />
      </svg>
    </div>
    
    <select onchange="filterEquipment()" id="category-filter"
            class="px-5 py-4 border border-gray-300 rounded-3xl focus:outline-none focus:border-indigo-500">
      <option value="">All Categories</option>
      @foreach($equipments->keys() as $category)
        <option value="{{ $category }}">{{ $category }}</option>
      @endforeach
    </select>
  </div>

  <!-- Equipment Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="equipment-grid">

    @forelse($equipments->flatten() as $equipment)
    <div class="equipment-card bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition"
         data-name="{{ strtolower($equipment->name) }}"
         data-category="{{ $equipment->category }}">

      <!-- Image or Emoji -->
      <div class="h-48 overflow-hidden
        @if($equipment->category === 'Cardio') bg-gradient-to-br from-gray-100 to-gray-200
        @elseif($equipment->category === 'Free Weights') bg-gradient-to-br from-amber-100 to-orange-100
        @elseif($equipment->category === 'Machines') bg-gradient-to-br from-blue-100 to-cyan-100
        @elseif($equipment->category === 'Strength') bg-gradient-to-br from-red-100 to-pink-100
        @elseif($equipment->category === 'Flexibility') bg-gradient-to-br from-green-100 to-teal-100
        @else bg-gradient-to-br from-purple-100 to-indigo-100
        @endif
        flex items-center justify-center">

        @if($equipment->image)
          <img src="{{ asset('storage/' . $equipment->image) }}"
               alt="{{ $equipment->name }}"
               class="w-full h-full object-cover">
        @else
          <span class="text-7xl">
            @if($equipment->category === 'Cardio') 🏃‍♂️
            @elseif($equipment->category === 'Free Weights') 🏋️
            @elseif($equipment->category === 'Machines') 🏋️‍♀️
            @elseif($equipment->category === 'Strength') 💪
            @elseif($equipment->category === 'Flexibility') 🧘
            @else ⚙️
            @endif
          </span>
        @endif
      </div>

      <div class="p-6">
        <div class="flex justify-between items-start">
          <h3 class="font-semibold text-xl text-gray-900">{{ $equipment->name }}</h3>
          <span class="px-4 py-1 text-xs font-medium rounded-2xl
            @if($equipment->category === 'Cardio') bg-emerald-100 text-emerald-700
            @elseif($equipment->category === 'Free Weights') bg-amber-100 text-amber-700
            @elseif($equipment->category === 'Machines') bg-purple-100 text-purple-700
            @elseif($equipment->category === 'Strength') bg-red-100 text-red-700
            @elseif($equipment->category === 'Flexibility') bg-teal-100 text-teal-700
            @else bg-blue-100 text-blue-700
            @endif">
            {{ $equipment->category }}
          </span>
        </div>

        <div class="mt-6 space-y-4 text-sm">
          @if($equipment->brand)
          <div class="flex justify-between">
            <span class="text-gray-500">Brand</span>
            <span class="font-medium">{{ $equipment->brand }}</span>
          </div>
          @endif

          <div class="flex justify-between">
            <span class="text-gray-500">Quantity</span>
            <span class="font-medium">{{ $equipment->quantity }}</span>
          </div>

          @if($equipment->description)
          <div class="flex justify-between gap-4">
            <span class="text-gray-500 shrink-0">Specifications</span>
            <span class="font-medium text-right">{{ $equipment->description }}</span>
          </div>
          @endif

          <div class="flex justify-between">
            <span class="text-gray-500">Condition</span>
            <span class="inline-flex items-center gap-1
              @if($equipment->condition === 'excellent') text-emerald-600
              @elseif($equipment->condition === 'good') text-blue-600
              @elseif($equipment->condition === 'fair') text-amber-600
              @else text-red-600
              @endif">
              <div class="w-2 h-2 rounded-full
                @if($equipment->condition === 'excellent') bg-emerald-500
                @elseif($equipment->condition === 'good') bg-blue-500
                @elseif($equipment->condition === 'fair') bg-amber-500
                @else bg-red-500
                @endif">
              </div>
              {{ ucfirst($equipment->condition === 'maintenance' ? 'Under Maintenance' : $equipment->condition) }}
            </span>
          </div>
        </div>

        <div class="mt-8 pt-6 border-t text-xs text-gray-500 flex items-center gap-2">
          @if($equipment->is_available)
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
            Available
          @else
            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
            Not Available
          @endif
        </div>
      </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16">
      <p class="text-5xl mb-4">🔧</p>
      <h3 class="text-xl font-semibold text-gray-700">No equipment added yet</h3>
      <p class="text-gray-500 mt-2">Admin will add equipment soon.</p>
    </div>
    @endforelse

  </div>

  <!-- No Results Message -->
  <div id="no-results" class="hidden text-center py-16">
    <p class="text-5xl mb-4">🔍</p>
    <h3 class="text-xl font-semibold text-gray-700">No equipment found</h3>
    <p class="text-gray-500 mt-2">Try changing your search or filter</p>
  </div>

</section>

<script>
function filterEquipment() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const category = document.getElementById('category-filter').value.toLowerCase();
    const cards = document.querySelectorAll('.equipment-card');

    let visibleCount = 0;

    cards.forEach(card => {
        const name = card.dataset.name;
        const cardCategory = card.dataset.category.toLowerCase();

        const matchesSearch = name.includes(searchTerm);
        const matchesCategory = !category || cardCategory === category.toLowerCase();

        if (matchesSearch && matchesCategory) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('no-results').classList.toggle('hidden', visibleCount > 0);
}
</script>
</x-layout>