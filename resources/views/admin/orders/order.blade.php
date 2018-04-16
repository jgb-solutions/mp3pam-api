<tr onclick="window.location='{{route('admin.orders.show', $order->id)}}'"" style="cursor: pointer;">
   <th scope="row">{{$order->id}}</th>
   <td>{{$order->price}}</td>
   <td>{{$order->state}}</td>
   <td>{{$order->branch->name}}</td>
   <td>{{$order->client->name}}</td>
   <td>{{$order->amount}}</td>
</tr>