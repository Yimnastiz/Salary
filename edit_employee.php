<?php
session_start();
include 'db.php';

// ตรวจสอบล็อกอินและสิทธิ์ admin
if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
    header("Location: login.php");
    exit();
}

// ตรวจสอบ emp_id
if(!isset($_GET['emp_id'])){
    echo "❌ ไม่มีรหัสพนักงาน";
    exit();
}
$emp_id=intval($_GET['emp_id']);
$message='';

// เลือกเดือน/ปี
$selected_month = $_GET['month'] ?? date('m');
$selected_year  = $_GET['year']  ?? date('Y');

// ดึง attendance เดือนนั้น
$att_sql = "SELECT * FROM attendance 
            WHERE emp_id=$emp_id 
              AND MONTH(work_date)=$selected_month 
              AND YEAR(work_date)=$selected_year 
            ORDER BY work_date";
$att_result = $conn->query($att_sql);
$attendance = [];
while($att = $att_result->fetch_assoc()){
    $attendance[$att['work_date']] = [
        'status' => $att['status'],
        'ot' => $att['ot'] ?? 0
    ];
}

// กำหนดจำนวนวันในเดือนนั้น
$days_in_month = date('t', strtotime("$selected_year-$selected_month-01"));

// ดึงข้อมูลพนักงาน
$emp_sql="SELECT * FROM employees WHERE emp_id=$emp_id";
$emp_result=$conn->query($emp_sql);
if($emp_result->num_rows==0){
    echo "❌ ไม่พบพนักงาน";
    exit();
}
$emp=$emp_result->fetch_assoc();

// ดึง salary ล่าสุด
$sal_sql="SELECT * FROM salaries WHERE emp_id=$emp_id ORDER BY salary_id DESC LIMIT 1";
$sal_result=$conn->query($sal_sql);
if($sal_result->num_rows>0){
    $sal=$sal_result->fetch_assoc();
}else{
    $sal=['month_year'=>date('Y-m'),'base_salary'=>0,'ot_pay'=>0,'deductions'=>0,'net_salary'=>0];
}

// ดึง attendance เดือนนี้
$att_sql="SELECT * FROM attendance WHERE emp_id=$emp_id AND MONTH(work_date)=MONTH(CURDATE()) ORDER BY work_date";
$att_result=$conn->query($att_sql);
$attendance=[];
while($att=$att_result->fetch_assoc()){
    $attendance[$att['work_date']] = [
        'status' => $att['status'],
        'ot' => $att['ot'] ?? 0
    ];
}

// ลบพนักงาน
if(isset($_POST['delete'])){
    $conn->query("DELETE FROM attendance WHERE emp_id=$emp_id");
    $conn->query("DELETE FROM salaries WHERE emp_id=$emp_id");
    $conn->query("DELETE FROM employees WHERE emp_id=$emp_id");
    header("Location: employee_list.php");
    exit();
}

