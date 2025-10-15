<?php
include 'header.php';
$conn = new mysqli("localhost", "root", "*31311055Jee", "BookStore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "
SELECT 
  final.E_ReceiptNumber,
  final.OrderID,
  final.Payee,
  final.MemberEmail,
  final.PaymentMethod,
  final.TotalAmount
FROM (
    SELECT 
        er.E_ReceiptNumber AS E_ReceiptNumber,
        er.OrderID AS OrderID,
        er.Payee AS Payee,
        m.Email AS MemberEmail,
        er.PaymentMethod AS PaymentMethod,
        er.TotalAmount AS TotalAmount
    FROM E_Receipt er
    LEFT JOIN Orders o ON er.OrderID = o.OrderID
    LEFT JOIN Member m ON o.MemberID = m.MemberID

    UNION

    SELECT 
        er.E_ReceiptNumber,
        er.OrderID,
        er.Payee,
        m.Email AS MemberEmail,
        er.PaymentMethod,
        er.TotalAmount
    FROM E_Receipt er
    RIGHT JOIN Orders o ON er.OrderID = o.OrderID
    RIGHT JOIN Member m ON o.MemberID = m.MemberID
) AS final;
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>E-Receipt Viewer</title>
    <style>
        .page-container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 35px;
            text-align: center;
        }

        .page-container h2 {
            display: inline-block;
            border-bottom: 3px solid #ef7a12;
            padding-bottom: 6px;
            margin-bottom: 25px;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #1a73e8;
            color: white;
            font-weight: normal;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e6f0ff;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <h2>E-Receipt List</h2>
        <table>
            <tr>
                <th>Receipt No.</th>
                <th>Order ID</th>
                <th>Payee</th>
                <th>Member Email</th>
                <th>Payment Method</th>
                <th>Total Amount</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['E_ReceiptNumber']}</td>
                    <td>{$row['OrderID']}</td>
                    <td>{$row['Payee']}</td>
                    <td>{$row['MemberEmail']}</td>
                    <td>{$row['PaymentMethod']}</td>
                    <td>{$row['TotalAmount']}</td>
                </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;color:#777;'>No receipts found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>

</html>