    @php
        $currentEvent = \App\Models\Event::default()->first();
        if (!$currentEvent) {
            $currentEvent = \App\Models\Event::latest()->first();
        }
        //  $eventDate = $currentEvent ? $currentEvent->start_date : null;
        //  $hasEventStarted = $currentEvent ? now()->greaterThanOrEqualTo($currentEvent->start_date) : true;




        $startDate = $currentEvent ? $currentEvent->start_date : null;
        $endDate   = $currentEvent ? $currentEvent->end_date : null;
        $now       = now();

        $hasEventStarted = $startDate ? $now->greaterThanOrEqualTo($startDate) : false;
        $hasEventEnded   = $endDate ? $now->greaterThan($endDate) : false;

    @endphp

    @if(!$hasEventStarted)
        <div class="flex items-center">
            <div class="countdown-timer bg-gradient-to-r from-[#ffcc00] via-[#ff9900] to-[#ffcc00] text-white py-2 px-2 rounded-lg animate-gradient">
                <div id="event-countdown" class="flex items-center justify-center ">
                
                    <div class="countdown-item text-center flex flex-col items-center justify-center mx-2">
                        <div
                            class="countdown-value bg-[#333]/30 text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
                            <span id="countdown-minutes">00</span>
                        </div>
                        <div class="countdown-label text-[0.6rem] mt-1 text-white uppercase">{{ __db('minutes') }}</div>
                    </div>

                    <div class="countdown-separator text-lg font-bold text-white">:</div>

                    <div class="countdown-item text-center flex flex-col items-center  justify-center mx-2">
                        <div
                            class="countdown-value bg-[#333]/30 text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
                            <span id="countdown-hours">00</span>
                        </div>
                        <div class="countdown-label text-[0.6rem] mt-1 text-white uppercase">{{ __db('hours') }}</div>
                    </div>
                    
                    <div class="countdown-separator text-lg font-bold text-white">:</div>

                    <div class="countdown-item text-center flex flex-col items-center  justify-center mx-2">
                        <div
                            class="countdown-value bg-[#333]/30 text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
                            <span id="countdown-days">00</span>
                        </div>
                        <div class="countdown-label text-[0.6rem] mt-1 text-white uppercase">{{ __db('days') }}</div>
                    </div>

                    {{-- <div class="countdown-separator text-lg font-bold text-white">:</div> --}}

                    <div class="countdown-item text-center flex flex-col items-center justify-center mx-2 hidden">
                        <div
                            class="countdown-value bg-[#333]/30 text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
                            <span id="countdown-seconds">00</span>
                        </div>
                        <div class="countdown-label text-[0.6rem] mt-1 text-white uppercase">Seconds</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($hasEventStarted && !$hasEventEnded)
        @php
            $startDate = \Carbon\Carbon::parse($currentEvent->start_date);
            $endDate   = \Carbon\Carbon::parse($currentEvent->end_date);

            $dayNumber = $startDate->diffInDays(now()->startOfDay()) + 1;

            $totalDays = $startDate->diffInDays($endDate) + 1;
        @endphp
        <div class="flex items-center">
            <div class="countdown-timer bg-gradient-to-r from-[#ffcc00] via-[#ff9900] to-[#ffcc00] text-white py-2 px-4 rounded-lg animate-gradient">
                <span class="text-xl font-bold text-white">Day {{ $dayNumber }}</span>
            </div>
        </div>
    @endif

 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const eventDateString = @json($startDate ?? null);
         const eventDate = new Date(eventDateString).getTime();
         const countdownWrapper = document.getElementById('event-countdown');

        if (!countdownWrapper || !eventDateString) {
            return;
        }

         const countdownInterval = setInterval(function() {
             const now = new Date().getTime();
             const timeRemaining = eventDate - now;

             const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
             const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
             const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
             const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

             const formattedDays = days.toString().padStart(2, '0');
             const formattedHours = hours.toString().padStart(2, '0');
             const formattedMinutes = minutes.toString().padStart(2, '0');
             const formattedSeconds = seconds.toString().padStart(2, '0');

             const daysElement = document.getElementById('countdown-days');
             const hoursElement = document.getElementById('countdown-hours');
             const minutesElement = document.getElementById('countdown-minutes');
             const secondsElement = document.getElementById('countdown-seconds');

             daysElement.innerHTML = formattedDays;
             hoursElement.innerHTML = formattedHours;
             minutesElement.innerHTML = formattedMinutes;
             secondsElement.innerHTML = formattedSeconds;

             daysElement.classList.add('animate-change');
             hoursElement.classList.add('animate-change');
             minutesElement.classList.add('animate-change');
             secondsElement.classList.add('animate-change');

             setTimeout(() => {
                 daysElement.classList.remove('animate-change');
                 hoursElement.classList.remove('animate-change');
                 minutesElement.classList.remove('animate-change');
                 secondsElement.classList.remove('animate-change');
             }, 1000);

             if (timeRemaining < 0) {
                 clearInterval(countdownInterval);
                 document.getElementById('countdown-days').innerHTML = '00';
                 document.getElementById('countdown-hours').innerHTML = '00';
                 document.getElementById('countdown-minutes').innerHTML = '00';
                 document.getElementById('countdown-seconds').innerHTML = '00';
             }
         }, 1000);
     });
 </script>

 <style>
     @keyframes countdownChange {
         0% {
             transform: scale(1);
             opacity: 1;
         }

         50% {
             transform: scale(1.2);
             opacity: 0.5;
         }

         100% {
             transform: scale(1);
             opacity: 1;
         }
     }

     .animate-change {
         animation: countdownChange 1s ease-out;
     }

     .countdown-item {
         font-family: Arial, sans-serif;
     }

     .countdown-separator {
         font-size: 2rem;
         color: #4a4a4a;
     }

     .countdown-value {
         font-size: 1.25rem;
     }

     .countdown-label {
         font-size: 0.75rem;
     }

     @keyframes countdownGradient {
         0% {
             background: linear-gradient(90deg, #9e7526, #ffcc00);
         }

         50% {
             background: linear-gradient(90deg, #ffcc00, #9e7526);
         }

         100% {
             background: linear-gradient(90deg, #9e7526, #ffcc00);
         }
     }

     .animate-gradient {
         animation: countdownGradient 6s ease-in-out infinite;
     }
 </style>
