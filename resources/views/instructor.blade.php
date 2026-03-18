<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instructor</title>
    @vite(['resources/css/app.css', 'resources/css/instructor.css', 'resources/css/dashboard.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
</head>
<body class="bg-gray-50">

@include('includes.navbar')
@include('includes.sidebar')

<main x-data="instructorModal()" class="ml-64 pt-4 p-6">

    <h1 class="text-3xl font-bold mb-4">Instructor</h1>

    <!-- ADD BUTTON -->
    <div class="flex justify-end mb-4">
        <button @click="openAdd()" class="bg-[#065f46] text-white px-4 py-3 rounded-xl">
            <i class="fa fa-plus mr-2"></i>Add Instructor
        </button>
    </div>

    <!-- TABLE -->
    <div class="mt-2 overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead>
            <tr>
                <th>Name</th>
                <th>Rank</th>
                <th>Role</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($instructors as $inst)
                <tr>
                    <td>{{ $inst->fname }} {{ $inst->lname }}</td>
                    <td>{{ $inst->rank_belt }}</td>
                    <td>{{ $inst->certification_level }}</td>
                    <td>{{ $inst->contact }}</td>
                    <td>
                        <span class="status {{ $inst->status }}">{{ ucfirst($inst->status) }}</span>
                    </td>
                    <td class="flex gap-2">
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
                            class="btn-edit"
                        >
                            <i class="fa fa-pen"></i>
                        </button>

                        <!-- DELETE -->
                        <form action="{{ route('instructor.delete', $inst->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- MODAL -->
    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

        <div class="modal-content relative bg-white rounded-xl shadow-lg p-6 z-10 text-base max-w-2xl w-full">
            <h2 class="text-2xl font-semibold mb-4 text-center" x-text="isEdit ? 'Edit Instructor' : 'Add Instructor'"></h2>

            <form :action="isEdit ? '/instructor/update/' + form.id : '/instructor/store'" method="POST" enctype="multipart/form-data">
                @csrf
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

</main>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
function instructorModal() {
    return {
        showModal: false,
        isEdit: false,
        form: {},
        openAdd() {
            this.isEdit = false;
            this.form = {
                id: null, fname: '', lname: '', email: '', username: '',
                contact: '', password: '', password_confirmation: '',
                rank_belt: 'Black Belt', certification_level: 'Head Instructor',
                specialization: '', bio: '', status: 'active', photo: ''
            };
            this.showModal = true;
        },
        openEdit(data) {
            this.isEdit = true;
            this.form = { ...data, password: '', password_confirmation: '' };
            this.showModal = true;
        },
        closeModal() { this.showModal = false; }
    }
}
</script>

</body>
</html>c
