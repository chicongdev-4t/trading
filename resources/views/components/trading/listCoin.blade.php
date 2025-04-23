<div x-data="{name: '{{ $name }}',
        balance: '{{$balance}}',
        id: '{{$id}}',
        user_id: '{{$user_id}}',
        async transaction(name) {
                if (!name) {
                    alert('Vui lòng chọn loại tiền hợp lệ');
                    return;
                }
                try {
                    console.log(name);

                    window.location.href = `/trading/${name}`;
                } catch (error) {
                    alert('Đã xảy ra lỗi');
                }
            },
          validateName(name) {
                if(name == 'BTC' || name == 'ETH' || name == 'USDC') {
                  alert('không hỗ trợ rút coin này trên The Open Network ')
                  return false;
                }
                return true;
         }
     }" class="p-4 grid grid-cols-10 items-center">
    <!-- Coin -->
    <div class="col-span-2 flex items-center space-x-2">
        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
            <img class="w-6 h-6 object-contain" src="{{$img}}" alt="">
        </div>
        <span>{{$name}}</span>
    </div>

    <!-- Balance -->
    <div class="col-span-2 text-center text-blue-500">{{ rtrim(rtrim(number_format($balance, 8, '.', ''), '0'), '.') }}</div>

    <!-- Giao dịch -->
    <div class="col-span-2 text-right">
        <button @click="transaction(name)" :disabled="!name"  class="bg-white text-blue-500 font-bold px-4 py-2 rounded-full border border-blue-400 hover:bg-blue-400 hover:text-white hover:shadow-md transition">Giao Dịch</button>
    </div>
    <div class="col-span-2 flex justify-end">
        <a href="">
            <button class="flex items-center px-3 py-2 bg-blue-50 text-blue-600 font-semibold rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Nạp
            </button>
        </a>
    </div>

    <div class="col-span-2 flex justify-end">
        <a :href="'/withdraw/' + name + '/' + balance + '/' + id + '/' + user_id">
            <button @click.prevent="if (validateName(name)) window.location.href = '/withdraw/' + name + '/' + balance + '/' + id + '/' + user_id" class="flex items-center px-3 py-2 bg-blue-400 text-white font-bold rounded-full hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14L21 3m0 0l-6 18-2-8-8-2 16-8z" />
                </svg>
                Rút
            </button>
        </a>

    </div>




</div>

