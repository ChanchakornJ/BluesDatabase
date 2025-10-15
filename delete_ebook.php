<?php
include 'header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "BookStore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$type = $_POST["type"] ?? null;
$bookData = null;
$searchResults = [];
$message = "";

// 🔍 Step 2: Handle search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $type = $_POST["type"];
    $search = $_POST["searchTerm"];
    $table = ($type == "ebook") ? "E_BOOK" : "BOOK";
    $idField = ($type == "ebook") ? "EBookID" : "BookID";

    $query = $conn->prepare("
        SELECT $idField AS ID, Title, Author, Genre, PRICE
        FROM $table
        WHERE $idField LIKE ? OR Title LIKE ?
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
        $message = "<div class='message error'>❌ No matching records found.</div>";
    }
    $query->close();
}

// 📖 Step 3: Show details for selected record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectBook"])) {
    $type = $_POST["type"];
    $id = $_POST["id"];
    $table = ($type == "ebook") ? "E_BOOK" : "BOOK";
    $idField = ($type == "ebook") ? "EBookID" : "BookID";

    $detailQuery = $conn->prepare("SELECT * FROM $table WHERE $idField = ?");
    $detailQuery->bind_param("i", $id);
    $detailQuery->execute();
    $bookData = $detailQuery->get_result()->fetch_assoc();
    $detailQuery->close();
}

// 🗑️ Step 4: Handle delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteBook"])) {
    $type = $_POST["type"];
    $id = $_POST["id"];
    $table = ($type == "ebook") ? "E_BOOK" : "BOOK";
    $idField = ($type == "ebook") ? "EBookID" : "BookID";

    $check = $conn->prepare("SELECT COUNT(*) FROM OrdersDetail WHERE $idField = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
        $message = "<div class='message error'>⚠️ Cannot delete — this item is used in existing orders.</div>";
    } else {
        $stmt = $conn->prepare("DELETE FROM $table WHERE $idField = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "<div class='message success'>✅ Deleted successfully.</div>";
        } else {
            $message = "<div class='message error'>❌ Error: {$stmt->error}</div>";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<div class="container">
    <?php
    if ($type == "ebook") {
        echo "<h2>Delete E-Book</h2>";
    } elseif ($type == "book") {
        echo "<h2>Delete Book</h2>";
    } else {
        echo "<h2>Delete Book</h2>";
    }
    ?>    <?php echo $message; ?>

    <!-- Step 1: Choose Type -->
    <?php if (!$type): ?>
        <div class="choice-box">
            <form method="POST">
                <button class="choice book" name="type" value="book">📘 Delete Book</button>
            </form>
            <form method="POST">
                <button class="choice ebook" name="type" value="ebook">💻 Delete E-Book</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Step 2: Search -->
    <?php if ($type && !$bookData && empty($searchResults)): ?>
        <form method="POST" class="form-box">
            <input type="hidden" name="type" value="<?php echo $type; ?>">
            <label>Search by ID or Title:</label>
            <input type="text" name="searchTerm" placeholder="e.g. 3 or Harry Potter" required>
            <button type="submit" name="search">Search</button>
        </form>
    <?php endif; ?>

    <!-- Step 2.5: Search results list -->
    <?php if (!empty($searchResults)): ?>
        <div class="results-box">
            <h3>Search Results</h3>
            <?php foreach ($searchResults as $b): ?>
                <form method="POST" class="result-item">
                    <input type="hidden" name="type" value="<?php echo $type; ?>">
                    <input type="hidden" name="id" value="<?php echo $b['ID']; ?>">
                    <p><strong><?php echo htmlspecialchars($b['Title']); ?></strong>
                        — <?php echo $b['Author']; ?> (<?php echo $b['Genre']; ?>) — <?php echo $b['PRICE']; ?>฿
                    </p>
                    <button type="submit" name="selectBook">Select</button>
                </form>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Step 3: Book Details -->
    <?php if ($bookData): ?>
        <div class="detail-box">
            <h3><?php echo $bookData["Title"]; ?></h3>
            <p><strong>ID:</strong> <?php echo ($type == "ebook" ? $bookData["EBookID"] : $bookData["BookID"]); ?></p>
            <p><strong>Author:</strong> <?php echo $bookData["Author"]; ?></p>
            <p><strong>Genre:</strong> <?php echo $bookData["Genre"]; ?></p>
            <p><strong>Price:</strong> <?php echo $bookData["PRICE"]; ?>฿</p>
            <?php if ($type == "book"): ?>
                <p><strong>Stock:</strong> <?php echo $bookData["Stock"]; ?></p>
            <?php else: ?>
                <p><strong>Contract Expiration:</strong> <?php echo $bookData["ContractExpirationDate"]; ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="id" value="<?php echo ($type == "ebook" ? $bookData["EBookID"] : $bookData["BookID"]); ?>">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
                <button type="submit" name="deleteBook" onclick="return confirm('⚠️ Are you sure you want to delete this record?')">
                    Delete <?php echo ($type == "ebook" ? 'E-Book' : 'Book'); ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
    .container {
        max-width: 550px;
        margin: 60px auto;
        background: white;
        padding: 35px 40px;
        border-radius: 15px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        text-align: center;
    }
    .container h2 {
        margin-bottom: 25px;
        border-bottom: 3px solid #1a73e8;
        display: inline-block;
        padding-bottom: 6px;
    }
    .choice-box { display: flex; flex-direction: column; gap: 20px; }
    .choice {
        width: 100%; padding: 20px;
        font-size: 20px; font-weight: bold;
        border: none; border-radius: 10px;
        cursor: pointer; color: white;
        transition: transform 0.1s, background-color 0.3s;
    }
    .choice.book { background-color: #1a73e8; }
    .choice.ebook { background-color: #34a853; }
    .choice:hover { transform: scale(1.02); opacity: 0.9; }
    .form-box { text-align: left; }
    .form-box label { font-weight: bold; }
    .form-box input[type="text"] {
        width: 100%; padding: 10px;
        border: 1px solid #ccc; border-radius: 6px;
        margin-top: 5px; margin-bottom: 10px;
    }
    .form-box button {
        width: 100%; padding: 12px;
        border: none; border-radius: 6px;
        background-color: #1a73e8;
        color: white; font-size: 16px;
        cursor: pointer; transition: background-color 0.3s;
    }
    .form-box button:hover { background-color: #0b59d0; }
    .results-box {
        margin-top: 20px; text-align: left;
        background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; padding: 15px;
    }
    .result-item {
        border-bottom: 1px solid #ddd; padding: 8px 0; display: flex; justify-content: space-between; align-items: center;
    }
    .result-item button {
        background-color: #1a73e8; color: white; border: none;
        padding: 6px 12px; border-radius: 6px; cursor: pointer;
    }
    .result-item button:hover { background-color: #0b59d0; }
    .detail-box {
        background: #f8f9fa; border: 1px solid #ddd;
        border-radius: 8px; padding: 20px; text-align: left;
        margin-top: 20px;
    }
    .detail-box h3 { margin-top: 0; color: #222; }
    .detail-box button {
        width: 100%; background-color: #e63946; color: white;
        border: none; padding: 14px 0;
        font-size: 17px; border-radius: 6px;
        cursor: pointer; font-weight: bold;
        transition: background-color 0.3s, transform 0.1s;
    }
    .detail-box button:hover { background-color: #c62828; transform: scale(1.02); }
    .message {
        margin: 20px auto; padding: 12px;
        border-radius: 8px; text-align: center;
        font-weight: bold; width: 90%;
    }
    .success { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }
</style>
