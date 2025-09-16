<?php
session_start();
include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    // ตรวจสอบว่ามีการส่งรหัสผ่านมาหรือไม่ ก่อนทำการ hash
    if (isset($_POST['password'])) {
        $password = hash('sha256', $_POST['password']); // เข้ารหัส SHA-256
    } else {
        // จัดการกรณีที่ไม่มีการส่งรหัสผ่านมา (อาจจะแสดงข้อความผิดพลาด)
        $message = "❌ กรุณากรอกรหัสผ่าน";
        // หรืออาจจะ redirect กลับไปหน้าล็อกอินพร้อมข้อความผิดพลาด
        header("Location: login.php?error=password_missing");
        exit();
        // สำหรับตอนนี้ จะให้แสดงข้อความและไม่ดำเนินการต่อ
        // คุณอาจต้องปรับการจัดการข้อผิดพลาดนี้ให้เหมาะสมกับแอปพลิเคชันของคุณ
        // ในตัวอย่างนี้ ผมจะอนุญาตให้โค้ดทำงานต่อไป แต่จะแสดงข้อความผิดพลาด
        // หากคุณต้องการให้หยุดการทำงานเมื่อไม่มีรหัสผ่าน ให้ใช้ exit() ข้างบน
    }

    // ตรวจสอบว่ามีข้อความผิดพลาดจากการกรอกรหัสผ่านหรือไม่ ก่อนทำการ query
    if (empty($message)) {
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: salary_check.php"); // เข้าหน้าหลัก
            exit();
        } else {
            $message = "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบตรวจสอบเงินเดือนพนักงาน</title>
    <style>
        body {
            font-family: Tahoma, sans-serif;
            margin: 50px;
            background-color: #f4f4f4; /* เพิ่มสีพื้นหลังให้ดูสบายตาขึ้น */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh; /* ทำให้แน่ใจว่าหน้าจอเต็ม */
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* เงาให้ดูมีมิติ */
            text-align: center; /* จัดข้อความและโลโก้กลาง */
            max-width: 500px; /* เพิ่มความกว้างของกล่อง */
            width: 100%;
        }
        .logo-container {
            margin-bottom: 20px; /* เว้นระยะห่างระหว่างโลโก้กับหัวข้อ */
        }
        .logo-container img {
            max-width: 300px; /* กำหนดขนาดสูงสุดของโลโก้ */
            height: auto; /* รักษาสัดส่วน */
        }
        h2 {
            color: #333;
            margin-bottom: 15px;
        }
        form {
            display: inline-block; /* ทำให้ฟอร์มอยู่กึ่งกลาง */
            text-align: left; /* จัดข้อความในฟอร์มชิดซ้าย */
        }
        label {
            display: block; /* ให้ label อยู่คนละบรรทัด */
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(100% - 22px); /* ปรับความกว้างให้พอดีกับ padding */
            box-sizing: border-box; /* รวม padding และ border ในความกว้าง */
        }
        button[type="submit"] {
            background-color: #007bff; /* สีฟ้าสดใส */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease; /* เพิ่ม transition */
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #0056b3; /* สีฟ้าเข้มขึ้นเมื่อ hover */
        }
        .error {
            color: #dc3545; /* สีแดงสำหรับข้อความผิดพลาด */
            margin-top: 15px;
            font-weight: bold;
        }
        .login-box { /* ลบ class login-box ออกแล้วรวม style ไปที่ login-container */
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border:1px solid #ccc;
            border-radius:10px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>ระบบตรวจสอบเงินเดือนพนักงาน</h2>
    <div class="logo-container">
        <img src="logo.png" alt="Storm Express Logo">
    </div>
    <form method="post">
        <label for="username">ชื่อผู้ใช้:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">รหัสผ่าน:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">เข้าสู่ระบบ</button>
    </form>
    <?php if($message) echo "<p class='error'>$message</p>"; ?>
</div>
</body>
</html>