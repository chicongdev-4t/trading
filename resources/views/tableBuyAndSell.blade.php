<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen">
<div>
    <div>
        <div>
            <table class="w-full  border-collapse">
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">user_id</th>
                    <th class="px-4 py-2">currency_id</th>
                    <th class="px-4 py-2">type</th>
                    <th class="px-4 py-2">amount</th>
                    <th class="px-4 py-2">available amount</th>
                    <th class="px-4 py-2">price</th>
                    <th class="px-4 py-2">status</th>
                </tr>
                @foreach($offers as $offer)
                    @if($offer->type == 'sell')
                        <tr>
                            <td class="px-4 py-2 border-t">{{$offer->user_id}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->currency_id}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->type}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->amount}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->available_amount}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->price}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->status}}</td>
                        </tr>
                    @endif()
                @endforeach
            </table>
        </div>
        <div>
            <table class="w-full max-w-4xl border-collapse">
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">user_id</th>
                    <th class="px-4 py-2">currency_id</th>
                    <th class="px-4 py-2">type</th>
                    <th class="px-4 py-2">amount</th>
                    <th class="px-4 py-2">available amount</th>
                    <th class="px-4 py-2">price</th>
                    <th class="px-4 py-2">status</th>
                </tr>
                @foreach($offers as $offer)
                    @if($offer->type == 'buy')
                        <tr>
                            <td class="px-4 py-2 border-t">{{$offer->user_id}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->currency_id}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->type}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->amount}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->available_amount}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->price}}</td>
                            <td class="px-4 py-2 border-t">{{$offer->status}}</td>
                        </tr>
                    @endif()
                @endforeach
            </table>
        </div>
    </div>
</div>
</body>
</html>


