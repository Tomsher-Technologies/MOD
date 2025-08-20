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
                <form method="POST" action="{{ route('web.login') }}" autocomplete="off">
                    @csrf

                    <div class="mt-4 mb-4">
                        {{-- <label class="block mb-1 text-sm text-gray-600 dark:text-gray-300">{{ __db('event') }}</label> --}}
                        <select name="event_id" data-live-search="true" class="select2 form-control h-[56px] border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl">
                            <option value="">{{ __db('select_an_event') }}</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name_en }}</option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <span class="text-red-500 text-xs" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            <br>
                        @enderror
                    </div>

                    <div class="icon-field mb-4 relative">
                        <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>

                        <input id="email" type="email" class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autofocus placeholder="{{ __db('email') }}">
                        @error('email')
                            <span class="text-red-500 text-xs" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            <br>
                        @enderror

                    </div>
                    <div class="relative mb-5">
                        <div class="icon-field">
                            <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>

                            <div class="position-relative">
                                <input id="password-field" type="password" class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl @error('password') is-invalid @enderror" name="password" value="" placeholder="{{ __db('password') }}">
                                <div class="fa fa-fw fa-eye-slash text-light fs-16 field-icon toggle-password2">
                                </div>
                            </div>

                            @error('password')
                                <span class="text-red-500 text-xs" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <span
                            class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light"
                            data-toggle="#your-password"></span>
                    </div>
                    <div class="mt-7">
                        <div class="flex justify-between gap-2">
                            <div class="flex items-center">

                                <input type="checkbox" class="form-check-input border border-neutral-300" id="check-1"  name="remember">
                                <label class="ps-2" for="remeber">{{ __db('remember_me') }} </label>
                            </div>
                            <a href="javascript:void(0)" class="text-primary-600 font-medium hover:underline">{{ __db('forgot_password') }}</a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary justify-center text-sm btn-sm px-3 py-4 w-full rounded-xl mt-8 signIn-createBtn ">{{ __db('sign_in') }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection


@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function () {

    });
</script>
@endsection
