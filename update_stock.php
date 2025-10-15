<?php
include 'header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "BookStore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchResults = [];
$bookData = null;
$message = "";

// 🔍 Handle search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search = $_POST["searchTerm"];
    $query = $conn->prepare("
        SELECT BookID, Title, Author, Genre, PRICE, Stock
        FROM BOOK
        WHERE BookID LIKE ? OR Title LIKE ?
        ORDER BY Title ASC
        LIMIT 10
    ");
    $term = "%$search%";
    $query->bind_param("ss", $term, $term);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $searchResults = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $message = "<div class='message error'>❌ No books found.</div>";
    }
    $query->close();
}

// 📖 Handle selection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectBook"])) {
    $id = $_POST["id"];
    $stmt = $conn->prepare("SELECT * FROM BOOK WHERE BookID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $bookData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// ✏️ Handle stock update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateStock"])) {
    $id = $_POST["bookID"];
    $newStock = $_POST["newStock"];

    $stmt = $conn->prepare("UPDATE BOOK SET Stock = ? WHERE BookID = ?");
    $stmt->bind_param("ii", $newStock, $id);

    if ($stmt->execute()) {
        $message = "<div class='message success'>✅ Book stock updated successfully!</div>";
    } else {
        $message = "<div class='message error'>❌ Error updating stock: {$stmt->error}</div>";
    }
    $stmt->close();
}
$conn->close();
?>

<div class="container">
    <h2>Update Book Stock</h2>
    <?php echo $message; ?>

    <!-- Search bar -->
    <?php if (!$bookData && empty($searchResults)): ?>
        <form method="POST" class="form-box">
            <label>Search by BookID or Title:</label>
            <input type="text" name="searchTerm" placeholder="e.g. 1003 or Harry Potter" required>
            <button type="submit" name="search">Search</button>
        </form>
    <?php endif; ?>

    <!-- Search results -->
    <?php if (!empty($searchResults)): ?>
        <div class="results-box">
            <h3>Search Results</h3>
            <?php foreach ($searchResults as $b): ?>
                <form method="POST" class="result-item">
                    <input type="hidden" name="id" value="<?php echo $b['BookID']; ?>">
                    <p><strong><?php echo $b['BookID']; ?> <?php echo htmlspecialchars($b['Title']); ?> </strong>
                        — <?php echo $b['Author']; ?> (<?php echo $b['Genre']; ?>) — Stock: <?php echo $b['Stock']; ?>
                    </p>
                    <button type="submit" name="selectBook">Select</button>
                </form>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Book details -->
    <?php if ($bookData): ?>
        <div class="detail-box">
            <h3><?php echo $bookData["Title"]; ?></h3>
            <p><strong>Book ID:</strong> <?php echo $bookData["BookID"]; ?></p>
            <p><strong>Author:</strong> <?php echo $bookData["Author"]; ?></p>
            <p><strong>Genre:</strong> <?php echo $bookData["Genre"]; ?></p>
            <p><strong>Price:</strong> <?php echo $bookData["PRICE"]; ?>฿</p>
            <p><strong>Current Stock:</strong> <?php echo $bookData["Stock"]; ?></p>

            <form method="POST">
                <input type="hidden" name="bookID" value="<?php echo $bookData['BookID']; ?>">
                <label>New Stock Quantity:</label>
                <input type="number" name="newStock" placeholder="Enter new stock amount" required>
                <button type="submit" name="updateStock">Update Stock</button>
            </form>


        </div>
    <?php endif; ?>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 550px;
        margin: 60px auto;
        background: white;
        padding: 35px 40px;
        border-radius: 15px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .container h2 {
        margin-bottom: 25px;
        border-bottom: 3px solid #1a73e8;
        display: inline-block;
        padding-bottom: 6px;
    }

    .form-box {
        text-align: left;
    }

    .form-box label {
        font-weight: bold;
    }

    .form-box input[type="text"],
    .form-box input[type="number"],
    .detail-box input[type="number"] {
        width: 100%;
        padding: 15px 12px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-top: 8px;
        margin-bottom: 15px;
        box-sizing: border-box;
    }

    .detail-box button {
        padding: 16px 0;
        font-size: 17px;
    }


    .form-box button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 6px;
        background-color: #1a73e8;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .form-box button:hover {
        background-color: #0b59d0;
    }

    .results-box {
        margin-top: 20px;
        text-align: left;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
    }

    .result-item {
        border-bottom: 1px solid #ddd;
        padding: 8px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .result-item button {
        background-color: #1a73e8;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
    }

    .result-item button:hover {
        background-color: #0b59d0;
    }

    .detail-box {
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: left;
        margin-top: 20px;
    }

    .detail-box h3 {
        margin-top: 0;
        color: #222;
    }

    .detail-box button {
        width: 100%;
        background-color: #1a73e8;
        color: white;
        border: none;
        padding: 14px 0;
        font-size: 17px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s, transform 0.1s;
    }

    .detail-box button:hover {
        background-color: #0b59d0;
        transform: scale(1.02);
    }

    .back-btn {
        background-color: transparent;
        color: #1a73e8;
        border: none;
        font-size: 16px;
        cursor: pointer;
        text-decoration: underline;
        font-weight: bold;
    }

    .back-btn:hover {
        color: #0b59d0;
    }

    .message {
        margin: 20px auto;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        font-weight: bold;
        width: 90%;
    }

    .success {
        background: #d4edda;
        color: #155724;
    }

    .error {
        background: #f8d7da;
        color: #721c24;
    }
</style>