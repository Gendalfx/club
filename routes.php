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

// Запит до бази даних для отримання даних з таблиці Маршрути
$sql = "SELECT id, назва, опис, вартість, дати_подорожі FROM Маршрути";
$result = $conn->query($sql);

// Закриваємо підключення до бази даних
$conn->close();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблиця Маршрути</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Таблиця Маршрути</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Назва</th>
                    <th>Опис</th>
                    <th>Вартість</th>
                    <th>Дати подорожі</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row["id"]."</td>
                                <td>".$row["назва"]."</td>
                                <td>".$row["опис"]."</td>
                                <td>".$row["вартість"]."</td>
                                <td>".$row["дати_подорожі"]."</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>0 результатів</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
