<?php
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "*31311055Jee", "BookStore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = $_POST["isbn"];
    $price = $_POST["price"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $genre = $_POST["genre"];
    $translator = $_POST["translator"];
    $illustrator = $_POST["illustrator"];
    $pub_date = $_POST["pub_date"];
    $edition = $_POST["edition"];
    $printing = $_POST["printing"];
    $stock = $_POST["stock"];

    $sql = "INSERT INTO BOOK (ISBN, PRICE, Title, Author, Genre, Translator, Illustrator, PublicationDate, Edition, NumberOfPrinting, Stock)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sisssssssii",
        $isbn,
        $price,
        $title,
        $author,
        $genre,
        $translator,
        $illustrator,
        $pub_date,
        $edition,
        $printing,
        $stock
    );

    if ($stmt->execute()) {
        echo "<p style='color:green;text-align:center;'>✅ Book added successfully!</p>";
    } else {
        echo "<p style='color:red;text-align:center;'>❌ Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
$conn->close();
?>

<!-- Page Content -->
<div class="container">
    <h2>Create Book</h2>
    <form method="POST" class="form-box">
        <label>ISBN:</label><br>
        <input name="isbn" placeholder="ISBN" required><br><br>

        <label>Price:</label><br>
        <input name="price" type="number" placeholder="Price"><br><br>

        <label>Title:</label><br>
        <input name="title" placeholder="Title" required><br><br>

        <label>Author:</label><br>
        <input name="author" placeholder="Author" required><br><br>

        <label>Genre:</label><br>
        <input name="genre" placeholder="Genre" required><br><br>

        <label>Translator:</label><br>
        <input name="translator" placeholder="Translator"><br><br>

        <label>Illustrator:</label><br>
        <input name="illustrator" placeholder="Illustrator"><br><br>

        <label>Publication Date:</label><br>
        <input name="pub_date" type="date"><br><br>

        <label>Edition:</label><br>
        <input name="edition" placeholder="Edition"><br><br>

        <label>Number of Printing:</label><br>
        <input name="printing" type="number" placeholder="Printing"><br><br>

        <label>Stock:</label><br>
        <input name="stock" type="number" placeholder="Stock"><br><br>

        <button type="submit">Create Book</button>
    </form>
</div>

</body>

</html>