<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice['invoice_number'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {
            .status-stamp {
                /* Rotasi 45 derajat ke kanan bawah */
                @apply absolute top-24 right-6 transform rotate-45 origin-top-right bg-green-100 text-green-800 font-bold py-1 px-8 text-sm border-2 border-green-800 border-dashed z-10;
            }

            .status-stamp.unpaid {
                @apply bg-red-100 text-red-800 border-red-800;
            }
        }
    </style>
</head>

<body class="bg-white text-gray-800">
    <!-- Contoh untuk status PAID -->
    <div class="relative max-w-4xl mx-auto bg-white shadow-none">

        <!-- Kanan Atas: Pita Status -->
        <div class="flex justify-between items-start mb-4 sm:mb-6 pt-24 px-14">
            <div class="flex items-center space-x-2">
                <!-- Logo Placeholder -->
                <div class="w-10 h-10 border-2 border-blue-400 rotate-45"></div>
                <h1 class="text-2xl font-bold text-blue-800">
                    <span class="text-blue-900">PyLabs</span> <span class="text-sky-500">Nodes</span>
                </h1>
            </div>
            <div class="text-sm text-right text-gray-800 leading-tight">
                <p><strong>Strasmore, Inc.</strong></p>
                <p>2522 Chambers Road Suite 100</p>
                <p>Tustin, CA 92780</p>
            </div>

        </div>
        <div
            class="absolute top-8 -right-24 w-72 bg-lime-500 text-white text-center font-bold text-xl py-2 rotate-45 shadow-lg">
            PAID
        </div>

        <div class="px-14">
            <div>
                <h2 class="text-xl font-bold mb-1">Invoice #{{ $invoice['invoice_number'] }}</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Invoice Date: {{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('F jS, Y') }}<br>
                    Due Date: {{ \Carbon\Carbon::parse($invoice['due_date'])->format('F jS, Y') }}
                </p>
            </div>
            <!-- Invoiced To -->
            <section class="mb-6">
                <h2 class="font-semibold mb-1">Invoiced To</h2>
                <address class="not-italic text-sm">
                    <p>Deddy Raharjo</p>
                    <p>Bogor Jawa Barat</p>
                    <p>Kota Jakarta Pusat, DKI Jakarta, Indonesia</p>
                </address>
            </section>

            <!-- Items Table -->
            <section class="mb-6">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">Description</th>
                            <th class="border border-gray-300 px-4 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">
                                PyMap Standard Plan<br>
                                Inc.:
                                <ul>
                                    @forelse($invoice['items'] ?? [] as $feature)
                                        <li>{{ $feature }}</li>
                                    @empty
                                        <li>No features included.</li> <!-- Pesan jika tidak ada fitur -->
                                    @endforelse
                                </ul>
                                Period: {{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('F jS, Y') }} - {{ \Carbon\Carbon::parse($invoice['due_date'])->format('F jS, Y') }}<br>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-right">${{ $invoice['total'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Totals -->
            <section class="mb-6 ml-auto w-full max-w-xs">
                <table class="min-w-full border-collapse">
                    <tr>
                        <td class="py-1">Sub Total</td>
                        <td class="py-1 text-right">${{ $invoice['total'] }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Credit</td>
                        <td class="py-1 text-right">$0.00</td>
                    </tr>
                    <tr class="border-t border-gray-400 font-bold">
                        <td class="pt-2">Total</td>
                        <td class="pt-2 text-right">${{ $invoice['total'] }}</td>
                    </tr>
                </table>
            </section>

            <!-- Transactions Table -->
            <section class="mb-6">
                <h3 class="font-semibold mb-2">Transactions</h3>
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">Transaction Date</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Gateway</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Transaction ID</th>
                            <th class="border border-gray-300 px-4 py-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('F jS, Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2">Credit Card</td>
                            <td class="border border-gray-300 px-4 py-2">${{ $invoice['txn_id'] }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-right">${{ $invoice['total'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Balance -->
            <div class="mb-4">
                <p class="font-semibold">Balance: $0.00</p>
            </div>
        </div>
    </div>

    <!-- Contoh untuk status UNPAID -->
    <!--
  <div class="relative max-w-4xl mx-auto bg-white p-8 shadow-none print:shadow-lg print:border print:border-gray-300">
    <div class="status-stamp unpaid">UNPAID</div>
    ... (isi invoice yang sama) ...
  </div>
  -->
</body>

</html>
