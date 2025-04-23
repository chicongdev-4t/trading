<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100">
        <div class="max-w-6xl mx-auto p-4">
            <!-- Tổng tài sản -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6 space-y-3">
                <h2 class="flex items-center space-x-2 justify-between">
                    <div class="text-2xl font-semibold mb-4 flex items-center space-x-2">
                        <img class="w-6 h-6" src="https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/cea2fa38-61ae-4fd9-b229-fbe622f3dcdd.png" alt="">
                        <span>Tổng Tài Sản</span>
                    </div>
                    <div  class="flex space-x-20">
                        <a href="">
                            <button class="flex items-center px-3 py-2 bg-blue-50 text-blue-600 font-semibold rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Nạp
                            </button>
                        </a>

                        <a href="/withdraw/{{ $accountUSDT->name }}/{{ $accountUSDT->balance }}/{{$accountUSDT->id}}/{{$accountUSDT->user_id}}">
                            <button class="flex items-center px-3 py-2 bg-blue-400 text-white font-bold rounded-full hover:bg-blue-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14L21 3m0 0l-6 18-2-8-8-2 16-8z" />
                                </svg>
                                Rút
                            </button>
                        </a>
                    </div>
                </h2>

                <div class="bg-gray-100 p-4 rounded-lg flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Số Dư Khả Dụng</p>
                        <h3 class="text-2xl font-semibold">{{ rtrim(rtrim(number_format($accountUSDT->balance, 8, '.', ''), '0'), '.') }}  USDT</h3>
                    </div>
                    <button class="text-gray-500 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"></svg>
                    </button>
                </div>
            </div>

            <!-- Danh sách ví -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-4 border-b font-semibold text-gray-700 grid grid-cols-10">
                    <div class="col-span-2">Tiền Tệ</div>
                    <div class="col-span-2 text-center">Số Dư </div>

                </div>

                <div class="divide-y">
                    @foreach ($coins as $coin)
                        @include('components.trading.listCoin',[
                            'id' => $coin['id'],
                            'user_id' => $coin['user_id'],
                            'img' => $coin['img'],
                            'name' => $coin['name'],
                            'balance' => $coin['balance']
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
