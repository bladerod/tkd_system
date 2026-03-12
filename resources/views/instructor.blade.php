<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css'])
         @vite(['resources/css/instructor.css'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite(['resources/css/dashboard.css'])
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instructor</title>
</head>
<body class="bg-gray-50">
 <!-- navbar -->
    @include("includes.navbar")
    <!-- Sidebar -->
    @include('includes.sidebar')

    <div class="row">
        <main class="ml-64 pt-4 p-6">
            <div class="container-fluid m-0">
                <div class="flex items-center gap-2 text-sm text-gray-500 my-6">
                    <a href="/dashboard" class="hover:text-[#1C1C1D]">Dashboard</a>
                    <span>/</span>
                    <span class="text-[#1C1C1D] font-medium">Instructor</span>
                </div>
                <h1 class="font-bold text-4xl">Instructor</h1>
                <div class="row mt-4">
                    <div class="col-lg-12">
                            <div class="card-header pb-0 bg-transparent">
                                    <div class="p-3 bg-[#1C1C1D] rounded-t-xl">
                                        <h6 class="text-gray-800 font-semibold text-xl text-white text-center">List of Instructor</h6>
                                    </div>
                            </div>
                            <div class="flex justify-end bg-gray-50">
                                <button class="bg-[#065f46] text-white p-3 rounded-xl mt-3 font-medium"><span class="mr-2"><i class="fa-solid fa-plus"></i></span>Add Instructor</button>
                            </div>
                                <div class="table-card">

                <div class="overflow-x-auto mt-6">
                    <table class="w-full">
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
                            <tr>
                                <td>Michael Tan</td>
                                <td><span class="belt black">Black Belt</span></td>
                                <td>Head Instructor</td>
                                <td>0917-456-1234</td>
                                <td class="text-center"><span class="status active">Active</span></td>
                                <td class="text-center">
                                    <a href="#" class="btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="#" class="btn-delete"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>Angela Cruz</td>
                                <td><span class="belt red">Red Belt</span></td>
                                <td>Assistant Instructor</td>
                                <td>0918-223-8891</td>
                                <td class="text-center"> <span class="status active ">Active</span></td>
                                <td class="text-center">
                                    <a href="#" class="btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="#" class="btn-delete"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>Daniel Reyes</td>
                                <td><span class="belt black">Black Belt</span></td>
                                <td>Senior Instructor</td>
                                <td>0921-778-5543</td>
                                <td class="text-center"><span class="status active">Active</span></td>
                                <td class="text-center">
                                    <a href="#" class="btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="#" class="btn-delete"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>



        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js'])
    @vite(['resources/js/dashboard.js'])
</body>
</html>
