@extends('layouts.admin_account', ['title' => __db('profile')])

@section('content')
    <div class="">
        <!-- Overview boxes -->
        <div class="flex flex-wrap items-center justify-between gap-2 mb-6">
            <h2 class="font-semibold mb-0 text-xl">{{ __db('profile') }}</h2>
        </div>
        <div class="bg-white h-full vh-100 max-h-full min-h-full rounded-lg border-0 p-6">
            <div class="container mx-auto py-6">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">{{ __db('profile_details') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-md">
                        <div><strong>{{ __db('username') }}:</strong> {{ $user->username }}</div>
                        <div><strong>{{ __db('name') }}:</strong> {{ $user->name ?? '-' }}</div>
                        <div><strong>{{ __db('email') }}:</strong> {{ $user->email ?? '-' }}</div>
                        <div><strong>{{ __db('phone') }}:</strong> {{ $user->phone ?? '-' }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">{{ __db('assigned') }} {{ __db('roles') }}</h2>
                    @forelse($user->eventUserRoles as $eur)
                        <div class="mb-2">
                            <span class="font-semibold">{{ $eur->event->getTranslation('name') ?? 'N/A' }}</span>
                            (<span class="text-blue-600">{{ ucfirst($eur->module) }}</span> –
                            <span class="text-green-600">{{ $eur->role->name ?? 'N/A' }}</span>)
                        </div>
                    @empty
                        @if ($user->user_type === 'admin')
                            {{ __db('admin') }}
                        @else
                            {{ __db($user->user_type) }} ({{ $user->roles->pluck('name')->join(', ') }})
                        @endif
                    @endforelse
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">{{ __db('change_password') }}</h2>

                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('staffs.change-password') }}">
                        @csrf

                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-3 relative">
                                <label class="form-label">{{ __db('current_password') }}</label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password"
                                        autocomplete="new-password"
                                        class="p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0"
                                        value="{{ old('current_password') }}">

                                    <!-- Eye Toggle -->
                                    <button type="button" onclick="togglePassword('current_password')" 
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-primary-600">
                                        <!-- Default (Eye) -->
                                        <svg id="eye_open_current_password" xmlns="http://www.w3.org/2000/svg" 
                                            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 
                                                4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>

                                        <!-- Hidden (Eye Slash) -->
                                        <svg id="eye_closed_current_password" xmlns="http://www.w3.org/2000/svg" 
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

                                @error('current_password')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-span-3 relative">
                                <label class="form-label">{{ __db('password') }} </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" autocomplete="new-password" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('password') }}">

                                    <button type="button" onclick="togglePassword('password')" 
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500 hover:text-primary-600">
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

                                    @error('password')
                                        <div class="text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-3">
                                <label class="form-label">{{ __db('confirm_password') }} </label>
                                <input type="text" id="password_confirmation" name="password_confirmation" class=" p-3 rounded-lg w-full border text-sm border-neutral-300 text-neutral-600 focus:border-primary-600 focus:ring-0" value="{{ old('password_confirmation') }}">
                                @error('password_confirmation')
                                    <div class="text-red-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="flex  gap-5 mt-6">
                            <button type="submit" class="btn text-md  !bg-[#B68A35] text-white rounded-lg h-12 mr-4">
                                {{ __db('update_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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