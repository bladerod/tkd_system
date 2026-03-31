<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $invoice->invoice_no }}</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div>
                <a href="{{ route('billing.index') }}" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Billing
                </a>
            </div>
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg print:hidden">
                <i class="fas fa-print mr-2"></i> Print Receipt
            </button>
        </div>

        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">RECEIPT</h1>
                <p class="text-gray-500">Invoice #: {{ $invoice->invoice_no }}</p>
                <p class="text-gray-500">Date: {{ \Carbon\Carbon::now()->format('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800">TrainNova Martial Arts</h2>
                <p class="text-gray-500">123 Training Dojo St.</p>
                <p class="text-gray-500">contact@trainnova.com</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-gray-500 font-semibold mb-2 border-b pb-1">Billed To:</h3>
                <p class="font-bold text-lg text-gray-800">{{ $invoice->student->first_name }} {{ $invoice->student->last_name }}</p>
                @if($invoice->student->parent && $invoice->student->parent->user)
                    <p class="text-gray-600">Parent: {{ $invoice->student->parent->user->fname }} {{ $invoice->student->parent->user->lname }}</p>
                @endif
                <p class="text-gray-600">Period: {{ \Carbon\Carbon::parse($invoice->billing_period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($invoice->billing_period_end)->format('M d, Y') }}</p>
            </div>
            <div>
                <h3 class="text-gray-500 font-semibold mb-2 border-b pb-1">Status:</h3>
                @if($invoice->status == 'paid')
                    <span class="bg-green-100 text-green-800 text-lg font-bold px-3 py-1 rounded-full uppercase">PAID IN FULL</span>
                @elseif($invoice->status == 'partial')
                    <span class="bg-blue-100 text-blue-800 text-lg font-bold px-3 py-1 rounded-full uppercase">PARTIALLY PAID</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 text-lg font-bold px-3 py-1 rounded-full uppercase">{{ $invoice->status }}</span>
                @endif
            </div>
        </div>

        <table class="w-full mb-8">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="p-3 text-gray-600 font-semibold rounded-tl-lg">Description</th>
                    <th class="p-3 text-gray-600 font-semibold text-right rounded-tr-lg">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="p-3 text-gray-800">Monthly Training Fee</td>
                    <td class="p-3 text-gray-800 text-right">₱{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                @if($invoice->discount > 0)
                <tr class="border-b text-green-600">
                    <td class="p-3">Discount Applied</td>
                    <td class="p-3 text-right">- ₱{{ number_format($invoice->discount, 2) }}</td>
                </tr>
                @endif
                @if($invoice->penalty > 0)
                <tr class="border-b text-red-600">
                    <td class="p-3">Late Penalty</td>
                    <td class="p-3 text-right">+ ₱{{ number_format($invoice->penalty, 2) }}</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="font-bold text-lg bg-gray-50">
                    <td class="p-3 text-right text-gray-800">Total Due:</td>
                    <td class="p-3 text-right text-gray-800">₱{{ number_format($invoice->total_due, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        @if($invoice->payments->count() > 0)
        <div class="mb-8">
            <h3 class="text-gray-800 font-bold mb-3">Payment History</h3>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                @foreach($invoice->payments as $payment)
                <div class="flex justify-between items-center mb-2 last:mb-0 border-b last:border-0 pb-2 last:pb-0">
                    <div>
                        <span class="font-semibold text-gray-700">₱{{ number_format($payment->amount, 2) }}</span>
                        <span class="text-gray-500 text-sm ml-2">via {{ ucfirst($payment->payment_method) }}</span>
                        @if($payment->transaction_reference)
                            <span class="text-gray-400 text-xs ml-2">(Ref: {{ $payment->transaction_reference }})</span>
                        @endif
                    </div>
                    <div class="text-gray-500 text-sm">
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y h:i A') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="flex justify-end mt-4">
            <div class="bg-gray-800 text-white p-4 rounded-lg w-64">
                <div class="flex justify-between mb-1">
                    <span>Total Paid:</span>
                    <span>₱{{ number_format($invoice->payments->sum('amount'), 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t border-gray-600 pt-1 mt-1">
                    <span>Balance:</span>
                    <span>₱{{ number_format(max(0, $invoice->total_due - $invoice->payments->sum('amount')), 2) }}</span>
                </div>
            </div>
        </div>
        @endif

        <div class="mt-12 text-center text-gray-400 text-sm border-t pt-8">
            <p>Thank you for training with us!</p>
            <p>If you have any questions about this receipt, please contact management.</p>
        </div>
    </div>

    <style>
        /* Hide buttons and back links when printing */
        @media print {
            body { background-color: white; padding: 0; }
            .shadow-md { box-shadow: none; }
            .print\:hidden { display: none !important; }
        }
    </style>
</body>
</html>