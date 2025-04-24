<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('transactions.index') }}" class="text-blue-500 hover:underline">
                            &larr; Kembali ke Daftar Transaksi
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Informasi Transaksi</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="text-gray-600">ID Transaksi:</div>
                                <div>{{ $transaction->id }}</div>
                                
                                <div class="text-gray-600">Tanggal:</div>
                                <div>{{ $transaction->created_at->format('d M Y H:i') }}</div>
                                
                                <div class="text-gray-600">Status:</div>
                                <div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($transaction->status == 'completed') bg-green-100 text-green-800 
                                        @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-800 
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Informasi Pelanggan</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="text-gray-600">Nama:</div>
                                <div>{{ $transaction->customer_name }}</div>
                                
                                <div class="text-gray-600">Email:</div>
                                <div>{{ $transaction->customer_email ?? '-' }}</div>
                                
                                <div class="text-gray-600">Telepon:</div>
                                <div>{{ $transaction->customer_phone ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Detail Item</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction->transactionDetails as $detail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $detail->item->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">Rp {{ number_format($detail->price, 0, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">{{ $detail->quantity }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-right font-semibold">
                                            Total:
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 font-semibold">
                                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('transactions.print', $transaction->id) }}" target="_blank" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                            Cetak Invoice
                        </a>
                        
                        @if($transaction->status == 'pending')
                            <form action="{{ route('transactions.update-status', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Selesaikan Transaksi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>