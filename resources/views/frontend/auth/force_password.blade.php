@extends('layouts.frontend_login',['title' => 'update_password'])

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
                    <h4 class="mb-3">{{ __db('change_your_password') }}</h4>
                    {{-- <p class="mb-8 text-secondary-light text-lg">{{ __db('login_welcome_back') }}</p> --}}
                </div>
                <form method="POST" action="{{ route('force.password.update') }}" autocomplete="off">
                    @csrf

                    <div class="mb-4 relative">
                        <label for="password">{{ __db('new_password') }}</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="mt-2 form-control h-[50px] ps-11 border-neutral-300 bg-neutral-50 rounded-xl"
                                required>
                            
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

                    <div class="mb-4">
                        <label for="password_confirmation">{{ __db('confirm_password') }}</label>
                        <input type="text" name="password_confirmation" id="password_confirmation"
                            class="mt-2 form-control h-[50px] border-neutral-300 bg-neutral-50 rounded-xl"
                            required>
                    </div>

                    @error('password')
                        <div class="text-red-600 text-sm mb-4">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn !bg-[#B68A35] text-white rounded-lg h-12 w-full">
                        {{ __db('update_password') }}
                    </button>

                </form>

            </div>
        </div>
    </section>
@endsection


@section('script')
<script>
    
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
