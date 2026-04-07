<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TrainNova | Instructor</title>
    @vite(['resources/css/app.css', 'resources/css/instructor.css', 'resources/css/dashboard.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
    @vite("resources/js/instructor.js")

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.css">
    <!-- Add SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">

@include('includes.navbar')
@include('includes.sidebar')

<main x-data="instructorModal()" class="ml-64 pt-6 p-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
        <span>/</span>
        <span class="text-[#1C1C1D] font-medium">Instructor</span>
    </div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-4xl font-bold mb-4 ">Instructor</h1>
    </div>
    

    <!-- TABLE -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                <div class="card-header pb-0 bg-transparent">
                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Instructor Table</h6>
                    </div>
                </div>
                <div class="" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                    <!-- ADD BUTTON -->
                    <div class="m-3 flex justify-end ">
                        <button @click="openAdd()" class="bg-green-700 text-white px-4 py-3 rounded-xl hover:bg-green-600">
                            <i class="fa fa-plus mr-2"></i>Add Instructor
                        </button>
                    </div>

                    <!-- Users Table -->
                    <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                        <table id="instructorTable" class="min-w-full divide-y divide-gray-200 p-3">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($instructors as $inst)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">{{ $inst->fname }} {{ $inst->lname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">{{ $inst->rank_belt }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">{{ $inst->certification_level }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">{{ $inst->contact }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($inst->status == 'active')
                                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Active</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <!-- EDIT -->
                                                <button
                                                    @click.prevent="openEdit({
                                                        id: '{{ $inst->id }}',
                                                        fname: '{{ $inst->fname }}',
                                                        lname: '{{ $inst->lname }}',
                                                        email: '{{ $inst->email }}',
                                                        username: '{{ $inst->username }}',
                                                        rank_belt: '{{ $inst->rank_belt }}',
                                                        certification_level: '{{ $inst->certification_level }}',
                                                        contact: '{{ $inst->contact }}',
                                                        status: '{{ $inst->status }}',
                                                        specialization: '{{ $inst->specialization }}',
                                                        bio: `{{ addslashes($inst->bio) }}`
                                                    })"
                                                    class="text-white bg-green-600 hover:bg-green-500 p-2.5 rounded-lg"
                                                >
                                                   <i class="fa-regular fa-pen-to-square"></i>
                                                </button>

                                                <!-- DELETE -->
                                                <form action="{{ route('instructor.delete', $inst->id) }}" method="POST" class="delete-form" data-instructor-name="{{ $inst->fname }} {{ $inst->lname }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="bg-red-600 hover:bg-red-500 p-2.5 rounded-lg delete-instructor-btn" data-instructor-id="{{ $inst->id }}" data-instructor-name="{{ $inst->fname }} {{ $inst->lname }}">
                                                        <i class="fa-regular fa-trash-can text-white"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="fa-solid fa-chalkboard-user text-4xl text-gray-300 mb-3"></i>
                                                <p class="text-lg font-medium">No instructors found</p>
                                                <p class="text-sm">Click the "Add Instructor" button to create a new instructor.</p>
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
    <!-- MODAL -->
    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

        <div class="modal-content relative bg-white rounded-xl shadow-lg z-10 text-base max-w-2xl w-full">
            <div class="bg-[#1C1C1D] rounded-t-xl ps-5 py-3 flex">
                <i class="fa fa-plus mr-2 text-white text-xl font-bold mt-2"></i>
                <h2 class="text-2xl text-white font-semibold " x-text="isEdit ? 'Edit Instructor' : 'Add Instructor'"></h2>
            </div>
            <div class="p-6">
                <form :action="isEdit ? '/instructor/update/' + form.id : '/instructor/store'" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="branch">Branch</label>
                    <div>
                        <select name="branch_id" id="branch" class="rounded-md mb-3 py-2" style="border: solid 1px black; width: 100%;">
                            <option value="" disabled selected>-- Select a Branch --</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->code.' '.$branch->name.' '.$branch->city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm">First Name</label>
                            <input type="text" name="fname" x-model="form.fname" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-sm">Last Name</label>
                            <input type="text" name="lname" x-model="form.lname" class="w-full border rounded p-2">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm">Profile</label>
                        <input type="file" name="photo" class="w-full border rounded p-2 text-sm">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm">Username</label>
                        <input type="text" name="username" x-model="form.username" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm">Email</label>
                        <input type="email" name="email" x-model="form.email" class="w-full border rounded p-2">
                    </div>

                    <!-- PASSWORD -->
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <!-- Password -->
                        <div x-data="{ show: false }" class="relative">
                            <label class="block text-sm">Password</label>
                            <input
                                :type="show ? 'text' : 'password'"
                                name="password"
                                class="w-full border rounded p-2 pr-10"
                            >
                            <button type="button" @click="show = !show"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 mt-2">
                                <template x-if="!show">
                                    <!-- Eye Closed Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.191.194-2.337.55-3.422m2.816-3.164A9.97 9.97 0 0112 5c5.523 0 10 4.477 10 10 0 1.191-.194 2.337-.55 3.422m-2.816 3.164M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </template>
                                <template x-if="show">
                                    <!-- Eye Open Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </template>
                            </button>
                        </div>

                        <!-- Confirm Password -->
                        <div x-data="{ show: false }" class="relative">
                            <label class="block text-sm">Confirm</label>
                            <input
                                :type="show ? 'text' : 'password'"
                                name="password_confirmation"
                                class="w-full border rounded p-2 pr-10"
                            >
                            <button type="button" @click="show = !show"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 mt-2">
                                <template x-if="!show">
                                    <!-- Eye Closed Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.191.194-2.337.55-3.422m2.816-3.164A9.97 9.97 0 0112 5c5.523 0 10 4.477 10 10 0 1.191-.194 2.337-.55 3.422m-2.816 3.164M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </template>
                                <template x-if="show">
                                    <!-- Eye Open Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </template>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm">Contact</label>
                        <input type="text" name="contact" x-model="form.contact" class="w-full border rounded p-2">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm">Rank</label>
                            <select name="rank_belt" x-model="form.rank_belt" class="w-full border rounded p-2">
                                <option>Black Belt</option>
                                <option>Red Belt</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm">Certification Level</label>
                            <select name="certification_level" x-model="form.certification_level" class="w-full border rounded p-2">
                                <option>Head Instructor</option>
                                <option>Assistant Instructor</option>
                                <option>Senior Instructor</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm">Specialization</label>
                        <input type="text" name="specialization" x-model="form.specialization" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm">Bio</label>
                        <textarea name="bio" x-model="form.bio" class="w-full border rounded p-2 text-sm" rows="3"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm">Status</label>
                        <select name="status" x-model="form.status" class="w-full border rounded p-2">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 bg-gray-300 rounded text-sm">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#065f46] text-white rounded text-sm">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</main>

<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>

    @vite("resources/js/navbarDrop.js")
</body>
</html>