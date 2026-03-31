<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrainNova</title>
    @vite(['resources/css/app.css', 'resources/css/competition.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
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
                        <span class="text-[#1C1C1D] font-medium">Competitions</span>
                    </div>
                    
                    <!-- Header with Title and Add Button -->
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-4xl font-bold text-[#1C1C1D]">Competitions</h1>
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
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">List of Competitions</h6>
                                    </div>
                                </div>
                                <div style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <div class="m-4 flex justify-end">
                                        <button onclick="addCompetitionModal()" class="bg-[#1c1c1d] text-white px-4 py-2 rounded-lg hover:bg-[#2f2f2f] transition-colors flex items-center gap-2">
                                            <i class="fa-solid fa-plus"></i>
                                            Add Competition
                                        </button>
                                    </div>

                                    <!-- Competitions Table -->
                                    <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                                        <table id="competitionTable" class="min-w-full divide-y divide-gray-200 p-3">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Event Name</th>
                                                    <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Location</th>
                                                    <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Organizer</th>
                                                    <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Level</th>
                                                    <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse($competition as $competitions)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $competitions->name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-700">{{ $competitions->location }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-700">{{ $competitions->date->format('M d, Y') }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-700">{{ $competitions->organizer }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full ">
                                                            {{ ucfirst($competitions->level) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center gap-3">
                                                            <!-- View button (eye icon) -->
                                                            <button class="text-white bg-blue-500 hover:bg-blue-600 p-2.5 rounded-lg view-btn" 
                                                                    data-competition-id="{{ $competitions->id }}">
                                                                <i class="fa-regular fa-eye"></i>
                                                            </button>
                                                            <!-- Edit button (pen icon) -->
                                                            <button class="text-white bg-green-500 hover:bg-green-600 p-2.5 rounded-lg edit-btn" 
                                                                    data-competition-id="{{ $competitions->id }}">
                                                                <i class="fa-regular fa-pen-to-square"></i>
                                                            </button>
                                                            <!-- Delete button -->
                                                            <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg delete-competition-btn" 
                                                                    data-competition-id="{{ $competitions->id }}"
                                                                    data-competition-name="{{ $competitions->name }}"> 
                                                                <i class="fa-regular fa-trash-can text-white"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                                        <div class="flex flex-col items-center">
                                                            <i class="fa-solid fa-trophy text-4xl text-gray-300 mb-3"></i>
                                                            <p class="text-lg font-medium">No competitions found</p>
                                                            <p class="text-sm">Click the "Add Competition" button to create a new competition.</p>
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
            </main>
        </div>
    </div>

    {{-- Add Competition Modal --}}
    <div id="addCompetitionModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-solid fa-plus text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Add Competition</h3>
            </div>
            <div class="p-4">
                <!-- Modal Body - Form -->
                <form id="addCompetitionForm" method="POST" action="{{ route('competition.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Competition Name -->
                        <div class=" form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Competition Name <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="name" 
                                id="add_name"
                                value="{{ old('name') }}"
                                required 
                                minlength="2"
                                maxlength="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] @error('name') border-red-500 @enderror">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_name"></div>
                        </div>

                        <!-- Location -->
                        <div class=" form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="location" 
                                id="add_location"
                                value="{{ old('location') }}"
                                required 
                                minlength="2"
                                maxlength="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_location"></div>
                        </div>

                        <!-- Date -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-[#FF0000]">*</span></label>
                            <input type="date" 
                                name="date" 
                                id="add_date"
                                value="{{ old('date') }}"
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_date"></div>
                        </div>

                        <!-- Organizer -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organizer <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="organizer" 
                                id="add_organizer"
                                value="{{ old('organizer') }}"
                                required 
                                minlength="2"
                                maxlength="100"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_organizer"></div>
                        </div>

                        <!-- Level -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Level <span class="text-[#FF0000]">*</span></label>
                            <select name="level" id="add_level" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="" selected disabled>Select Level</option>
                                <option value="international" {{ old('level') == 'international' ? 'selected' : '' }}>International</option>
                                <option value="national" {{ old('level') == 'national' ? 'selected' : '' }}>National</option>
                                <option value="regional" {{ old('level') == 'regional' ? 'selected' : '' }}>Regional</option>
                                <option value="local" {{ old('level') == 'local' ? 'selected' : '' }}>Local</option>
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_level"></div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeAddCompetitionModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitAddBtn" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">
                            Add Competition
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Competition Modal --}}
    <div id="editCompetitionModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-regular fa-pen-to-square text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Edit Competition</h3>
            </div>
            <div class="p-4">
                <!-- Modal Body - Form -->
                <form id="editCompetitionForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Competition Name -->
                        <div class=" form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Competition Name <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="name" 
                                id="edit_name" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_name"></div>
                        </div>

                        <!-- Location -->
                        <div class=" form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="location" 
                                id="edit_location" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_location"></div>
                        </div>

                        <!-- Date -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-[#FF0000]">*</span></label>
                            <input type="date" 
                                name="date" 
                                id="edit_date" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_date"></div>
                        </div>

                        <!-- Organizer -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Organizer <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="organizer" 
                                id="edit_organizer" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_organizer"></div>
                        </div>

                        <!-- Level -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Level <span class="text-[#FF0000]">*</span></label>
                            <select name="level" id="edit_level" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="" disabled>Select Level</option>
                                <option value="international">International</option>
                                <option value="national">National</option>
                                <option value="regional">Regional</option>
                                <option value="local">Local</option>
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_level"></div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeEditCompetitionModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitEditBtn" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">
                            Update Competition
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/competition.js', 'resources/js/navbarDrop.js'])

</body>
</html>