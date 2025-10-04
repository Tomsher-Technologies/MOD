@extends('layouts.admin_login',['title' => 'Login'])

@section('content')
   <section class="bg-white dark:bg-dark-2 flex flex-wrap min-h-[100vh]">
        <div class="lg:w-1/2 lg:block hidden px-2 py-2">
            <div class="flex items-center flex-col h-full justify-center">
                <img src="{{ getloginImage() }}" alt="">
            </div>
        </div>
        <div class="lg:w-1/2 py-8 px-6 flex flex-col justify-center">
            <div class="lg:max-w-[464px] mx-auto w-full">
                <div>
                    <a href="{{ route('home') }}" class="mb-2 max-w-[290px]">
                        <img src="{{ asset('assets/img/md-logo.svg') }}" alt="">
                    </a>
                    <a href="{{ route('home') }}" class="mb-2 max-w-[290px]">
                        <img src="{{ getModuleEventLogo() }}" alt="">
                    </a>
                    <h4 class="mb-3">{{ __db('sign_in_to_your_account') }}</h4>
                    <p class="mb-8 text-secondary-light text-sm">{{ __db('login_welcome_back') }}</p>
                </div>
                <form method="POST" action="{{ route('post.login') }}" autocomplete="off">
                    @csrf
                    <div class="icon-field mb-4 relative">
                        <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                    
                        <input id="email" type="text" class="form-control  ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autofocus placeholder="{{ __db('email') }}">
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
                                <input id="password-field" type="password" class="form-control ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 rounded-xl @error('password') is-invalid @enderror" name="password" value="" placeholder="{{ __db('password') }}">
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
                    <div class="mt-2">
                        <div class="flex justify-between gap-2">
                            <div class="flex items-center">
                              
                                <input type="checkbox" class="form-check-input border border-neutral-300" id="check-1"  name="remember">
                                <label class="ps-2" for="remeber">{{ __db('remember_me') }} </label>
                            </div>
                           
                        </div>
                    </div>

                    <button type="submit" class="btn bg-[#B68A35] text-white justify-center text-md btn-sm px-1 py-2 w-[50%] rounded-xl mt-4 signIn-createBtn ">{{ __db('sign_in') }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection
