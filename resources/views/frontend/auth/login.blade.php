@extends('layouts.frontend_login',['title' => 'Login'])

@section('content')
    <section class="bg-white dark:bg-dark-2 flex flex-wrap min-h-[100vh]">
        <div class="lg:w-1/2 lg:block hidden">
            <div class="flex items-center flex-col h-full justify-center">
                <img src="{{ getloginImage() }}" alt="">
            </div>
        </div>
        <div class="lg:w-1/2 py-8 px-6 flex flex-col justify-center">
            <div class="lg:max-w-[464px] mx-auto w-full">
                <div>
                    <a href="index.html" class="mb-2.5 max-w-[290px]">
                        <img src="{{ getModuleEventLogo() }}" alt="">
                    </a>
                    <h4 class="mb-3">{{ __db('sign_in_to_your_account') }}</h4>
                    <p class="mb-8 text-secondary-light text-lg">{{ __db('login_welcome_back') }}</p>
                </div>
                <form method="POST" action="{{ route('web.login') }}" autocomplete="off" id="loginForm">
                    @csrf

                    {{-- Username Field --}}
                    <div class="icon-field mb-4 relative">
                        <label for="password">{{ __db('username') }}</label>
                        <input id="username" type="text" 
                            class="mt-2 form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 rounded-xl" 
                            name="username" 
                            value="{{ old('username') }}" 
                            autofocus 
                            placeholder="{{ __db('username') }}">
                       
                    </div>

                    {{-- Event Dropdown (hidden by default) --}}
                    <div id="eventWrapper" class="mt-4 mb-4 hidden">
                        <label for="event_id">{{ __db('event') }}</label>
                        <select name="event_id" id="event_id"
                            class="mt-2 select2 form-control h-[56px] border-neutral-300 bg-neutral-50 rounded-xl">
                            <option value="">{{ __db('select_an_event') }}</option>
                        </select>
                        
                    </div>

                    {{-- Password Field (hidden by default) --}}
                    <div id="passwordWrapper" class="relative mb-5 hidden">
                        <label for="password">{{ __db('password') }}</label>
                        <div class="icon-field">
                            <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input id="password" type="password" 
                                class="mt-2  form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 rounded-xl" 
                                name="password" 
                                placeholder="{{ __db('password') }}">
                        </div>
                        
                    </div>

                    <div  class="mt-4 mb-4">
                        @error('username')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror

                        @error('event_id')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror

                        @error('password')
                            <div class="text-red-600 text-sm mt-2">{{ $message }}</div>
                        @enderror
                     <span id="username-error" class="text-red-500 text-xs hidden"></span>
                        <span id="event-error" class="text-red-500 text-xs hidden"></span>
                        <span id="password-error" class="text-red-500 text-xs hidden"></span>
                    </div>
                    {{-- Final Sign In Button --}}
                    <button type="button" id="loginBtn" class="btn text-md  !bg-[#B68A35] text-white rounded-lg h-12 mr-4">
                        {{ __db('login') }}
                    </button>

                </form>

            </div>
        </div>
    </section>
@endsection


@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const usernameInput = document.getElementById("username");
        const eventWrapper = document.getElementById("eventWrapper");
        const passwordWrapper = document.getElementById("passwordWrapper");
        const loginBtn = document.getElementById("loginBtn");
        const eventSelect = document.getElementById("event_id");
        const loginForm = document.getElementById("loginForm");
        const usernameError = document.getElementById("username-error");

        let step = 1; // step 1 = username check, step 2 = real login

        loginBtn.addEventListener("click", function (e) {
            e.preventDefault();

            if (step === 1) {
                // First click → check username
                const username = usernameInput.value.trim();
                if (!username) {
                    usernameError.textContent = "⚠️ {{ __db('username_required') }}";
                    usernameError.classList.remove("hidden");
                    return;
                }

                fetch("{{ route('check.username') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ username })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.status) {
                        usernameError.textContent = data.message;
                        usernameError.classList.remove("hidden");
                        return;
                    }

                    // ✅ Clear error
                    usernameError.classList.add("hidden");

                    if (data.type === "admin") {
                        eventWrapper.classList.add("hidden");
                    } else {
                        // fill events
                        eventSelect.innerHTML = `<option value="">{{ __db('select_an_event') }}</option>`;
                        data.events.forEach(ev => {
                            eventSelect.innerHTML += `<option value="${ev.id}">${ev.name}</option>`;
                        });
                        eventWrapper.classList.remove("hidden");
                    }

                    passwordWrapper.classList.remove("hidden");

                    // Switch to Step 2
                    step = 2;
                });
            } else {
                // Step 2 → real login
                loginForm.submit();
            }
        });
    });

</script>

@endsection
