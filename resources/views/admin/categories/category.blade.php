<tr onclick="window.location='{{route('admin.categories.show', $category->id)}}'"" style="cursor: pointer;">
   <th scope="row">{{$category->id}}</th>
   <td>{{$category->name}}</td>
   <td>{{$category->state}}</td
</tr>