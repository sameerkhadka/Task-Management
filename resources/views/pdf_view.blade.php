<html>
<head>
<style>
  table {
    border-collapse: separate;
    border-spacing: 0;
    color: #4a4a4d;
    font: 14px/1.4 "Helvetica Neue", Helvetica, Arial, sans-serif;
  }
  th,
  td {
    padding: 10px 15px;
    vertical-align: middle;
  }
  thead {   
    color: #ff6666;
    font-size: 11px;
    text-transform: uppercase;
  }
  th:first-child {
    border-top-left-radius: 5px;
    text-align: left;
  }
  th:last-child {
    border-top-right-radius: 5px;
  }
  tbody tr:nth-child(even) {
    background: #f0f0f2;
  }
  td {
    border-bottom: 1px solid #cecfd5;
    border-right: 1px solid #cecfd5;
  }
  td:first-child {
    border-left: 1px solid #cecfd5;
  }
  .book-title {
    color: #395870;
    display: block;
  }
  .text-offset {
    color: #7c7c80;
    font-size: 12px;
  }
  .item-stock,
  .item-qty {
    text-align: center;
  }
  .item-price {
    text-align: right;
  }
  .item-multiple {
    display: block;
  }
  tfoot {
    text-align: right;
  }
  tfoot tr:last-child {
    background: #f0f0f2;
    color: #395870;
    font-weight: bold;
  }
  tfoot tr:last-child td:first-child {
    border-bottom-left-radius: 5px;
  }
  tfoot tr:last-child td:last-child {
    border-bottom-right-radius: 5px;
  } 

</style>
</head>
<body>
Report of:{{ \App\Models\User::find($user)->name }}
<table>
  <thead>
    <tr>
      <th>S.N.</th>
      <th>Title</th>
      <th>Company Name</th>
      <th>Assigned Date</th>
      <th>Email proofed at</th>
      <th>Completed At</th>
    </tr>
  </thead>
  <tbody>
    @foreach($tasks as $task)
    <tr>
    <td>{{ $loop->iteration}}</td>
      <td>
        <strong class="book-title">{{ $task->title}}</strong>
      </td>
      <td class="item-stock">{{ $task->company->name}}</td>
      <td class="item-qty">{{ $task->assigned_date  }}</td>
      @if($task->email_proofing == true)
      <td>{{ $task->email_proofed_at  }}</td>
      @endif
      @if($task->completed_at == true)
      <td>{{ $task->completed_at  }}</td>
      @endif
    </tr>
    @endforeach
    
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5">Total Task</td>
      <td>{{ $count }}</td>
    </tr>
  </tfoot>
</table>
</body>
</html>

