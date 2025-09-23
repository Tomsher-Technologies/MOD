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
                    <div class="icon-field mb-4 relative">
                        <label for="password">{{ __db('username') }}</label>
                        <input id="username" type="text" 
                            class="mt-2 form-control h-[50px] ps-11 border-neutral-300 bg-neutral-50 rounded-xl" 
                            name="username" 
                            value="{{ old('username') }}" 
                            autofocus 
                            placeholder="{{ __db('username') }}">
                       
                    </div>

                    <div id="eventWrapper" class="mt-4 mb-4 hidden">
                        <label for="event_id">{{ __db('event') }}</label>
                        <select name="event_id" id="event_id"
                            class="mt-2 select2 form-control h-[50px] border-neutral-300 bg-neutral-50 rounded-xl">
                            <option value="">{{ __db('select_an_event') }}</option>
                        </select>
                        
                    </div>

                    <div id="passwordWrapper" class="relative mb-5 hidden">
                        <label for="password">{{ __db('password') }}</label>
                        <div class="relative">
                            <input id="password" type="password" class="mt-2  form-control h-[50px] ps-11 border-neutral-300 bg-neutral-50 rounded-xl" name="password" placeholder="{{ __db('password') }}">
                            
                            <button type="button" onclick="togglePassword('password')" class="absolute mt-1 left-3 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-primary-600">
                                <svg id="eye_open_password" xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 
                                        4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye_closed_password" xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 
                                        0-8.268-2.943-9.542-7a9.956 9.956 0 012.807-4.419M6.18 
                                        6.18A9.956 9.956 0 0112 5c4.477 0 8.268 
                                        2.943 9.542 7a9.956 9.956 0 01-4.038 5.223M15 
                                        12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                </svg>
                            </button>
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

        let step = 1; 

        loginBtn.addEventListener("click", function (e) {
            e.preventDefault();

            if (step === 1) {
                const username = usernameInput.value.trim();
                if (!username) {
                    usernameError.textContent = "{{ __db('username_required') }}";
                    usernameError.classList.remove("hidden");
                    return;
                }

                fetch("{{ route('check.username') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
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

                    usernameError.classList.add("hidden");

                    if (data.type === "admin") {
                        eventWrapper.classList.add("hidden");
                    } else {
                        eventSelect.innerHTML = `<option value="">{{ __db('select_an_event') }}</option>`;
                        data.events.forEach(ev => {
                            eventSelect.innerHTML += `<option value="${ev.id}">${ev.name}</option>`;
                        });
                        eventWrapper.classList.remove("hidden");
                    }

                    passwordWrapper.classList.remove("hidden");

                    step = 2;
                });
            } else {
                loginForm.submit();
            }
        });
    });

    function togglePassword(id) {
        const input = document.getElementById(id);
        const eyeOpen = document.getElementById("eye_open_" + id);
        const eyeClosed = document.getElementById("eye_closed_" + id);

        if (input.type === "password") {
            input.type = "text";
            eyeOpen.classList.add("hidden");
            eyeClosed.classList.remove("hidden");
        } else {
            input.type = "password";
            eyeOpen.classList.remove("hidden");
            eyeClosed.classList.add("hidden");
        }
    }
</script>

@endsection
