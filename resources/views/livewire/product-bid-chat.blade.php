<div class="flex flex-col h-[80vh] border border-gray-200 rounded-lg shadow-xl overflow-hidden bg-white">
    <x-flash-messages />
    <div
        class="bg-gradient-to-r from-blue-600 to-blue-800 text-black px-5 py-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center space-x-4">
            <span
                class="text-lg bg-yellow-300 text-black-900 px-4 py-1.5 rounded-full font-bold shadow-md transform hover:scale-105 transition-transform duration-200 ease-in-out">
                Product: {{ $product->name }}
            </span>
            <span
                class="text-lg bg-yellow-300 text-black-900 px-4 py-1.5 rounded-full font-bold shadow-md transform hover:scale-105 transition-transform duration-200 ease-in-out">
                Initial Bid: ₹{{ number_format($product->starting_bid, 2) }}
            </span>
            <span
                class="text-lg bg-yellow-300 text-black-900 px-4 py-1.5 rounded-full font-bold shadow-md transform hover:scale-105 transition-transform duration-200 ease-in-out">
                Highest Bid: ₹{{ number_format($product->display_bid_amount, 2) }}
            </span>
        </div>
        <span> Live Auction Room </span>
        <div x-data="countdownTimer('{{ $product->bid_end_time->format('Y-m-d H:i:s') }}')" x-init="start()"
            class="text-sm bg-white text-black-800 px-4 py-2 rounded-lg font-mono tracking-wide shadow-inner border border-blue-200">
            Ends In: <span x-text="timeLeft" class="font-bold text-base"></span>
        </div>
    </div>

    <div wire:poll.5s class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 border-b border-gray-200" id="chatBox">
        @forelse($bids as $message)
            @if ($message['user_id'] === auth()->id())
                <div class="flex justify-end animate-fade-in-up">
                    <div
                        class="bg-blue-500 text-black px-5 py-2.5 rounded-tl-xl rounded-bl-xl rounded-tr-lg shadow-lg max-w-sm text-base break-words relative">
                        @if (isset($message['is_bid']) && $message['is_bid'])
                            <p class="text-lg font-bold mb-1 border-b border-blue-400 pb-1">Your Bid:
                                ₹{{ number_format($message['text'], 2) }}</p>
                        @endif
                        <p>{{ $message['text'] }}</p>
                        <div class="text-right text-xs mt-1 opacity-85">
                            {{ \Carbon\Carbon::parse($message['time'])->diffForHumans() }}</div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-blue-600 rounded-br-lg"></div>
                    </div>
                </div>
            @else
                <div class="flex justify-start animate-fade-in-up">
                    <div
                        class="bg-gray-200 text-black-800 px-5 py-2.5 rounded-tr-xl rounded-br-xl rounded-tl-lg shadow-lg max-w-sm text-base break-words relative">
                        <strong class="text-black-700">{{ $message['username'] }}</strong>:
                        @if (isset($message['is_bid']) && $message['is_bid'])
                            <p class="text-lg font-bold mb-1 border-b border-gray-300 pb-1">Bid:
                                ₹{{ number_format($message['text'], 2) }}</p>
                        @endif
                        <p>{{ $message['text'] }}</p>
                        <div class="text-right text-xs mt-1 opacity-85">
                            {{ \Carbon\Carbon::parse($message['time'])->diffForHumans() }}</div>
                        <div class="absolute bottom-0 left-0 w-3 h-3 bg-gray-300 rounded-bl-lg"></div>
                    </div>
                </div>
            @endif
        @empty
            <p class="text-lg font-bold mb-1 border-b border-gray-300 pb-1">No bid..!</p>
        @endforelse
    </div>

    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <form wire:submit.prevent="createBid" class="flex items-center space-x-3">
            <input wire:model.defer="newBidAmount" type="number" step="0.01"
                min="{{ $product->display_bid_amount + 0.01 }}" placeholder="Your bid amount"
                class="w-36 px-5 py-2.5 border border-black-500 rounded-full focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent text-black-700 font-semibold transition duration-200 ease-in-out placeholder-green-400 text-base">

            <button type="submit"
                class="bg-gradient-to-r from-green-500 to-green-700 text-black px-7 py-2.5 rounded-full hover:from-green-600 hover:to-green-800 transition duration-200 ease-in-out shadow-lg transform hover:scale-105">
                Send
            </button>
        </form>
        <form wire:submit.prevent="sendMessage" class="flex items-center space-x-3">
            <input wire:model.defer="newMessage" type="text" placeholder="Type your message..."
                class="flex-1 px-5 py-2.5 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 ease-in-out text-black-700 placeholder-gray-400 text-base">
            <button type="submit"
                class="bg-gradient-to-r from-green-500 to-green-700 text-black px-7 py-2.5 rounded-full hover:from-green-600 hover:to-green-800 transition duration-200 ease-in-out shadow-lg transform hover:scale-105">
                Send
            </button>
        </form>
    </div>
</div>
<script>
    const countdownTimer = (endTimeStr) => {
        return {
            timeLeft: '',
            interval: null,

            start() {
                const endTime = new Date(endTimeStr).getTime();
                this.updateTime(endTime);

                this.interval = setInterval(() => {
                    this.updateTime(endTime);
                }, 1000);
            },

            updateTime(endTime) {
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance <= 0) {
                    this.timeLeft = 'Expired';
                    clearInterval(this.interval);
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                this.timeLeft =
                    `${days > 0 ? days + 'd ' : ''}${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
        };
    }
</script>
