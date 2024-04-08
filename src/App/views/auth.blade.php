<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-size: 18px;
            font-family: "Ubuntu Mono", monospace;
        }

        html, body {
            height: 100%;
        }

        body {
            line-height: 1.5;
            background-color: #F1F3F6;
            color: #34495E;
            overflow-y: scroll;
        }


        a.button, input[type="submit"] {
            background-color: #62CB31;
            border-radius: 3px;
            color: #FFFFFF;
            padding: 18px 27px;
            border: none;
            display: inline-block;
            margin-top: 18px;
            font-weight: 700;
        }

        a.button:hover, input[type="submit"]:hover {
            background-color: #4EB722;
            color: #FFFFFF;
            cursor: pointer;
            text-decoration: none;
        }

        form div {
            margin-bottom: 18px;
        }

        form div:last-child {
            border-top: 1px dashed #E4E5E7;
        }

        form input[type="radio"] {
            position: relative;
            top: 2px;
            margin-left: 18px;
        }

        form input[type="text"], form input[type="password"], form input[type="email"] {
            padding: 0.75em 18px;
            width: 100%;
        }

        form input[type=text], form input[type="password"], form input[type="email"], textarea {
            color: #6A6C6F;
            background: #FFFFFF;
            border: 1px solid #E4E5E7;
            border-radius: 3px;
        }

        form label {
            display: inline-block;
            margin-bottom: 9px;
        }

        .error {
            color: #C0392B;
            font-weight: bold;
            display: block;
        }

        .error + textarea, .error + input {
            border-color: #C0392B !important;
            border-width: 2px !important;
        }

        textarea {
            padding: 18px;
            width: 100%;
            height: 266px;
        }

        button {
            background: none;
            padding: 0;
            border: none;
            color: #62CB31;
            text-decoration: none;
        }

        button:hover {
            color: #4EB722;
            text-decoration: underline;
            cursor: pointer;
        }

        div.flash {
            color: #FFFFFF;
            font-weight: bold;
            background-color: #34495E;
            padding: 18px;
            margin-bottom: 36px;
            text-align: center;
        }

        div.error {
            color: #FFFFFF;
            background-color: #C0392B;
            padding: 18px;
            margin-bottom: 36px;
            font-weight: bold;
            text-align: center;
        }

        table {
            background: white;
            border: 1px solid #E4E5E7;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            text-align: left;
            padding: 9px 18px;
        }

        th:last-child, td:last-child {
            text-align: right;
            color: #6A6C6F;
        }

        tr {
            border-bottom: 1px solid #E4E5E7;
        }

        tr:nth-child(2n) {
            background-color: #F7F9FA;
        }

    </style>
</head>
<body>
<div class='form'>
    <h2>{{$title}}</h2>
    <form method="post" action="/auth">
        <div class='form-group'>
            <label for="email">Электронная почта:</label>
            <input type="email" id="email" name="email" placeholder="Введите адрес электронной почты" required>
        </div>
        <div class='form-group'>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" placeholder="Введите пароль" required>
        </div>
        <button type="submit" >Авторизоваться</button>
    </form>
</div>
</body>
</html>