if($_SERVER['REQUEST_METHOD']=='POST' && !isset($_POST['delete'])){
    // อัปเดตข้อมูลพนักงาน
    $emp_name=$conn->real_escape_string($_POST['emp_name']);
    $position=$conn->real_escape_string($_POST['position']);
    $bank_name=$conn->real_escape_string($_POST['bank_name']);
    $bank_account=$conn->real_escape_string($_POST['bank_account']);
    $salary_per_day = floatval($_POST['salary_per_day']);
    $conn->query("UPDATE employees SET emp_name='$emp_name', position='$position', bank_name='$bank_name', bank_account='$bank_account',salary_per_day=$salary_per_day WHERE emp_id=$emp_id");

    // เดือน/ปี
    $month_year=$_POST['month_year'];

    // <<< วางโค้ด OT รายวันตรงนี้ >>>
    $present=$late=$absent=$leave=0;
    $total_ot=0;

    foreach($_POST['attendance'] as $date => $data){
        $date=$conn->real_escape_string($date);
        $status=$conn->real_escape_string($data['status']);
        $ot_hours=floatval($data['ot']);
        $total_ot += $ot_hours;

        $check=$conn->query("SELECT * FROM attendance WHERE emp_id=$emp_id AND work_date='$date'");
        if($check->num_rows>0){
            $conn->query("UPDATE attendance SET status='$status', ot=$ot_hours WHERE emp_id=$emp_id AND work_date='$date'");
        } else {
            $conn->query("INSERT INTO attendance (emp_id, work_date, status, ot) VALUES ($emp_id,'$date','$status',$ot_hours)");
        }

        if($status=='Present') $present++;
        elseif($status=='Late') $late++;
        elseif($status=='Absent') $absent++;
        elseif($status=='Leave') $leave++;
    }

    // คำนวณเงินเดือน
    $salary_per_day = floatval($_POST['salary_per_day']);
    $late_deduction = 50;
    $absent_deduction = 100;
    $leave_deduction = 0;
    $ot_rate = 100;

    $base_salary_total = ($present + $leave) * $salary_per_day;
    $deductions_total = $late * $late_deduction + $absent * $absent_deduction;
    $ot_total = $total_ot * $ot_rate;
    $net_salary = $base_salary_total - $deductions_total + $ot_total;

    // บันทึก salary
    $conn->query("INSERT INTO salaries (emp_id, month_year, base_salary, ot_pay, deductions, net_salary)
                  VALUES ($emp_id,'$month_year',$base_salary_total,$ot_total,$deductions_total,$net_salary)
                  ON DUPLICATE KEY UPDATE base_salary=$base_salary_total, ot_pay=$ot_total, deductions=$deductions_total, net_salary=$net_salary");

    $message="✅ บันทึกเรียบร้อยแล้ว";
}


// กำหนดวันที่เดือนปัจจุบัน
$year=date('Y');
$month=date('m');
$days_in_month=date('t',strtotime("$year-$month-01"));

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขข้อมูลพนักงาน</title>
<style>
body{font-family:Tahoma,sans-serif;margin:20px;}
.navbar{background:#333;overflow:hidden;border-radius:5px;margin-bottom:20px;}
.navbar a{float:left;display:block;color:#fff;padding:12px 16px;text-decoration:none;font-weight:bold;}
.navbar a:hover{background:#4CAF50;}
.navbar a.logout{float:right;background:#f44336;}
.box{max-width:900px;margin:auto;padding:20px;border:1px solid #ccc;border-radius:10px;}
input,select,button{padding:8px;width:100%;margin:5px 0;}
h2{text-align:center;}
.success{color:green;text-align:center;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{border:1px solid #ddd;padding:6px;text-align:center;}
th{background:#f4f4f4;}
button.delete{background:#f44336;color:white;padding:10px;width:100%;margin-bottom:10px;}
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
<h2>✏️ แก้ไขข้อมูลพนักงาน: <?php echo htmlspecialchars($emp['emp_name']); ?></h2>
<?php if($message) echo "<p class='success'>$message</p>"; ?>

<!-- ปุ่มลบ -->
<form method="post" onsubmit="return confirm('คุณแน่ใจว่าจะลบพนักงานนี้? ข้อมูลทั้งหมดจะหาย');">
    <button type="submit" name="delete" class="delete">🗑️ ลบพนักงาน</button>
</form>

<form method="post">
<h3>👤 ข้อมูลพนักงาน</h3>
<label>ชื่อพนักงาน:</label>
<input type="text" name="emp_name" value="<?php echo htmlspecialchars($emp['emp_name']); ?>" required>
<label>ตำแหน่ง:</label>
<input type="text" name="position" value="<?php echo htmlspecialchars($emp['position']); ?>" required>
<label>ธนาคาร:</label>
<select name="bank_name" required>
<?php
$banks=['กสิกรไทย','ไทยพาณิชย์','กรุงไทย','กรุงเทพ','ทหารไทย'];
foreach($banks as $b){
    $sel=$b==$emp['bank_name']?'selected':'';
    echo "<option value='$b' $sel>$b</option>";
}
?>
</select>
<label>เลขบัญชี:</label>
<input type="text" name="bank_account" value="<?php echo htmlspecialchars($emp['bank_account']); ?>" required>

<hr>
<h3>💰 เงินเดือน</h3>
<label>เดือน/ปี (เช่น 2025-09):</label>
<input type="text" name="month_year" value="<?php echo $sal['month_year']; ?>" required>
<label>เงินเดือนต่อวัน:</label>
<input type="number" name="salary_per_day" value="<?php echo htmlspecialchars($emp['salary_per_day']); ?>" required step="0.01">
<label>จำนวนชั่วโมง OT:</label>
<input type="number" name="ot_hours" value="<?php echo $sal['ot_pay']/100; ?>" step="0.1" required>
<p>ระบบจะคำนวณเงินเดือนอัตโนมัติจาก attendance และ OT</p>

<hr>
<h3>📅 การเข้าทำงานเดือนนี้</h3>
<table>
<tr><th>วันที่</th><th>สถานะ</th><th>OT (ชม.)</th></tr>
<?php
for($d=1;$d<=$days_in_month;$d++){
    $day_str=sprintf('%02d',$d);
    $date="$year-$month-$day_str";
    $status=$attendance[$date]['status']??'Present';
    $ot=$attendance[$date]['ot']??0; // OT แยกรายวัน
    echo "<tr>
            <td>$date</td>
            <td>
                <select name='attendance[$date][status]'>
                    <option value='Present' ".($status=='Present'?'selected':'').">Present</option>
                    <option value='Late' ".($status=='Late'?'selected':'').">Late</option>
                    <option value='Absent' ".($status=='Absent'?'selected':'').">Absent</option>
                    <option value='Leave' ".($status=='Leave'?'selected':'').">Leave</option>
                </select>
            </td>
            <td>
                <input type='number' name='attendance[$date][ot]' value='$ot' step='0.1' min='0'>
            </td>
          </tr>";
}
?>
</table>
<button type="submit">💾 บันทึกการแก้ไข</button>
</form>
</div>
</body>
</html>
