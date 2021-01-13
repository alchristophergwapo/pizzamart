<center>
            <table border = "1">
            <tr>
                <td>Order ID</td>
                <td>PRODUCTNAME</td>
                <td>CUSTOMERNAME</td>
                <td>QUANTITY</td>
                <td>AMOUNT</td>
                <td>STORENAME</td>
                <td>DATE PURCHASED</td>
            </tr>
            @foreach ($data as $order)
            <tr>
                <!-- <td>{{ $order->trasactionId }}</td> -->
                <td><span>{{ $order->trasactionId}}</span></td>
                <td>{{ $order->productname }}</td>
                <td>{{ $order->lastname }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->amount }}</td>
                <td>{{ $order->storename }}</td>
                <td>{{ $order->timestamp}}</td>
                <td>
                    <button>DELETE</button>
                </td>
            </tr>
            @endforeach
        </center>