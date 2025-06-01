<div id="productModal"
    class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 p-4 sm:p-0">

    <div
        class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-auto overflow-hidden
                transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">

        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-2xl font-semibold text-gray-800" id="modalHeading">Create Product</h3>
            <button onclick="closeProductModal()"
                class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full p-1 transition ease-in-out duration-150">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6">
            <form action={{ route('products.store') }} method="POST">
                @csrf
                <input type="hidden" id="productId" name="id" value="">
                <div class="mb-6">
                    <label for="ProductName" class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    <input type="text" name="name" id="ProductName" placeholder="Enter Product Name" required
                        minlength="3" maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  transition duration-150 ease-in-out text-gray-800">
                </div>

                <div class="mb-6">
                    <label for="ProductDescription" class="block text-sm font-medium text-gray-700 mb-2">Product
                        Description</label>
                    <textarea name="description" id="ProductDescription" placeholder="Enter Product Description" required rows="2"
                        maxlength="1000"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                     transition duration-150 ease-in-out text-gray-800 resize-y"></textarea>
                </div>

                <div class="mb-6">
                    <label for="ProductAmount" class="block text-sm font-medium text-gray-700 mb-2">Starting Bid
                        Amount</label>
                    <input type="number" name="starting_bid" id="ProductAmount" placeholder="Enter Starting Bid Amount"
                        required min="0.01" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  transition duration-150 ease-in-out text-gray-800">
                </div>

                <div class="mb-6">
                    <label for="bidEndTime" class="block text-sm font-medium text-gray-700 mb-2">Bid End Time</label>
                    <input type="datetime-local" name="bid_end_time" id="bidEndTime" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  transition duration-150 ease-in-out text-gray-800">
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200 mt-6">
                    <button type="button" onclick="closeProductModal()"
                        class="px-5 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg
                                   focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2
                                   transition duration-150 ease-in-out mr-3">
                        Cancel
                    </button>
                    <button type="submit" id="createProductButton"
                        class="px-6 py-2 bg-green-600 text-black font-semibold rounded-lg shadow-md
                                   hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2
                                   transition duration-150 ease-in-out">
                        Save
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    window.appTimezone = "{{ config('app.timezone') }}";
    const openProductModal = () => {
        document.getElementById('productModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    const closeProductModal = () => {
        document.getElementById('productModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden'); 
    }

    const {
        DateTime
    } = luxon;

    const toAppTzDateTimeLocalString = (dateString = null) => {
        const dt = dateString ?
            DateTime.fromISO(dateString, {
                zone: 'utc'
            }).setZone(window.appTimezone) :
            DateTime.now().setZone(window.appTimezone);

        return dt.toFormat("yyyy-LL-dd'T'HH:mm");
    };

    const showProductModal = (product = {}) => {
        document.getElementById('ProductName').value = product.name || '';
        document.getElementById('ProductDescription').value = product.description || '';
        document.getElementById('ProductAmount').value = product.starting_bid || '';
        document.getElementById('productId').value = product.id || '';
        modalHeading
        $('#modalHeading').html(product.id ? 'Edit Product' : 'Create Product');

        const nowLocal = toAppTzDateTimeLocalString();
        const bidEndInput = document.getElementById('bidEndTime');
        bidEndInput.value = product.bid_end_time ? toAppTzDateTimeLocalString(product.bid_end_time) : '';
        bidEndInput.removeAttribute('min');
        bidEndInput.removeAttribute('max');

        openProductModal();
    };

    document.getElementById('productModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeProductModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !document.getElementById('productModal').classList.contains('hidden')) {
            closeProductModal();
        }
    });
</script>
