@extends('layouts.app')

@section('title', $competition->name)

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/js/competition.js'])
<div class="container-fluid m-0">
    <div class="row">
        <main class="">
            <div class="container-fluid">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('competition.index') }}" class="hover:text-[#1C1C1D]">Competitions</a>
                    <span>/</span>
                    <span class="text-[#1C1C1D] font-medium">{{ $competition->name }}</span>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">{{ $competition->name }}</h1>
                    <div class="flex items-center gap-3">
                        <button onclick="openAddEntryModal()" 
                            class="bg-[#62b236] hover:bg-[#6abc3a] p-3 rounded-xl font-medium text-white transition-colors cursor-pointer">
                                <i class="fa-solid fa-plus mr-2"></i>Add Entry
                        </button>
                    </div>
                </div>

                <!-- Competition Details Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Location</p>
                            <p class="text-lg font-semibold">{{ $competition->location }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Date</p>
                            <p class="text-lg font-semibold">{{ $competition->date->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Organizer</p>
                            <p class="text-lg font-semibold">{{ $competition->organizer ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Level</p>
                            <span class="px-3 py-1 text-sm rounded-full 
                                @if($competition->level == 'local') bg-gray-100 text-gray-800
                                @elseif($competition->level == 'regional') bg-blue-100 text-blue-800
                                @elseif($competition->level == 'national') bg-purple-100 text-purple-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($competition->level) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-2">Total Participants</p>
                        <p class="text-3xl font-bold">{{ $competition->entries->count() }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-2">Gold Medals</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $competition->entries->where('medal', 'gold')->count() }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-2">Silver Medals</p>
                        <p class="text-3xl font-bold text-gray-500">{{ $competition->entries->where('medal', 'silver')->count() }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <p class="text-sm text-gray-500 mb-2">Bronze Medals</p>
                        <p class="text-3xl font-bold text-amber-700">{{ $competition->entries->where('medal', 'bronze')->count() }}</p>
                    </div>
                </div>

                <!-- Entries Table -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-semibold">Competition Entries</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Division</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($competition->entries as $entry)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $entry->student->student_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $entry->instructor->fname ?? 'N/A' }} {{ $entry->instructor->lname ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $entry->category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $entry->division }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($entry->result == 'win') bg-green-100 text-green-800
                                            @elseif($entry->result == 'loss') bg-red-100 text-red-800
                                            @elseif($entry->result == 'draw') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($entry->result) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($entry->medal != 'none')
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($entry->medal == 'gold') bg-yellow-100 text-yellow-800
                                                @elseif($entry->medal == 'silver') bg-gray-100 text-gray-800
                                                @else bg-amber-100 text-amber-800
                                                @endif">
                                                {{ ucfirst($entry->medal) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">None</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $entry->remarks ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <button type="button" 
                                                    onclick="openEditEntryModal({{ $competition->id }}, {{ $entry->id }})" 
                                                    class="text-blue-600 hover:text-blue-900 cursor-pointer">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>
                                            {{-- <form action="{{ route('competitions.destroy-entry', [$competition->id, $entry->id]) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete this entry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        No entries yet. Click "Add Entry" to add participants.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- Start of modal --}}

{{-- Add Entry Modal --}}
    <div id="addEntryModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white mb-20">
            <div class="flex items-center justify-between p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <div class="flex items-center">
                    <i class="fa-solid fa-user-plus text-white text-xl pe-2"></i>
                    <h3 class="text-xl font-bold text-white">Add Participant Entry</h3>
                </div>
                <button onclick="closeAddEntryModal()" class="text-white hover:text-gray-300">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-5">
                <form id="addEntryForm" action="{{ route('competition.entries.store', $competition->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="col-span-2 md:col-span-1 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Student <span class="text-red-500">*</span></label>
                            <select name="student_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="" disabled selected>Select student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->student_code }} - {{ $student->student_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructor <span class="text-red-500">*</span></label>
                            <select name="instructor_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="" disabled selected>Select instructor</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}">Coach {{ $instructor->fname }} {{ $instructor->lname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <input type="text" name="category" placeholder="e.g. Sparring" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Division <span class="text-red-500">*</span></label>
                            <input type="text" name="division" placeholder="e.g. Under 30" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Result <span class="text-red-500">*</span></label>
                            <select name="result" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="pending" selected>Pending</option>
                                <option value="win">Win</option>
                                <option value="loss">Loss</option>
                                <option value="draw">Draw</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medal <span class="text-red-500">*</span></label>
                            <select name="medal" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="none" selected>None</option>
                                <option value="gold">Gold</option>
                                <option value="silver">Silver</option>
                                <option value="bronze">Bronze</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea name="remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-3 border-t">
                        <button type="button" onclick="closeAddEntryModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D]">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Entry Modal --}}
    <div id="editEntryModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white mb-20">
            <div class="flex items-center justify-between p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <div class="flex items-center">
                    <i class="fa-solid fa-pen-to-square text-white text-xl pe-2"></i>
                    <h3 class="text-xl font-bold text-white">Edit Participant Entry</h3>
                </div>
                <button onclick="closeEditEntryModal()" class="text-white hover:text-gray-300">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div class="p-5">
                <form id="editEntryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="col-span-2 md:col-span-1 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Student <span class="text-red-500">*</span></label>
                            <select name="student_id" id="edit_entry_student" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->student_code }} - {{ $student->first_name }} {{ $student->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructor <span class="text-red-500">*</span></label>
                            <select name="instructor_id" id="edit_entry_instructor" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}">Coach {{ $instructor->fname }} {{ $instructor->lname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <input type="text" name="category" id="edit_entry_category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Division <span class="text-red-500">*</span></label>
                            <input type="text" name="division" id="edit_entry_division" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Result <span class="text-red-500">*</span></label>
                            <select name="result" id="edit_entry_result" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="pending">Pending</option>
                                <option value="win">Win</option>
                                <option value="loss">Loss</option>
                                <option value="draw">Draw</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medal <span class="text-red-500">*</span></label>
                            <select name="medal" id="edit_entry_medal" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="none">None</option>
                                <option value="gold">Gold</option>
                                <option value="silver">Silver</option>
                                <option value="bronze">Bronze</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                        <textarea name="remarks" id="edit_entry_remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#1C1C1D]"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-3 border-t">
                        <button type="button" onclick="closeEditEntryModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D]">Update Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{-- end of modal --}}
@endsection