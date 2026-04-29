@extends('layouts.app')

@section('title', 'TKD | Skill Checklist')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-500 mt-1 mb-6">
                <a href="{{ route('dashboard.index') }}" class="hover:text-gray-700">Dashboard</a>
                <span>/</span>
                <span class="text-gray-500">Settings</span>
                <span>/</span>
                <span class="text-gray-700 font-medium">Skill Checklist</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-800">Skill Checklist</h1>
            <p class="text-gray-500 text-sm mt-1">Manage skills per belt level for student evaluations</p>
        </div>
        <button onclick="openAddModal()"
            class="bg-[#1c1c1d] hover:bg-[#3d3d3f] cursor-pointer text-white px-5 py-2.5 rounded-lg transition-colors duration-200 flex items-center gap-2 shadow-sm">
            <i class="fas fa-plus"></i>
            <span>Add Skill</span>
        </button>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#1c1c1d] rounded-xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-300 text-sm font-medium mb-1">Total Skills</p>
                    <h2 class="text-3xl font-bold">{{ $skills->count() }}</h2>
                </div>
                <i class="fas fa-list-check text-3xl text-gray-400"></i>
            </div>
        </div>
        <div class="bg-[#1c1c1d] rounded-xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-300 text-sm font-medium mb-1">Belt Levels</p>
                    <h2 class="text-3xl font-bold">{{ $beltLevels->count() }}</h2>
                </div>
                <i class="fas fa-belt text-3xl text-gray-400"></i>
            </div>
        </div>
        <div class="bg-[#1c1c1d] rounded-xl shadow-lg p-5 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-300 text-sm font-medium mb-1">Avg Skills per Belt</p>
                    <h2 class="text-3xl font-bold">
                        {{ $beltLevels->count() > 0 ? round($skills->count() / $beltLevels->count(), 1) : 0 }}
                    </h2>
                </div>
                <i class="fas fa-chart-bar text-3xl text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Belt Filter Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        <button onclick="filterBelt('all')" id="tab-all"
            class="belt-tab active-tab px-4 py-2 rounded-full text-sm font-semibold border transition-colors duration-200 cursor-pointer">
            All Belts
        </button>
        @foreach($beltLevels as $belt)
            <button onclick="filterBelt('{{ $belt->name }}')" id="tab-{{ Str::slug($belt->name) }}"
                class="belt-tab px-4 py-2 rounded-full text-sm font-semibold border transition-colors duration-200 cursor-pointer"
                style="border-color: {{ $belt->color_code }}; color: {{ $belt->color_code === '#FFFFFF' || $belt->color_code === '#FFFF00' || $belt->color_code === '#FFFACD' || $belt->color_code === '#ADFF2F' ? '#374151' : $belt->color_code }}">
                {{ $belt->name }}
            </button>
        @endforeach
    </div>

    <!-- Skills Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-600">#</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Belt Level</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Skill Name</th>
                    <th class="px-6 py-4 font-semibold text-gray-600">Description</th>
                    <th class="px-6 py-4 font-semibold text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="skillsTableBody">
                @forelse($skills as $index => $skill)
                    <tr class="skill-row border-b border-gray-100 hover:bg-gray-50 transition-colors"
                        data-belt="{{ $skill->belt_level }}">
                        <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            @php
                                $belt = $beltLevels->firstWhere('name', $skill->belt_level);
                                $color = $belt->color_code ?? '#cccccc';
                                $isDark = !in_array($color, ['#FFFFFF', '#FFFF00', '#FFFACD', '#ADFF2F']);
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                style="background-color: {{ $color }}; color: {{ $isDark ? '#ffffff' : '#374151' }}">
                                {{ $skill->belt_level }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $skill->skill_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $skill->description ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="openEditModal({{ $skill->id }}, '{{ $skill->belt_level }}', '{{ addslashes($skill->skill_name) }}', '{{ addslashes($skill->description ?? '') }}')"
                                    class="px-3 py-1.5 text-xs text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors cursor-pointer flex items-center gap-1">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteSkill({{ $skill->id }}, '{{ addslashes($skill->skill_name) }}')"
                                    class="px-3 py-1.5 text-xs text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors cursor-pointer flex items-center gap-1">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i class="fas fa-list-check text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 font-medium">No skills found</p>
                            <p class="text-gray-400 text-sm">Click "Add Skill" to get started</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto border w-full max-w-lg shadow-2xl rounded-xl bg-white">
        <div class="flex items-center justify-between p-4 border-b bg-[#1c1c1d] rounded-t-xl">
            <div class="flex items-center gap-2">
                <i class="fas fa-plus text-white"></i>
                <h3 class="text-lg font-bold text-white">Add New Skill</h3>
            </div>
            <button onclick="closeAddModal()" class="text-white hover:text-gray-300 cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('settings.skill-checklist.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Belt Level <span class="text-red-500">*</span></label>
                        <select name="belt_level" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800">
                            <option value="">Select Belt Level</option>
                            @foreach($beltLevels as $belt)
                                <option value="{{ $belt->name }}">{{ $belt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skill Name <span class="text-red-500">*</span></label>
                        <input type="text" name="skill_name" required maxlength="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="e.g., Front Kick">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-gray-400 text-xs">(optional)</span></label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="Brief description of this skill..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#1c1c1d] text-white rounded-lg hover:bg-[#3d3d3f] transition-colors cursor-pointer">
                        Add Skill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto border w-full max-w-lg shadow-2xl rounded-xl bg-white">
        <div class="flex items-center justify-between p-4 border-b bg-green-600 rounded-t-xl">
            <div class="flex items-center gap-2">
                <i class="fas fa-edit text-white"></i>
                <h3 class="text-lg font-bold text-white">Edit Skill</h3>
            </div>
            <button onclick="closeEditModal()" class="text-white hover:text-gray-300 cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Belt Level <span class="text-red-500">*</span></label>
                        <select name="belt_level" id="edit_belt_level" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800">
                            <option value="">Select Belt Level</option>
                            @foreach($beltLevels as $belt)
                                <option value="{{ $belt->name }}">{{ $belt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skill Name <span class="text-red-500">*</span></label>
                        <input type="text" name="skill_name" id="edit_skill_name" required maxlength="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-gray-400 text-xs">(optional)</span></label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
                        Update Skill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Filter by belt ─────────────────────────────────────────
function filterBelt(belt) {
    const rows = document.querySelectorAll('.skill-row');
    const tabs = document.querySelectorAll('.belt-tab');

    tabs.forEach(tab => {
        tab.classList.remove('active-tab', 'bg-gray-800', 'text-white', 'border-gray-800');
    });

    const activeTab = document.getElementById(belt === 'all' ? 'tab-all' : `tab-${belt.toLowerCase().replace(' ', '-')}`);
    if (activeTab) {
        activeTab.classList.add('bg-gray-800', 'text-white', 'border-gray-800');
    }

    rows.forEach(row => {
        if (belt === 'all' || row.dataset.belt === belt) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// ── Add Modal ──────────────────────────────────────────────
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// ── Edit Modal ─────────────────────────────────────────────
function openEditModal(id, beltLevel, skillName, description) {
    document.getElementById('editForm').action = `/settings/skill-checklist/${id}`;
    document.getElementById('edit_belt_level').value = beltLevel;
    document.getElementById('edit_skill_name').value = skillName;
    document.getElementById('edit_description').value = description;
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// ── Delete ─────────────────────────────────────────────────
function deleteSkill(id, skillName) {
    Swal.fire({
        title: 'Delete Skill?',
        html: `Are you sure you want to delete <strong>${skillName}</strong>?<br>This cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/settings/skill-checklist/${id}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Close on outside click
window.onclick = function(event) {
    if (event.target === document.getElementById('addModal')) closeAddModal();
    if (event.target === document.getElementById('editModal')) closeEditModal();
}

// Init filter
filterBelt('all');
</script>

@push('styles')
<style>
.active-tab {
    background-color: #1c1c1e;
    color: white;
    border-color: #1c1c1e;
}
</style>
@endpush
@endpush
@endsection