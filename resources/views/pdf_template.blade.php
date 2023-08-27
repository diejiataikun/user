<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th>Account</th>
        <th>Name</th>
        <th>Grade</th>
        <th>Specialized</th>
        <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item['account'] }}</td>
            <td>{{ $item['name'] }}</td>
            <td>{{ $item['grade'] }}</td>
            <td>{{ $item['specialized'] }}</td>
            <td>{{ $item['score'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
