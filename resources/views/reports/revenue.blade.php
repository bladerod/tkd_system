@extends('layouts.app')

@section('content')
<div class="main-content">
    <h1 class="text-3xl font-bold mb-4">Revenue Reports</h1>

    <!-- FILTER -->
    <form method="GET" class="flex gap-2 mb-4">
        <input type="text" name="search" value="{{ $search }}"
               placeholder="Search student..."
               class="border px-3 py-2 rounded w-64">

        <button class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>

        <!-- IMPORTANT: type="button" -->
        <button type="button" onclick="downloadPDF()"
            class="bg-green-500 text-white px-4 py-2 rounded">
            Download PDF
        </button>
    </form>

    <!-- ONLY ONE printArea -->
    <div id="printArea">
        <table class="w-full border">
            <thead class="bg-gray-200">
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Payment Type</th>
                    <th>Method</th>
                    <th>Amount Paid</th>
                    <th>Receipt No.</th>
                </tr>
            </thead>
            <tbody>
            @forelse($revenue as $row)
                <tr>
                    <td>{{ date('Y-m-d', strtotime($row->date)) }}</td>
                    <td>{{ $row->student }}</td>
                    <td>{{ $row->payment_type }}</td>
                    <td>{{ $row->method }}</td>
                    <td>{{ $row->amount_paid }}</td>
                    <td>{{ $row->receipt_no }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">No data found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- LOAD LIBRARY -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- YOUR SCRIPT -->
<script>
function downloadPDF() {
    let element = document.getElementById('printArea');

    let opt = {
        margin: 0.5,
        filename: 'revenue_report.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save();
}
</script>

@endsection
