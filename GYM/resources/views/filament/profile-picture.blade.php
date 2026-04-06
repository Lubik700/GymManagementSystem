@php
    $state = $getState();
@endphp

@if($state)
    <img src="{{ asset('storage/' . $state) }}" 
         style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #e5e7eb;">
@else
    <div style="width:120px;height:120px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;color:#9ca3af;">
        No Photo
    </div>
@endif