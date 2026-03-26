@extends('layouts.app')

@section('content')

<div class="main-content">
    <h1 class="text-3xl font-bold mb-4">Attendance Reports</h1>

    <!-- FILTER -->
    <form method="GET" class="flex gap-2 mb-4">
        <input type="text" name="search" value="{{ $search }}"
               placeholder="Search student..."
               class="border px-3 py-2 rounded w-64">

        <button class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>

        <button type="button" onclick="printReport()"
                class="bg-green-500 text-white px-4 py-2 rounded">
            Print
        </button>
    </form>

    <!-- TABLE -->
    <div id="printArea">
        <table class="w-full border">
            <thead class="bg-gray-200">
                <tr>
                    <th>Student</th>
                    <th>Date</th>
                    <th>Time in</th>
                    <th>Time out</th>
                    <th>Status</th>
                    <th>Instructor</th>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendance as $row)
                <tr>
                    <td>{{ $row->student }}</td>
                    <td>{{ $row->date }}</td>
                    <td>{{ $row->time_in }}</td>
                    <td>{{ $row->time_out }}</td>
                    <td>{{ $row->status }}</td>
                    <td>{{ $row->instructor }}</td>
                    <td>{{ $row->class }}</td>
                </tr>

                    @empty
    <tr>
        <td colspan="7" class="text-center py-4">No data found</td>
    </tr>
    @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- <script>
// function printReport() {
//     let content = document.getElementById('printArea').innerHTML;
//     let win = window.open('', '', 'width=900,height=700');
//     win.document.write(`
//         <html>
//         <head>
//             <title>Print Report</title>
//         </head>
//         <body>
//             <h2>Attendance Report</h2>
//             ${content}
//         </body>
//         </html>
//     `);
//     win.document.close();
//     win.print();
// }
// </script> --}}

@endsection
