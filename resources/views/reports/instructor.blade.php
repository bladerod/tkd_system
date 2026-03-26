@extends('layouts.app')

@section('content')

<div class="main-content">
    <h1 class="text-3xl font-bold mb-4">Instructor Load Reports</h1>

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
                    <th>Instructor</th>
                    <th>Date</th>
                    <th>Class</th>
                    <th>No. of Students</th>
                    <th>Time</th>
                    <th>Hours</th>
                </tr>
            </thead>
            <tbody>
                 <tr>
          <td>Sir Mark</td><td>2026-02-04</td><td>Kids TKD</td>
          <td>15</td><td>08:00–10:00 AM</td><td>2 hrs</td>
        </tr>
        <tr>
          <td>Sir John</td><td>2026-02-04</td><td>Adult TKD</td>
          <td>15</td><td>08:00–10:00 AM</td><td>2 hrs</td>
        </tr>
                @forelse($instructors as $row)
                <tr>
                    <td>{{ $row->instructor }}</td>
                    <td>{{ $row->date }}</td>
                    <td>{{ $row->class }}</td>
                    <td>{{ $row->total_students }}</td>
                    <td>{{ $row->time }}</td>
                    <td>{{ $row->hours }}</td>
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
