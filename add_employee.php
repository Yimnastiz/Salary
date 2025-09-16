<?php
session_start();
include 'db.php';

// ตรวจสอบล็อกอินและ admin
if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
    header("Location: login.php");
    exit();
}

$message='';

if($_SERVER['REQUEST_METHOD']=='POST'){

    // รับข้อมูลจากฟอร์ม
    $emp_id = intval($_POST['emp_id']); 
    $emp_name = $conn->real_escape_string($_POST['emp_name']);
    $position = $conn->real_escape_string($_POST['position']);
    $bank_name = $conn->real_escape_string($_POST['bank_name']);
    $bank_account = $conn->real_escape_string($_POST['bank_account']);
    $month_year = $_POST['month_year'];
    $ot_hours = floatval($_POST['ot_hours']); 
    $salary_per_day = floatval($_POST['salary_per_day']); // ✅ เงินเดือนพื้นฐานจากฟอร์ม
    $late_deduction = 50;
    $absent_deduction = 500;
    $ot_rate = 100;

    // ตรวจสอบว่ารหัสพนักงานซ้ำหรือไม่
    $check = $conn->query("SELECT * FROM employees WHERE emp_id=$emp_id");
    if($check->num_rows > 0){
        $message = "❌ รหัสพนักงานนี้มีอยู่แล้ว!";
    } else {
        // เพิ่มพนักงาน
        $conn->query("INSERT INTO employees (emp_id, emp_name, position, bank_name, bank_account, salary_per_day)
                      VALUES ($emp_id,'$emp_name','$position','$bank_name','$bank_account',$salary_per_day)");

        // สร้าง attendance เดือนนี้เป็น Present ทั้งหมด
        $year = date('Y');
        $month = date('m');
        $days_in_month = date('t', strtotime("$year-$month-01"));
        for($d=1; $d<=$days_in_month; $d++){
            $day_str = sprintf('%02d', $d);
            $date = "$year-$month-$day_str";
            $conn->query("INSERT INTO attendance (emp_id, work_date, status) VALUES ($emp_id,'$date','Present')");
        }

        // คำนวณเงินเดือนอัตโนมัติ
        $present = $days_in_month;
        $late = 0;
        $absent = 0;
        $leave = 0;
        $base_salary_total = ($present+$leave)*$salary_per_day;
        $late_deduction_total = $late*$late_deduction;
        $absent_deduction_total = $absent*$absent_deduction;
        $ot_total = $ot_hours*$ot_rate;
        $net_salary = $base_salary_total - $late_deduction_total - $absent_deduction_total + $ot_total;
        $deductions_total = $late_deduction_total + $absent_deduction_total;

        // เพิ่ม salary
        $conn->query("INSERT INTO salaries (emp_id, month_year, base_salary, ot_pay, deductions, net_salary)
                      VALUES ($emp_id,'$month_year',$base_salary_total,$ot_total,$deductions_total,$net_salary)");

        $message = "✅ เพิ่มพนักงานเรียบร้อยแล้ว";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เพิ่มพนักงาน</title>
<style>
body{font-family:Tahoma,sans-serif;margin:20px;}
.navbar{background:#333;overflow:hidden;border-radius:5px;margin-bottom:20px;}
.navbar a{float:left;display:block;color:#fff;padding:12px 16px;text-decoration:none;font-weight:bold;}
.navbar a:hover{background:#4CAF50;}
.navbar a.logout{float:right;background:#f44336;}
.box{max-width:600px;margin:auto;padding:20px;border:1px solid #ccc;border-radius:10px;}
input,select,button{padding:8px;width:100%;margin:5px 0;}
h2{text-align:center;}
.success{color:green;text-align:center;}
.error{color:red;text-align:center;}
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
<h2>➕ เพิ่มพนักงาน</h2>
<?php
if($message){
    $class = strpos($message,'❌')!==false ? 'error' : 'success';
    echo "<p class='$class'>$message</p>";
}
?>
<form method="post">
<label>รหัสพนักงาน:</label>
<input type="number" name="emp_id" required>

<label>ชื่อพนักงาน:</label>
<input type="text" name="emp_name" required>

<label>ตำแหน่ง:</label>
<input type="text" name="position" required>

<label>ธนาคาร:</label>
<select name="bank_name" required>
<?php
$banks=['กสิกรไทย','ไทยพาณิชย์','กรุงไทย','กรุงเทพ','ทหารไทย'];
foreach($banks as $b){
    echo "<option value='$b'>$b</option>";
}
?>
</select>

<label>เลขที่บัญชี:</label>
<input type="text" name="bank_account" required>

<label>💰 เงินเดือนต่อวัน (บาท):</label>
<input type="number" name="salary_per_day" value="500" required>

<label>เดือน/ปี (เช่น 2025-09):</label>
<input type="text" name="month_year" value="<?php echo date('Y-m'); ?>" required>

<label>จำนวนชั่วโมง OT เริ่มต้น:</label>
<input type="number" name="ot_hours" value="0" step="0.1">

<button type="submit">💾 เพิ่มพนักงาน</button>
</form>
</div>
</body>
</html>
