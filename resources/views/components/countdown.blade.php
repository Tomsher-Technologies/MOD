<div id="event-countdown" class="flex items-center gap-2 justify-center mx-4">
    <div class="countdown-item text-center">
        <div
            class="countdown-value bg-[#b68a35] text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
            <span id="countdown-days">00</span>
        </div>
        <div class="countdown-label text-[0.6rem] mt-1 text-gray-600 uppercase">Days</div>
    </div>
    <div class="countdown-separator text-lg font-bold text-gray-600">:</div>
    <div class="countdown-item text-center">
        <div
            class="countdown-value bg-[#b68a35] text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
            <span id="countdown-hours">00</span>
        </div>
        <div class="countdown-label text-[0.6rem] mt-1 text-gray-600 uppercase">Hours</div>
    </div>
    <div class="countdown-separator text-lg font-bold text-gray-600">:</div>
    <div class="countdown-item text-center">
        <div
            class="countdown-value bg-[#b68a35] text-white w-10 h-10 flex items-center justify-center rounded font-bold text-sm">
            <span id="countdown-minutes">00</span>
        </div>
        <div class="countdown-label text-[0.6rem] mt-1 text-gray-600 uppercase">Minutes</div>
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

            const formattedDays = days.toString().padStart(2, '0');
            const formattedHours = hours.toString().padStart(2, '0');
            const formattedMinutes = minutes.toString().padStart(2, '0');

            document.getElementById('countdown-days').innerHTML = formattedDays;
            document.getElementById('countdown-hours').innerHTML = formattedHours;
            document.getElementById('countdown-minutes').innerHTML = formattedMinutes;

            if (timeRemaining < 0) {
                clearInterval(countdownInterval);
                document.getElementById('countdown-days').innerHTML = '00';
                document.getElementById('countdown-hours').innerHTML = '00';
                document.getElementById('countdown-minutes').innerHTML = '00';
            }
        }, 1000);
    });
</script>
