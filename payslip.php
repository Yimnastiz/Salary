<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
include 'db.php';

// รับ emp_id และ salary_id
$emp_id = isset($_GET['emp_id']) ? intval($_GET['emp_id']) : 0;
$salary_id = isset($_GET['salary_id']) ? intval($_GET['salary_id']) : 0;

// ตรวจสอบว่าได้รับค่า emp_id และ salary_id หรือไม่
if ($emp_id === 0 || $salary_id === 0) {
    die("Error: Missing emp_id or salary_id.");
}

// ดึงข้อมูลพนักงาน
$emp_sql = "SELECT * FROM employees WHERE emp_id = $emp_id";
$emp_result = $conn->query($emp_sql);
if (!$emp_result || $emp_result->num_rows === 0) {
    die("Error: Employee not found.");
}
$emp = $emp_result->fetch_assoc();

// ดึงข้อมูลเงินเดือน
$sal_sql = "SELECT * FROM salaries WHERE salary_id = $salary_id AND emp_id = $emp_id";
$sal_result = $conn->query($sal_sql);
if (!$sal_result || $sal_result->num_rows === 0) {
    die("Error: Salary details not found for this employee.");
}
$sal = $sal_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ใบจ่ายเงินเดือน</title>
<style>
body {
    font-family: Tahoma, sans-serif;
    margin: 20px;
    background-color: #f9f9f9; /* เพิ่มสีพื้นหลังเล็กน้อย */
}
.payslip-container {
    max-width: 800px;
    margin: auto;
    background-color: #fff;
    padding: 20px;
    border: 2px solid #007bff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1); /* เพิ่มเงา */
    position: relative; /* สำหรับจัดตำแหน่งโลโก้ */
    padding-top: 80px; /* เพิ่มพื้นที่ด้านบนสำหรับโลโก้ */
}
h2 {
    text-align: center;
    margin: 0 0 10px 0; /* ปรับ margin */
    color: #0056b3;
}
.company-title {
    text-align: center;
    font-size: 1.1em;
    margin-bottom: 5px;
    color: #333;
}
.payslip-title {
    text-align: center;
    font-size: 0.9em;
    color: #666;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
th, td {
    border: 1px solid #ccc;
    padding: 8px; /* เพิ่ม padding เล็กน้อย */
    text-align: center;
}
th {
    background: linear-gradient(to bottom, #e0e0e0, #f4f4f4); /* ไล่เฉดสี */
    color: #333;
    font-weight: bold;
}
tr:nth-child(even) {
    background-color: #f8f9fa; /* สีแถวสลับ */
}
.section {
    margin-top: 20px;
}
.signature {
    margin-top: 40px;
    text-align: right;
    font-style: italic;
}
.btn-print {
    display: inline-block;
    padding: 10px 20px; /* เพิ่มขนาดปุ่ม */
    background: linear-gradient(to bottom, #28a745, #218838); /* ไล่เฉดสีเขียว */
    color: #fff;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.btn-print:hover {
    background: linear-gradient(to bottom, #218838, #1e7e34);
}

/* CSS สำหรับโลโก้ */
.logo-container {
    position: absolute; /* จัดตำแหน่งแบบสัมพัทธ์ */
    top: 10px; /* ระยะห่างจากขอบบน */
    left: 10px; /* ระยะห่างจากขอบซ้าย */
    z-index: 10; /* ให้โลโก้แสดงอยู่บนสุด */
}
.logo-container img {
    max-width: 120px; /* ปรับขนาดโลโก้ให้เล็กลง */
    height: auto; /* รักษาสัดส่วน */
    display: block; /* ป้องกันช่องว่างใต้รูป */
}
</style>
</head>
<body>

<div class="payslip-container">

    <div class="logo-container">
        <img src="logo.png" alt="Storm Express Logo">
    </div>

    <h2>บริษัท Strom Express</h2>
    <p class="company-title">Storm Express Company</p>
    <p class="payslip-title">ใบจ่ายเงินเดือน/ค่าจ้างประจำตำแหน่ง</p>

    <table>
        <tr>
            <td><b>รหัส</b><br><?php echo htmlspecialchars($emp['emp_id']); ?></td>
            <td><b>ชื่อพนักงาน</b><br><?php echo htmlspecialchars($emp['emp_name']); ?></td>
            <td><b>ตำแหน่ง</b><br><?php echo htmlspecialchars($emp['position']); ?></td>
            <td><b>เดือน/ปี</b><br><?php echo htmlspecialchars($sal['month_year']); ?></td>
        </tr>
    </table>

    <table class="section">
        <tr>
            <th>เงินเดือนพื้นฐาน</th>
            <th>ค่าล่วงเวลา (OT)</th>
            <th>รายการหัก</th>
            <th>เงินเดือนสุทธิ</th>
        </tr>
        <tr>
            <td><?php echo number_format($sal['base_salary'], 2); ?></td>
            <td><?php echo number_format($sal['ot_pay'], 2); ?></td>
            <td><?php echo number_format($sal['deductions'], 2); ?></td>
            <td><b><?php echo number_format($sal['net_salary'], 2); ?></b></td>
        </tr>
    </table>

    <div class="signature">
        <p>ลงชื่อพนักงาน _______________________</p>
        <p>(<?php echo htmlspecialchars($emp['emp_name']); ?>)</p>
    </div>

    <p style="text-align:center; margin-top:30px;">
        <a href="#" class="btn-print" onclick="window.print(); return false;">🖨 พิมพ์ใบจ่ายเงินเดือน</a>
    </p>
</div>

</body>
</html>