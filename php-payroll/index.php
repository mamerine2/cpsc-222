<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Payroll Tool</title>

    <style>
        body {
            font-family: Verdana;
            margin: 25px;
        }

        .box {
            margin-bottom: 15px;
        }

        table {
            border-collapse: collapse;
            margin-top: 20px;
            width: 650px;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
        }

        th {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<h2>Employee Weekly Pay Calculator</h2>

<form method="post">
    <div class="box">
        Name:
        <input type="text" name="empName" required>
    </div>

    <div class="box">
        Hours:
        <input type="number" step="0.1" name="hours" required>
    </div>

    <div class="box">
        Pay Rate ($):
        <input type="number" step="0.01" name="pay" required>
    </div>

    <div class="box">
        Federal Tax (%):
        <input type="number" step="0.1" name="fed" required>
    </div>

    <div class="box">
        State Tax (%):
        <input type="number" step="0.1" name="state" required>
    </div>

    <input type="submit" value="Calculate">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Grab form values
    $name = $_POST["empName"];
    $hrs = floatval($_POST["hours"]);
    $rate = floatval($_POST["pay"]);
    $fed = floatval($_POST["fed"]) / 100;
    $state = floatval($_POST["state"]) / 100;

    // Do calculations
    $grossPay = $hrs * $rate;
    $fedAmount = $grossPay * $fed;
    $stateAmount = $grossPay * $state;
    $deductions = $fedAmount + $stateAmount;
    $takeHome = $grossPay - $deductions;

    // Determine tax bracket
    if ($grossPay <= 11925) {
        $bracket = "10%";
    } elseif ($grossPay <= 48475) {
        $bracket = "12%";
    } elseif ($grossPay <= 103350) {
        $bracket = "22%";
    } elseif ($grossPay <= 197300) {
        $bracket = "24%";
    } else {
        $bracket = "Higher than 24%";
    }
?>

    <h3>Results</h3>

    <table>
        <tr>
            <th>Description</th>
            <th>Value</th>
        </tr>

        <tr>
            <td>Employee</td>
            <td><?= htmlspecialchars($name) ?></td>
        </tr>

        <tr>
            <td>Hours Worked</td>
            <td><?= $hrs ?></td>
        </tr>

        <tr>
            <td>Hourly Rate</td>
            <td>$<?= number_format($rate, 2) ?></td>
        </tr>

        <tr>
            <td>Gross Pay</td>
            <td>$<?= number_format($grossPay, 2) ?></td>
        </tr>

        <tr>
            <td>Federal Tax</td>
            <td>$<?= number_format($fedAmount, 2) ?></td>
        </tr>

        <tr>
            <td>State Tax</td>
            <td>$<?= number_format($stateAmount, 2) ?></td>
        </tr>

        <tr>
            <td>Total Deductions</td>
            <td>$<?= number_format($deductions, 2) ?></td>
        </tr>

        <tr>
            <td>Net Pay</td>
            <td>$<?= number_format($takeHome, 2) ?></td>
        </tr>

        <tr>
            <td>Tax Bracket</td>
            <td><?= $bracket ?></td>
        </tr>
    </table>

<?php
}
?>

</body>
</html>
