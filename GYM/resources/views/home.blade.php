<x-layout>
   <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

<!-- Welcome Header -->
<div class="mb-10">
    @php
        $hour = now()->timezone('Asia/Kathmandu')->format('H');
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour >= 12 && $hour < 17) {
            $greeting = 'Good Afternoon';
        } elseif ($hour >= 17 && $hour < 21) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }
        $client = App\Models\Client::find(session('client_id'));
    @endphp

    <h1 class="text-3xl font-bold text-gray-900">
        {{ $greeting }}, {{ $client ? $client->name : 'Member' }}!
    </h1>

     <!-- Quotes -->
   @php
    $quotes = [
        "Push yourself, because no one else is going to do it for you.",
        "Success starts with self-discipline.",
        "Your body can stand almost anything. It's your mind you have to convince.",
        "The only bad workout is the one that didn't happen.",
        "Sweat is just fat crying.",
        "Train insane or remain the same.",
        "Wake up. Work out. Be happy.",
        "No pain, no gain. Shut up and train.",
        "Strive for progress, not perfection.",
        "You don't have to be great to start, but you have to start to be great.",
        "The clock is ticking. Are you becoming the person you want to be?",
        "Fitness is not about being better than someone else. It's about being better than you used to be.",
        "Take care of your body. It's the only place you have to live.",
        "The pain you feel today will be the strength you feel tomorrow.",
        "Don't stop when you're tired. Stop when you're done.",
        "Your health is an investment, not an expense.",
        "Believe in yourself and all that you are.",
        "Do something today that your future self will thank you for.",
        "It never gets easier, you just get stronger.",
        "Be stronger than your excuses.",
        "Results happen over time, not overnight. Work hard, stay consistent.",
        "You are one workout away from a good mood.",
        "Sore today, strong tomorrow.",
        "Champions aren't made in gyms. Champions are made from something deep inside.",
        "If it doesn't challenge you, it doesn't change you.",
        "Every workout is progress.",
        "Make yourself proud.",
        "Your only limit is you.",
        "Success is walking from failure to failure with no loss of enthusiasm.",
        "The secret of getting ahead is getting started.",
        "You didn't come this far to only come this far.",
        "Dream it. Wish it. Do it.",
        "Strength does not come from the body. It comes from the will of the soul.",
        "Energy and persistence conquer all things.",
        "Motivation is what gets you started. Habit is what keeps you going.",
        "Today I will do what others won't, so tomorrow I can do what others can't.",
        "The difference between try and triumph is a little umph.",
        "A one-hour workout is 4% of your day. No excuses.",
        "Don't wish for it. Work for it.",
        "Hard work beats talent when talent doesn't work hard.",
        "The gym is a mirror of life. What you put in is what you get out.",
        "The body achieves what the mind believes.",
        "You have to expect things of yourself before you can do them.",
        "Tough times never last, but tough people do.",
        "Fall in love with taking care of yourself.",
        "Your future is created by what you do today, not tomorrow.",
        "Be patient and tough; someday this pain will be useful to you.",
        "Life begins at the end of your comfort zone.",
        "The harder you work, the luckier you get.",
        "Don't limit your challenges. Challenge your limits.",
        "Great things never come from comfort zones.",
        "A little progress each day adds up to big results.",
        "What hurts today makes you stronger tomorrow.",
        "Stop doubting yourself. Work hard and make it happen.",
        "You are stronger than you think.",
        "Discipline is choosing between what you want now and what you want most.",
        "Success is the sum of small efforts repeated day in and day out.",
        "Don't count the days, make the days count.",
        "The harder the battle, the sweeter the victory.",
        "Rise up, start fresh, see the bright opportunity in each day.",
        "Every rep counts. Every set matters.",
        "Hustle for that muscle.",
        "Train like a beast, look like a beauty.",
        "Your body is your most priceless possession. Take care of it.",
        "Be the best version of yourself.",
        "Excuses don't burn calories.",
        "Earn it.",
        "Commit to be fit.",
        "Sweat, smile, repeat.",
        "One more rep. One more mile. One more step.",
        "Stronger every day.",
        "Work hard in silence, let success be your noise.",
        "The only way to finish is to start.",
        "Push harder than yesterday if you want a different tomorrow.",
        "You are what you repeatedly do.",
        "Progress is progress, no matter how small.",
        "Don't wait for the perfect moment. Take the moment and make it perfect.",
        "The successful warrior is the average man with laser-like focus.",
        "Your habits determine your future.",
        "Eat clean, train dirty.",
        "Nothing truly great ever came from a comfort zone.",
        "You've got what it takes, but it will take everything you've got.",
        "Fitness is a journey, not a destination.",
        "Dedication is the key to your dreams.",
        "Start where you are. Use what you have. Do what you can.",
        "Strength doesn't come from what you can do. It comes from overcoming what you thought you couldn't.",
        "You are capable of more than you know.",
        "Every day is another chance to get stronger.",
        "Turn your wounds into wisdom.",
        "Be fearless in the pursuit of what sets your soul on fire.",
        "You were given this life because you are strong enough to live it.",
        "The only person you should try to be better than is who you were yesterday.",
        "Success doesn't come from what you do occasionally, it comes from what you do consistently.",
        "Show up. Work hard. Be kind.",
        "It's going to be hard, but hard is not impossible.",
        "Make it happen. Shock everyone.",
        "If you're tired of starting over, stop giving up.",
        "You are braver than you believe, stronger than you seem.",
        "Today's pain is tomorrow's power.",
        "Keep going. You're closer than you think.",
    ];

    // Use day of year so quote changes daily but is same for all users
    $dayOfYear = now()->dayOfYear;
    $quote = $quotes[$dayOfYear % count($quotes)];
@endphp

<p class="text-gray-600 mt-2 italic">"{{ $quote }}"</p>
</div>

  <!-- Notice Board -->
<div class="bg-white rounded-3xl shadow-xl overflow-hidden">
    
    <!-- Notice Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center">
                <span class="text-white text-xl">📢</span>
            </div>
            <h2 class="text-xl font-semibold text-white">Notice Board</h2>
        </div>
        <span class="text-xs bg-white/20 text-white px-4 py-1.5 rounded-3xl">Latest Updates</span>
    </div>

    <!-- Notices List -->
    <div class="divide-y divide-gray-100">

        @forelse($notices as $notice)
            <div class="p-8 hover:bg-gray-50 transition">
                <div class="flex flex-col md:flex-row md:items-start gap-6">
                    <div class="flex-shrink-0">
                        @if($notice->type === 'important')
                            <span class="inline-block px-4 py-2 bg-amber-100 text-amber-700 text-xs font-semibold rounded-2xl">IMPORTANT</span>
                        @elseif($notice->type === 'event')
                            <span class="inline-block px-4 py-2 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-2xl">EVENT</span>
                        @else
                            <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 text-xs font-semibold rounded-2xl">GENERAL</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 text-lg">{{ $notice->title }}</h3>
                        <p class="text-gray-600 mt-2">{{ $notice->content }}</p>
                        <p class="text-xs text-gray-500 mt-4">
                            Posted on: {{ $notice->posted_at->format('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                <span class="text-4xl">📭</span>
                <p class="mt-2 font-medium">No notices at the moment.</p>
            </div>
        @endforelse

    </div>
</div>

    <!-- Footer Note -->
    <div class="bg-gray-50 px-8 py-5 text-center text-xs text-gray-500 border-t">
      Only important notices from the gym management will appear here.
    </div>
  </div>

</section>
</x-layout>