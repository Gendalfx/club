<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблиця Учасники</title>
    <style>
        /* Ваш стиль CSS тут */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .scroll-container {
            width: 100%;
            overflow-x: auto; /* дозволяє прокручувати горизонтально */
        }
        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto; /* центруємо контейнер по горизонталі */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn {
            padding: 6px 10px;
            font-size: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #45a049;
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
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="scroll-container">
        <div class="container">
            <h2>Таблиця Учасники</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ім'я</th>
                        <th>Прізвище</th>
                        <th>Електронна пошта</th>
                        <th>Телефон</th>
                        <th>Статус</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Підключення до бази даних
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "club";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Помилка підключення до бази даних: " . $conn->connect_error);
                }

                // Функція для санітарної очистки вхідних даних
                function sanitize_input($data) {
                    return htmlspecialchars(stripslashes(trim($data)));
                }

                // Обробка додавання нового учасника
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $name = sanitize_input($_POST['name']);
                    $surname = sanitize_input($_POST['surname']);
                    $email = sanitize_input($_POST['email']);
                    $phone = sanitize_input($_POST['phone']);
                    $status = sanitize_input($_POST['status']);

                    $sql_insert = "INSERT INTO Учасники (ім_я, прізвище, електронна_пошта, телефон, статус) 
                                   VALUES ('$name', '$surname', '$email', '$phone', '$status')";

                    if ($conn->query($sql_insert) === TRUE) {
                        echo "<p>Новий учасник доданий успішно!</p>";
                    } else {
                        echo "Помилка: " . $sql_insert . "<br>" . $conn->error;
                    }
                }

                // Обробка видалення учасника
                if (isset($_GET['delete_id'])) {
                    $delete_id = sanitize_input($_GET['delete_id']);
                    $sql_delete = "DELETE FROM Учасники WHERE id=$delete_id";

                    if ($conn->query($sql_delete) === TRUE) {
                        echo "<p>Учасника видалено успішно!</p>";
                    } else {
                        echo "Помилка видалення: " . $conn->error;
                    }
                }

                // Запит до бази даних для отримання даних з таблиці Учасники
                $sql_select = "SELECT id, ім_я, прізвище, електронна_пошта, телефон, статус FROM Учасники";
                $result = $conn->query($sql_select);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row["id"]."</td>
                                <td>".$row["ім_я"]."</td>
                                <td>".$row["прізвище"]."</td>
                                <td>".$row["електронна_пошта"]."</td>
                                <td>".$row["телефон"]."</td>
                                <td>".$row["статус"]."</td>
                                <td>
                                    <button class='btn' onclick=\"editParticipant(".$row['id'].")\">Редагувати</button>
                                    <button class='btn' onclick=\"deleteParticipant(".$row['id'].")\">Видалити</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>0 результатів</td></tr>";
                }

                $conn->close();
                ?>
                </tbody>
            </table>

            <div class="form-container">
                <h3>Додати нового учасника</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <label for="name">Ім'я:</label><br>
                    <input type="text" id="name" name="name" required><br>
                    <label for="surname">Прізвище:</label><br>
                    <input type="text" id="surname" name="surname" required><br>
                    <label for="email">Електронна пошта:</label><br>
                    <input type="email" id="email" name="email" required><br>
                    <label for="phone">Телефон:</label><br>
                    <input type="tel" id="phone" name="phone" required><br>
                    <label for="status">Статус:</label><br>
                    <select id="status" name="status" required>
                        <option value="Активний">Активний</option>
                        <option value="Неактивний">Неактивний</option>
                        <option value="Новачок">Новачок</option>
                    </select><br><br>
                    <input type="submit" value="Додати учасника">
                </form>
            </div>
        </div>
    </div>

    <script>
        function editParticipant(id) {
            window.location.href = 'edit_participant.php?id=' + id;
        }

        function deleteParticipant(id) {
            if (confirm("Ви впевнені, що хочете видалити цього учасника?")) {
                window.location.href = 'participants.php?delete_id=' + id;
            }
        }
    </script>
</body>
</html>
