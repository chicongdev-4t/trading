<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div x-data="data()">
        <div class="py-12 bg-white flex">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h1 class="text-4xl md:text-4xl font-extrabold text-gray-900 uppercase pt-4 pb-4 leading-loose">
                        ĐẦU TƯ TÀI SẢN SỐ BẰNG USDT <br>
                        CỰC DỄ DÀNG VÀ AN TOÀN <br>
                        TRONG 60 GIÂY
                    </h1>
                </div>
                <div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold  rounded-lg">
                        <a   href="{{ route('trading', ['code' => 'BTC']) }}">
                            <div class="px-4 py-2">Bắt Đầu Ngay</div>
                        </a>
                    </button>
                </div>
            </div>
            <div>
                <img src="https://v1.aliniex.com/imgs/landing-page/background.png" alt="">
            </div>
        </div>
        <div class="ml-14">
            <div class="max-w-10xl mx-auto p-10">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 ml-2">Danh sách Coin</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="text-gray-500 border-b">
                        <tr>
                            <th class="px-4 py-2">TÊN COIN</th>
                            <th class="px-4 py-2">GIÁ MUA (USDT)</th>
                            <th class="px-4 py-2">GIÁ BÁN (USDT)</th>
                            <th class="px-4 py-2">THAO TÁC</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-900 font-medium">
                        <!-- Bitcoin -->
                        @foreach($coins as $coin)
                            <tr class="border-b">
                                <td class="flex items-center gap-3 px-4 py-3">
                                    <img src={{$coin['img']}} class="w-6 h-6" alt="Bitcoin">
                                    <div>
                                        <div>{{$coin['name']}}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($coin['priceBuy'] === 'N/A')
                                        {{ $coin['priceBuy'] }}
                                    @else
                                        {{ rtrim(rtrim(number_format($coin['priceBuy'], 8, '.', ''), '0'), '.') }}
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($coin['priceSell'] === 'N/A')
                                        {{ $coin['priceSell'] }}
                                    @else
                                        {{ rtrim(rtrim(number_format($coin['priceSell'], 8, '.', ''), '0'), '.') }}
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <button @click="transaction('{{ $coin['name'] }}')" class="bg-blue-600 text-white px-4 py-1 rounded-md hover:bg-blue-700">Giao dịch</button>
                                </td>
                            </tr>
                        @endforeach()
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="flex justify-around">
            <img class="w-644 h-64" src="https://v1.aliniex.com/imgs/landing-page/invest.png" alt="">
            <div class="space-y-4">
                <div class="text-sm md:text-xl font-extrabold text-gray-900 leading-tight">Đầu tư, tích lũy và mua bán tài sản số</div>
                <div>Giao diện thân thiện, dễ sử dụng, giao dịch ngay lập tức. Bất kỳ ai cũng có thể thao tác mua bán</div>
                <div class="space-y-4">
                    <div class="flex space-x-4">
                        <div>
                            <img src="https://v1.aliniex.com/imgs/landing-page/icons/fee.svg" alt="">
                        </div>
                        <h4>Phí giao dịch thấp, tiết kiệm tối đa mọi chi phí.</h4>
                    </div>
                    <div class="flex space-x-4">
                        <img src="https://v1.aliniex.com/imgs/landing-page/icons/money.svg" alt="">
                        <div>Đầu tư tiền kỹ thuật số trực tiếp từ USDT.</div>
                    </div>
                    <div class="flex space-x-4">
                        <img src="https://v1.aliniex.com/imgs/landing-page/icons/transfer.svg" alt="">
                        <div>Giao dịch an toàn, nạp rút nhanh chóng.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function data() {
            return {
                transaction(name) {
                    if (!name) {
                        alert("Không có tên coin để giao dịch.");
                        return;
                    }
                    window.location.href = `/trading/${name}`;
                }
            }

        }

    </script>
</x-app-layout>
