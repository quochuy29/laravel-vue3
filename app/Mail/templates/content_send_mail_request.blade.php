<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Xin chào người dùng {{ $mailInfo['user_name'] }} !</p>
    <p>Yêu cầu [Điều chỉnh timesheet] của bạn đã bị {{ $mailInfo['user_approve'] }} {{$mailInfo['type']['type_request']}}</p>
    <p>Lý do là : </p></br>

    <img src="https://i.kym-cdn.com/entries/icons/original/000/037/848/cover2.jpg" alt="" width="200px">
</body>
</html>