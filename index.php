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
    <title>Elegance Wear</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #ECF0F1; /* اللون الخلفي */
    color: #2C3E50; /* لون النص */
    direction: rtl;
}

/* شريط النص المتحرك */
.promo-bar {
    background-color: #F39C12; /* اللون الذهبي */
    color: #fff;
    padding: 10px 0;
    text-align: center;
    font-size: 20px;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
}

.promo-bar span {
    display: inline-block;
    padding-left: 100%;
    animation: moveText 20s linear infinite;
}

@keyframes moveText {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

header {
    background-color: #2C3E50; /* الأزرق الداكن */
    color: #fff;
    padding: 20px;
    text-align: center;
    position: relative;
}

header h1 {
    margin: 0;
}

/* شريط الاتصال */
.contact-bar {
    background-color: #F39C12;
    color: #fff;
    padding: 10px;
    text-align: center;
}

.contact-bar a {
    color: white;
    margin: 0 10px;
    text-decoration: none;
    font-weight: bold;
}

.contact-bar a:hover {
    text-decoration: underline;
}

.banner {
    background-image: url('https://media-bcn1-1.cdn.whatsapp.net/v/t61.24694-24/436110087_1501121823856806_5565345534191371561_n.jpg?ccb=11-4&oh=01_Q5AaIKW0E12ChQCKTgiT5Ym5DLLgN8xBBfNQCIHntQsOqK9M&oe=66F0F677&_nc_sid=5e03e0&_nc_cat=103');
    height: 300px;
    background-size: cover;
    background-position: center;
    text-align: center;
    color: #F39C12; /* الذهبي */
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.banner h2 {
    padding-top: 0;
    font-size: 48px;
    margin: 0;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
}

/* تصميم قائمة المنتجات */
.products {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 20px;
}

.product {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 30%;
    margin: 10px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.product:hover {
    transform: translateY(-5px);
    border: 1px solid #F39C12; /* اللون الذهبي عند التحويم */
}

.product img {
    width: 100%;
    border-bottom: 1px solid #ddd;
}

.product h3 {
    margin: 10px 0;
    color: #2C3E50; /* لون النص */
}

.product p {
    color: #777;
}

.product button {
    background-color: #F39C12; /* الذهبي */
    color: white;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    margin-bottom: 15px;
    transition: background-color 0.3s;
}

.product button:hover {
    background-color: #D68910; /* تدرج أغمق للون الذهبي عند التحويم */
}

/* تحسينات للهواتف */
@media (max-width: 768px) {
    .product {
        width: 45%;
    }

    .banner h2 {
        font-size: 36px;
        padding-top: 80px;
    }
}

@media (max-width: 480px) {
    .product {
        width: 100%;
    }

    .banner h2 {
        font-size: 24px;
        padding-top: 50px;
    }
}

footer {
    background-color: #2C3E50; /* الأزرق الداكن */
    color: white;
    text-align: center;
    padding: 20px;
}

footer a {
    color: #F39C12; /* اللون الذهبي */
    text-decoration: none;
    margin: 0 10px;
    font-size: 18px;
}

footer a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <!-- شريط النص المتحرك في الأعلى -->
    <div class="promo-bar">
        <span>🎉 تسوق الآن واحصل على خصومات تصل إلى 50%! 🌟 أحدث صيحات الموضة بانتظارك! 🚀</span>
    </div>

    <!-- شريط الاتصال في الأعلى -->
    <div class="contact-bar">
        <span>اتصل بنا: </span>
        <a href="tel:+213676626102">213676626102+</a> | 
        <a href="https://wa.me/213676626102" target="_blank">واتساب</a> | 
        <a href="https://www.facebook.com/groups/BtcMarketing" target="_blank">فيسبوك</a>
    </div>

    <header>
        <h1>Elegance Wear - متجر الأناقة</h1>
    </header>

    <div class="banner">
        <h2>اكتشف أحدث صيحات الموضة</h2>
    </div>

    <section class="products">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p>سعر: <?php echo htmlspecialchars($row['price']); ?> دج</p>
                <button>شراء الآن</button>
               <!-- <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn">حذف</a> -->
               <!-- <a href="update_product.php?id=<?php echo $row['id']; ?>" class="update-btn">تحديث</a> -->
            </div>
        <?php endwhile; ?>
    </section>

    <!-- معلومات الاتصال في أسفل الصفحة -->
    <footer>
        <p>تواصل معنا:</p>
        <a href="tel:+213676626102">+213 676 62 61 02</a> | 
        <a href="https://wa.me/213123456789" target="_blank">واتساب</a> | 
        <a href="https://www.facebook.com/groups/BtcMarketing" target="_blank">فيسبوك</a>
        <p>© 2024 Elegance Wear - جميع الحقوق محفوظة</p>
    </footer>
</body>
</html>

