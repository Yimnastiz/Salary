<?php
session_start();
include 'db.php';

// ตรวจสอบล็อกอิน
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลพนักงาน</title>
    <style>
        body { font-family: Tahoma, sans-serif; margin:20px; }
        .navbar {
            background-color: #333;
            overflow: hidden;
            border-radius:5px;
            margin-bottom:20px;
        }
        .navbar a {
            float:left;
            display:block;
            color:white;
            text-align:center;
            padding:12px 16px;
            text-decoration:none;
            font-weight:bold;
        }
        .navbar a:hover { background-color: #4CAF50; color:white; }
        .navbar a.logout { float:right; background:#f44336; }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { border:1px solid #ddd; padding:8px; text-align:center; }
        th { background:#f4f4f4; }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="salary_check.php">ตรวจสอบเงินเดือน</a>
    <a href="add_employee.php">เพิ่มพนักงาน</a>
    <a href="employee_list.php">แก้ไขข้อมูล</a>
    <a href="logout.php" style="float:right; background:#f44336;">Logout</a>
</div>

<h2>📋 รายชื่อพนักงานทั้งหมด</h2>

<?php
// ดึงข้อมูลพนักงาน
$emp_sql = "SELECT * FROM employees";
$emp_result = $conn->query($emp_sql);

if($emp_result->num_rows > 0){
    echo "<table>
            <tr>
                <th>รหัส</th>
                <th>ชื่อ</th>
                <th>ตำแหน่ง</th>
                <th>ธนาคาร</th>
                <th>เลขบัญชี</th>
                <th>เดือนล่าสุด</th>
                <th>เงินเดือนพื้นฐาน</th>
                <th>OT</th>
                <th>หัก</th>
                <th>เงินเดือนสุทธิ</th>
                <th>การขาด-ลามาสาย</th>
                <th>จัดการ</th>

            </tr>";

    while($emp = $emp_result->fetch_assoc()){
        $emp_id = $emp['emp_id'];

        // ดึงเงินเดือนล่าสุด
        $sal_sql = "SELECT * FROM salaries WHERE emp_id=$emp_id ORDER BY salary_id DESC LIMIT 1";
        $sal_result = $conn->query($sal_sql);
        if($sal_result->num_rows > 0){
            $sal = $sal_result->fetch_assoc();
        } else {
            $sal = ['month_year'=>'-', 'base_salary'=>0, 'ot_pay'=>0, 'deductions'=>0, 'net_salary'=>0];
        }

        // สรุปการเข้าทำงานเดือนล่าสุด
        $att_sql = "SELECT status, COUNT(*) as cnt FROM attendance WHERE emp_id=$emp_id AND MONTH(work_date)=MONTH(CURDATE()) GROUP BY status";
        $att_result = $conn->query($att_sql);
        $attendance_summary = [];
        while($att = $att_result->fetch_assoc()){
            $attendance_summary[$att['status']] = $att['cnt'];
        }
        $att_text = "Present: ".($attendance_summary['Present'] ?? 0).
                    ", Late: ".($attendance_summary['Late'] ?? 0).
                    ", Absent: ".($attendance_summary['Absent'] ?? 0).
                    ", Leave: ".($attendance_summary['Leave'] ?? 0);

        echo "<tr>
                <td>{$emp['emp_id']}</td>
                <td>{$emp['emp_name']}</td>
                <td>{$emp['position']}</td>
                <td>{$emp['bank_name']}</td>
                <td>{$emp['bank_account']}</td>
                <td>{$sal['month_year']}</td>
                <td>{$sal['base_salary']}</td>
                <td>{$sal['ot_pay']}</td>
                <td>{$sal['deductions']}</td>
                <td>{$sal['net_salary']}</td>
                <td>$att_text</td>
                <td><a href='edit_employee.php?emp_id={$emp['emp_id']}'>แก้ไข</a></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>❌ ไม่มีข้อมูลพนักงาน</p>";
}
?>

</body>
</html>
