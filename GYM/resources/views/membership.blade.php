<x-layout>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<!-- Membership Details -->
<h1 class="text-3xl font-bold text-gray-900">Membership Details</h1>
<p class="text-gray-500 mt-1">Your active plan and complete membership history</p>

<!-- Active Plan Card -->
@if($activeSubscription)
@php
    $start = \Carbon\Carbon::parse($activeSubscription->start_date);
    $end = \Carbon\Carbon::parse($activeSubscription->end_date);
    $today = \Carbon\Carbon::today();
    $totalDays = $start->diffInDays($end);
    $remainingDays = max(0, $today->diffInDays($end, false));
    $progressPercent = $totalDays > 0 ? min(100, round((($totalDays - $remainingDays) / $totalDays) * 100)) : 0;
@endphp

<div class="mt-8 bg-white rounded-3xl shadow-lg p-8">
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
        <div class="flex-1">
            <!-- Status Badge -->
            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                CURRENTLY ACTIVE
            </span>

            <!-- Plan Name -->
            <h2 class="text-3xl font-bold text-gray-900 mt-4">
                {{ $activeSubscription->plan_name }}
            </h2>
            <p class="text-gray-500 mt-1">Duration: {{ $activeSubscription->duration }}</p>

            <!-- Dates + Remaining -->
            <div class="grid grid-cols-3 gap-6 mt-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Purchased On</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">
                        {{ $start->format('d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Due Date</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">
                        {{ $end->format('d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Remaining Days</p>
                    <p class="text-4xl font-bold text-emerald-500 mt-1">
                        {{ $remainingDays }}
                        <span class="text-base font-normal text-gray-500">days left</span>
                    </p>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ $progressPercent }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $progressPercent }}% of plan used</p>
            </div>
        </div>

        <!-- Right side illustration -->
        <div class="flex-shrink-0 bg-emerald-50 rounded-2xl p-6 text-center w-40">
            <span class="text-6xl">🏋️</span>
            <p class="text-emerald-600 font-semibold mt-2 text-sm">Keep Going!</p>
        </div>
    </div>
</div>

@else
<div class="mt-8 bg-white rounded-3xl shadow-lg p-8 text-center">
    <span class="text-5xl">😔</span>
    <h2 class="text-xl font-bold text-gray-900 mt-4">No Active Subscription</h2>
    <p class="text-gray-500 mt-2">Please visit the gym to renew your membership.</p>
</div>
@endif

<!-- Membership History -->
<div class="mt-10">
    <h2 class="text-2xl font-bold text-gray-900">Membership History</h2>

    <div class="mt-4 bg-white rounded-3xl shadow-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">SN</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Package Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Duration</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Purchased Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Finished Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Amount</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subscriptionHistory as $index => $sub)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-gray-500">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $sub->plan_name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $sub->duration }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($sub->start_date)->format('d F Y') }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($sub->end_date)->format('d F Y') }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">NPR {{ number_format($sub->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($sub->status === 'active')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                Active
                            </span>
                        @elseif($sub->status === 'expired')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                Completed
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                Cancelled
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No membership history found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</section>
</x-layout>