<?php
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "BookStore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    $contract_exp = $_POST["contract_expiration"];

    $sql = "INSERT INTO E_BOOK (ISBN, PRICE, Title, Author, Genre, Translator, Illustrator, PublicationDate, Edition, NumberOfPrinting, ContractExpirationDate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);

    $stmt->bind_param(
        "sisssssssis",
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
        $contract_exp
    );

    if ($stmt->execute()) {
        echo "<p style='color:green;text-align:center;'>✅ E-Book added successfully!</p>";
    } else {
        echo "<p style='color:red;text-align:center;'>❌ Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
$conn->close();
?>

<!-- Page Content -->
<div class="container">
    <h2>Create E-Book</h2>
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

        <label>Contract Expiration Date:</label><br>
        <input name="contract_expiration" type="date"><br><br>

        <button type="submit">Create E-Book</button>
    </form>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 400px;
        margin: 60px auto;
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        text-align: left;
    }

    .container h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #000;
    }

    .form-box label {
        display: block;
        margin: 8px 0 4px;
        font-weight: bold;
    }

    .form-box input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
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
</style>