<?php
session_start();

// التحقق مما إذا كان المستخدم قد قام بتسجيل الدخول
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// إضافة منتج
if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $image_url = $conn->real_escape_string($_POST['image_url']);

    $sql = "INSERT INTO products (name, description, price, image_url) VALUES ('$name', '$description', '$price', '$image_url')";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>تم إضافة المنتج بنجاح</p>";
    } else {
        echo "<p class='error'>خطأ: " . $conn->error . "</p>";
    }
}

// حذف منتج
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $sql = "DELETE FROM products WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>تم حذف المنتج بنجاح</p>";
    } else {
        echo "<p class='error'>خطأ: " . $conn->error . "</p>";
    }
}

// تحديث منتج
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $image_url = $conn->real_escape_string($_POST['image_url']);

    $sql = "UPDATE products SET name='$name', description='$description', price='$price', image_url='$image_url' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>تم تحديث المنتج بنجاح</p>";
    } else {
        echo "<p class='error'>خطأ: " . $conn->error . "</p>";
    }
}

// جلب المنتجات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المسؤول</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
            direction: rtl;
        }
        header {
            background-color: #34495e;
            color: #ecf0f1;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form input, form textarea {
            display: block;
            margin-bottom: 15px;
            width: calc(100% - 24px);
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        form button {
            background-color: #2980b9;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #1c6ea4;
        }
        .success {
            color: #2ecc71;
            font-size: 16px;
            margin: 10px 0;
        }
        .error {
            color: #e74c3c;
            font-size: 16px;
            margin: 10px 0;
        }
        table {
            width: calc(100% - 40px);
            margin: 20px;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #2980b9;
            color: #fff;
        }
        img {
            max-width: 120px;
            height: auto;
            border-radius: 5px;
        }
        .btn {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            margin: 5px;
        }
        .btn:hover {
            background-color: #c0392b;
        }
        @media (max-width: 768px) {
            form input, form textarea {
                font-size: 14px;
            }
            form button {
                font-size: 14px;
            }
            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>لوحة تحكم المسؤول</h1>
    </header>

    <h2>إضافة منتج جديد</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="اسم المنتج" required>
        <textarea name="description" placeholder="وصف المنتج" required></textarea>
        <input type="text" name="price" placeholder="السعر" required>
        <input type="text" name="image_url" placeholder="رابط الصورة" required>
        <button type="submit" name="add" class="btn">إضافة</button>
    </form>

    <h2>تحديث منتج</h2>
    <form method="POST" action="">
        <input type="number" name="id" placeholder="رقم المنتج" required>
        <input type="text" name="name" placeholder="اسم المنتج" required>
        <textarea name="description" placeholder="وصف المنتج" required></textarea>
        <input type="text" name="price" placeholder="السعر" required>
        <input type="text" name="image_url" placeholder="رابط الصورة" required>
        <button type="submit" name="update" class="btn">تحديث</button>
    </form>

    <h2>إدارة المنتجات</h2>
    <table>
        <tr>
            <th>رقم المنتج</th>
            <th>اسم المنتج</th>
            <th>وصف المنتج</th>
            <th>السعر</th>
            <th>الصورة</th>
            <th>حذف</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo htmlspecialchars($row['price']); ?></td>
            <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
            <td><a href="?delete=<?php echo htmlspecialchars($row['id']); ?>" class="btn">حذف</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
<?php
$conn->close();
?>

