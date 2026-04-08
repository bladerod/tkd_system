<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrainNova | Attendance</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css', 'resources/css/attendance.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif

    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif
</head>
<body class="bg-gray-50">
    @include("includes.navbar")
    @include('includes.sidebar')
    
    <div class="container-fluid m-0">
        <div class="row">
            <main class="ml-64 p-6"> 
                <div class="container-fluid">
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                        <span>/</span>
                        <span class="text-[#1C1C1D] font-medium">Attendance</span>
                    </div>

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-4xl font-bold text-[#1C1C1D]">Attendance</h1>
                        <div class="flex gap-4">
                            <div class="bg-white rounded-lg shadow-sm px-4 py-2">
                                <span class="text-sm text-gray-500">Today's Total</span>
                                <p class="text-2xl font-bold text-[#1C1C1D]">{{ $totalToday ?? 0 }}</p>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm px-4 py-2">
                                <span class="text-sm text-gray-500">Unique Students</span>
                                <p class="text-2xl font-bold text-[#1C1C1D]">{{ $uniqueStudentsToday ?? 0 }}</p>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm px-4 py-2">
                                <span class="text-sm text-gray-500">Active Classes</span>
                                <p class="text-2xl font-bold text-[#1C1C1D]">{{ $activeClasses ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Today's Log</h6>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="flex justify-end gap-3 mb-1 p-3">
                                        <form action="{{ route('attendance.manual-override') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-[#f99b20] px-6 py-2.5 rounded-xl hover:bg-[#fcb85f] font-medium transition-colors">
                                                Manual Override
                                            </button>
                                        </form>
                                        
                                        <button type="button" onclick="openModal()" class="bg-[#9ca5ad] px-6 py-2.5 rounded-xl hover:bg-[#BFC9D1] font-medium transition-colors inline">
                                            Add Manual
                                        </button>
                                        
                                        
                                        <a href="{{ route('attendance.export', request()->all()) }}" class="bg-[#A62809] px-6 py-2.5 rounded-xl hover:bg-[#bf2d0d] text-white font-medium transition-colors inline-block">
                                            Export as CSV
                                        </a>
                                    </div>
                                    
                                    <form method="GET" action="{{ route('attendance.index') }}" class="mb-6 p-3">
                                        <div class="flex items-center gap-2 mb-4">
                                            <h1 class="font-semibold text-gray-700">Filter Options</h1>
                                        </div>
                                        
                                        <div class="grid grid-cols-12 gap-4 items-end">
                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">From Date</label>
                                                <input type="date" name="from_date" value="{{ $fromDate ?? '' }}" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                                            </div>
                                            
                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">To Date</label>
                                                <input type="date" name="to_date" value="{{ $toDate ?? '' }}" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                                            </div>
                                            
                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Class</label>
                                                <select name="class_id" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                                                    <option value="">All Classes</option>
                                                    @foreach($classes ?? [] as $class)
                                                        <option value="{{ $class->id }}" {{ ($classId ?? '') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->class_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Instructor</label>
                                                <select name="instructor_id" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                                                    <option value="">All Instructors</option>
                                                    @foreach($instructors ?? [] as $instructor)
                                                        <option value="{{ $instructor->id }}" {{ ($instructorId ?? '') == $instructor->id ? 'selected' : '' }}>
                                                            {{ $instructor->fname }} {{ $instructor->lname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-gray-500 mb-1 uppercase tracking-wider">Camera Device</label>
                                                <select name="device_id" 
                                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all duration-200 bg-gray-50 hover:bg-white">
                                                    <option value="">All Cameras</option>
                                                    @foreach($devices ?? [] as $device)
                                                        <option value="{{ $device->id }}" {{ ($deviceId ?? '') == $device->id ? 'selected' : '' }}>
                                                            {{ $device->device_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-span-2">
                                                <button type="submit" class="w-full bg-[#1C1C1D] text-white px-6 py-2.5 rounded-lg hover:bg-[#2C2C2D] transition-all duration-200 font-medium text-sm shadow-sm hover:shadow-md flex items-center justify-center gap-2 group">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                                    </svg>
                                                    <span>Apply Filters</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <div class="bg-white shadow-sm border border-gray-100 overflow-hidden">
                                        <div class="overflow-x-auto">
                                            <table id="userTable" class="w-full text-sm">
                                                <thead>
                                                    <tr class="border-b border-gray-200 bg-[#1C1C1D] ">
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Time</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Branch</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Student Name</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Class</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Instructor</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Camera</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Match %</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Method</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody class="divide-y divide-gray-100">
                                                    @forelse($attendanceLogs as $log)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{-- format(m=month,d=day, y=year, g=12 hours format, i=minute format, a=AM or PM) --}}
                                                            {{ optional($log->checkin_time)->format('m-d-y g:i A') ?? 'N/A' }}
                                                            @if($log->checkout_time)
                                                                <br><span class="text-xs text-gray-400">out: {{ $log->checkout_time->format('m-d-y g:i A') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-gray-800">
                                                                        {{ $log->branch ?? 'N/A' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-gray-800">
                                                                        {{ $log->student_name ?? 'Unknown' }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ $log->student_code ?? 'No Code' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ $log->class_name ?? 'N/A' }}
                                                            <br>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ $log->instructor_name }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ optional($log->device)->device_name ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 py-1 text-xs font-medium {{ ($log->confidence_score ?? 0) >= 90 ?? '' }} rounded-full">
                                                                {{ $log->confidence_score ?? 'N/A' }}{{ $log->confidence_score ? '%' : '' }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full">
                                                                {{ ucfirst($log->method ?? 'manual') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @if(!$log->checkout_time || $log->checkout_time->format('Y-m-d H:i:s') == '0000-00-00 00:00:00')
                                                                <span class="px-3 py-1 text-xs font-medium bg-green-500 text-white rounded-full">IN</span>
                                                            @else
                                                                <span class="px-3 py-1 text-xs font-medium bg-red-500 text-white rounded-full">OUT</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                                            <div class="flex flex-col items-center">
                                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <p class="text-lg font-medium">No attendance records found</p>
                                                                <p class="text-sm text-gray-400">Try adjusting your filters or add manual attendance</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
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

    <!-- Manual Attendance Modal -->
    <div id="manualModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-solid fa-pen-to-square text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Add Manual Attendance</h3>
            </div>
            
            <div class="p-4">
                <!-- Modal Body - Form -->
                <form method="POST" action="{{ route('attendance.add-manual') }}" id="manualAttendanceForm">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        {{-- branches --}}
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Branch <span class="text-[#FF0000]">*</span>
                            </label>
                            <select name="branch" id="branch" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]" required>
                                <option value="">Select Branch</option>
                                @foreach($branches ?? [] as $branch)
                                    <option value="{{ $branch->id }}">
                                        {{ $branch->code ?? 'No Code' }} - {{ $branch->name }} 
                                    </option>
                                @endforeach
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_student_id"></div>
                        </div>
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Student <span class="text-[#FF0000]">*</span>
                            </label>
                            <select name="student_id" id="student_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]" required>
                                <option value="">Select Student</option>
                                @foreach($students ?? [] as $student)
                                    <option value="{{ $student->id }}" data-branch="{{ $student->branch_id }}">
                                        {{ $student->first_name }} {{ $student->last_name }} - {{ $student->student_code ?? 'No Code' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_student_id"></div>
                        </div>
                        
                        <!-- Class Session Selection -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Class Session <span class="text-[#FF0000]">*</span>
                            </label>
                            <select name="class_session_id" id="class_session_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]" required>
                                <option value="">Select Class Session</option>
                                @foreach($classSessions ?? [] as $session)
                                    <option value="{{ $session->id }}">
                                        {{ $session->class->class_name ?? 'Unknown Class' }} - 
                                        {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }} 
                                        ({{ \Carbon\Carbon::parse($session->start_time)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($session->end_time)->format('g:i A') }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_class_session_id"></div>
                        </div>
                        
                        <!-- Check-in Time -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Check-in Time <span class="text-[#FF0000]">*</span>
                            </label>
                            <input type="datetime-local" name="checkin_time" id="checkin_time" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]" 
                                value="{{ now()->format('Y-m-d\TH:i') }}"
                                required>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_checkin_time"></div>
                        </div>
                        
                        <!-- Attendance Status -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Attendance Status <span class="text-[#FF0000]">*</span>
                            </label>
                            <select name="attendance_status" id="attendance_status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]" required>
                                <option value="present">Present</option>
                                <option value="late">Late</option>
                                <option value="absent">Absent</option>
                                <option value="excused">Excused</option>
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_attendance_status"></div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitAttendanceBtn" 
                            class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">
                            Save Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js', 'resources/js/attendance.js'])
</body>
</html>