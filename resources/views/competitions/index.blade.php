@extends('layouts.app')

@section('title', 'Competitions')

@section('content')
<div class="container-fluid m-0">
    <div class="row">
        <main class="ml-64 p-6">
            <div class="container-fluid">
                <!-- Breadcrumb -->
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                    <span>/</span>
                    <span class="text-[#1C1C1D] font-medium">Competitions</span>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Competitions</h1>
                    <a href="{{ route('competitions.create') }}" class="bg-[#62b236] p-3 rounded-xl hover:bg-[#6abc3a] font-medium text-white">
                        <i class="fa-solid fa-plus mr-2"></i>Add Competition
                    </a>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                            <div class="card-header pb-0 bg-transparent">
                                <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                    <h6 class="text-gray-800 font-semibold text-xl text-white text-center">List of Competitions</h6>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm">
                                            <thead>
                                                <tr class="bg-gray-50 border-b border-gray-200">
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Results</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medals</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fees</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @forelse($competitions as $competition)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                        <a href="{{ route('competitions.show', $competition->id) }}" class="text-blue-600 hover:underline">
                                                            {{ $competition->name }}
                                                        </a>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $competition->location }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $competition->date->format('M d, Y') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            @if($competition->level == 'local') bg-gray-100 text-gray-800
                                                            @elseif($competition->level == 'regional') bg-blue-100 text-blue-800
                                                            @elseif($competition->level == 'national') bg-purple-100 text-purple-800
                                                            @else bg-yellow-100 text-yellow-800
                                                            @endif">
                                                            {{ ucfirst($competition->level) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $competition->participants_count ?? 0 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $competition->results_summary }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $competition->medal_summary }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱0.00</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center gap-2">
                                                            <a href="{{ route('competitions.edit', $competition->id) }}" 
                                                               class="bg-amber-500 hover:bg-amber-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group"
                                                               title="Edit Competition">
                                                                <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                                                            </a>
                                                            <a href="{{ route('competitions.add-entry', $competition->id) }}" 
                                                               class="bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group"
                                                               title="Add Entry">
                                                                <i class="fa-solid fa-user-plus text-white text-sm"></i>
                                                            </a>
                                                            <form action="{{ route('competitions.destroy', $competition->id) }}" method="POST" class="inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center group"
                                                                        title="Delete Competition"
                                                                        onclick="return confirm('Are you sure you want to delete this competition?')">
                                                                    <i class="fa-solid fa-trash text-white text-sm"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                                        No competitions found. Click "Add Competition" to create one.
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                                        <div class="text-xs text-gray-400">
                                            Showing {{ $competitions->firstItem() ?? 0 }} to {{ $competitions->lastItem() ?? 0 }} of {{ $competitions->total() ?? 0 }} entries
                                        </div>
                                        <div class="flex gap-2">
                                            {{ $competitions->links() }}
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
@endsection