<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD - Announcements</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/announcement.css'])
    <!-- Add SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add Simple-Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
</head>
<body class="bg-gray-50">
    <!-- navbar -->
    @include("includes.navbar")
    <!-- Sidebar -->
    @include('includes.sidebar')
    <!-- Main Content -->
    <div class="container-fluid m-0">
        <div class="row">
            <main class="ml-64 p-6"> 
                <div class="container-fluid">
                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                        <span>/</span>
                        <span class="text-[#1C1C1D] font-medium">Announcement Management</span>
                    </div>
                    
                    <!-- Header with Title and Add Button -->
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-4xl font-bold text-[#1C1C1D]">Announcement Management</h1>
                    </div>
                    
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Announcement List</h6>
                                    </div>
                                </div>
                                
                                <div class="p-6" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <!-- Create Button -->
                                    <div class="flex justify-end mb-6">
                                        <button onclick="openAddAnnouncementModal()" class="bg-[#1c1c1d] text-white px-4 py-2 rounded-lg hover:bg-[#2f2f2f] transition-colors flex items-center gap-2">
                                            <i class="fa-solid fa-plus"></i>
                                            Create Announcement
                                        </button>
                                    </div>
                                    
                                    <!-- Filter Section -->
                                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                            Filter Options:
                                        </h3>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                            <!-- Filter by Target -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Target Audience</label>
                                                <select id="filter_target" class="filter-select w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                                    <option value="">All Targets</option>
                                                    <option value="all">All</option>
                                                    <option value="class">Class</option>
                                                    <option value="belt">Belt</option>
                                                    <option value="branch">Branch</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Filter by Channel -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Channel</label>
                                                <select id="filter_channel" class="filter-select w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                                    <option value="">All Channels</option>
                                                    <option value="App">App</option>
                                                    <option value="SMS">SMS</option>
                                                    <option value="Email">Email</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Filter by Date Range -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">From Date</label>
                                                <input type="date" id="filter_date_from" class="filter-input w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">To Date</label>
                                                <input type="date" id="filter_date_to" class="filter-input w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <!-- Filter by Class -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Class</label>
                                                <select id="filter_class" class="filter-select w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                                    <option value="">All Classes</option>
                                                    @isset($classes)
                                                        @foreach($classes as $class)
                                                            <option value="{{ $class->class_name }}">{{ $class->class_name }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            
                                            <!-- Filter by Belt -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Belt Level</label>
                                                <select id="filter_belt" class="filter-select w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                                    <option value="">All Belts</option>
                                                    <option value="White Belt">White Belt</option>
                                                    <option value="Yellow Belt">Yellow Belt</option>
                                                    <option value="Orange Belt">Orange Belt</option>
                                                    <option value="Green Belt">Green Belt</option>
                                                    <option value="Blue Belt">Blue Belt</option>
                                                    <option value="Brown Belt">Brown Belt</option>
                                                    <option value="Black Belt">Black Belt</option>
                                                </select>
                                            </div>
                                            
                                            <!-- Filter by Branch -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Branch</label>
                                                <select id="filter_branch" class="filter-select w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                                    <option value="">All Branches</option>
                                                    @isset($branches)
                                                        @foreach($branches as $branch)
                                                            <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <!-- Filter by Competition Team -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Competition Team</label>
                                                <input type="text" id="filter_team" placeholder="Enter team name" class="filter-input w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                            </div>
                                            
                                            <!-- Filter by Status -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                                <select id="filter_status" class="filter-select w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#1c1c1d]">
                                                    <option value="">All Status</option>
                                                    <option value="active">Active</option>
                                                    <option value="expired">Expired</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- Filter Actions -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-gray-500" id="filter_result_count">Showing all entries</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button onclick="resetFilters()" class="px-3 py-1.5 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors flex items-center gap-1">
                                                    <i class="fa-solid fa-undo"></i>
                                                    Reset Filters
                                                </button>
                                                <button onclick="applyFilters()" class="px-3 py-1.5 text-xs bg-[#1c1c1d] text-white rounded hover:bg-[#2f2f2f] transition-colors flex items-center gap-1">
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                    Apply Filters
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Table Section -->
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                        <!-- Table -->
                                        <div class="overflow-x-auto">
                                            <table id="announcementTable" class="w-full text-sm min-w-full divide-y divide-gray-200">
                                                <!-- Table Head -->
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="bg-[#1C1C1D] px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Title</th>
                                                        <th class="bg-[#1C1C1D] px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Message</th>
                                                        <th class="bg-[#1C1C1D] px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Target</th>
                                                        <th class="bg-[#1C1C1D] px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Target Details</th>
                                                        <th class="bg-[#1C1C1D] px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Channel</th>
                                                        <th class="bg-[#1C1C1D] px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Publish Date</th>
                                                        <th class="bg-[#1C1C1D] px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Expire Date</th>
                                                        <th class="bg-[#1C1C1D] px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                
                                                <!-- Table Body -->
                                                <tbody class="bg-white divide-y divide-gray-200" id="announcementTableBody">
                                                    @isset($announcements)
                                                        @forelse($announcements as $announcement)
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150 announcement-row" 
                                                            data-target="{{ $announcement->target_type }}"
                                                            data-channel="{{ $announcement->channel }}"
                                                            data-class="{{ $announcement->class->class_name ?? '' }}"
                                                            data-belt="{{ $announcement->belt_level ?? '' }}"
                                                            data-branch="{{ $announcement->branch->name ?? '' }}"
                                                            data-publish="{{ $announcement->publish_date ? date('Y-m-d', strtotime($announcement->publish_date)) : '' }}"
                                                            data-expire="{{ $announcement->expire_date ? date('Y-m-d', strtotime($announcement->expire_date)) : '' }}">
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm font-medium text-gray-900">{{ $announcement->title }}</div>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <div class="text-sm text-gray-700 max-w-xs truncate">{{ $announcement->message }}</div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 py-1 text-xs font-medium rounded-full">
                                                                    {{ ucfirst($announcement->target_type) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="text-sm text-gray-700">
                                                                    @if($announcement->target_type == 'class' && $announcement->class)
                                                                        {{ $announcement->class->class_name ?? 'N/A' }}
                                                                    @elseif($announcement->target_type == 'belt' && $announcement->belt_level)
                                                                        {{ $announcement->belt_level }}
                                                                    @elseif($announcement->target_type == 'branch' && $announcement->branch)
                                                                        {{ $announcement->branch->name ?? 'N/A' }}
                                                                    @else
                                                                        —
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex gap-1">
                                                                    @php
                                                                        $channels = $announcement->channel ? explode(',', $announcement->channel) : [];
                                                                    @endphp
                                                                    @foreach($channels as $channel)
                                                                        <span class="px-2 py-1 text-gray-600 text-xs">{{ $channel }}</span>
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="text-sm text-gray-700">{{ $announcement->publish_date ? date('M d, Y', strtotime($announcement->publish_date)) : 'N/A' }}</span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="text-sm text-gray-700">{{ $announcement->expire_date ? date('M d, Y', strtotime($announcement->expire_date)) : 'N/A' }}</span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex items-center gap-2">
                                                                    <!-- View Button -->
                                                                    <button class="text-white bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg transition-all duration-200 view-announcement-btn" 
                                                                            onclick="openViewAnnouncementModal({{ $announcement->id }})"
                                                                            data-announcement-id="{{ $announcement->id }}"
                                                                            title="View Details">
                                                                        <i class="fa-regular fa-eye text-white text-sm"></i>
                                                                    </button>
                                                                    
                                                                    <!-- Edit Button -->
                                                                    <button class="text-white bg-green-500 hover:bg-green-600 p-2.5 rounded-lg transition-all duration-200 edit-announcement-btn"
                                                                            onclick="openEditAnnouncementModal({{ $announcement->id }})"
                                                                            data-announcement-id="{{ $announcement->id }}"
                                                                            title="Edit">
                                                                        <i class="fa-regular fa-pen-to-square text-white text-sm"></i>
                                                                    </button>
                                                                    
                                                                    <!-- Delete Button -->
                                                                    <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 delete-announcement-btn"
                                                                            data-announcement-id="{{ $announcement->id }}"
                                                                            data-announcement-title="{{ $announcement->title }}"
                                                                            title="Delete">
                                                                        <i class="fa-regular fa-trash-can text-white text-sm"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                                                <div class="flex flex-col items-center">
                                                                    <i class="fa-solid fa-bullhorn text-4xl text-gray-300 mb-3"></i>
                                                                    <p class="text-lg font-medium">No announcements found</p>
                                                                    <p class="text-sm">Click the "Create Announcement" button to create a new announcement.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    @else
                                                        <tr>
                                                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                                                <div class="flex flex-col items-center">
                                                                    <i class="fa-solid fa-exclamation-triangle text-4xl text-yellow-300 mb-3"></i>
                                                                    <p class="text-lg font-medium">Error loading announcements</p>
                                                                    <p class="text-sm">Please check your database connection and try again.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endisset
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Table Footer with Pagination Info -->
                                        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                                            <div class="text-xs text-gray-500" id="pagination_info">
                                                Showing <span id="visible_start">1</span> to <span id="visible_end">0</span> of <span id="visible_total">0</span> entries
                                            </div>
                                            <div class="flex gap-2" id="pagination_controls">
                                                <!-- Pagination will be handled by DataTable -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Add Announcement Modal --}}
    <div id="addAnnouncementModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[700px] shadow-lg rounded-xl bg-white">
            <!-- Header -->
            <div class="bg-[#1c1c1d] p-4 text-white flex items-center gap-2 rounded-t-lg">
                <i class="fa fa-plus text-xl" aria-hidden="true"></i>
                <h1 class="font-bold text-xl">Create New Announcement</h1>
            </div>
            
            <!-- Form Body -->
            <div class="px-6 pt-6 pb-4 bg-white">
                <form id="addAnnouncementForm" method="POST" action="{{ route('announcements.store') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Created By (Hidden - will be set from session) -->
                    <input type="hidden" name="created_by_user_id" value="{{ auth()->user()->id ?? 1 }}">
                    
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" placeholder="Enter announcement title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[100px] bg-white placeholder-gray-400"
                            placeholder="Enter announcement message..."></textarea>
                    </div>
                    
                    <!-- Target Audience -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience <span class="text-red-500">*</span></label>
                            <select name="target_type" id="target_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                <option value="" disabled selected>Select target</option>
                                <option value="all">All</option>
                                <option value="class">Class</option>
                                <option value="belt">Belt</option>
                                <option value="branch">Branch</option>
                            </select>
                        </div>
                        
                        <!-- Competition Team -->
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Competition Team</label>
                            <input type="text" name="competition_team" placeholder="Enter team name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                        </div> --}}
                    </div>
                    
                    <!-- Class Field (conditional) -->
                    <div id="class_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Class <span class="text-red-500">*</span></label>
                        <select name="class_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                            <option value="">Select class</option>
                            @isset($classes)
                                @forelse($classes as $class)
                                    <option value="{{ $class->id }}">
                                        {{ $class->class_name }}
                                    </option>
                                @empty
                                    <option value="" disabled>No classes available</option>
                                @endforelse
                            @else
                                <option value="" disabled>Classes not loaded</option>
                            @endisset
                        </select>
                    </div>
                    
                    <!-- Belt Field (conditional) -->
                    <div id="belt_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Belt Level <span class="text-red-500">*</span></label>
                        <select name="belt_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                            <option value="">Select belt level</option>
                            <option value="White Belt">White Belt</option>
                            <option value="Yellow Belt">Yellow Belt</option>
                            <option value="Orange Belt">Orange Belt</option>
                            <option value="Green Belt">Green Belt</option>
                            <option value="Blue Belt">Blue Belt</option>
                            <option value="Brown Belt">Brown Belt</option>
                            <option value="Black Belt">Black Belt</option>
                        </select>
                    </div>

                    <!-- Branch Field (conditional) -->
                    <div id="branch_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Branch <span class="text-red-500">*</span></label>
                        <select name="branch_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                            <option value="">Select branch</option>
                            @isset($branches)
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    
                    <!-- Channel -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Channel <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="channel[]" value="App" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                <span class="text-sm text-gray-700">App</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="channel[]" value="SMS" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                <span class="text-sm text-gray-700">SMS</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="channel[]" value="Email" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                <span class="text-sm text-gray-700">Email</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Expire Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expire Date <span class="text-red-500">*</span></label>
                        <input type="date" name="expire_date" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                    </div>
                </form>
            </div>
            
            <hr class="m-0 border-t border-gray-200">
            
            <!-- Footer Buttons -->
            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 rounded-b-lg">
                <button type="button" onclick="closeAddAnnouncementModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" form="addAnnouncementForm" class="px-4 py-2 text-sm font-medium text-white bg-[#1c1c1d] rounded-md hover:bg-[#2f2f2f] transition-colors">
                    Create Announcement
                </button>
            </div>
        </div>
    </div>

    {{-- Edit Announcement Modal --}}
    <div id="editAnnouncementModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[700px] shadow-lg rounded-xl bg-white">
            <!-- Header -->
            <div class="bg-[#1c1c1d] p-4 text-white flex items-center gap-2 rounded-t-lg">
                <i class="fa fa-pen-to-square text-xl" aria-hidden="true"></i>
                <h1 class="font-bold text-xl">Edit Announcement</h1>
            </div>
            
            <!-- Form Body -->
            <div class="px-6 pt-6 pb-4 bg-white">
                <form id="editAnnouncementForm" method="POST" action="" class="space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" id="edit_announcement_id" name="announcement_id">
                    
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="edit_title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" id="edit_message" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] resize-y min-h-[100px]"></textarea>
                    </div>
                    
                    <!-- Target Audience -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience <span class="text-red-500">*</span></label>
                            <select name="target_type" id="edit_target_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                                <option value="" disabled>Select target</option>
                                <option value="all">All</option>
                                <option value="class">Class</option>
                                <option value="belt">Belt</option>
                                <option value="branch">Branch</option>
                            </select>
                        </div>
                        
                        <!-- Competition Team -->
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Competition Team</label>
                            <input type="text" name="competition_team" id="edit_competition_team"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                        </div> --}}
                    </div>
                    
                    <!-- Class Field (conditional) -->
                    <div id="edit_class_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Class <span class="text-red-500">*</span></label>
                        <select name="class_id" id="edit_class_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                            <option value="">Select class</option>
                            @isset($classes)
                                @forelse($classes as $class)
                                    <option value="{{ $class->id }}">
                                        {{ $class->class_name }}
                                    </option>
                                @empty
                                    <option value="" disabled>No classes available</option>
                                @endforelse
                            @else
                                <option value="" disabled>Classes not loaded</option>
                            @endisset
                        </select>
                    </div>
                    
                    <!-- Belt Field (conditional) -->
                    <div id="edit_belt_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Belt Level <span class="text-red-500">*</span></label>
                        <select name="belt_level" id="edit_belt_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                            <option value="">Select belt level</option>
                            <option value="White Belt">White Belt</option>
                            <option value="Yellow Belt">Yellow Belt</option>
                            <option value="Orange Belt">Orange Belt</option>
                            <option value="Green Belt">Green Belt</option>
                            <option value="Blue Belt">Blue Belt</option>
                            <option value="Brown Belt">Brown Belt</option>
                            <option value="Black Belt">Black Belt</option>
                        </select>
                    </div>

                    <!-- Branch Field (conditional) -->
                    <div id="edit_branch_field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Branch <span class="text-red-500">*</span></label>
                        <select name="branch_id" id="edit_branch_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d] text-gray-700">
                            <option value="">Select branch</option>
                            @isset($branches)
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    
                    <!-- Channel -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Channel <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="edit_channel[]" value="App" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                <span class="text-sm text-gray-700">App</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="edit_channel[]" value="SMS" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                <span class="text-sm text-gray-700">SMS</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="edit_channel[]" value="Email" class="rounded text-[#1c1c1d] focus:ring-[#1c1c1d]">
                                <span class="text-sm text-gray-700">Email</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Expire Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expire Date <span class="text-red-500">*</span></label>
                        <input type="date" name="expire_date" id="edit_expire_date" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#1c1c1d] focus:border-[#1c1c1d]">
                    </div>
                </form>
            </div>
            
            <hr class="m-0 border-t border-gray-200">
            
            <!-- Footer Buttons -->
            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 rounded-b-lg">
                <button type="button" onclick="closeEditAnnouncementModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" form="editAnnouncementForm" class="px-4 py-2 text-sm font-medium text-white bg-[#1c1c1d] rounded-md hover:bg-[#2f2f2f] transition-colors">
                    Update Announcement
                </button>
            </div>
        </div>
    </div>

    {{-- View Announcement Modal --}}
    <div id="viewAnnouncementModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <h3 class="text-2xl font-bold text-[#ffffff]">Announcement Details</h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Title</h4>
                        <p id="view_announcement_title" class="text-lg font-semibold text-gray-900"></p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Message</h4>
                        <p id="view_announcement_message" class="text-gray-700 bg-gray-50 p-3 rounded-lg"></p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Target Audience</h4>
                            <p id="view_announcement_target" class="text-gray-900"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Class</h4>
                            <p id="view_announcement_class" class="text-gray-900"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Branch</h4>
                            <p id="view_announcement_branch" class="text-gray-900"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Belt Level</h4>
                            <p id="view_announcement_belt" class="text-gray-900"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Competition Team</h4>
                            <p id="view_announcement_team" class="text-gray-900"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Channel</h4>
                            <div id="view_announcement_channels" class="flex gap-2 mt-1"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Publish Date</h4>
                            <p id="view_announcement_publish" class="text-gray-900"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Expire Date</h4>
                            <p id="view_announcement_expire" class="text-gray-900"></p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Created By</h4>
                        <p id="view_announcement_creator" class="text-gray-900"></p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                    <button type="button" onclick="closeViewAnnouncementModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <!-- Pass CSRF token to JavaScript -->
    <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>
    
    @vite(['resources/js/announcement.js', 'resources/js/dashboard.js'])
</body>
</html>