<!DOCTYPE html>
<html>
<head>
    <title>METANIT.COM</title>
    <meta charset="utf-8">
</head>
<body>
<h2>Введите свои данные:</h2>
<form action="login.php" method="POST">
    <p>Введите имя: <input type="text" name="firstname" /></p>
    <p>Введите логин: <input type="text" name="login" /></p>
    <p>Введите возраст: <input type="number" name="age" /></p>
    <p>Введите пароль: <input type="text" name="password" /></p>
    <input type="submit" value="Отправить">
</form>
<button id="delete-button" onclick="">Удалить пользователя по id</button>
</body>
<script>
    document.getElementById('delete-button').addEventListener('click', function() {
        window.location.href = 'delete.php';
    });
</script>
</html>
