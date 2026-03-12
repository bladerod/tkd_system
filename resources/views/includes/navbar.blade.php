<nav style="background: #1C1C1D;" class="sticky top-0 z-10 shadow-sm " id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-3 px-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center flex-1 md:mr-4">
                <div class="relative w-full max-w-md">
                    <h1 style="color: white;">LOGO</h1>
                </div>
            </div>
            <!-- Left side - Search bar -->
            <div class="flex items-center flex-1 md:mr-4">
                <div class="relative w-full max-w-md">
                    <input type="text" class="w-full pl-4 pr-12 py-2 border bg-white border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-white-500 focus:border-transparent" placeholder="Search..." aria-label="Search">
                    <button class="absolute right-0 top-0 h-full px-3 hover:text-black-600 transition" type="button">
                        <i class="fa fa-search text-xl icon" aria-hidden="true"></i>
                    </button>
                    {{-- <wa-icon style="color: aliceblue;" name="bell"></wa-icon> --}}
                </div>
                    <button class="" type="button">
                        <i class="fa-solid fa-bell text-white ms-3 text-2xl icon"></i>
                    </button>
                
            </div>
            
            <!-- Right side - User Profile Dropdown -->
            <div class="flex items-center space-x-4">
                <!-- User Profile Dropdown -->
                <div class="relative" id="profileDropdown">
                    <button class="flex items-center space-x-2 focus:outline-none" id="profileDropdownButton" aria-expanded="false">
                        <div class="flex items-center space-x-2">
                            <div class="hidden md:block text-left">
                                <span class="font-semibold text-sm text-white">{{ auth()->user()->fname . ' ' . auth()->user()->lname }}</span>
                            </div>
                            @if (auth()->user()->photo_url)
                                <img src="{{ asset('storage/'.auth()->user()->photo_url) }}" class="rounded-full w-8 h-8 object-cover" alt="{{ auth()->user()->fname.' '. auth()->user()->lname }}" width="32" height="32">
                            @else
                                <img src="{{ Vite::asset('resources/assets/img/default.png') }}" class="rounded-full w-8 h-8 object-cover" width="32" height="32">
                            @endif
                        </div>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 hidden" id="profileDropdownMenu">
                        <!-- In your navbar, add a logout button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-[#1C1C1D] px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fa-solid fa-sign-out-alt mr-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>