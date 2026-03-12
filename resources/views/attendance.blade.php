<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD - Attendance</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
</head>
<body class="bg-gray-50">
    @include("includes.navbar")
    @include('includes.sidebar')
    
    <div class="container-fluid m-0">
        <div class="row">
            <main class="ml-64 p-6"> 
                <div class="container-fluid">
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
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
                                <div class="p-3">
                                    <div class="flex justify-end gap-3 mb-6">
                                        <form action="{{ route('attendance.manual-override') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-[#f99b20] px-6 py-2.5 rounded-xl hover:bg-[#fcb85f] font-medium transition-colors">
                                                Manual Override
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('attendance.add-manual') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-[#9ca5ad] px-6 py-2.5 rounded-xl hover:bg-[#BFC9D1] font-medium transition-colors">
                                                Add Manual
                                            </button>
                                        </form>
                                        
                                        <a href="{{ route('attendance.export', request()->all()) }}" class="bg-[#A62809] px-6 py-2.5 rounded-xl hover:bg-[#bf2d0d] text-white font-medium transition-colors inline-block">
                                            Export as CSV
                                        </a>
                                    </div>
                                    
                                    <form method="GET" action="{{ route('attendance.index') }}" class="mb-6">
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
                                    
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <h2 class="font-semibold text-gray-800">Attendance Logs</h2>
                                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                        {{ $attendanceLogs->total() ?? 0 }} entries
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm">
                                                <thead>
                                                    <tr class="bg-gray-50 border-b border-gray-200">
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Camera</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Match %</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded By</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody class="divide-y divide-gray-100">
                                                    @forelse($attendanceLogs ?? [] as $log)
                                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ optional($log->checkin_time)->format('g:i A') ?? 'N/A' }}
                                                            @if($log->checkout_time)
                                                                <br><span class="text-xs text-gray-400">out: {{ $log->checkout_time->format('g:i A') }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                                    <span class="text-xs font-medium text-gray-600">
                                                                        {{ substr(optional($log->student)->first_name ?? 'N', 0, 1) }}{{ substr(optional($log->student)->last_name ?? 'A', 0, 1) }}
                                                                    </span>
                                                                </div>
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-gray-800">
                                                                        {{ optional($log->student)->first_name ?? 'Unknown' }} {{ optional($log->student)->last_name ?? '' }}
                                                                    </p>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ optional($log->student)->student_code ?? 'No Code' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ optional(optional($log->classSession)->class)->class_name ?? 'N/A' }}
                                                            <br>
                                                            <span class="text-xs text-gray-500">{{ optional(optional($log->classSession)->class)->level ?? '' }}</span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ optional(optional($log->classSession)->instructor)->fname ?? 'N/A' }} {{ optional(optional($log->classSession)->instructor)->lname ?? '' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ optional($log->device)->device_name ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 py-1 text-xs font-medium {{ ($log->confidence_score ?? 0) >= 90 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full">
                                                                {{ $log->confidence_score ?? 'N/A' }}{{ $log->confidence_score ? '%' : '' }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                                @if($log->method == 'face') bg-blue-100 text-blue-800
                                                                @elseif($log->method == 'qr') bg-purple-100 text-purple-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif">
                                                                {{ ucfirst($log->method ?? 'manual') }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                            {{ optional($log->recordedBy)->fname ?? 'System' }} {{ optional($log->recordedBy)->lname ?? '' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap flex items-center gap-1">
                                                            @if(!$log->checkout_time)
                                                                <span class="px-3 py-1 text-xs font-medium bg-green-500 text-white rounded-full">IN</span>
                                                            @else
                                                                <span class="px-3 py-1 text-xs font-medium bg-red-500 text-white rounded-full">OUT</span>
                                                            @endif
                                                            
                                                            @if($log->status == 1)
                                                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full" title="Valid Log">Valid</span>
                                                            @elseif($log->status === 0)
                                                                <span class="px-2 py-1 text-xs font-medium bg-gray-200 text-gray-800 rounded-full" title="Invalid/Voided">Void</span>
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
                                        
                                        @if(($attendanceLogs ?? null) && $attendanceLogs->hasPages())
                                        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                                            <div class="text-xs text-gray-400">
                                                Showing {{ $attendanceLogs->firstItem() ?? 0 }} to {{ $attendanceLogs->lastItem() ?? 0 }} of {{ $attendanceLogs->total() ?? 0 }} entries
                                            </div>
                                            <div class="flex gap-2">
                                                @if($attendanceLogs->onFirstPage())
                                                    <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-300 cursor-not-allowed" disabled>Previous</button>
                                                @else
                                                    <a href="{{ $attendanceLogs->previousPageUrl() }}" class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50">Previous</a>
                                                @endif
                                                
                                                @foreach($attendanceLogs->getUrlRange(max(1, $attendanceLogs->currentPage() - 2), min($attendanceLogs->lastPage(), $attendanceLogs->currentPage() + 2)) as $page => $url)
                                                    <a href="{{ $url }}" class="px-3 py-1 text-xs border border-gray-200 rounded {{ $page == $attendanceLogs->currentPage() ? 'bg-[#1C1C1D] text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                                                        {{ $page }}
                                                    </a>
                                                @endforeach
                                                
                                                @if($attendanceLogs->hasMorePages())
                                                    <a href="{{ $attendanceLogs->nextPageUrl() }}" class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-500 hover:bg-gray-50">Next</a>
                                                @else
                                                    <button class="px-3 py-1 text-xs border border-gray-200 rounded bg-white text-gray-300 cursor-not-allowed" disabled>Next</button>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div id="manualModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Manual Attendance</h3>
                <form method="POST" action="{{ route('attendance.add-manual') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                        <select name="student_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                            <option value="">Select Student</option>
                            </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class Session</label>
                        <select name="class_session_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                            <option value="">Select Class</option>
                            </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                        <input type="datetime-local" name="checkin_time" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D]">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function openModal() {
            document.getElementById('manualModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('manualModal').classList.add('hidden');
        }

        // Add event listener for manual add button
        document.querySelector('button[onclick*="addManual"]')?.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    </script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])
</body>
</html>