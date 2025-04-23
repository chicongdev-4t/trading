<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
            <div x-data="data()" class="flex w-1/5 text-base mt-4">
                <select x-model="code" @change="getCode(code)" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-rose-500">
                    <option value="" disabled>Danh sách tiền tệ </option>
                    @foreach(\App\Models\Currency::where('code', '!=', 'USDT')->get() as $currency)
                        <option value="{{ $currency->code }}">{{ $currency->code }}  </option>
                    @endforeach
                </select>
            </div>
        </h2>
    </x-slot>

    <div x-data="data() " >

        <div class="bg-gray-100 py-6">
            <div class="container mx-auto px-10">
                <div class="flex justify-center gap-12 flex-wrap">
                    <!-- Khối bảng BÁN -->
                    <div class="w-full md:w-[48%] space-y-4">
                        <!--dat ban-->
                        <div x-show="showOrderSell" x-cloak x-transition class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
                                <!-- Đóng -->
                                <button @click="showOrderSell=false" class="absolute top-4 right-4 text-gray-400 hover:text-black text-xl font-bold">&times;</button>

                                <!-- Tiêu đề -->
                                <h2 class="text-xl font-bold mb-6 text-gray-800">Đặt Bán <span class="text-black"></span></h2>

                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-1">Tiền Tệ</label>
                                    <div class="flex mb-2">
                                        <input x-init="codeSeller=code" x-model="codeSeller" readonly   type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-rose-500" value="0">
                                    </div>
                                </div>

                                <!-- Input: Số lượng -->
                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-1">Số Luợng</label>
                                    <div class="flex mb-2">
                                        <input x-model="amountSeller"  type="number" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-rose-500" value="0">
                                    </div>
                                </div>

                                <!-- Input: Giá -->
                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-1">Giá</label>
                                    <div class="flex">
                                        <input x-model="priceSeller"  type="number" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-rose-500" value="0">
                                        <span class="px-3 py-2 bg-gray-100 border border-l-0 border-blue-300 rounded-r-md text-gray-600">USDT</span>
                                    </div>
                                </div>


                                <!-- Input: Tổng tiền thu -->
                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-1">Tổng Tiền</label>
                                    <div class="flex">
                                        <input x-model="totalOrderSell()" type="number" class="flex-1 px-3 py-2 border border-blue-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-400" readonly>
                                        <span class="px-3 py-2 bg-gray-100 border border-l-0 border-blue-300 rounded-r-md text-gray-600">USDT</span>
                                    </div>
                                </div>

                                <!-- Nút đặt bán -->
                                <button @click="orderSell" readonly class="w-full mt-4 bg-red-600 hover:bg-red-500 text-white font-bold py-2 rounded-lg text-center text-lg">
                                    Đặt Bán
                                </button>
                            </div>
                        </div>

                        <!-- Popup Buy -->
                        <div x-show="showTransactionBuy" x-cloak>
                            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-50">
                                <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
                                    <button class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl" @click="showTransactionBuy =! showTransactionBuy">&times;</button>
                                    <h2 class="text-xl font-semibold mb-4">Giao Dịch</h2>

                                    <label class="block mb-2 text-sm font-medium">Số Lượng</label>
                                    <div class="flex items-center mb-4">
                                        <input type="number" x-model="amountBuy" min="1" placeholder="0" class="w-full px-4 py-2 border rounded-l-md focus:outline-none" />
                                        <span class="px-3 py-2 border border-l-0 rounded-r-md bg-gray-100 min-w-[70px] text-center" x-text="selectedOfferBuy?.currency?.code"></span>
                                    </div>

                                    <label class="block mb-2 text-sm font-medium">Tổng Tiền</label>
                                    <div class="flex items-center mb-4">
                                        <input type="text" x-model="totalBuy" placeholder="0" class="w-full px-4 py-2 border rounded-l-md bg-gray-100 focus:outline-none" />
                                        <span class="px-3 py-2 border border-l-0 rounded-r-md bg-gray-100 min-w-[70px] text-center">USDT</span>
                                    </div>

                                    <button @click="buy(amountBuy, selectedOfferBuy)" class="w-full bg-green-600 hover:bg-green-500 text-white font-semibold py-2 rounded-lg" x-text="`Mua ${selectedOfferBuy?.currency?.code || ''}`"></button>
                                </div>
                            </div>
                        </div>

                        <!-- Bảng Bán -->
                        <div class="bg-white p-4 rounded-xl shadow-md">
                            <h3 class="text-lg font-bold mb-2">Danh Sách Bán</h3>
                            <table class="w-full border-collapse text-sm ">
                                <thead class="bg-gray-100  text-gray-500">
                                <tr class="text-center">
                                    <th class="px-3 py-2">Người Bán</th>
                                    <th class="px-3 py-2">Tiền tệ</th>
                                    <th class="px-3 py-2">Số Lượng</th>
                                    <th class="px-3 py-2">Số Lượng Còn Lại</th>
                                    <th class="px-3 py-2">Giá</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($offersSell as $offerSell)
                                    <tr class="text-center border-t ">
                                        <td class="px-3 py-2 font-semibold">{{$offerSell->user->name}}</td>
                                        <td class="flex items-center space-x-2 inline-flex">
                                            <img class="w-4 h-4 object-contain" src="{{ $offerSell->img }}" alt="{{ $offerSell->currency->code }}">
                                            <span>{{ $offerSell->currency->code }}</span>
                                        </td>
                                        <td>{{ rtrim(rtrim(number_format($offerSell->amount, 8, '.', ''), '0'), '.') }}</td>
                                        <td>{{ rtrim(rtrim(number_format($offerSell->available_amount, 8, '.', ''), '0'), '.') }}</td>
                                        <td class="text-red-500 font-semibold">{{ rtrim(rtrim(number_format($offerSell->price, 8, '.', ''), '0'), '.') }}</td>
                                        <td>
                                            <button class="bg-emerald-50 text-emerald-600 font-bold text-xs px-3 py-1 rounded transition duration-200 hover:bg-emerald-500 hover:text-white" @click="if(!checkStatusBuyer({{ json_encode($offerSell) }})){ if (!checkBuyerId({{ json_encode($offerSell) }}, userId)) { selectedOfferBuy = {{ json_encode($offerSell) }}; showTransactionBuy = true; }}">Mua</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="text-right mt-4">
                                <div class="flex space-x-4 justify-between">
                                    <div>
                                        <!-- Nút Previous -->
                                        <button
                                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 {{ $offersSell->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            @click="{{ $offersSell->onFirstPage() ? '' : 'window.location.href=\'' . $offersSell->previousPageUrl() . '\'' }}"
                                        >
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <!-- Nút Next -->
                                        <button
                                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 {{ $offersSell->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                                            @click="{{ $offersSell->hasMorePages() ? 'window.location.href=\'' . $offersSell->nextPageUrl() . '\'' : '' }}"
                                        >
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <button @click="showOrderSell=!showOrderSell" class="font-semibold text-red-600 border border-red-500 bg-white py-2 px-4 rounded-xl transition duration-200 hover:bg-red-600 hover:text-white">Đặt Bán</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Khối bảng MUA -->
                    <div class="w-full md:w-[48%] space-y-4">
                        <!--dat mua-->
                        <div x-show="showOrderBuy" x-cloak x-transition class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
                                <!-- Đóng -->
                                <button @click="showOrderBuy=false" class="absolute top-4 right-4 text-gray-400 hover:text-black text-xl font-bold">&times;</button>

                                <!-- Tiêu đề -->
                                <h2 class="text-xl font-bold mb-6 text-gray-800">Đặt Mua <span class="text-black"></span></h2>

                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-1">Tiền Tệ</label>
                                    <div class="flex mb-2">
                                        <input x-init="codeBuyer=code" x-model="codeBuyer" readonly   type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-rose-500" value="0">
                                    </div>
                                </div>

                                <!-- Input: Số lượng -->
                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-1">Số Lượng</label>
                                    <div class="flex mb-2">
                                        <input x-model="amountBuyer"  type="number" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-rose-500" value="0">
                                    </div>
                                </div>

                                <!-- Input: Giá -->
                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-1">Giá</label>
                                    <div class="flex">
                                        <input x-model="priceBuyer"  type="number" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-rose-500" value="0">
                                        <span class="px-3 py-2 bg-gray-100 border border-l-0 border-blue-300 rounded-r-md text-gray-600">USDT</span>
                                    </div>
                                </div>


                                <!-- Input: Tổng tiền thu -->
                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-1">Tổng Giá</label>
                                    <div class="flex">
                                        <input x-model="totalOrderBuy()" type="number" class="flex-1 px-3 py-2 border border-blue-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-400" readonly>
                                        <span class="px-3 py-2 bg-gray-100 border border-l-0 border-blue-300 rounded-r-md text-gray-600">USDT</span>
                                    </div>
                                </div>

                                <button @click="orderBuy" readonly class="w-full mt-4 bg-green-600 hover:bg-green-500 text-white font-bold py-2 rounded-lg text-center text-lg">
                                    Đặt Mua
                                </button>
                            </div>
                        </div>
                        <!-- Popup Sell -->
                        <div x-show="showTransactionSell" x-cloak>
                            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-50">
                                <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
                                    <button class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl" @click="showTransactionSell =! showTransactionSell">&times;</button>
                                    <h2 class="text-xl font-semibold mb-4">Giao Dịch</h2>

                                    <label class="block mb-2 text-sm font-medium">Số Lượng</label>
                                    <div class="flex items-center mb-4">
                                        <input type="number" x-model="amountSell" min="1" placeholder="0" class="w-full px-4 py-2 border rounded-l-md focus:outline-none" />
                                        <span class="px-3 py-2 border border-l-0 rounded-r-md bg-gray-100 min-w-[70px] text-center" x-text="selectedOfferSell?.currency?.code"></span>
                                    </div>

                                    <label class="block mb-2 text-sm font-medium">Tổng Giá</label>
                                    <div class="flex items-center mb-4">
                                        <input type="text" x-model="totalSell" placeholder="0" class="w-full px-4 py-2 border rounded-l-md bg-gray-100 focus:outline-none" />
                                        <span class="px-3 py-2 border border-l-0 rounded-r-md bg-gray-100 min-w-[70px] text-center">USDT</span>
                                    </div>

                                    <button @click="sell(amountSell, selectedOfferSell)" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-semibold py-2 rounded-lg" x-text="`Bán ${selectedOfferSell?.currency?.code || ''}`"></button>
                                </div>
                            </div>
                        </div>

                        <!-- Bảng Mua -->
                        <div class="bg-white p-4 rounded-xl shadow-md">
                            <h3 class="text-lg font-bold mb-2">Danh Sách Mua</h3>
                            <table class="w-full border-collapse text-sm">
                                <thead class="bg-gray-100 text-gray-500">
                                <tr class="text-center">
                                    <th class="px-3 py-2">Người Mua</th>
                                    <th class="px-3 py-2">Tiền Tệ</th>
                                    <th class="px-3 py-2">Số Lượng</th>
                                    <th class="px-3 py-2">Số Lượng Còn Lại</th>
                                    <th class="px-3 py-2">Giá</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($offersBuy as $offerBuy)
                                    <tr class="text-center border-t ">
                                        <td class="px-3 py-2 font-semibold">{{$offerBuy->user->name}}</td>
                                        <td class="flex items-center space-x-2 inline-flex">
                                            <img class="w-4 h-4 object-contain" src="{{ $offerBuy->img }}" alt="{{ $offerBuy->currency->code }}">
                                            <span>{{ $offerBuy->currency->code }}</span>
                                        </td>
                                        <td>{{ rtrim(rtrim(number_format($offerBuy->amount, 8, '.', ''), '0'), '.') }}</td>
                                        <td>{{ rtrim(rtrim(number_format($offerBuy->available_amount, 8, '.', ''), '0'), '.') }}</td>
                                        <td class="text-green-500 font-semibold">{{ rtrim(rtrim(number_format($offerBuy->price, 8, '.', ''), '0'), '.') }}</td>
                                        <td>
                                            <button class="bg-red-50 text-pink-500 font-bold text-xs px-3 py-1 rounded transition duration-200 hover:bg-red-500 hover:text-white" @click="if(!checkStatusSeller({{ json_encode($offerBuy) }})) { if (!checkSellerId({{ json_encode($offerBuy) }}, userId)) { selectedOfferSell = {{ json_encode($offerBuy) }}; showTransactionSell = true; }}">Bán</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="text-right mt-4">
                                <div class="flex space-x-4 justify-between">
                                    <div>
                                        <!-- Nút Previous -->
                                        <button
                                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 {{ $offersBuy->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            @click="{{ $offersBuy->onFirstPage() ? '' : 'window.location.href=\'' . $offersBuy->previousPageUrl() . '\'' }}"
                                        >
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <!-- Nút Next -->
                                        <button
                                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 {{ $offersBuy->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                                            @click="{{ $offersBuy->hasMorePages() ? 'window.location.href=\'' . $offersBuy->nextPageUrl() . '\'' : '' }}"
                                        >
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <button @click="showOrderBuy =! showOrderBuy" class="font-semibold text-green-600 border border-green-600 bg-white py-2 px-4 rounded-xl transition duration-200 hover:bg-green-600 hover:text-white">Đặt Mua</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" p-4 rounded-xl shadow-md px-10 bg-gray-100">
            <div class="p-6 bg-white rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Danh sách Lệnh Đang Chờ, Lịch Sử</h2>

                <div class="flex items-center space-x-6 mb-6">
                    <button @click="pendingOrder =! pendingOrder, transactionOrder = false" data-toggle="pendingOrder" class="bg-blue-100 text-blue-600 font-semibold px-4 py-1 rounded-full">📄 Lệnh đang chờ</button>
                    <button @click="transactionOrder =! transactionOrder, pendingOrder = false" data-toggle="transactionOrder" class="bg-blue-100 text-blue-600 font-semibold px-4 py-1 rounded-full">⇄ Lịch sử giao dịch</button>
                </div>

                <div class="overflow-x-auto" x-show="pendingOrder" x-cloak id="pending-order">
                    <table class="min-w-full text-sm text-gray-600">
                        <thead>
                        <tr class="text-black-400 uppercase text-xs border-b">
                            <th class="px-4 py-2 text-left">Hình Thức</th>
                            <th class="px-4 py-2 text-left">Tiền Tệ</th>
                            <th class="px-4 py-2 text-left">Giá (USDT)</th>
                            <th class="px-4 py-2 text-left">Số Lượng</th>
                            <th class="px-4 py-2 text-left">Số Lượng Còn Lại </th>
                            <th class="px-4 py-2 text-left">Thời gian</th>
                            <th class="px-4 py-2 text-left"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($offerPendings->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-4 0h4m4 0a2 2 0 002-2v-6a2 2 0 00-2-2h-4m-4 0H5a2 2 0 00-2 2v6a2 2 0 002 2h4" />
                                        </svg>
                                        <span>Không có dữ liệu</span>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($offerPendings as $offerPending)
                                <tr class="text-gray-400 uppercase text-xs border-b">
                                    @if($offerPending->type == 'Sell')
                                        <th class="px-4 py-4 text-left">Bán</th>
                                    @elseif($offerPending->type == 'Buy')
                                        <th class="px-4 py-4 text-left">Mua</th>
                                    @endif()
                                    <td class=" px-4 py-4 text-left flex items-center space-x-2 inline-flex text-gray-600">
                                        <img class="w-4 h-4 object-contain" src="{{ $offerPending->img }}" alt="">
                                        <span> {{$offerPending->currency->code}} </span>
                                    </td>
                                    <th class="px-4 py-4 text-left">{{rtrim(rtrim(number_format($offerPending->price, 8, '.', ''), '0'), '.')}}</th>
                                    <th class="px-4 py-4 text-left">{{rtrim(rtrim(number_format($offerPending->amount, 8, '.', ''), '0'), '.')}}</th>
                                    <th class="px-4 py-4 text-left">{{rtrim(rtrim(number_format($offerPending->available_amount, 8, '.', ''), '0'), '.')}}</th>
                                    <th class="px-4 py-4 text-left">{{$offerPending->updated_at->format('H:i d/m/Y')}}</th>
                                    @if($offerPending->amount == $offerPending->available_amount)
                                    <th class="px-4 py-2 text-left text-white "><button class="bg-white text-blue-500 font-bold px-4 py-2 rounded-full border border-blue-400 hover:bg-blue-400 hover:text-white hover:shadow-md transition" @click="cancel = true; selectedOrderId = {{$offerPending->id}}">HỦY LỆNH</button></th>
                                    @endif()
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="mt-4 hidden">
                        {{ $offerPendings->withQueryString()->fragment('pending-order')->links() }}
                    </div>
                    <div class="mt-4 flex space-x-4 justify-between">
                        <button
                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 {{ $offerPendings->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            @click="{{ $offerPendings->onFirstPage() ? '' : 'window.location.href=\'' . $offerPendings->previousPageUrl() . '\'' }}"
                        >
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button
                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 {{ $offerPendings->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                            @click="{{ $offerPendings->hasMorePages() ? 'window.location.href=\'' . $offerPendings->nextPageUrl() . '\'' : '' }}"
                        >
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!--nut huy lenh-->
                <div x-show="cancel" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
                        <h1 class="text-xl font-semibold mb-4">Bạn có chắc chăn muốn hủy lệnh không</h1>
                        <div class="flex space-x-4">
                            <button @click="deleteOffersPending(selectedOrderId); cancel = false" class="w-full bg-blue-400 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg">Chắc Chắn</button>
                            <button @click="cancel = false" class="w-full bg-blue-400 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg">Hủy</button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto" x-show="transactionOrder" x-cloak id="transaction-history">
                    <table class="min-w-full text-sm text-gray-600">
                        <thead>
                        <tr class="text-black-400 uppercase text-xs border-b">
                            <th class="px-4 py-2 text-left">Hình Thức</th>
                            <th class="px-4 py-2 text-left">Tiền Tệ</th>
                            <th class="px-4 py-2 text-left">Số Lượng USDT Trước Đó</th>
                            <th class="px-4 py-2 text-left">Thay Đổi</th>
                            <th class="px-4 py-2 text-left">Số Lượng USDT Sau Đó</th>
                            <th class="px-4 py-2 text-left">Thời Gian</th>
                            <th class="px-4 py-2 text-left">Ghi Chú</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($transactions === 'NULL')
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-4 0h4m4 0a2 2 0 002-2v-6a2 2 0 00-2-2h-4m-4 0H5a2 2 0 00-2 2v6a2 2 0 002 2h4" />
                                        </svg>
                                        <span>Không có dữ liệu</span>
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($transactions as $transaction)
                                <tr class="text-gray-400 uppercase text-xs border-b">
                                    @if($transaction->type == 'Sell')
                                        <th class="px-4 py-4 text-left">Bán</th>
                                    @elseif($transaction->type == 'Buy')
                                        <th class="px-4 py-4 text-left">Mua</th>
                                    @elseif($transaction->type == 'send')
                                        <th class="px-4 py-4 text-left">Gửi</th>
                                    @elseif($transaction->type == 'receive')
                                        <th class="px-4 py-4 text-left">Nhận</th>
                                    @endif
                                    <td class=" px-4 py-4 text-left flex items-center space-x-2 inline-flex text-gray-600">
                                        <img class="w-4 h-4 object-contain" src="{{ $transaction->img }}" alt="">
                                        <span> {{$transaction->currency}} </span>
                                    </td>
                                    <th class="px-4 py-4 text-left">{{ rtrim(rtrim(number_format($transaction->before_balance, 8, '.', ''), '0'), '.') }}</th>
                                    <th class="px-4 py-4 text-left">{{ rtrim(rtrim(number_format($transaction->change, 8, '.', ''), '0'), '.') }}</th>
                                    <th class="px-4 py-4 text-left">{{ rtrim(rtrim(number_format($transaction->after_balance, 8, '.', ''), '0'), '.') }}</th>
                                    <th class="px-4 py-4 text-left">{{ $transaction->updated_at->format('H:i d/m/Y') }}</th>
                                    <th class="px-4 py-4 text-left normal-case">{{ $transaction->note }}</th>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <div class="mt-4 hidden">
                        {{ $transactions->withQueryString()->fragment('transaction-history')->links() }}
                    </div>
                    <div class="mt-4 flex space-x-4 justify-between">
                        <button
                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 {{ $transactions->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            @click="{{ $transactions->onFirstPage() ? '' : 'window.location.href=\'' . $transactions->previousPageUrl() . '\'' }}"
                        >
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button
                            class="p-2 rounded-lg border border-gray-300 hover:bg-gray-100 {{ $transactions->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}"
                            @click="{{ $transactions->hasMorePages() ? 'window.location.href=\'' . $transactions->nextPageUrl() . '\'' : '' }}"
                        >
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        function data() {
            return {
                code: '{{ $code ?? '' }}',
                async getCode(code) {
                    try {
                        window.location.href = `/trading/${code}`;
                    } catch (error) {
                        alert(error.response?.data?.message || 'Đã xảy ra lỗi');
                    }
                },


                userId: {{ auth()->id() }},
                showTransactionSell: false,
                amountSell: 0,
                selectedOfferSell: null,


                showTransactionBuy: false,
                amountBuy: 0,
                selectedOfferBuy: null,

                //order sell
                showOrderSell: false,
                codeSeller: '',
                amountSeller: '',
                priceSeller: '',
                totalSeller: '',
                totalOrderSell: function() {
                    total = this.amountSeller * this.priceSeller - (this.amountSeller * this.priceSeller)*(0.1/100);

                    return total;
                },


                //order buy
                showOrderBuy: false,
                codeBuyer: '',
                amountBuyer: '',
                priceBuyer: '',
                totalBuyer: '',
                totalOrderBuy: function() {
                    total = this.amountBuyer * this.priceBuyer + (this.amountBuyer * this.priceBuyer)*(0.1/100);
                    return total;
                },


                pendingOrder: window.location.hash === '#pending-order',
                transactionOrder: window.location.hash === '#transaction-history',



                cancel: false,
                selectedOrderId: '',



            }
        }


        function tradingPage() {
            const hash = window.location.hash;
            return {
                pendingOrder: hash === '#pending-order',
                transactionOrder: hash === '#transaction-history',
                cancel: false,
                selectedOrderId: '',
            }
        }

        async function deleteOffersPending(id) {
            try {
                await axios.delete(`${window.location.origin}/offers/${id}`);
                console.log(data);
                alert('CANCEL SUCCESS');
                location.reload();
            } catch (error) {
                alert(error.response?.data?.message || 'Đã xảy ra lỗi');
            }
        }




        function checkStatusSeller(selectedOfferSell) {
            if(selectedOfferSell?.status === 'Completed') {
                alert('không thể giao dịch với lệnh này');
                return true;
            }
            return false;
        }

        function checkStatusBuyer(selectedOfferBuyer) {
            if(selectedOfferBuyer?.status === 'Completed') {
                alert('không thể giao dịch với lệnh này');
                return true;
            }
            return false;
        }


        async function orderSell() {
            const body = {
                codeSeller: this.codeSeller,
                amountSeller: this.amountSeller,
                priceSeller: this.priceSeller,
            };
            try {
                const { data } = await axios.post('/orders/sell', body);
                console.log(data);
                alert('dat ban thanh cong');
                window.location.href = `/trading/${this.codeSeller}`;
                this.showOrderSell = false;
            } catch (error) {
                alert(error.response?.data?.message || 'Đã xảy ra lỗi');
            }
        }

        async function orderBuy() {
            const body = {
                codeBuyer: this.codeBuyer,
                amountBuyer: this.amountBuyer,
                priceBuyer: this.priceBuyer,
            };
            try {
                const { data } = await axios.post('/orders/buy', body);
                console.log(data);
                alert('dat mua thanh cong');
                window.location.href = `/trading/${this.codeBuyer}`;
                this.showOrderBuy = false;
            } catch (error) {
                alert(error.response?.data?.message || 'Đã xảy ra lỗi');
            }
        }



        function totalSell() {
            if(!this.selectedOfferSell) {
                return 0;
            }
             return this.amountSell * this.selectedOfferSell.price;
        }

        function totalBuy() {
            if(!this.selectedOfferBuy) {
                return 0;
            }
            return this.amountBuy * this.selectedOfferBuy.price;
        }

        function checkBuyerId(selectedOfferBuy, userId) {
            if (selectedOfferBuy?.user_id === userId) {
                alert('Không thể giao dịch với chính bạn');
                return true;
            }
            return false;
        }

        function checkSellerId(selectedOfferSell, userId) {
            if (selectedOfferSell?.user_id === userId) {
                alert('Không thể giao dịch với chính bạn');
                return true;
            }
            return false;
        }

        async function buy(amountBuy, selectedOfferBuy) {
            const body = {
                amount: amountBuy,
            };
            try {
                const { data } = await axios.put(`/offers/${selectedOfferBuy?.id}/buy`, body);
                console.log(data);
                alert('Giao dịch thành công!');
                window.location.href = `/trading/${selectedOfferBuy?.currency?.code}`;
                showTransactionBuy = false;
            } catch (error) {
                alert(error.response?.data?.message || 'Đã xảy ra lỗi');
            }
        }


        async function sell(amountSell, selectedOfferSell) {
            const body = {
                amount: amountSell,
            };
            try {
                const { data } = await axios.put(`/offers/${selectedOfferSell?.id}/sell`, body);
                console.log(data);
                alert('Giao dịch thành công!');
                window.location.href = `/trading/${selectedOfferSell?.currency?.code}`;
                showTransactionSell = false;
            } catch (error) {
                alert(error.response?.data?.message || 'Đã xảy ra lỗi');
            }
        }


        // const amountInput = document.getElementById('amountInput');
        // const totalInput = document.getElementById('totalInput');
        // const pricePerBTC = 2095928119;
        //
        // amountInput.addEventListener('input', () => {
        //     const amount = parseFloat(amountInput.value) || 0;
        //     const total = amount * pricePerBTC;
        //     totalInput.value = total.toLocaleString('vi-VN');
        // });
    </script>
</x-app-layout>
