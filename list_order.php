<?php
include 'header.php';

$conn = new mysqli("localhost", "root", "", "BookStore");

$search = $_GET['search'] ?? '';
$sql = "SELECT 
          o.OrderID, m.First_Name, m.Last_Name, o.OrderDate, o.ShippingDate, w.Publisher,
          od.Quantity, b.Title AS BookTitle, eb.Title AS EBookTitle
        FROM Orders o
        JOIN Member m ON o.MemberID = m.MemberID
        JOIN Warehouse w ON o.WarehouseID = w.WarehouseID
        JOIN OrdersDetail od ON o.OrderID = od.OrderID
        LEFT JOIN Book b ON od.BookID = b.BookID
        LEFT JOIN E_BOOK eb ON od.EBookID = eb.EBookID
        WHERE CONCAT(m.First_Name, ' ', m.Last_Name) LIKE ?
        ORDER BY o.OrderID";

$stmt = $conn->prepare($sql);
$like = "%$search%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="page-container">
    <h2>Member Orders</h2>

    <form class="search-box" method="GET">
        <input type="text" name="search" placeholder="Search by member name" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>OrderID</th>
            <th>Member</th>
            <th>Order Date</th>
            <th>Shipping Date</th>
            <th>Warehouse</th>
            <th>Item Title</th>
            <th>Quantity</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $itemTitle = $row['BookTitle'] ?? $row['EBookTitle'];
                echo "<tr>
                    <td>{$row['OrderID']}</td>
                    <td>{$row['First_Name']} {$row['Last_Name']}</td>
                    <td>{$row['OrderDate']}</td>
                    <td>{$row['ShippingDate']}</td>
                    <td>{$row['Publisher']}</td>
                    <td>{$itemTitle}</td>
                    <td>{$row['Quantity']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;color:#777;'>No orders found</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</div>

<style>
    .page-container {
        max-width: 1000px;
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

    /* Centered search box */
    .search-box {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
    }

    .search-box input[type="text"] {
        padding: 10px 15px;
        width: 300px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
    }

    .search-box button {
        padding: 10px 20px;
        background-color: #ef7a12; /* same orange as other pages */
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .search-box button:hover {
        background-color: #0b59d0; /* darker blue hover */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th, td {
        text-align: left;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #1a73e8; /* same blue as Book List */
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
