{{-- resources/views/partials/class-details.blade.php --}}
@if(isset($class))
<div class="space-y-6">
    <!-- Basic Information -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-semibold text-gray-700 mb-3">Basic Information</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Class Name:</span>
                <p class="font-medium">{{ $class->class_name }}</p>
            </div>
            <div>
                <span class="text-gray-500">Branch:</span>
                <p class="font-medium">{{ $class->branch->name ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Age Group:</span>
                <p class="font-medium">{{ $class->age_group ?? 'All ages' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Level:</span>
                <p class="font-medium">{{ $class->level ?? 'Not specified' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Max Students:</span>
                <p class="font-medium">{{ $class->max_students ?: 'Unlimited' }}</p>
            </div>
            <div>
                <span class="text-gray-500">Current Students:</span>
                <p class="font-medium">{{ $class->student_count ?? 0 }}</p>
            </div>
            <div>
                <span class="text-gray-500">Status:</span>
                <p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $class->status == 'active' ? 'bg-green-100 text-green-700' : 
                           ($class->status == 'inactive' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ ucfirst($class->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Instructors -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-semibold text-gray-700 mb-3">Instructors</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Primary Instructor:</span>
                <p class="font-medium">
                    @if($class->primaryInstructor)
                        {{ $class->primaryInstructor->fname }} {{ $class->primaryInstructor->lname }}
                        <span class="text-xs text-gray-400">({{ $class->primaryInstructor->rank_belt ?? 'No Belt' }})</span>
                    @else
                        Not assigned
                    @endif
                </p>
            </div>
            <div>
                <span class="text-gray-500">Assistant Instructor:</span>
                <p class="font-medium">
                    @if($class->assistantInstructor)
                        {{ $class->assistantInstructor->fname }} {{ $class->assistantInstructor->lname }}
                        <span class="text-xs text-gray-400">({{ $class->assistantInstructor->rank_belt ?? 'No Belt' }})</span>
                    @else
                        Not assigned
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    <!-- Schedule -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-semibold text-gray-700 mb-3">Class Schedule</h4>
        @if($class->schedules->count() > 0)
            <div class="space-y-2">
                @foreach($class->schedules as $schedule)
                    <div class="flex items-center text-sm">
                        <span class="w-24 font-medium capitalize">{{ $schedule->day_of_week }}</span>
                        <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">No schedule set</p>
        @endif
    </div>
    
    <!-- Enrolled Students -->
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-semibold text-gray-700">Enrolled Students</h4>
            <button onclick="openEnrollStudentModal({{ $class->id }})" 
                    class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer">
                <i class="fas fa-user-plus mr-1"></i> Enroll Student
            </button>
        </div>
        
        @if($class->students->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Student Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Belt</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Start Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($class->students as $enrollment)
                            <tr>
                                <td class="px-4 py-2 text-sm">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800">
                                        {{ $enrollment->student->first_name ?? '' }} {{ $enrollment->student->last_name ?? '' }}
                                    </a>
                                </td>
                                <td class="px-4 py-2 text-sm">{{ $enrollment->student->currentBelt->name ?? 'No Belt' }}</td>
                                <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($enrollment->start_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $enrollment->status == 'active' ? 'bg-green-100 text-green-700' : 
                                           ($enrollment->status == 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm">
                                    <button onclick="removeStudent({{ $class->id }}, {{ $enrollment->student->id }}, '{{ addslashes($enrollment->student->first_name . ' ' . $enrollment->student->last_name) }}')" 
                                            class="text-red-500 hover:text-red-700 transition-colors duration-200 p-1 rounded hover:bg-red-50"
                                            title="Remove student from class">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm text-center py-4">No students enrolled yet</p>
        @endif
    </div>
</div>
@else
<div class="text-center py-8">
    <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-3"></i>
    <p class="text-red-500">Class not found</p>
</div>
@endif