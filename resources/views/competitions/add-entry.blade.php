@extends('layouts.app')

@section('title', 'Add Entry - ' . $competition->name)

@section('content')
<div class="container-fluid m-0">
    <div class="row">
        <main class="">
            <div class="container-fluid">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('competition.index') }}" class="hover:text-[#1C1C1D]">Competitions</a>
                    <span>/</span>
                    <a href="{{ route('competition.show', $competition->id) }}" class="hover:text-[#1C1C1D]">{{ $competition->name }}</a>
                    <span>/</span>
                    <span class="text-[#1C1C1D] font-medium">Add Entry</span>
                </div>

                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-[#1C1C1D]">Add Participant Entry</h1>
                    <p class="text-gray-500 mt-1">Adding a student to {{ $competition->name }}</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 max-w-8xl">
                    <form action="{{ route('competition.entries.store', $competition->id) }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Student <span class="text-red-500">*</span></label>
                                <select name="student_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                    <option value="" disabled selected>Select a student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->student_code }} - {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Coach/Instructor <span class="text-red-500">*</span></label>
                                <select name="instructor_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                    <option value="" disabled selected>Select an instructor</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                            Coach {{ $instructor->fname }} {{ $instructor->lname }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                                <input type="text" name="category" value="{{ old('category') }}" placeholder="e.g. Sparring, Poomsae" required 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Division <span class="text-red-500">*</span></label>
                                <input type="text" name="division" value="{{ old('division') }}" placeholder="e.g. Finweight, Under 30" required 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                @error('division') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Result <span class="text-red-500">*</span></label>
                                <select name="result" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                    <option value="pending" {{ old('result') == 'pending' ? 'selected' : '' }}>Pending (Upcoming)</option>
                                    <option value="win" {{ old('result') == 'win' ? 'selected' : '' }}>Win</option>
                                    <option value="loss" {{ old('result') == 'loss' ? 'selected' : '' }}>Loss</option>
                                    <option value="draw" {{ old('result') == 'draw' ? 'selected' : '' }}>Draw</option>
                                </select>
                                @error('result') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Medal <span class="text-red-500">*</span></label>
                                <select name="medal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">
                                    <option value="none" {{ old('medal') == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="gold" {{ old('medal') == 'gold' ? 'selected' : '' }}>Gold</option>
                                    <option value="silver" {{ old('medal') == 'silver' ? 'selected' : '' }}>Silver</option>
                                    <option value="bronze" {{ old('medal') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                                </select>
                                @error('medal') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Remarks / Notes</label>
                            <textarea name="remarks" rows="3" placeholder="Any additional notes about this performance..." 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1C1C1D]">{{ old('remarks') }}</textarea>
                            @error('remarks') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('competition.show', $competition->id) }}" 
                               class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] font-medium transition-colors">
                                Save Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection