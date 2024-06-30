<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування Учасника</title>
    <style>
        /* Ваш стиль CSS тут */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto; /* центруємо контейнер по горизонталі */
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-container {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .form-container h3 {
            margin-bottom: 10px;
            text-align: center; /* Центруємо текст заголовка */
        }
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="tel"],
        .form-container select {
            width: calc(100% - 16px);
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 16px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            display: block; /* Робимо кнопку блоковим елементом для центрування */
            margin: 0 auto; /* Центруємо кнопку */
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-button {
            text-align: center;
            margin-top: 20px;
        }
        .back-button a {
            background-color: #ccc;
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }
        .back-button a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Редагування Учасника</h2>
        <?php
        // Перевірка, чи існує параметр ID в URL
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            die('Помилка: Невірний ідентифікатор учасника.');
        }

        $id = $_GET['id'];

        // Підключення до бази даних
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "club";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Помилка підключення до бази даних: " . $conn->connect_error);
        }

        // Отримання даних учасника за його ID
        $sql_select = "SELECT * FROM Учасники WHERE id=$id";
        $result = $conn->query($sql_select);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            die('Помилка: Не вдалося знайти учасника з ідентифікатором ' . $id);
        }

        // Обробка оновлення даних учасника
        $update_message = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $status = $_POST['status'];

            $sql_update = "UPDATE Учасники SET ім_я='$name', прізвище='$surname', електронна_пошта='$email', 
                          телефон='$phone', статус='$status' WHERE id=$id";

            if ($conn->query($sql_update) === TRUE) {
                $update_message = "Дані учасника оновлено успішно!";
                // Оновлення $row для відображення нових даних у формі
                $row['ім_я'] = $name;
                $row['прізвище'] = $surname;
                $row['електронна_пошта'] = $email;
                $row['телефон'] = $phone;
                $row['статус'] = $status;
            } else {
                $update_message = "Помилка оновлення: " . $conn->error;
            }
        }
        $conn->close();
        ?>
        <?php if (!empty($update_message)): ?>
            <p style="text-align: center;"><?php echo $update_message; ?></p>
        <?php endif; ?>
        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="POST">
                <label for="name">Ім'я:</label><br>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['ім_я']); ?>" required><br>
                <label for="surname">Прізвище:</label><br>
                <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($row['прізвище']); ?>" required><br>
                <label for="email">Електронна пошта:</label><br>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['електронна_пошта']); ?>" required><br>
                <label for="phone">Телефон:</label><br>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($row['телефон']); ?>" required><br>
                <label for="status">Статус:</label><br>
                <select id="status" name="status" required>
                    <option value="Активний" <?php if ($row['статус'] === 'Активний') echo 'selected'; ?>>Активний</option>
                    <option value="Неактивний" <?php if ($row['статус'] === 'Неактивний') echo 'selected'; ?>>Неактивний</option>
                    <option value="Новачок" <?php if ($row['статус'] === 'Новачок') echo 'selected'; ?>>Новачок</option>
                </select><br><br>
                <input type="submit" value="Оновити дані учасника">
            </form>
        </div>
        <div class="back-button">
            <a href="participants.php">Повернутися до списку учасників</a>
        </div>
    </div>
</body>
</html>
