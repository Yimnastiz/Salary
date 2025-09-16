<?php 
session_start(); 
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
} 
include 'db.php'; 
?> 
<!DOCTYPE html> 
<html lang="th"> 
<head> 
    <meta charset="UTF-8"> 
    <title>ตรวจสอบเงินเดือนพนักงาน</title> 
    <style> 
        body { font-family: Tahoma, sans-serif; margin: 20px; } 
        .box { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; } 
        input, button { padding: 8px; width: 100%; margin: 5px 0; } 
        table { width: 100%; border-collapse: collapse; margin-top: 20px; } 
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; } 
        th { background: #f4f4f4; } 
        .btn { display:inline-block; padding:6px 12px; background:#007bff; color:#fff; text-decoration:none; border-radius:5px; } 
        .btn:hover { background:#0056b3; } 
        /* Navbar */ 
        .navbar { 
            background-color: #333; 
            overflow: hidden; 
            border-radius: 5px; 
            margin-bottom: 20px; 
        } 
        .navbar a { 
            float: left; 
            display: block; 
            color: white; 
            text-align: center; 
            padding: 12px 16px; 
            text-decoration: none; 
            font-weight: bold; 
        } 
        .navbar a:hover { 
            background-color: #4CAF50; 
            color: white; 
        } 
    </style> 
</head> 
<body> 

<div class="navbar"> 
    <a href="salary_check.php">ตรวจสอบเงินเดือน</a> 
    <a href="add_employee.php">เพิ่มพนักงาน</a> 
    <a href="employee_list.php">แก้ไขข้อมูล</a> 
    <a href="logout.php" style="float:right; background:#f44336;">Logout</a> 
</div> 

<div class="box"> 
    <h2>🔍 ตรวจสอบเงินเดือนพนักงาน</h2> 
    <form method="post"> 
        <label>กรอกรหัสพนักงาน:</label> 
        <input type="number" name="emp_id" required> 
        <button type="submit">ค้นหา</button> 
    </form> 

    <?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $emp_id = intval($_POST['emp_id']); 

        // ข้อมูลพนักงาน 
        $emp_sql = "SELECT * FROM employees WHERE emp_id = $emp_id"; 
        $emp_result = $conn->query($emp_sql); 

        if ($emp_result->num_rows > 0) { 
            $emp = $emp_result->fetch_assoc(); 
            echo "<h3>👤 ข้อมูลพนักงาน</h3>"; 
            echo "<p><b>ชื่อ:</b> {$emp['emp_name']} <br> 
                    <b>ตำแหน่ง:</b> {$emp['position']} <br> 
                    <b>ธนาคาร:</b> {$emp['bank_name']} <br> 
                    <b>เลขบัญชี:</b> {$emp['bank_account']}</p>"; 

            // เงินเดือนล่าสุด 
            $sal_sql = "SELECT * FROM salaries WHERE emp_id = $emp_id ORDER BY salary_id DESC LIMIT 1"; 
            $sal_result = $conn->query($sal_sql); 

            if ($sal_result->num_rows > 0) { 
                $sal = $sal_result->fetch_assoc(); 
                echo "<h3>💰 เงินเดือนล่าสุด (เดือน {$sal['month_year']})</h3>"; 
                echo "<table> 
                        <tr><th>เงินเดือนพื้นฐาน</th><th>OT</th><th>หัก</th><th>สุทธิ</th><th>ดูสลิป</th></tr> 
                        <tr> 
                            <td>{$sal['base_salary']}</td> 
                            <td>{$sal['ot_pay']}</td> 
                            <td>{$sal['deductions']}</td> 
                            <td><b>{$sal['net_salary']}</b></td> 
                            <td><a class='btn' href='payslip.php?emp_id={$emp['emp_id']}&salary_id={$sal['salary_id']}'target='_blank'  
                rel='noopener noreferrer'>ดูใบเสร็จ</a></td> 
                        </tr> 
                    </table>"; 
            } else { 
                echo "<p style='color:red;'>❌ ไม่มีข้อมูลเงินเดือน</p>"; 
            } 

        } else { 
            echo "<p style='color:red;'>❌ ไม่พบพนักงาน</p>"; 
        } 
    } 
    
    ?> 
</div> 
</body> 
</html>