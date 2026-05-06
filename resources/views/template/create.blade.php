@php
    $branding = \App\Models\Branding::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(isset($branding) && $branding->logo_path)
        <link rel="icon" href="{{ Storage::url($branding->logo_path) }}">
    @endif
    <title>TrainNova | Create Template</title>
    @vite(['resources/css/app.css'])
    @vite(['resources/css/dashboard.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-50">

    @include("includes.navbar")
    @include("includes.sidebar")

    <div class="main-content ms-64 px-6 py-6 ">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
            <span>/</span>
            <a href="{{ route('templates.index') }}" class="hover:text-[#1C1C1D]">Templates</a>
            <span>/</span>
            <span class="text-[#1C1C1D] font-medium">Create</span>
        </div>

        <h1 class="text-4xl font-bold text-[#1C1C1D] mb-6">Create Certificate Template</h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-2xl">
            <div class="p-4 bg-[#1C1C1D] rounded-t-xl">
                <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                    <i class="fa fa-certificate"></i> Template Details
                </h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('templates.store') }}">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Template Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="e.g., Regional Tournament Gold" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all">
                        <p class="text-xs text-gray-500 mt-1">A descriptive name to help you identify this template later.</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Type <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1C1C1D] focus:border-transparent transition-all">
                            <option value="" disabled selected>-- Select Type --</option>
                            <option value="promotion">Belt Promotion</option>
                            <option value="competition">Competition / Tournament</option>
                            <option value="dan">Black Belt (Dan)</option>
                            <option value="achievement">Achievement / Participation</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('templates.index') }}" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition-colors">Cancel</a>
                        <button type="submit" class="px-5 py-2.5 bg-[#1C1C1D] text-white rounded-lg hover:bg-[#2C2C2D] font-medium transition-colors flex items-center gap-2">
                            Proceed to Editor <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/js/navbarDrop.js'])
</body>
</html>
