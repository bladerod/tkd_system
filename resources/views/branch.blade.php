<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Branch</title>
    @vite(['resources/css/app.css', 'resources/css/instructor.css', 'resources/css/dashboard.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
</head>
<body class="bg-gray-50">

@include('includes.navbar')
@include('includes.sidebar')

<main x-data="branchModal()" class="ml-64 pt-4 p-6">

    <h1 class="text-3xl font-bold mb-4">Branch</h1>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- SEARCH + FILTER -->
    <form method="GET" class="flex gap-3 mb-4">
        <input type="text" name="search" placeholder="Search name/code"
            value="{{ request('search') }}"
            class="border p-2 rounded w-64">

        <input type="text" name="city" placeholder="City"
            value="{{ request('city') }}"
            class="border p-2 rounded">

        <select name="status" class="border p-2 rounded">
            <option value="">All Status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
        </select>

        <button class="bg-[#065f46] text-white px-4 rounded">Filter</button>
    </form>

    <!-- ADD BUTTON -->
    <div class="flex justify-end mb-4">
        <button @click="openAdd()" class="bg-[#065f46] text-white px-4 py-3 rounded-xl">
            <i class="fa fa-plus mr-2"></i>Add Branch
        </button>
    </div>

    <!-- TABLE -->
    <div class="mt-2 overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>City</th>
                    <th>Province</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($branches as $branch)
                <tr>
                    <td>{{ $branch->name }}</td>
                    <td>{{ $branch->code }}</td>
                    <td>{{ $branch->city }}</td>
                    <td>{{ $branch->province }}</td>
                    <td>{{ $branch->mobile }}</td>
                    <td>{{ $branch->email }}</td>
                    <td>
                        <span class="status {{ $branch->status }}">
                            {{ ucfirst($branch->status) }}
                        </span>
                    </td>
                    <td class="flex gap-2">

                        <!-- EDIT -->
                        <button
                            @click.prevent="openEdit({
                                id: '{{ $branch->id }}',
                                name: '{{ $branch->name }}',
                                code: '{{ $branch->code }}',
                                address: '{{ $branch->address }}',
                                city: '{{ $branch->city }}',
                                province: '{{ $branch->province }}',
                                mobile: '{{ $branch->mobile }}',
                                email: '{{ $branch->email }}',
                                status: '{{ $branch->status }}'
                            })"
                            class="btn-edit">
                            <i class="fa fa-pen"></i>
                        </button>

                        <!-- DELETE -->
                        <form action="{{ route('branch.delete', $branch->id) }}" method="POST">
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

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $branches->links() }}
    </div>

    <!-- MODAL -->
<div x-show="showModal" x-transition class="fixed inset-0 flex items-center justify-center z-50" x-cloak>

    <!-- BACKDROP -->
    <div class="absolute inset-0 bg-black/50" @click="closeModal()"></div>

    <!-- MODAL BOX -->
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-xl p-6 z-10 max-h-[90vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold"
                x-text="isEdit ? 'Edit Branch' : 'Add Branch'"></h2>

            <button @click="closeModal()" class="text-gray-500 hover:text-black text-lg">
                ✕
            </button>
        </div>

        <!-- FORM -->
        <form
    :action="isEdit ? '/branch/update/' + form.id : '/branch/store'"
    method="POST"
    @submit.prevent="Object.values(errors).includes(true) ? alert('Fix duplicate fields first!') : $el.submit()"
>
            @csrf
            @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-3 text-sm">
        <ul class="list-disc ml-4">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <!-- NAME + CODE -->
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="text-sm text-gray-600">Branch Name</label>
                    <input type="text" name="name" x-model="form.name"
                        @input.debounce.500ms="checkField('name')"
                        class="w-full border rounded-lg p-2 text-sm">

                    <p x-show="errors.name" class="text-red-500 text-xs mt-1">
                        Branch name already exists. Please choose a different name.
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Code</label>
                    <input type="text" name="code" x-model="form.code"
                        @input.debounce.500ms="checkField('code')"
                        class="w-full border rounded-lg p-2 text-sm">

                    <p x-show="errors.code" class="text-red-500 text-xs mt-1">
                        Code already used. Please choose a different code.
                    </p>
                </div>
            </div>

            <!-- ADDRESS -->
            <div class="mb-3">
                <label class="text-sm text-gray-600">Address</label>
                <input type="text" name="address" x-model="form.address"
                    class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-green-600">
            </div>

            <!-- CITY + PROVINCE -->
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="text-sm text-gray-600">City</label>
                    <input type="text" name="city" x-model="form.city"
                        class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-green-600">
                </div>

                <div>
                    <label class="text-sm text-gray-600">Province</label>
                    <input type="text" name="province" x-model="form.province"
                        class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-green-600">
                </div>
            </div>

            <!-- MOBILE + EMAIL -->
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="text-sm text-gray-600">Mobile</label>
                   <input type="text" name="mobile" x-model="form.mobile"
    @input.debounce.500ms="checkField('mobile')"
    class="w-full border rounded-lg p-2 text-sm">

<p x-show="errors.mobile" class="text-red-500 text-xs mt-1">
    Mobile already used. Please enter a different mobile number.
</p>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <input type="email" name="email" x-model="form.email"
    @input.debounce.500ms="checkField('email')"
    class="w-full border rounded-lg p-2 text-sm">

<p x-show="errors.email" class="text-red-500 text-xs mt-1">
    Email already used. Please enter a different email address.
</p>
                </div>
            </div>

            <!-- STATUS -->
            <div class="mb-4">
                <label class="text-sm text-gray-600">Status</label>
                <select name="status" x-model="form.status"
                    class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-green-600">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-2">
                <button type="button" @click="closeModal()"
                    class="px-4 py-2 bg-gray-200 rounded-lg text-sm hover:bg-gray-300">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-[#065f46] text-white rounded-lg text-sm hover:bg-green-800">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

</main>

<script src="//unpkg.com/alpinejs" defer>
@if ($errors->any())
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('branchModalState', {
            open: true
        });
    });
</script>
@endif
</script>

<script>
function branchModal() {
    return {
        showModal: false,
        isEdit: false,
        form: {},
        errors: {},

        openAdd() {
            this.isEdit = false;
            this.errors = {};
            this.form = {
                id: null,
                name: '',
                code: '',
                address: '',
                city: '',
                province: '',
                mobile: '',
                email: '',
                status: 'active'
            };
            this.showModal = true;
        },

        openEdit(data) {
            this.isEdit = true;
            this.errors = {};
            this.form = { ...data };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        async checkField(field) {
            if (!this.form[field]) return;

            try {
                let res = await fetch("{{ route('branch.check') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        field: field,
                        value: this.form[field],
                        id: this.form.id
                    })
                });

                let data = await res.json();

                this.errors[field] = data.exists;
            } catch (e) {
                console.error(e);
            }
        }
    }
}
</script>

</body>
</html>
