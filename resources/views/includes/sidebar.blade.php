<aside style="background: #1C1C1D;" class="w-64 shadow-lg h-[calc(100vh-65px)] overflow-y-auto" id="sidenav-main">
    <nav class="p-3">
        <ul class="space-y-1">

            @if(auth()->user()->canViews1('dashboard'))
                <li>
                    <a href="/dashboard"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-dashboard">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa fa-area-chart" aria-hidden="true"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Dashboard</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('students'))
                <li>
                    <a href="/student"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-product">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Students</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('parents'))
            <li>
                <a href="/parents" class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group" id="nav-membership">
                    <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <span class="text-white font-medium group-hover:text-[#1C1C1D]">Parents</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->canViews1('instructors'))
                <li>
                    <a href="/instructor"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Instructor</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('classes'))
                <li>
                    <a href="/classes"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa-solid fa-chalkboard"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Classes</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('branches'))
                <li>
                    <a href="/branch"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Branch</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('attendance'))
                <li>
                    <a href="/attendance"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Attendance</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('billing'))
                <li>
                    <a href="/billing"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Billing</span>
                    </a>
                </li>
            @endif
            {{-- @if(auth()->user()->canViews1('billing')) --}}
            <li>
                <a href="/plans"
                    class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                    id="nav-membership">
                    <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <span class="text-white font-medium group-hover:text-[#1C1C1D]">Plans</span>
                </a>
            </li>
            {{-- @endif --}}

            @if(auth()->user()->canViews1('certificates'))
                <li>
                    <a href="/certificates"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Certificate</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('competitions'))
                <li>
                    <a href="/competition"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa fa-trophy" aria-hidden="true"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Competitions</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('announcements'))
                <li>
                    <a href="/announcement"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa fa-bullhorn" aria-hidden="true"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Announcements</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('chat'))
                <li>
                    <a href="/chat"
                        class="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group"
                        id="nav-membership">
                        <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <span class="text-white font-medium group-hover:text-[#1C1C1D]">Chat</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->canViews1('reports'))
            <li x-data="{ open: false }" class="mb-2">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <span class="text-white font-medium group-hover:text-[#1C1C1D]">Reports</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#1C1C1D] transition-transform duration-200"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                <ul x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="ml-8 mt-1 space-y-1">

                    <li>
                        <a href="{{ route('reports.attendance') }}"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                            <div class="w-4 h-4 flex items-center justify-center">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Attendance</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('reports.revenue') }}"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                            <div class="w-4 h-4 flex items-center justify-center">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Revenue</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('reports.billing') }}"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                            <div class="w-4 h-4 flex items-center justify-center">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Billing</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('reports.instructor') }}"
                        class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                            <div class="w-4 h-4 flex items-center justify-center">
                                <i class="fa-solid fa-chalkboard-teacher"></i>
                            </div>
                            <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Instructor Load</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->canViews1('settings'))
                <li x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2 text-gray-700 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 flex items-center justify-center text-gray-500 group-hover:text-[#1C1C1D]">
                                <i class="fa-solid fa-gear"></i>
                            </div>
                            <span class="text-white font-medium group-hover:text-[#1C1C1D]">Settings</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#1C1C1D] transition-transform duration-200"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <ul x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95" class="ml-8 mt-1 space-y-1">

                        @if(auth()->user()->canViews1('users'))
                            <li>
                                <a href="/settings/user"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-solid fa-user-group"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">User Management</span>
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->canViews1('settings'))
                            <li>
                                <a href="/settings/club-profile"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-regular fa-building"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Club Profile</span>
                                </a>
                            </li>

                            <li>
                                <a href="/settings/branding"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Branding</span>
                                </a>
                            </li>

                            <li>
                                <a href="/settings/billing-rules"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Billing Rules</span>
                                </a>
                            </li>

                            <li>
                                <a href="/settings/discounts"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-solid fa-tag"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Discounts</span>
                                </a>
                            </li>

                            <li>
                                <a href="/settings/roles-and-permissions"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Roles &
                                        Permissions</span>
                                </a>
                            </li>

                            <li>
                                <a href="/settings/device"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-solid fa-tablet"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Devices</span>
                                </a>
                            </li>

                            <li>
                                <a href="/settings/integration"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-solid fa-cloud"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Integration</span>
                                </a>
                            </li>
                            <li>
                                <a href="/settings/skill-checklist"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-gray-400 rounded-lg hover:bg-white hover:text-[#1C1C1D] transition-colors group">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="fa-solid fa-list-check"></i>
                                    </div>
                                    <span class="text-white/80 font-medium group-hover:text-[#1C1C1D]">Skill Checklist</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</aside>
