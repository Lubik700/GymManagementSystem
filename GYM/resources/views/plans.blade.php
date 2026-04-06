<x-layout>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

  @if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl text-green-700 font-medium">
        {{ session('success') }}
    </div>
  @endif

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">My Workout Plans</h1>
      <p class="text-gray-600 mt-1">Manage your routines with sets and reps</p>
    </div>
    <button onclick="document.getElementById('newPlanModal').classList.remove('hidden')"
            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-2xl flex items-center gap-2 transition">
      <span>+ New Workout Plan</span>
    </button>
  </div>

  <!-- Workout Plans -->
  @if($plans->count() > 0)
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    @foreach($plans as $plan)
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

      <!-- Plan Header -->
      <div class="px-8 py-6 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-2xl font-bold">{{ $plan->name }}</h2>
            <p class="text-indigo-100 mt-1">
              {{ $plan->frequency }}
              @if($plan->frequency && $plan->duration) • @endif
              {{ $plan->duration }}
            </p>
          </div>
          <div class="text-right">
            <span class="text-xs bg-white/20 px-4 py-2 rounded-3xl">
              Last updated: {{ $plan->updated_at->format('d M Y') }}
            </span>
          </div>
        </div>
      </div>

      <!-- Exercises List -->
      <div class="p-8">
        <h3 class="font-semibold text-gray-700 mb-4">Exercises</h3>

        <div class="space-y-6">
          @forelse($plan->exercises as $exercise)
          <div class="border border-gray-200 rounded-2xl p-5">
            <div class="flex justify-between items-center">
              <h4 class="font-semibold text-lg">{{ $exercise->name }}</h4>
              <div class="flex gap-3">
                <button onclick="openEditExercise(
                    {{ $exercise->id }},
                    '{{ addslashes($exercise->name) }}',
                    '{{ addslashes($exercise->equipment) }}',
                    '{{ addslashes($exercise->position) }}',
                    '{{ $exercise->sets }}',
                    '{{ addslashes($exercise->reps) }}',
                    '{{ addslashes($exercise->rest) }}'
                  )" class="text-indigo-600 text-sm hover:underline">Edit</button>
                <form method="POST" action="{{ route('exercises.destroy', $exercise->id) }}"
                  onsubmit="return confirm('Delete this exercise?')" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-500 text-sm hover:underline">Delete</button>
                </form>
              </div>
            </div>
            <p class="text-sm text-gray-500">
              {{ $exercise->equipment }}
              @if($exercise->equipment && $exercise->position) • @endif
              {{ $exercise->position }}
            </p>

            <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
              <div>
                <p class="text-gray-500">Sets</p>
                <p class="text-2xl font-bold text-gray-900">{{ $exercise->sets ?? '-' }}</p>
              </div>
              <div>
                <p class="text-gray-500">Reps per set</p>
                <p class="text-2xl font-bold text-gray-900">{{ $exercise->reps ?? '-' }}</p>
              </div>
              <div>
                <p class="text-gray-500">Rest</p>
                <p class="text-2xl font-bold text-gray-900">{{ $exercise->rest ?? '-' }}</p>
              </div>
            </div>
          </div>
          @empty
          <div class="text-center py-6 text-gray-400">
            <p>No exercises yet. Add one below!</p>
          </div>
          @endforelse
        </div>

        <!-- Add Exercise Button -->
        <button onclick="openAddExercise({{ $plan->id }})"
          class="mt-6 w-full py-3 border-2 border-dashed border-indigo-300 text-indigo-600 rounded-2xl hover:bg-indigo-50 transition font-medium">
          + Add Exercise
        </button>
      </div>

      <!-- Plan Footer -->
      <div class="border-t px-8 py-5 flex gap-3 bg-gray-50">
        <button onclick="openEditPlan(
            {{ $plan->id }},
            '{{ addslashes($plan->name) }}',
            '{{ addslashes($plan->frequency) }}',
            '{{ addslashes($plan->duration) }}'
          )"
          class="flex-1 py-4 text-sm font-medium border border-indigo-200 text-indigo-600 rounded-2xl hover:bg-indigo-50 transition">
          Edit Plan
        </button>
        <form method="POST" action="{{ route('plans.destroy', $plan->id) }}"
          onsubmit="return confirm('Delete this entire plan and all exercises?')" class="flex-1">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="w-full py-4 text-sm font-medium border border-red-200 text-red-600 rounded-2xl hover:bg-red-50 transition">
            Delete Plan
          </button>
        </form>
      </div>
    </div>
    @endforeach

  </div>
  @else

  <!-- Empty State -->
  <div class="text-center py-20">
    <p class="text-6xl mb-6">🏋️</p>
    <h3 class="text-2xl font-semibold text-gray-700">No Workout Plans Created</h3>
    <p class="text-gray-500 mt-3">Start building your personalized workout routine</p>
    <button onclick="document.getElementById('newPlanModal').classList.remove('hidden')"
            class="mt-8 px-8 py-4 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700">
      Create First Workout Plan
    </button>
  </div>
  @endif

</section>


<!-- ==================== NEW PLAN MODAL ==================== -->
<div id="newPlanModal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center">
  <div class="bg-white rounded-3xl w-full max-w-md mx-4 shadow-2xl">
    <div class="px-8 py-6 border-b">
      <h2 class="text-2xl font-bold">Create New Workout Plan</h2>
    </div>
    <form method="POST" action="{{ route('plans.store') }}">
      @csrf
      <div class="p-8 space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Plan Name *</label>
          <input type="text" name="name" required placeholder="e.g. Upper Body Push Day"
            class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Frequency</label>
          <input type="text" name="frequency" placeholder="e.g. 4 Days / Week"
            class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
          <input type="text" name="duration" placeholder="e.g. 50 minutes"
            class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
        </div>
      </div>
      <div class="flex border-t">
        <button type="button" onclick="document.getElementById('newPlanModal').classList.add('hidden')"
          class="flex-1 py-5 text-gray-600 font-medium border-r">Cancel</button>
        <button type="submit"
          class="flex-1 py-5 bg-indigo-600 text-white font-medium rounded-br-3xl">Create Plan</button>
      </div>
    </form>
  </div>
