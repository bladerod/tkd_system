{{-- resources/views/settings/rolespermission.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TrainNova | Roles & Permissions</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css', 'resources/css/rolepermission.css'])
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    @include("includes.navbar")
    @include('includes.sidebar')

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
                        <span class="text-[#1C1C1D] font-medium">Roles & Permissions</span>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-4xl font-bold text-[#1C1C1D]">Roles & Permissions</h1>
                        <button onclick="resetPermissions('{{ route('settings.roles-permissions.reset') }}')" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i class="fas fa-undo-alt"></i>
                            Reset Staff Permissions
                        </button>
                    </div>

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
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Permissions Matrix</h6>
                                    </div>
                                </div>
                                <div class="p-8" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <!-- Info Box -->
                                    <div class="bg-gray-300 border border-[#1C1C1D] rounded-lg p-4 mb-6">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-info-circle text-[#1C1C1D] mt-0.5"></i>
                                            <div class="text-sm text-[#1C1C1D]">
                                                <p class="font-medium mb-1">Permission Notes:</p>
                                                <ul class="list-disc list-inside space-y-1 text-[#1C1C1D]">
                                                    <li><strong>Admin</strong> has full access to all modules by default (cannot be changed)</li>
                                                    <li><strong>Staff</strong> permissions can be customized below</li>
                                                    <li>Changes take effect immediately after saving</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Quick Select Actions -->
                                        <div class="mt-4 mb-6 flex items-center gap-4 flex-wrap">
                                            <span class="text-sm text-gray-600 font-medium">Quick Actions:</span>
                                            {{-- <button type="button" onclick="selectAllStaff()" class="text-sm text-blue-600 hover:text-blue-800">
                                                Select All Staff Permissions
                                            </button> --}}
                                            <span class="text-gray-300">|</span>
                                            <button type="button" onclick="deselectAllStaff()" class="text-sm text-blue-600 hover:text-blue-800">
                                                Deselect All Staff Permissions
                                            </button>
                                            <span class="text-gray-300">|</span>
                                            <button type="button" onclick="setStaffReadOnly()" class="text-sm text-blue-600 hover:text-blue-800">
                                                Set Staff to Read Only (View Only)
                                            </button>
                                            <span class="text-gray-300">|</span>
                                            <button type="button" onclick="setStaffFullAccess()" class="text-sm text-orange-600 hover:text-orange-800">
                                                Set Staff to Full Access (Not Recommended)
                                            </button>
                                        </div>
                                    <form id="permissionsForm" method="POST" action="{{ route('settings.roles-permissions.update') }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="overflow-x-auto">
                                            <table class="min-w-full bg-white border border-gray-200">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 w-48">Module</th>
                                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Staff</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($modules as $module)
                                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                            <td class="px-4 py-3 font-medium capitalize">
                                                                {{ ucfirst($module) }}
                                                            </td>
                                                            
                                                            {{-- <!-- Admin Column - Full Access (Read-only) -->
                                                            <td class="px-4 py-3">
                                                                <div class="text-green-600">
                                                                    <i class="fas fa-check-circle"></i> Full Access
                                                                    <div class="text-xs text-gray-500 mt-1">View, Create, Edit, Delete</div>
                                                                </div>
                                                                <input type="hidden" name="permissions[admin][{{ $module }}][can_view]" value="1">
                                                                <input type="hidden" name="permissions[admin][{{ $module }}][can_create]" value="1">
                                                                <input type="hidden" name="permissions[admin][{{ $module }}][can_edit]" value="1">
                                                                <input type="hidden" name="permissions[admin][{{ $module }}][can_delete]" value="1">
                                                            </td> --}}
                                                            
                                                            <!-- Staff Column - Editable -->
                                                            <td class="px-4 py-3">
                                                                <div class="flex flex-wrap gap-4">
                                                                    <label class="flex items-center gap-1 text-sm">
                                                                        <input type="checkbox" 
                                                                               name="permissions[staff][{{ $module }}][can_view]"
                                                                               value="1"
                                                                               class="rounded text-[#1C1C1D] focus:ring-[#1C1C1D]"
                                                                               {{ isset($permissions['staff'][$module]['can_view']) && $permissions['staff'][$module]['can_view'] ? 'checked' : '' }}>
                                                                        <span class="text-gray-700">View</span>
                                                                    </label>
                                                                    <label class="flex items-center gap-1 text-sm">
                                                                        <input type="checkbox" 
                                                                               name="permissions[staff][{{ $module }}][can_create]"
                                                                               value="1"
                                                                               class="rounded text-[#1C1C1D] focus:ring-[#1C1C1D]"
                                                                               {{ isset($permissions['staff'][$module]['can_create']) && $permissions['staff'][$module]['can_create'] ? 'checked' : '' }}>
                                                                        <span class="text-gray-700">Create</span>
                                                                    </label>
                                                                    <label class="flex items-center gap-1 text-sm">
                                                                        <input type="checkbox" 
                                                                               name="permissions[staff][{{ $module }}][can_edit]"
                                                                               value="1"
                                                                               class="rounded text-[#1C1C1D] focus:ring-[#1C1C1D]"
                                                                               {{ isset($permissions['staff'][$module]['can_edit']) && $permissions['staff'][$module]['can_edit'] ? 'checked' : '' }}>
                                                                        <span class="text-gray-700">Edit</span>
                                                                    </label>
                                                                    <label class="flex items-center gap-1 text-sm">
                                                                        <input type="checkbox" 
                                                                               name="permissions[staff][{{ $module }}][can_delete]"
                                                                               value="1"
                                                                               class="rounded text-[#1C1C1D] focus:ring-[#1C1C1D]"
                                                                               {{ isset($permissions['staff'][$module]['can_delete']) && $permissions['staff'][$module]['can_delete'] ? 'checked' : '' }}>
                                                                        <span class="text-gray-700">Delete</span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="flex items-center justify-end gap-3 pt-6 border-t">
                                            <button type="submit" 
                                                    class="px-8 py-2 text-sm text-white bg-[#1C1C1D] rounded-md hover:bg-[#2f2f2f] transition-colors">
                                                Save Permissions
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/app.js', 'resources/js/navbarDrop.js', 'resources/js/rolepermission.js'])
</body>
</html>