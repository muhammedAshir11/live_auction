<x-app-layout>
    <x-flash-messages />
    <x-slot name="header">
        <div class="flex justify-between items-center px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                💰 Live Auction Listings & Bid Details
            </h2>
        </div>
    </x-slot>


    @can('create', App\Models\Product::class)
        <div class="flex justify-end px-4 sm:px-6 lg:px-8 mt-6">
            <a href="#" onclick="showProductModal()"
                class="bg-green-600 hover:bg-green-700 text-black font-semibold py-2 px-4 rounded shadow transition duration-200">
                ➕ Create Product
            </a>
        </div>
    @endcan

    <div class="py-10 px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-white shadow rounded-lg p-6 w-full">
            @if ($products->count())
                <div class="overflow-x-auto">
                    <table class="w-full table-auto divide-y divide-gray-200 text-sm text-center">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold text-gray-600">Product</th>
                                <th class="px-6 py-3 font-semibold text-gray-600">Current Bid</th>
                                <th class="px-6 py-3 font-semibold text-gray-600">End Date and time</th>
                                <th class="px-6 py-3 font-semibold text-gray-600">Status</th>
                                @can('create', App\Models\Product::class)
                                    <th class="px-6 py-3 font-semibold text-gray-600">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-6 py-4 text-green-600 font-semibold">
                                        ${{ number_format($product->display_bid_amount, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $product->bid_end_time->format('M d, Y h:i A') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if (!$product->is_ended)
                                            <button class="bg-red-600 text-black px-3 py-1 rounded text-sm"
                                                onclick="window.location.href='{{ route('products.show', $product->id) }}'">
                                                Bid Now
                                            </button>
                                        @else
                                            <div class="text-sm text-gray-600">
                                                <div class="font-semibold text-red-600">Ended</div>
                                                @if ($product->winner_name)
                                                    <div class="text-sm mt-1">Winner: {{ $product->winner_name }}</div>
                                                @else
                                                    <div class="text-sm mt-1 text-black-400">No bids placed</div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    @canany(['update', 'delete'], $product)
                                        <td class="px-6 py-4 flex justify-center space-x-2">
                                            @can('update', $product)
                                                <button onclick="showProductModal({{ $product }})"
                                                    class="text-blue-600 hover:text-blue-800" title="Edit Product" @if($product->is_ended) disabled @endif>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.232 5.232l3.536 3.536M9 11l6 6M5 20h14a2 2 0 002-2V7a2 2 0 00-2-2h-7l-4 4v9z" />
                                                    </svg>
                                                </button>
                                            @endcan

                                            @can('delete', $product)
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800"
                                                        title="Delete Product">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center text-gray-600 text-lg py-20">
                    😕 No auctions available at the moment.
                </div>
            @endif
        </div>
    </div>
    <x-product-modal-component />
</x-app-layout>
