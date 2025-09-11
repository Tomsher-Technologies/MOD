   <header class="@if (Route::currentRouteName() == 'home') top-0 w-full py-6 bg-transparent z-[999] @else
      w-full py-6 z-[999]  bg-[#ebebea]
   @endif ">
      <div class="container m-auto">
         <div class="grid grid-cols-12 items-center justify-center">
            <div class="col-span-3 flex items-center gap-6">
               <a href="{{ route('home') }}"><img src="{{ getModuleEventLogo() }}" class="h-[60px]" alt="Main Logo"></a>
               <a href="{{ route('home') }}"><img src="{{ asset('assets/img/md-logo.svg') }}" class="h-[70px]" alt="Main Logo"></a>
            </div>
            <div class="col-span-6">
               <ul class="flex items-center gap-6 text-md   justify-end">
                  <li>
                     <a href="{{ route('about-us') }}">{{ __db('about_us') }}</a>
                  </li>
                  <li>
                     <a href="{{ route('committees') }}">{{ __db('committees') }}</a>
                  </li>
                  <li>
                     <a href="{{ route('news') }}">{{ __db('news_events') }}</a>
                  </li>
               </ul>
            </div>
            <div class="col-span-3 text-end">
               <a href="{{ route('login') }}" class="inline-block bg-[#b68a35] text-white px-8 py-2 rounded-lg hover:bg-[#9e7526] text-end me-auto">
               {{ __db('login') }}
               </a>
            </div>
         </div>
      </div>
   </header>