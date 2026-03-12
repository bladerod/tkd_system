<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TKD - Integrations</title>
    @vite(['resources/css/app.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    @vite(['resources/css/attendance.css'])
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
                        <span class="text-[#1C1C1D] font-medium">Integrations</span>
                    </div>
                    <h1 class="text-4xl font-bold text-[#1C1C1D]">Settings</h1>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card z-index-2 bg-white rounded-xl shadow-sm">
                                <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">Integrations</h6>
                                    </div>
                                </div>
                                <div class="p-8" style="border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                    <!-- SMS Integration -->
                                    <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3">SMS Integration</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">SMS Provider</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>Semaphore</option>
                                                <option>Chikka</option>
                                                <option>Twilio</option>
                                                <option>Vonage</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">API Key</label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                        </div>
                                    </div>

                                    {{-- <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Sender Name</label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="TKD Academy">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Enable SMS Notifications</label>
                                            <div class="flex items-center mt-2">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer" checked>
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1C1C1D]"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <!-- Email Integration -->
                                    <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3 mt-6">Email Integration</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">SMTP Host</label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="smtp.gmail.com">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">SMTP Port</label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="587">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Email Address</label>
                                            <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="academy@tkd.com">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Email Password</label>
                                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                        </div>
                                    </div>

                                    {{-- <div class="mb-6">
                                        <label class="block text-sm text-gray-600 mb-1">Enable Email Notifications</label>
                                        <div class="flex items-center mt-2">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer" checked>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1C1C1D]"></div>
                                            </label>
                                        </div>
                                    </div> --}}

                                    <!-- Payment Gateway -->
                                    <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3 mt-6">Payment Gateway</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Provider Name</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>GCash</option>
                                                <option>PayMongo</option>
                                                <option>Stripe</option>
                                                <option>PayPal</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">API Key</label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Secret Key</label>
                                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Webhook URL</label>
                                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" value="https://api.tkd.com/webhook">
                                        </div>
                                    </div>

                                    {{-- <div class="mb-6">
                                        <label class="block text-sm text-gray-600 mb-1">Enable Online Payments</label>
                                        <div class="flex items-center mt-2">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer" checked>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1C1C1D]"></div>
                                            </label>
                                        </div>
                                    </div> --}}

                                    {{-- <!-- Cloud Storage -->
                                    <h2 class="text-xl font-semibold text-[#1C1C1D] mb-3 mt-6">Cloud Storage (Optional)</h2>
                                    <hr style="border: solid 1px gray; opacity: 20%; margin-bottom: 20px;">

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Storage Provider</label>
                                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                                <option>AWS S3</option>
                                                <option>Google Cloud</option>
                                                <option>Azure</option>
                                                <option>DigitalOcean</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 mb-1">Access Key</label>
                                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none">
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm text-gray-600 mb-1">Bucket Name</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" placeholder="tkd-attendance-records">
                                    </div> --}}

                                    <!-- Buttons -->
                                    <div class="flex items-center justify-end gap-3 pt-6">
                                        <button type="button" class="px-8 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                                        <button type="submit" class="px-8 py-2 text-sm text-white bg-[#1C1C1D] rounded-md hover:bg-[#2f2f2f] transition-colors">Save Integrations</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])
</body>
</html>