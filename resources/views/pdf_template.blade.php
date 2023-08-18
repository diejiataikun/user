<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="UTF-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th>专有id</th>
        <th>账号</th>
        <th>密码</th>
        <th>实验室代码</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{ $row->t_id }}</td>
            <td>{{ $row->account }}</td>
            <td>{{ $row->password }}</td>
            <td>{{ $row->lab_code_id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