</div>


<!-- ==================== EDIT PLAN MODAL ==================== -->
<div id="editPlanModal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center">
  <div class="bg-white rounded-3xl w-full max-w-md mx-4 shadow-2xl">
    <div class="px-8 py-6 border-b">
      <h2 class="text-2xl font-bold">Edit Workout Plan</h2>
    </div>
    <form id="editPlanForm" method="POST">
      @csrf
      @method('PUT')
      <div class="p-8 space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Plan Name *</label>
          <input type="text" name="name" id="editPlanName" required
            class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Frequency</label>
          <input type="text" name="frequency" id="editPlanFrequency"
            class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
          <input type="text" name="duration" id="editPlanDuration"
            class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
        </div>
      </div>
      <div class="flex border-t">
        <button type="button" onclick="document.getElementById('editPlanModal').classList.add('hidden')"
          class="flex-1 py-5 text-gray-600 font-medium border-r">Cancel</button>
        <button type="submit"
          class="flex-1 py-5 bg-indigo-600 text-white font-medium rounded-br-3xl">Save Changes</button>
      </div>
    </form>
  </div>
</div>


<!-- ==================== ADD EXERCISE MODAL ==================== -->
<div id="addExerciseModal" class="hidden fixed inset-0 bg-black/70 z-[60] flex items-center justify-center">
  <div class="bg-white rounded-3xl w-full max-w-md mx-4 shadow-2xl">
    <div class="px-8 py-6 border-b">
      <h2 class="text-2xl font-bold">Add Exercise</h2>
    </div>
    <form id="addExerciseForm" method="POST">
      @csrf
      <div class="p-8 space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Exercise Name *</label>
          <input type="text" name="name" required placeholder="e.g. Bench Press"
            class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Equipment</label>
            <input type="text" name="equipment" placeholder="e.g. Barbell"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
            <input type="text" name="position" placeholder="e.g. Flat Bench"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sets</label>
            <input type="number" name="sets" placeholder="4"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Reps</label>
            <input type="text" name="reps" placeholder="8-12"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rest</label>
            <input type="text" name="rest" placeholder="90 sec"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
        </div>
      </div>
      <div class="flex border-t">
        <button type="button" onclick="document.getElementById('addExerciseModal').classList.add('hidden')"
          class="flex-1 py-5 text-gray-600 font-medium border-r">Cancel</button>
        <button type="submit"
          class="flex-1 py-5 bg-indigo-600 text-white font-medium rounded-br-3xl">Add Exercise</button>
      </div>
    </form>
  </div>
</div>


<!-- ==================== EDIT EXERCISE MODAL ==================== -->
<div id="editExerciseModal" class="hidden fixed inset-0 bg-black/70 z-[60] flex items-center justify-center">
  <div class="bg-white rounded-3xl w-full max-w-md mx-4 shadow-2xl">
    <div class="px-8 py-6 border-b">
      <h2 class="text-2xl font-bold">Edit Exercise</h2>
    </div>
    <form id="editExerciseForm" method="POST">
      @csrf
      @method('PUT')
      <div class="p-8 space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Exercise Name *</label>
          <input type="text" name="name" id="editExName" required
            class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Equipment</label>
            <input type="text" name="equipment" id="editExEquipment"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
            <input type="text" name="position" id="editExPosition"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sets</label>
            <input type="number" name="sets" id="editExSets"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Reps</label>
            <input type="text" name="reps" id="editExReps"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rest</label>
            <input type="text" name="rest" id="editExRest"
              class="w-full px-5 py-3 border border-gray-300 rounded-2xl focus:border-indigo-500 outline-none">
          </div>
        </div>
      </div>
      <div class="flex border-t">
        <button type="button" onclick="document.getElementById('editExerciseModal').classList.add('hidden')"
          class="flex-1 py-5 text-gray-600 font-medium border-r">Cancel</button>
        <button type="submit"
          class="flex-1 py-5 bg-indigo-600 text-white font-medium rounded-br-3xl">Save Changes</button>
      </div>
    </form>
  </div>
</div>


<script>
function openEditPlan(id, name, frequency, duration) {
    document.getElementById('editPlanForm').action = `/plans/${id}`;
    document.getElementById('editPlanName').value = name;
    document.getElementById('editPlanFrequency').value = frequency;
    document.getElementById('editPlanDuration').value = duration;
    document.getElementById('editPlanModal').classList.remove('hidden');
}

function openAddExercise(planId) {
    document.getElementById('addExerciseForm').action = `/plans/${planId}/exercises`;
    document.getElementById('addExerciseModal').classList.remove('hidden');
}

function openEditExercise(id, name, equipment, position, sets, reps, rest) {
    document.getElementById('editExerciseForm').action = `/exercises/${id}`;
    document.getElementById('editExName').value = name;
    document.getElementById('editExEquipment').value = equipment;
    document.getElementById('editExPosition').value = position;
    document.getElementById('editExSets').value = sets;
    document.getElementById('editExReps').value = reps;
    document.getElementById('editExRest').value = rest;
    document.getElementById('editExerciseModal').classList.remove('hidden');
}

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    ['newPlanModal', 'editPlanModal', 'addExerciseModal', 'editExerciseModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (e.target === modal) modal.classList.add('hidden');
    });
});
</script>

</x-layout>