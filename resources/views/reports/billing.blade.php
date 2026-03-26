@extends('layouts.app')

@section('content')

<div class="main-content">
    <h1 class="text-3xl font-bold mb-4">Parent Billing Summary</h1>

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
                    <th>ID</th>
                    <th>Student</th>
                    <th>Parent</th>
                    <th>Total Bill</th>
                    <th>Total Paid</th>
                    <th>Remaining Balance</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                 <tr>
          <td>001</td><td>Juan Dela Cruz</td><td>Ana Dela Cruz</td><td>₱9,000</td>
          <td>₱6,000</td><td class="balance due">₱3,000</td><td>2026-02-15</td>
        </tr>
        <tr>
          <td>002</td><td>Maria Dela Cruz</td><td>Ana Dela Cruz</td><td>₱15,000</td>
          <td>₱3,000</td><td class="balance due">₱12,000</td><td>2026-02-15</td>
        </tr>
                @forelse($billing as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->student }}</td>
                    <td>{{ $row->parent }}</td>
                    <td>{{ $row->total_bill }}</td>
                    <td>{{ $row->total_paid }}</td>
                    <td class="balance due">{{ $row->remaining_balance }}</td>
                    <td>{{ $row->due_date }}</td>
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
