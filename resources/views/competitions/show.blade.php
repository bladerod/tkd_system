@extends('layouts.app')

@section('title', $competition->name)

@section('content')
<div class="container-fluid m-0">
    <div class="row">
        <main class="ml-64 p-6">
            <div class="container-fluid">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('competitions.index') }}" class="hover:text-[#1C1C1D]">Competitions</a>
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
                        <a href="{{ route('competitions.add-entry', $competition->id) }}" 
                           class="bg-[#62b236] hover:bg-[#6abc3a] p-3 rounded-xl font-medium text-white">
                            <i class="fa-solid fa-plus mr-2"></i>Add Entry
                        </a>
                        <a href="{{ route('competitions.edit', $competition->id) }}" 
                           class="bg-amber-500 hover:bg-amber-600 p-3 rounded-xl font-medium text-white">
                            <i class="fa-solid fa-pen-to-square mr-2"></i>Edit
                        </a>
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
                                        {{ $entry->student->first_name ?? 'N/A' }} {{ $entry->student->last_name ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $entry->instructor->user->fname ?? 'N/A' }} {{ $entry->instructor->user->lname ?? '' }}
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
                                            <a href="{{ route('competitions.edit-entry', [$competition->id, $entry->id]) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form action="{{ route('competitions.destroy-entry', [$competition->id, $entry->id]) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete this entry?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
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
@endsection