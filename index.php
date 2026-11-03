
<?php

// Module 1: Calculate Course Fee
function calculateCourseFee($courseFee, $discount) {
    return $courseFee - $discount;
}

// Module 2: Calculate Payment Balance
function calculateBalance($totalFee, $paidAmount) {
    return $totalFee - $paidAmount;
}

// Initialize result
$result = false;
$error = "";

$studentName = "";
$courseFee = "";
$discount = "";
$paidAmount = "";

// Get User Inputs
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentName = trim($_POST["student_name"] ?? "");
    $courseFee = $_POST["course_fee"] ?? "";
    $discount = $_POST["discount"] ?? "";
    $paidAmount = $_POST["paid_amount"] ?? "";

    if (
        $studentName === "" ||
        $courseFee === "" ||
        $discount === "" ||
        $paidAmount === "" ||
        !is_numeric($courseFee) ||
        !is_numeric($discount) ||
        !is_numeric($paidAmount)
    ) {
        $error = "Please enter all fields correctly.";
    } elseif (
        $courseFee < 0 ||
        $discount < 0 ||
        $paidAmount < 0 ||
        $discount > $courseFee
    ) {
        $error = "Please enter valid amounts. Discount cannot exceed course fee.";
    } elseif ($paidAmount > ($courseFee - $discount)) {
        $error = "Paid amount cannot exceed the total fee.";
    } else {

        // Calling Module 1
        $totalFee = calculateCourseFee(
            (float)$courseFee,
            (float)$discount
        );

        // Calling Module 2
        $balance = calculateBalance(
            $totalFee,
            (float)$paidAmount
        );

        $status = ($balance == 0)
            ? "FULLY PAID"
            : "PAYMENT PENDING";

        $result = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Fee Management System</title>

<style>

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background: #eef2f7;
    padding: 30px 15px;
}

.container {
    max-width: 650px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.header {
    text-align: center;
    margin-bottom: 30px;
}

.header h1 {
    color: #1e3a8a;
    font-size: 25px;
    margin-bottom: 10px;
}

.header p {
    color: #64748b;
}

h2 {
    color: #334155;
    font-size: 19px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    font-weight: bold;
    color: #334155;
    margin-bottom: 8px;
}

input {
    width: 100%;
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 15px;
}

input:focus {
    outline: none;
    border-color: #2563eb;
}

button {
    width: 100%;
    background: #2563eb;
    color: white;
    border: none;
    padding: 14px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #1d4ed8;
}

.error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.result {
    margin-top: 25px;
    padding: 20px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
}

.result h2 {
    color: #166534;
}

.result p {
    padding: 9px 0;
    border-bottom: 1px solid #dcfce7;
    color: #334155;
}

.status {
    margin-top: 15px;
    font-weight: bold;
    color: #166534;
}

footer {
    text-align: center;
    margin-top: 25px;
    color: #94a3b8;
    font-size: 13px;
}

</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Student Fee Management System</h1>
        <p>Calculate Course Fee and Payment Balance</p>
    </div>

    <h2>Enter Student Details</h2>

    <?php if ($error): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Student Name</label>
            <input
                type="text"
                name="student_name"
                placeholder="Enter student name"
                value="<?= htmlspecialchars($studentName) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Course Fee (Rs.)</label>
            <input
                type="number"
                name="course_fee"
                placeholder="Enter course fee"
                min="0"
                step="0.01"
                value="<?= htmlspecialchars($courseFee) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Discount (Rs.)</label>
            <input
                type="number"
                name="discount"
                placeholder="Enter discount"
                min="0"
                step="0.01"
                value="<?= htmlspecialchars($discount) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Paid Amount (Rs.)</label>
            <input
                type="number"
                name="paid_amount"
                placeholder="Enter paid amount"
                min="0"
                step="0.01"
                value="<?= htmlspecialchars($paidAmount) ?>"
                required
            >
        </div>

        <button type="submit">Calculate Fee</button>

    </form>

    <?php if ($result): ?>

        <div class="result">

            <h2>Fee Calculation Result</h2>

            <p>
                <strong>Student Name:</strong>
                <?= htmlspecialchars($studentName) ?>
            </p>

            <p>
                <strong>Course Fee:</strong>
                Rs. <?= number_format((float)$courseFee, 2) ?>
            </p>

            <p>
                <strong>Discount:</strong>
                Rs. <?= number_format((float)$discount, 2) ?>
            </p>

            <p>
                <strong>Total Fee:</strong>
                Rs. <?= number_format($totalFee, 2) ?>
            </p>

            <p>
                <strong>Paid Amount:</strong>
                Rs. <?= number_format((float)$paidAmount, 2) ?>
            </p>

            <p>
                <strong>Remaining Balance:</strong>
                Rs. <?= number_format($balance, 2) ?>
            </p>

            <div class="status">
                Payment Status: <?= $status ?>
            </div>

        </div>

    <?php endif; ?>

    <footer>
        Student Fee Management System
    </footer>

</div>

</body>
</html>
