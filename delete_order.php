<?php
include 'header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "*31311055Jee", "BookStore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orderData = null;
$orderItems = [];
$message = "";

// 🟦 Handle search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search = $_POST["searchTerm"];

    $query = $conn->prepare("
        SELECT o.OrderID, o.OrderDate, o.ShippingDate, m.First_Name, m.Last_Name 
        FROM Orders o
        JOIN Member m ON o.MemberID = m.MemberID
        WHERE o.OrderID LIKE ? OR m.First_Name LIKE ? OR m.Last_Name LIKE ?
        LIMIT 1
    ");
    $term = "%$search%";
    $query->bind_param("sss", $term, $term, $term);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $orderData = $result->fetch_assoc();

        // Get all items in this order (both BOOK and E_BOOK)
        $orderID = $orderData['OrderID'];
        $itemQuery = $conn->prepare("
            SELECT 
                COALESCE(b.Title, e.Title) AS Title,
                CASE WHEN od.BookID IS NOT NULL THEN 'Book' ELSE 'E-Book' END AS Type,
                od.Quantity
            FROM OrdersDetail od
            LEFT JOIN BOOK b ON od.BookID = b.BookID
            LEFT JOIN E_BOOK e ON od.EBookID = e.EBookID
            WHERE od.OrderID = ?
        ");
        $itemQuery->bind_param("i", $orderID);
        $itemQuery->execute();
        $orderItems = $itemQuery->get_result()->fetch_all(MYSQLI_ASSOC);
        $itemQuery->close();
    } else {
        $message = "<div class='message error'>❌ No order found.</div>";
    }
    $query->close();
}

// 🧾 Handle delete
// 🧾 Handle delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteOrder"])) {
    $orderID = intval($_POST["orderID"]);

    // 🔹 Start a transaction to ensure atomic delete
    $conn->begin_transaction();

    try {
        // Delete dependent records first (to satisfy FK constraints)
        $stmt1 = $conn->prepare("DELETE FROM OrdersDetail WHERE OrderID = ?");
        $stmt1->bind_param("i", $orderID);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $conn->prepare("DELETE FROM Receipt WHERE OrderID = ?");
        $stmt2->bind_param("i", $orderID);
        $stmt2->execute();
        $stmt2->close();

        // Delete E_Receipt and its details
        $stmt3 = $conn->prepare("
            DELETE FROM E_Receipt_Detail 
            WHERE E_ReceiptNumber IN (
                SELECT E_ReceiptNumber FROM E_Receipt WHERE OrderID = ?
            )
        ");
        $stmt3->bind_param("i", $orderID);
        $stmt3->execute();
        $stmt3->close();

        $stmt4 = $conn->prepare("DELETE FROM E_Receipt WHERE OrderID = ?");
        $stmt4->bind_param("i", $orderID);
        $stmt4->execute();
        $stmt4->close();

        // Finally, delete the order itself
        $stmt5 = $conn->prepare("DELETE FROM Orders WHERE OrderID = ?");
        $stmt5->bind_param("i", $orderID);
        $stmt5->execute();
        $stmt5->close();

        // Commit all
        $conn->commit();

        $message = "<div class='message success'>✅ Order ID $orderID and its related records were deleted successfully.</div>";
    } catch (Exception $e) {
        $conn->rollback();
        $message = "<div class='message error'>❌ Failed to delete Order ID $orderID: " . $e->getMessage() . "</div>";
    }
}

$conn->close();
?>

<div class="container">
    <h2>Delete Order</h2>

    <!-- Search -->
    <form method="POST" class="form-box">
        <label for="searchTerm" id="labelForSearchTerm">Search by Order ID or Customer Name:</label>
        <input type="text" name="searchTerm" placeholder="e.g. 1010 or John" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php echo $message; ?>

    <!-- Order details panel -->
    <div class="order-info">
        <?php if ($orderData): ?>
            <p><strong>Order ID:</strong> <?php echo $orderData['OrderID']; ?></p>
            <p><strong>Customer:</strong> <?php echo $orderData['First_Name'] . " " . $orderData['Last_Name']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $orderData['OrderDate']; ?></p>
            <p><strong>Shipping Date:</strong> <?php echo $orderData['ShippingDate']; ?></p>

            <hr>
            <p><strong>Ordered Items:</strong></p>
            <?php if (count($orderItems) > 0): ?>
                <ul class="item-list">
                    <?php foreach ($orderItems as $item): ?>
                        <li>
                            <?php echo "<strong>{$item['Title']}</strong> ({$item['Type']}) — Qty: {$item['Quantity']}"; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color:#777;">No items found for this order.</p>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="orderID" value="<?php echo $orderData['OrderID']; ?>">
                <button type="submit" name="deleteOrder"
                    onclick="return confirm('⚠️ Are you sure you want to delete this order? This action cannot be undone.')">
                    Delete Order
                </button>
            </form>
        <?php else: ?>
            <p style="text-align:center; color:#777;">🔍 Enter an Order ID or Customer name above to view order details.</p>
        <?php endif; ?>
    </div>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        text-align: center;
        max-width: 450px;
        margin: 60px auto;
        background: white;
        padding: 30px 40px;
        border-radius: 15px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }

    .container h2 {

        color: #222;
        margin-bottom: 25px;
        border-bottom: 3px solid #1a73e8;
        display: inline-block;
        padding-bottom: 6px;
    }

    #labelForSearchTerm {
        text-align: left;
    }

    .form-box label {
        display: block;
        margin: 8px 0 4px;
        font-weight: bold;
    }

    .form-box input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .form-box button {
        width: 100%;
        background-color: #1a73e8;
        color: white;
        border: none;
        padding: 10px;
        font-size: 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .form-box button:hover {
        background-color: #0b59d0;
    }

    /* Order detail box */
    .order-info {
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        font-size: 15px;
        color: #333;
    }

    /* List of ordered items */
    .item-list {
        list-style-type: none;
        padding-left: 0;
        margin-top: 10px;
    }

    .item-list li {
        padding: 6px 0;
        border-bottom: 1px solid #eee;
    }

    .item-list li:last-child {
        border-bottom: none;
    }

    /* 🔴 Big prominent delete button */
    .order-info button {
        width: 100%;
        display: block;
        background-color: #e63946;
        color: white;
        border: none;
        padding: 14px 0;
        font-size: 17px;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 20px;
        font-weight: bold;
        transition: background-color 0.3s, transform 0.1s;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .order-info button:hover {
        background-color: #c62828;
        transform: scale(1.02);
    }

    .order-info button:active {
        transform: scale(0.98);
    }

    /* ✅ Success & error messages */
    .message {
        max-width: 450px;
        margin: 20px auto;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        font-weight: bold;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

</body>

</html>