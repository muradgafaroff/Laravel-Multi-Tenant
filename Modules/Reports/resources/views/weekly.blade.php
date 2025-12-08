<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    


<h2>Weekly Completed Tasks Report</h2>

<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Task</th>
            <th>Completed By</th>
            <th>Completed At</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $task)
        <tr>
            <td>{{ $task['title'] }}</td>
            <td>{{ $task['completed_by'] }}</td>
            <td>{{ $task['completed_at'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>



</body>
</html>

