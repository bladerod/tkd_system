<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/user.css'])
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
                        <span class="text-[#1C1C1D] font-medium">Settings</span>
                        <span>/</span>
                        <span class="text-[#1C1C1D] font-medium">User Management</span>
                    </div>
                    
                    <!-- Header with Title and Add Button -->
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-4xl font-bold text-[#1C1C1D]">User Management</h1>
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
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">User Table</h6>
                                    </div>
                                </div>
                                <div class="p-6" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <div class="mb-3 flex justify-end">
                                        <button onclick="openAddUserModal()" class="bg-[#1c1c1d] text-white px-4 py-2 rounded-lg hover:bg-[#2f2f2f] transition-colors flex items-center gap-2">
                                            <i class="fa-solid fa-plus"></i>
                                            Add User
                                        </button>
                                    </div>

                                    <!-- Users Table -->
                                    <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                                        <table id="userTable" class="min-w-full divide-y divide-gray-200 p-3">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse($users as $user)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($user->photo_url)
                                                            <img src="{{ asset('storage/'.$user->photo_url) }}" 
                                                                alt="Profile" 
                                                                style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                                        @else
                                                            <div style="width: 40px; height: 40px; background-color: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                                <i class="fas fa-user text-gray-400"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm">{{ ucfirst(trim(($user->fname ?? ''))) . ' ' . trim(($user->lname ?? ''))}}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-700">{{ $user->branch->name ?? 'No Branch' }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-700">{{ ucfirst($user->role) }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-700">{{ $user->email }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-sm text-gray-700">{{ $user->mobile }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center gap-3">
                                                            <button class="text-white bg-green-500 hover:bg-green-600 p-2.5 rounded-lg edit-btn" 
                                                                    data-user-id="{{ $user->id }}">
                                                                <i class="fa-regular fa-pen-to-square"></i>
                                                                <span class="hidden debug-id">{{ $user->id }}</span>
                                                            </button>
                                                            <button class="bg-red-500 hover:bg-red-600 p-2.5 rounded-lg delete-user-btn" 
                                                                    data-user-id="{{ $user->id }}"
                                                                    data-user-name="{{ $user->fname }} {{ $user->lname }}"> 
                                                                <i class="fa-regular fa-trash-can text-white"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                                        <div class="flex flex-col items-center">
                                                            <i class="fa-solid fa-users text-4xl text-gray-300 mb-3"></i>
                                                            <p class="text-lg font-medium">No users found</p>
                                                            <p class="text-sm">Click the "Add User" button to create a new user.</p>
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

    {{-- Add User Modal --}}
    <div id="addUserModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-solid fa-plus text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Add User</h3>
            </div>
            <div class="p-4">
                <!-- Modal Body - Form -->
                <form id="addUserForm" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <!-- branch_id - Dropdown -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Branch <span class="text-[#FF0000]">*</span></label>
                            <select name="branch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="">Select Branch</option>
                                @if(isset($branches) && $branches->count() > 0)
                                    @foreach($branches as $branch)
                                        @php
                                            // Ensure branch is an object and has required properties
                                            $branchId = is_object($branch) ? $branch->id : ($branch['id'] ?? null);
                                            $branchName = is_object($branch) ? $branch->name : ($branch['name'] ?? 'Unknown Branch');
                                        @endphp
                                        @if($branchId)
                                            <option value="{{ $branchId }}">{{ $branchName }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="" disabled class="text-red-500">No branches available. Please add a branch first.</option>
                                @endif
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_branch_id"></div>
                        </div>

                        <!-- role -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-[#FF0000]">*</span></label>
                            <select name="role" id="add_role" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] @error('role') border-red-500 @enderror">
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="instructor" {{ old('role') == 'instructor' ? 'selected' : '' }}>Instructor</option>
                                <option value="parent" {{ old('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_role"></div>
                        </div>

                        <!-- fname -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="fname" 
                                id="add_fname" 
                                value="{{ old('fname') }}"
                                required 
                                minlength="2"
                                maxlength="50"
                                pattern="[A-Za-z\s\-]+"
                                title="Only letters, spaces, and hyphens allowed"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] @error('fname') border-red-500 @enderror">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_fname"></div>
                            <div class="validation-hint text-gray-500 text-xs mt-1 hidden">Minimum 2 characters, letters only</div>
                        </div>

                        <!-- lname -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="lname" 
                                id="add_lname" 
                                value="{{ old('lname') }}"
                                required 
                                minlength="2"
                                maxlength="50"
                                pattern="[A-Za-z\s\-]+"
                                title="Only letters, spaces, and hyphens allowed"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] @error('lname') border-red-500 @enderror">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_lname"></div>
                            <div class="validation-hint text-gray-500 text-xs mt-1 hidden">Minimum 2 characters, letters only</div>
                        </div>

                        <!-- email -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-[#FF0000]">*</span></label>
                            <input type="email" 
                                name="email" 
                                id="add_email" 
                                value="{{ old('email') }}"
                                required 
                                maxlength="100"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                title="Please enter a valid email address"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] @error('email') border-red-500 @enderror">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_email"></div>
                        </div>

                        <!-- username -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="username" 
                                id="add_username" 
                                value="{{ old('username') }}"
                                required 
                                minlength="3"
                                maxlength="50"
                                pattern="[a-zA-Z0-9_]+"
                                title="Only letters, numbers, and underscores allowed"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] @error('username') border-red-500 @enderror">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_username"></div>
                            <div class="validation-hint text-gray-500 text-xs mt-1 hidden">Minimum 3 characters, letters, numbers, underscores only</div>
                        </div>

                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number <span class="text-[#FF0000]">*</span></label>
                            <div class="relative">
                                <input type="tel" 
                                    name="mobile" 
                                    id="add_mobile" 
                                    value="{{ old('mobile') }}"
                                    required 
                                    placeholder="09XXXXXXXXX"
                                    maxlength="13"
                                    minlength="11"
                                    pattern="^(09|\+639)\d{9}$"
                                    title="Please enter a valid Philippine mobile number (11-13 digits starting with 09 or +639)"
                                    class="w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] @error('mobile') border-red-500 @enderror">
                            </div>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_mobile_no"></div>
                            <div class="validation-hint text-gray-500 text-xs mt-1 hidden">Format: 09XXXXXXXXX or +639XXXXXXXXX (11-13 digits)</div>
                        </div>

                        <!-- password with toggle -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-[#FF0000]">*</span></label>
                            <div class="relative">
                                <input type="password" 
                                    name="password" 
                                    id="add_password"
                                    required 
                                    minlength="6"
                                    maxlength="100"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] pr-10 @error('password') border-red-500 @enderror">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 focus:outline-none toggle-password"
                                        data-target="add_password">
                                    <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        <path class="eye-closed hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_password"></div>
                            <div class="password-strength mt-2 hidden">
                                <div class="flex items-center gap-2">
                                    <div class="strength-bar h-1 flex-1 bg-gray-200 rounded">
                                        <div class="strength-progress h-1 rounded transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <span class="strength-text text-xs text-gray-500"></span>
                                </div>
                            </div>
                            <div class="validation-hint text-gray-500 text-xs mt-1">Minimum 6 characters with at least 1 letter and 1 number</div>
                        </div>

                        <!-- photo_url -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                            <input type="file" 
                                name="photo_url" 
                                id="add_photo" 
                                accept="image/jpeg,image/png,image/jpg"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] @error('photo_url') border-red-500 @enderror">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_photo"></div>
                            <div class="validation-hint text-gray-500 text-xs mt-1">PNG, JPEG, JPG only (max 2MB)</div>
                            <div id="image-preview" class="mt-2 hidden">
                                <img src="" alt="Preview" class="h-20 w-20 object-cover rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitAddBtn" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">
                            Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div id="editUserModal" class="fixed inset-0 bg-black/40 overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
        <div class="relative top-20 mx-auto border w-[600px] shadow-lg rounded-xl bg-white">
            <!-- Modal Header -->
            <div class="flex items-center p-3 border-b bg-[#1C1C1D] rounded-t-lg">
                <i class="fa-regular fa-pen-to-square text-white text-xl pe-1"></i>
                <h3 class="text-2xl font-bold text-[#ffffff]">Edit User</h3>
            </div>
            <div class="p-4">
                <!-- Modal Body - Form -->
                <form id="editUserForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- branch_id - Dropdown -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Branch <span class="text-[#FF0000]">*</span></label>
                            <select name="branch_id" id="edit_branch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="">Select Branch</option>
                                @foreach($branches ?? [] as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_branch_id"></div>
                        </div>

                        <!-- role -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-[#FF0000]">*</span></label>
                            <select name="role" id="edit_role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="instructor">Instructor</option>
                                <option value="parent">Parent</option>
                            </select>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_role"></div>
                        </div>

                        <!-- fname -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="fname" 
                                id="edit_fname" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_fname"></div>
                        </div>

                        <!-- lname -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="lname" 
                                id="edit_lname" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_lname"></div>
                        </div>

                        <!-- email -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-[#FF0000]">*</span></label>
                            <input type="email" 
                                name="email" 
                                id="edit_email" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_email"></div>
                        </div>
                        
                        <!-- username -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username <span class="text-[#FF0000]">*</span></label>
                            <input type="text" 
                                name="username" 
                                id="edit_username" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_username"></div>
                        </div>

                        <!-- mobile -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number <span class="text-[#FF0000]">*</span></label>
                            <input type="tel" 
                                name="mobile" 
                                id="edit_mobile" 
                                required 
                                placeholder="09XXXXXXXXX" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_mobile"></div>
                        </div>

                        <!-- password -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password (Leave blank to keep current)</label>
                            <div class="relative">
                                <input type="password" 
                                    name="password" 
                                    id="edit_password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D] pr-10">
                                <button type="button" 
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 focus:outline-none toggle-password"
                                        data-target="edit_password">
                                    <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        <path class="eye-closed hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_password"></div>
                        </div>

                        <!-- photo_url -->
                        <div class="col-span-2 form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Profile Photo</label>
                            <input type="file" 
                                name="photo_url" 
                                id="edit_photo" 
                                accept="image/jpeg,image/png,image/jpg" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1C1C1D]">
                            <div class="error-message text-red-500 text-xs mt-1 hidden" id="error_edit_photo"></div>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPEG, JPG only (max 2MB)</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6 pt-3 border-t">
                        <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="submitEditBtn" class="px-4 py-2 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] transition-colors">
                            Update User
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
    @vite(['resources/js/user.js', 'resources/js/dashboard.js'])
    {{-- <script src="{{ asset('js/user.js') }}"></script> --}}
    <!-- Pass CSRF token to JavaScript -->
    <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>
</body>
</html>