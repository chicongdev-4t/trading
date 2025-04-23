<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>


<div x-data="data()" x-init="getAccountWithdrawal()" class=" pt-8 bg-gray-100">
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md mb-10">
        <h2 class="text-xl font-semibold mb-4">Ví {{$name}}</h2>

        <!-- Số dư -->
        <div class="mb-4">
            <div class="text-gray-500 text-sm">Số dư {{$name}} khả dụng</div>
            <div class="text-yellow-500 text-2xl font-bold">{{ rtrim(rtrim(number_format($balance, 8, '.', ''), '0'), '.') }}</div>
        </div>

        <!-- Mạng lưới -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Mạng lưới</label>
            <select class="w-full border rounded px-3 py-2 text-sm pointer-events-none">
                <option> The Open Network</option>
            </select>
        </div>

        <!-- Địa chỉ ví nhận -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Địa chỉ ví nhận</label>
            <div class="relative">
                <input x-model="address" type="text" placeholder="Ví dụ: UQB...WA_h" class="w-full border rounded px-3 py-2 text-sm pr-10" />
                <button class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                    📋
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">Hãy chắc chắn rằng bạn nhập đúng địa chỉ TON mà bạn muốn rút về đó.</p>
        </div>

        <!-- Số lượng rút -->
        <div class="mb-4">
            <label class="block mb-1 font-medium">Số lượng rút ({{$name}})</label>
            <div class="flex items-center">
                <input  x-model="amount" type="number" value="0" class="w-full border rounded px-3 py-2 text-sm" />
                <span class="ml-2 text-sm text-gray-600">{{$name}}</span>
            </div>

        </div>

        <!-- Memo -->
        <div class="mb-2">
            <label class="block mb-1 font-medium">Nội dung (memo)</label>
            <input x-model="memo" type="text" placeholder="Kiểm tra chính xác memo trước khi thực hiện rút" class="w-full border rounded px-3 py-2 text-sm" />
        </div>

        <!-- Nút -->
        <button @click="storeAccountWithdrawal()" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
            RÚT TIỀN
        </button>
    </div>

    <!--lich su rut -->

    <div>
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="font-semibold text-base text-gray-800 mb-4">Danh sách lệnh {{$name}} đã rút</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700">
                    <tr class="text-xs text-gray-500 uppercase text-left border-b">
                        <th class="py-3 px-4">Số lượng</th>
                        <th class="py-3 px-4">Địa chỉ rút</th>
                        <th class="py-3 px-4">Mạng rút</th>
                        <th class="py-3 px-4">Trạng thái</th>
                        <th class="py-3 px-4">Note</th>
                        <th class="py-3 px-4">TX</th>
                        <th class="py-3 px-4">Lúc</th>
                    </tr>
                    <template x-if="!accountWithdrawals || accountWithdrawals.length === 0">
                        <tr>
                            <td colspan="7" class="py-16">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h13M9 17V5l-7 7 7 7z" />
                                    </svg>
                                    <p class="text-sm">Không có dữ liệu</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="accountWithdrawal in accountWithdrawals" :key="accountWithdrawal.id">
                        <tr class="text-gray-400 uppercase text-xs border-b">
                            <th class="px-4 py-4 text-left" x-text="parseFloat(accountWithdrawal.amount).toFixed(8).replace(/0+$/, '').replace(/\.$/, '')"></th>
                            <th class="px-4 py-4 text-left"></th>
                            <th class="px-4 py-4 text-left">The Open Network</th>
                            <th class="px-4 py-4 text-left" x-text="accountWithdrawal.status || 'N/A'"></th>
                            <th class="px-4 py-4 text-left" x-text="accountWithdrawal.memo || ''"></th>
                            <th class="px-4 py-4 text-left" x-text="accountWithdrawal.transaction_id || ''"></th>
                            <th class="px-4 py-4 text-left" x-text="new Date(accountWithdrawal.updated_at).toLocaleString('vi-VN', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit', year: 'numeric' })"></th>
                        </tr>
                    </template>
                </table>
            </div>
            <!-- Phân trang -->
            <div class="flex justify-start items-center gap-4 mt-6 justify-between">
                <button
                    @click="prevPage()"
                    :disabled="currentPage === 1"
                    class="w-10 h-8 flex items-center justify-center rounded-md border border-gray-300 text-gray-500 hover:bg-gray-100 transition"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span class="text-sm text-gray-600">
                     Trang <span x-text="currentPage"></span> / <span x-text="lastPage"></span>
                </span>
                <button
                    @click="nextPage()"
                    :disabled="currentPage === lastPage"
                    class="w-10 h-8 flex items-center justify-center rounded-md border border-gray-300 text-gray-500 hover:bg-gray-100 transition"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

        </div>

    </div>
   <div class="h-8 bg-gray"></div>
</div>

    <script>
        function data() {
            return {
                amount: '',
                nameAccount: '{{$name}}',
                id: '{{$id}}',
                user_id: '{{$user_id}}',
                balance: '{{$balance}}',
                memo: '',
                address: '',
                accountWithdrawals: [],
                currentPage: 1, // Theo dõi trang hiện tại
                lastPage: 1, // Theo dõi tổng số trang

                async storeAccountWithdrawal() {
                    if (!this.amount || this.amount <= 0) {
                        alert('Vui lòng nhập số lượng hợp lệ');
                        return;
                    }
                    if (!this.address) {
                        alert('Vui lòng nhập địa chỉ ví nhận');
                        return;
                    }
                    try {
                        const body = {
                            amount: this.amount,
                            nameAccount: this.nameAccount,
                            id: this.id,
                            user_id: this.user_id,
                            balance: this.balance,
                            memo: this.memo || '', // Gửi memo rỗng nếu không có
                            address: this.address
                        };
                        const {data} = await axios.post('/withdraw', body);
                        alert('Đã gửi lệnh rút thành công');
                        console.log(data);
                        await this.getAccountWithdrawal();
                    } catch (error) {
                        console.error(error);
                        if (error.response?.status === 422) {
                            alert('Dữ liệu không hợp lệ: ' + JSON.stringify(error.response.data.errors));
                        } else {
                            alert('Đã xảy ra lỗi khi gửi lệnh rút');
                        }
                    }
                },

                async getAccountWithdrawal(page = 1) {
                    try {
                        this.currentPage = page; // Cập nhật trang hiện tại
                        const { data } = await axios.get(`/api/account-withdrawals/${this.nameAccount}/${this.user_id}?page=${page}`);
                        console.log('API Response:', data); // Debug dữ liệu
                        this.accountWithdrawals = data.data; // Dữ liệu phân trang
                        this.lastPage = data.last_page; // Cập nhật tổng số trang
                    } catch (error) {
                        console.error('API Error:', error);
                        this.accountWithdrawals = [];
                    }
                },

                // Chuyển về trang trước
                prevPage() {
                    if (this.currentPage > 1) {
                        this.getAccountWithdrawal(this.currentPage - 1);
                    }
                },

                // Chuyển sang trang sau
                nextPage() {
                    if (this.currentPage < this.lastPage) {
                        this.getAccountWithdrawal(this.currentPage + 1);
                    }
                }



            }



        }
    </script>
</x-app-layout>



