@extends('layouts.frontend_login',['title' => 'change_password'])

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

                    <div class="mb-4">
                        <label for="password">{{ __db('new_password') }}</label>
                        <input type="password" name="password" id="password"
                            class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 rounded-xl"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation">{{ __db('confirm_password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control h-[56px] border-neutral-300 bg-neutral-50 rounded-xl"
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
    

</script>

@endsection
