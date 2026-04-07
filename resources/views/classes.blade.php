@extends('layouts.app')

@section('title', 'Class Management - TKD CMS')

@section('content')
@php
    $canCreateClass = auth()->user()->canCreate('classes');
    $canEditUser = auth()->user()->canEdit('classes');
    $canDeleteUser = auth()->user()->canDelete('classes');
@endphp
<div class="container-fluid">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 mt-1 mb-6">
                <a href="{{ route('dashboard.index') }}" class="hover:text-gray-700">Dashboard</a>
                <span>/</span>
                <span class="text-gray-700 font-medium">Classes</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-800">Class Management</h1>
        </div>
        
        <button type="button" 
            {{-- Only attach the click event if they have permission --}}
            @if($canCreateClass) onclick="openAddClassModal()" @endif
            
            class="bg-[#1c1c1d] hover:bg-[#3d3d3f] cursor-pointer text-white px-5 py-2.5  @if(!$canCreateClass) hidden @endif rounded-lg transition-colors duration-200 flex items-center gap-2 shadow-sm">
            <i class="fas fa-plus"></i>
            <span>Add New Class</span>
        </button>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#1c1c1d] rounded-xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-300 text-sm font-medium mb-1">Total Classes</p>
                    <h2 class="text-3xl font-bold">{{ $classes->count() }}</h2>
                </div>
                <i class="fas fa-chalkboard text-3xl text-gray-400"></i>
            </div>
        </div>
        
        <div class="bg-[#1c1c1d] rounded-xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-300 text-sm font-medium mb-1">Active Classes</p>
                    <h2 class="text-3xl font-bold">{{ $classes->where('status', 'active')->count() }}</h2>
                </div>
                <i class="fas fa-check-circle text-3xl text-gray-400"></i>
            </div>
        </div>
        
        <div class="bg-[#1c1c1d] rounded-xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-300 text-sm font-medium mb-1">Total Students</p>
                    <h2 class="text-3xl font-bold">{{ $classes->sum('student_count') }}</h2>
                </div>
                <i class="fas fa-users text-3xl text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $class)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                <!-- Card Header -->
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1 line-clamp-1">{{ $class->class_name }}</h3>
                            <p class="text-sm text-gray-500 flex items-center gap-1">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                {{ $class->branch->name ?? 'No Branch' }}
                            </p>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                            {{ $class->status == 'active' ? 'bg-green-100 text-green-700' : 
                               ($class->status == 'inactive' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($class->status) }}
                        </span>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-5 space-y-4">
                    <!-- Stats Row -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-500 mb-1">Students</p>
                            <p class="text-xl font-bold text-gray-700">{{ $class->student_count }} <span class="text-sm font-normal text-gray-400">/ {{ $class->max_students ?: '∞' }}</span></p>
                            @if($class->max_students > 0)
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                    <div class="bg-black-600 h-1.5 rounded-full" style="width: {{ min(($class->student_count / $class->max_students) * 100, 100) }}%"></div>
                                </div>
                            @endif
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-xs text-gray-500 mb-1">Level</p>
                            <p class="text-sm font-medium text-gray-700 truncate">{{ $class->level ?: 'Not Set' }}</p>
                        </div>
                    </div>
                    
                    <!-- Instructors -->
                    <div class="grid grid-cols-3 mb-2">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-black-500 text-sm"></i>
                            <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Instructors:</span>
                        </div>
                        @if($class->primaryInstructor)
                            <div class="flex items-center gap-2 text-sm text-gray-600 ">
                                <i class="fas fa-star text-yellow-500 text-xs"></i>
                                <span>{{ $class->primaryInstructor->fname }} {{ $class->primaryInstructor->lname }}</span>
                                <span class="text-xs text-gray-400">(Primary)</span>
                            </div>
                        @endif
                        @if($class->assistantInstructor)
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-user text-gray-400 text-xs"></i>
                                <span>{{ $class->assistantInstructor->fname }} {{ $class->assistantInstructor->lname }}</span>
                                <span class="text-xs text-gray-400">(Assistant)</span>
                            </div>
                        @endif
                        @if(!$class->primaryInstructor && !$class->assistantInstructor)
                            <p class="text-sm text-gray-400 italic">No instructors assigned</p>
                        @endif
                    </div>
                    
                    <!-- Schedule -->
                    @if($class->schedules->count() > 0)
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-calendar-alt text-black-500 text-sm"></i>
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Schedule</span>
                            </div>
                            <div class="space-y-1">
                                @foreach($class->schedules->take(2) as $schedule)
                                    <div class="text-sm text-gray-600 flex items-center gap-2">
                                        <span class="capitalize w-20">{{ $schedule->day_of_week }}</span>
                                        <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</span>
                                    </div>
                                @endforeach
                                @if($class->schedules->count() > 2)
                                    <p class="text-xs text-black-600">+{{ $class->schedules->count() - 2 }} more days</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Card Footer -->
                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex justify-between gap-2">
                    <button onclick="viewClass({{ $class->id }})" 
                            class="flex-1 px-3 py-2 cursor-pointer text-sm text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors duration-200 flex items-center justify-center gap-1">
                        <i class="fas fa-eye"></i>
                        <span>View</span>
                    </button>
                    <button 
                        @if ($canEditUser)
                            onclick="editClass({{ $class->id }})" 
                        @endif
                        class="flex-1 px-3 py-2 cursor-pointer text-sm text-green-600 hover:text-green-700 
                        @if (!$canEditUser)
                            hidden
                        @endif
                        hover:bg-green-50 rounded-lg transition-colors duration-200 flex items-center justify-center gap-1">

                        
                   
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </button>
                    <button 
                        @if ($canDeleteUser)
                            onclick="deleteClass({{ $class->id }}, '{{ addslashes($class->class_name) }}')" 
                        @endif
                        class="flex-1 px-3 py-2 cursor-pointer text-sm text-red-600 hover:text-red-700 
                        @if (!$canDeleteUser)
                            hidden
                        @endif
                        hover:bg-red-50 rounded-lg transition-colors duration-200 flex items-center justify-center gap-1">
                        <i class="fas fa-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <i class="fas fa-chalkboard text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">No Classes Found</h3>
                    <p class="text-gray-400">Click the "Add New Class" button to create your first class.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Class Modal -->
<div id="addClassModal" class="fixed inset-0 bg-black/50  overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
    <div class="relative top-10 mx-auto border w-full max-w-3xl shadow-2xl rounded-xl bg-white">
        <div class="flex items-center justify-between p-2.5 border-b bg-[#1c1c1d] rounded-t-xl">
            <div class="flex items-center gap-2">
                <i class="fas fa-plus text-white text-xl"></i>
                <h3 class="text-xl font-bold text-white">Add New Class</h3>
            </div>
        </div>
        
        <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
            <form id="addClassForm" action="{{ route('classes.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Branch -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Branch <span class="text-red-500">*</span></label>
                        <select name="branch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-transparent">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Class Name -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class Name <span class="text-red-500">*</span></label>
                        <input type="text" name="class_name" required maxlength="150" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-transparent"
                               placeholder="e.g., Beginner Taekwondo">
                    </div>
                    
                    <!-- Age Group -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Age Group</label>
                        <input type="text" name="age_group" maxlength="50" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-transparent"
                               placeholder="e.g., 5-7, 8-12, Adult">
                    </div>
                    
                    <!-- Level -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                        <select name="level" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-transparent">
                            <option value="">Select Level</option>
                            @foreach($belt_level as $belts)
                                <option value="{{ $belts->name }}">{{ $belts->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Max Students -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Students</label>
                        <input type="number" name="max_students" min="0" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-transparent"
                               placeholder="Leave empty for unlimited">
                    </div>
                    
                    <!-- Status -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-transparent">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <!-- Primary Instructor -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Instructor</label>
                        <select name="primary_instructor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-transparent">
                            <option value="">Select Instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->fname }} {{ $instructor->lname }} ({{ $instructor->rank_belt ?? 'No Belt' }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Assistant Instructor -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assistant Instructor</label>
                        <select name="assistant_instructor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500 focus:border-transparent">
                            <option value="">Select Instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->fname }} {{ $instructor->lname }} ({{ $instructor->rank_belt ?? 'No Belt' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Schedules Section -->
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Class Schedule</label>
                        <button type="button" onclick="addScheduleRow()" 
                                class="text-sm text-black-600 hover:text-black-800 flex items-center gap-1">
                            <i class="fas fa-plus"></i> Add Schedule
                        </button>
                    </div>
                    <div id="schedulesContainer" class="space-y-3">
                        <div class="schedule-row grid grid-cols-1 sm:grid-cols-4 gap-3 items-center">
                            <select name="schedules[0][day]" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                                <option value="">Select Day</option>
                                <option value="monday">Monday</option>
                                <option value="tuesday">Tuesday</option>
                                <option value="wednesday">Wednesday</option>
                                <option value="thursday">Thursday</option>
                                <option value="friday">Friday</option>
                                <option value="saturday">Saturday</option>
                                <option value="sunday">Sunday</option>
                            </select>
                            <input type="time" name="schedules[0][start_time]" placeholder="Start Time" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                            <input type="time" name="schedules[0][end_time]" placeholder="End Time" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                            <button type="button" onclick="removeScheduleRow(this)" 
                                    class="text-red-500 hover:text-red-700 px-2 py-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Add multiple schedules for classes that meet multiple times per week</p>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeAddClassModal()" 
                            class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-300 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-[#1c1c1d] text-white rounded-lg hover:bg-[#3d3d3f] transition-colors cursor-pointer">
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Class Modal -->
<div id="editClassModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
    <div class="relative top-10 mx-auto border w-full max-w-3xl shadow-2xl rounded-xl bg-white">
        <div class="flex items-center justify-between p-2.5 border-b bg-green-600 rounded-t-xl">
            <div class="flex items-center gap-2">
                <i class="fas fa-edit text-white text-xl"></i>
                <h3 class="text-xl font-bold text-white">Edit Class</h3>
            </div>
        </div>
        
        <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
            <form id="editClassForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Branch -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Branch <span class="text-red-500">*</span></label>
                        <select name="branch_id" id="edit_branch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Class Name -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class Name <span class="text-red-500">*</span></label>
                        <input type="text" name="class_name" id="edit_class_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                    </div>
                    
                    <!-- Age Group -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Age Group</label>
                        <input type="text" name="age_group" id="edit_age_group" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                    </div>
                    
                    <!-- Level  -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                        <select name="level" id="edit_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                            <option value="">Select Belt Level</option>
                            @foreach($belt_level as $belt)
                                <option value="{{ $belt->name }}">{{ $belt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Max Students -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Students</label>
                        <input type="number" name="max_students" id="edit_max_students" min="0" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                    </div>
                    
                    <!-- Status -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="edit_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <!-- Primary Instructor -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Instructor</label>
                        <select name="primary_instructor_id" id="edit_primary_instructor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                            <option value="">Select Instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->fname }} {{ $instructor->lname }} ({{ $instructor->rank_belt ?? 'No Belt' }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Assistant Instructor -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assistant Instructor</label>
                        <select name="assistant_instructor_id" id="edit_assistant_instructor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                            <option value="">Select Instructor</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}">{{ $instructor->fname }} {{ $instructor->lname }} ({{ $instructor->rank_belt ?? 'No Belt' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Schedules Section -->
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Class Schedule</label>
                        <button type="button" onclick="addEditScheduleRow()" 
                                class="text-sm text-black-600 hover:text-black-800 flex items-center gap-1">
                            <i class="fas fa-plus"></i> Add Schedule
                        </button>
                    </div>
                    <div id="editSchedulesContainer" class="space-y-3"></div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeEditClassModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                        Update Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Class Modal -->
<div id="viewClassModal" class="fixed inset-0 bg-black/50  overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
    <div class="relative top-10 mx-auto border w-full max-w-xl shadow-2xl rounded-xl bg-white">
        <div class="flex items-center justify-between p-2.5 border-b bg-blue-600 rounded-t-xl">
            <div class="flex items-center gap-2">
                <i class="fas fa-eye text-white text-xl"></i>
                <h3 class="text-xl font-bold text-white">Class Details</h3>
            </div>
            
        </div>
        <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto" id="viewClassContent">
            <div class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-black-600"></div>
                <p class="text-gray-500 mt-2">Loading class details...</p>
            </div>
        </div>
    </div>
</div>

<!-- Enroll Student Modal -->
<div id="enrollStudentModal" class="fixed inset-0 bg-black/50  overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
    <div class="relative top-20 mx-auto border w-full max-w-md shadow-2xl rounded-xl bg-white">
        <div class="flex items-center justify-between p-2.5 border-b bg-green-600 rounded-t-xl">
            <div class="flex items-center gap-2">
                <i class="fas fa-user-plus text-white text-xl"></i>
                <h3 class="text-xl font-bold text-white">Enroll Student</h3>
            </div>
        </div>
        <div class="p-6">
            <form id="enrollStudentForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Student <span class="text-red-500">*</span></label>
                    <select id="enroll_student_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500" required>
                        <option value="">Loading students...</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" id="enroll_start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500" required>
                </div>
            </form>
        </div>
        <div class="flex justify-end gap-3 p-5 pt-0">
            <button type="button" onclick="closeEnrollStudentModal()" 
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer">
                Cancel
            </button>
            <button type="button" onclick="submitEnrollment()" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                Enroll Student
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endpush

@push('scripts')
<script>
let scheduleCounter = 1;
let editScheduleCounter = 0;
let currentClassId = null;

function cleanSchedulesBeforeSubmit(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        const scheduleRows = document.querySelectorAll('#schedulesContainer .schedule-row, #editSchedulesContainer .schedule-row');
        let hasInvalid = false;
        
        scheduleRows.forEach((row, index) => {
            const day = row.querySelector('select[name*="[day]"]')?.value;
            const startTime = row.querySelector('input[name*="[start_time]"]')?.value;
            const endTime = row.querySelector('input[name*="[end_time]"]')?.value;
            
            // If day is selected but times are missing
            if (day && (!startTime || !endTime)) {
                Swal.fire('Warning', `Schedule ${index + 1}: Please fill in both start and end times`, 'warning');
                hasInvalid = true;
                e.preventDefault();
                return;
            }
            
            // If times are provided but day is missing
            if ((startTime || endTime) && !day) {
                Swal.fire('Warning', `Schedule ${index + 1}: Please select a day`, 'warning');
                hasInvalid = true;
                e.preventDefault();
                return;
            }
            
            // Validate time order
            if (startTime && endTime && startTime >= endTime) {
                Swal.fire('Warning', `Schedule ${index + 1}: End time must be after start time`, 'warning');
                hasInvalid = true;
                e.preventDefault();
                return;
            }
            
            // If the row is completely empty (no day, no times), remove it from the form
            if (!day && !startTime && !endTime) {
                row.remove();
            }
        });
        
        // If there are no schedules at all, make sure we don't send any schedule data
        const remainingRows = document.querySelectorAll('#schedulesContainer .schedule-row, #editSchedulesContainer .schedule-row');
        if (remainingRows.length === 0) {
            // Remove all schedule inputs from the form
            const scheduleInputs = form.querySelectorAll('[name*="schedules"]');
            scheduleInputs.forEach(input => input.remove());
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    cleanSchedulesBeforeSubmit('addClassForm');
    cleanSchedulesBeforeSubmit('editClassForm');
});

function addScheduleRow() {
    const container = document.getElementById('schedulesContainer');
    const newRow = document.createElement('div');
    newRow.className = 'schedule-row grid grid-cols-1 sm:grid-cols-4 gap-3 items-center';
    newRow.innerHTML = `
        <select name="schedules[${scheduleCounter}][day]" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
            <option value="">Select Day</option>
            <option value="monday">Monday</option>
            <option value="tuesday">Tuesday</option>
            <option value="wednesday">Wednesday</option>
            <option value="thursday">Thursday</option>
            <option value="friday">Friday</option>
            <option value="saturday">Saturday</option>
            <option value="sunday">Sunday</option>
        </select>
        <input type="time" name="schedules[${scheduleCounter}][start_time]" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
        <input type="time" name="schedules[${scheduleCounter}][end_time]" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
        <button type="button" onclick="removeScheduleRow(this)" class="text-red-500 hover:text-red-700 px-2 py-2">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newRow);
    scheduleCounter++;
}

function removeScheduleRow(button) {
    button.closest('.schedule-row').remove();
}

function addEditScheduleRow(day = '', startTime = '', endTime = '') {
    const container = document.getElementById('editSchedulesContainer');
    const newRow = document.createElement('div');
    newRow.className = 'schedule-row grid grid-cols-1 sm:grid-cols-4 gap-3 items-center';
    
    // Format times properly (ensure they are in HH:MM format)
    let formattedStartTime = startTime;
    let formattedEndTime = endTime;
    
    // If times are in format like "14:30:00", convert to "14:30"
    if (startTime && startTime.includes(':')) {
        formattedStartTime = startTime.substring(0, 5);
    }
    if (endTime && endTime.includes(':')) {
        formattedEndTime = endTime.substring(0, 5);
    }
    
    newRow.innerHTML = `
        <select name="schedules[${editScheduleCounter}][day]" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
            <option value="">Select Day</option>
            <option value="monday" ${day === 'monday' ? 'selected' : ''}>Monday</option>
            <option value="tuesday" ${day === 'tuesday' ? 'selected' : ''}>Tuesday</option>
            <option value="wednesday" ${day === 'wednesday' ? 'selected' : ''}>Wednesday</option>
            <option value="thursday" ${day === 'thursday' ? 'selected' : ''}>Thursday</option>
            <option value="friday" ${day === 'friday' ? 'selected' : ''}>Friday</option>
            <option value="saturday" ${day === 'saturday' ? 'selected' : ''}>Saturday</option>
            <option value="sunday" ${day === 'sunday' ? 'selected' : ''}>Sunday</option>
        </select>
        <input type="time" name="schedules[${editScheduleCounter}][start_time]" value="${formattedStartTime}" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
        <input type="time" name="schedules[${editScheduleCounter}][end_time]" value="${formattedEndTime}" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
        <button type="button" onclick="removeScheduleRow(this)" class="text-red-500 hover:text-red-700 px-2 py-2">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newRow);
    editScheduleCounter++;
}

function openAddClassModal() {
    document.getElementById('addClassModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddClassModal() {
    document.getElementById('addClassModal').classList.add('hidden');
    document.getElementById('addClassForm').reset();
    document.getElementById('schedulesContainer').innerHTML = `
        <div class="schedule-row grid grid-cols-1 sm:grid-cols-4 gap-3 items-center">
            <select name="schedules[0][day]" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
                <option value="">Select Day</option>
                <option value="monday">Monday</option>
                <option value="tuesday">Tuesday</option>
                <option value="wednesday">Wednesday</option>
                <option value="thursday">Thursday</option>
                <option value="friday">Friday</option>
                <option value="saturday">Saturday</option>
                <option value="sunday">Sunday</option>
            </select>
            <input type="time" name="schedules[0][start_time]" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
            <input type="time" name="schedules[0][end_time]" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black-500">
            <button type="button" onclick="removeScheduleRow(this)" class="text-red-500 hover:text-red-700 px-2 py-2">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    scheduleCounter = 1;
    document.body.style.overflow = 'auto';
}

function openEditClassModal() {
    document.getElementById('editClassModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditClassModal() {
    document.getElementById('editClassModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    editScheduleCounter = 0;
}

function closeViewClassModal() {
    document.getElementById('viewClassModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function closeEnrollStudentModal() {
    document.getElementById('enrollStudentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function editClass(classId) {
    fetch(`/classes/${classId}/edit`)
        .then(response => response.json())
        .then(data => {
            const classData = data.class;
            const schedules = data.schedules;
            
            document.getElementById('editClassForm').action = `/classes/${classId}`;
            document.getElementById('edit_branch_id').value = classData.branch_id;
            document.getElementById('edit_class_name').value = classData.class_name;
            document.getElementById('edit_age_group').value = classData.age_group || '';
            
            // Set the belt level select dropdown value
            const levelSelect = document.getElementById('edit_level');
            if (classData.level) {
                // Find and select the matching belt level option
                for (let i = 0; i < levelSelect.options.length; i++) {
                    if (levelSelect.options[i].value === classData.level) {
                        levelSelect.selectedIndex = i;
                        break;
                    }
                }
            } else {
                levelSelect.value = '';
            }
            
            document.getElementById('edit_max_students').value = classData.max_students;
            document.getElementById('edit_status').value = classData.status;
            document.getElementById('edit_primary_instructor_id').value = classData.primary_instructor_id || '';
            document.getElementById('edit_assistant_instructor_id').value = classData.assistant_instructor_id || '';
            
            const container = document.getElementById('editSchedulesContainer');
            container.innerHTML = '';
            editScheduleCounter = 0;
            
            if (schedules && schedules.length > 0) {
                schedules.forEach(schedule => {
                    addEditScheduleRow(schedule.day_of_week, schedule.start_time, schedule.end_time);
                });
            } else {
                addEditScheduleRow();
            }
            
            openEditClassModal();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Failed to load class data', 'error');
        });
}

function viewClass(classId) {
    openViewClassModal();
    fetch(`/classes/${classId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('viewClassContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('viewClassContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-3"></i>
                    <p class="text-red-500">Failed to load class details</p>
                </div>
            `;
        });
}

function openViewClassModal() {
    document.getElementById('viewClassModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function deleteClass(classId, className) {
    Swal.fire({
        title: 'Delete Class?',
        html: `Are you sure you want to delete <strong>${className}</strong>?<br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/classes/${classId}`;
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function openEnrollStudentModal(classId) {
    currentClassId = classId;
    
    // Show loading state
    const select = document.getElementById('enroll_student_id');
    select.innerHTML = '<option value="">Loading students...</option>';
    
    fetch(`/classes/${classId}/available-students`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(students => {
            const select = document.getElementById('enroll_student_id');
            select.innerHTML = '<option value="">Select Student</option>';
            
            if (students.length === 0) {
                select.innerHTML = '<option value="">No available students found</option>';
            } else {
                students.forEach(student => {
                    select.innerHTML += `<option value="${student.id}">${student.student_name} (${student.current_belt || 'No Belt'})</option>`;
                });
            }
            
            document.getElementById('enroll_start_date').valueAsDate = new Date();
            document.getElementById('enrollStudentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error loading students:', error);
            Swal.fire('Error', 'Failed to load available students. Please try again.', 'error');
            
            // Reset select
            const select = document.getElementById('enroll_student_id');
            select.innerHTML = '<option value="">Error loading students</option>';
        });
}

function submitEnrollment() {
    const studentId = document.getElementById('enroll_student_id').value;
    const startDate = document.getElementById('enroll_start_date').value;
    
    if (!studentId) {
        Swal.fire('Error', 'Please select a student', 'error');
        return;
    }
    if (!startDate) {
        Swal.fire('Error', 'Please select a start date', 'error');
        return;
    }
    
    fetch(`/classes/${currentClassId}/enroll`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ student_id: studentId, start_date: startDate })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', data.success, 'success');
            closeEnrollStudentModal();
            setTimeout(() => location.reload(), 1500);
        } else if (data.error) {
            Swal.fire('Error', data.error, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Failed to enroll student', 'error');
    });
}

// Close modals when clicking outside
window.onclick = function(event) {
    const addModal = document.getElementById('addClassModal');
    const editModal = document.getElementById('editClassModal');
    const viewModal = document.getElementById('viewClassModal');
    const enrollModal = document.getElementById('enrollStudentModal');
    
    if (event.target === addModal) closeAddClassModal();
    if (event.target === editModal) closeEditClassModal();
    if (event.target === viewModal) closeViewClassModal();
    if (event.target === enrollModal) closeEnrollStudentModal();
}

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddClassModal();
        closeEditClassModal();
        closeViewClassModal();
        closeEnrollStudentModal();
    }
});

function removeStudent(classId, studentId, studentName) {
    Swal.fire({
        title: 'Remove Student?',
        html: `Are you sure you want to remove <strong>${studentName}</strong> from this class?<br><br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, remove student',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Removing student...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make the API request to remove the student
            fetch(`/classes/${classId}/students/${studentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.success,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload the class details modal content to reflect changes
                        const currentClassId = classId;
                        fetch(`/classes/${currentClassId}`)
                            .then(response => response.text())
                            .then(html => {
                                document.getElementById('viewClassContent').innerHTML = html;
                            })
                            .catch(error => {
                                console.error('Error reloading class details:', error);
                            });
                    });
                } else if (data.error) {
                    Swal.fire({
                        title: 'Error!',
                        text: data.error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error removing student:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to remove student. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}


// Form validation for schedules
document.getElementById('addClassForm')?.addEventListener('submit', function(e) {
    const scheduleRows = document.querySelectorAll('#schedulesContainer .schedule-row');
    let hasInvalid = false;
    
    scheduleRows.forEach((row, index) => {
        const day = row.querySelector('select[name*="[day]"]').value;
        const startTime = row.querySelector('input[name*="[start_time]"]').value;
        const endTime = row.querySelector('input[name*="[end_time]"]').value;
        
        if (day && (!startTime || !endTime)) {
            Swal.fire('Warning', `Schedule ${index + 1}: Please fill in both start and end times`, 'warning');
            hasInvalid = true;
            e.preventDefault();
        }
        
        if (startTime && endTime && startTime >= endTime) {
            Swal.fire('Warning', `Schedule ${index + 1}: End time must be after start time`, 'warning');
            hasInvalid = true;
            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection