@php
    $client = App\Models\Client::with('subscriptions')->find($getRecord()->id);
    $subscriptions = $client->subscriptions()->orderBy('created_at', 'desc')->get();
@endphp

@if($subscriptions->count() > 0)
<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">SN</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Package Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Duration</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Amount (NPR)</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Start Date</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">End Date</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($subscriptions as $index => $sub)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-gray-500">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $sub->plan_name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $sub->duration ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-600">NPR {{ number_format($sub->amount, 2) }}</td>
                <td class="px-4 py-3 text-gray-600">
                    {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}
                </td>
                <td class="px-4 py-3 text-gray-600">
                    {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}
                </td>
                <td class="px-4 py-3">
                    @if($sub->status === 'active')
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                            Active
                        </span>
                    @elseif($sub->status === 'expired')
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                            Expired
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                            Cancelled
                        </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-8 text-gray-500">
    <span class="text-4xl">📋</span>
    <p class="mt-2 font-medium">No membership history found.</p>
</div>
@endif