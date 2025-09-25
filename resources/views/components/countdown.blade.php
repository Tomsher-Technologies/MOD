<div id="event-countdown" class="flex items-center justify-center ">
    <div class="countdown-item text-center flex flex-col items-center  justify-center mx-2">
        <div
            class="countdown-value bg-[#333]/30 text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
            <span id="countdown-days">00</span>
        </div>
        <div class="countdown-label text-[0.6rem] mt-1 text-white uppercase">Days</div>
    </div>
    <div class="countdown-separator text-lg font-bold text-white">:</div>
    
    <div class="countdown-item text-center flex flex-col items-center  justify-center mx-2">
        <div
            class="countdown-value bg-[#333]/30 text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
            <span id="countdown-hours">00</span>
        </div>
        <div class="countdown-label text-[0.6rem] mt-1 text-white uppercase">Hours</div>
    </div>
    <div class="countdown-separator text-lg font-bold text-white">:</div>
    
    <div class="countdown-item text-center flex flex-col items-center justify-center mx-2">
        <div
            class="countdown-value bg-[#333]/30 text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
            <span id="countdown-minutes">00</span>
        </div>
        <div class="countdown-label text-[0.6rem] mt-1 text-white uppercase">Minutes</div>
    </div>
    <div class="countdown-separator text-lg font-bold text-white">:</div>
    
    <div class="countdown-item text-center flex flex-col items-center justify-center mx-2">
        <div
            class="countdown-value bg-[#333]/30 text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
            <span id="countdown-seconds">00</span>
        </div>
        <div class="countdown-label text-[0.6rem] mt-1 text-white uppercase">Seconds</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const eventDateString = @json(config('app.event_start_date', '2025-12-31 00:00:00'));
        const eventDate = new Date(eventDateString).getTime();

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

            // Update countdown values with smooth animation
            const daysElement = document.getElementById('countdown-days');
            const hoursElement = document.getElementById('countdown-hours');
            const minutesElement = document.getElementById('countdown-minutes');
            const secondsElement = document.getElementById('countdown-seconds');

            // Trigger animation by updating the text content
            daysElement.innerHTML = formattedDays;
            hoursElement.innerHTML = formattedHours;
            minutesElement.innerHTML = formattedMinutes;
            secondsElement.innerHTML = formattedSeconds;

            // Apply smooth animation on update
            daysElement.classList.add('animate-change');
            hoursElement.classList.add('animate-change');
            minutesElement.classList.add('animate-change');
            secondsElement.classList.add('animate-change');

            // Remove the animation class after animation is done (1s duration)
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
                // Optionally, add a message when the countdown finishes
                alert("The event has started!");
            }
        }, 1000);
    });
</script>

<style>
    /* Smooth animation for countdown value changes */
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

    /* Apply the countdownChange animation when the value changes */
    .animate-change {
        animation: countdownChange 1s ease-out;
    }

    /* Countdown item styling */
    .countdown-item {
        font-family: Arial, sans-serif;
    }

    /* Countdown separator styling */
    .countdown-separator {
        font-size: 2rem;
        color: #4a4a4a;
    }

    /* Countdown value styling */
    .countdown-value {
        font-size: 1.25rem;
    }

    .countdown-label {
        font-size: 0.75rem;
    }

    /* Gradient animation with #9e7526 */
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

    /* Apply gradient animation for countdown value */
    .animate-gradient {
        animation: countdownGradient 6s ease-in-out infinite;
    }
</style>
