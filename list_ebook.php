<?php
include 'header.php';
$conn = new mysqli("localhost", "root", "*31311055Jee", "BookStore");

// Handle search
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM E_BOOK WHERE Title LIKE '%$search%' OR Author LIKE '%$search%'";
$result = $conn->query($query);
?>

<div class="page-container">
    <h2>E-Book List</h2>

    <form class="search-box" method="GET">
        <input type="text" name="search" placeholder="Search by title or author"
            value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>EBookID</th>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th>Contract Expiration</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['EBookID']}</td>
                    <td>{$row['ISBN']}</td>
                    <td>{$row['Title']}</td>
                    <td>{$row['Author']}</td>
                    <td>{$row['PRICE']}</td>
                    <td>" . date('Y-m-d', strtotime($row['ContractExpirationDate'])) . "</td>

                </tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;color:#777;'>No e-books found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

<style>
    .page-container {
        max-width: 900px;
        margin: 50px auto;
        /* Centers the whole box */
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 35px;
        text-align: center;
        /* centers Book List title */
    }

    .page-container h2 {
        display: inline-block;
        border-bottom: 3px solid #ef7a12;
        padding-bottom: 6px;
        margin-bottom: 25px;
        color: #222;
    }

    /* Center and style the search bar */
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

    /* ✅ Match search button color to header blue */
    .search-box button {
        padding: 10px 20px;
        background-color: #ef7a12;
        /* same blue as table header */
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .search-box button:hover {
        background-color: #0b59d0;
        /* darker blue hover */
    }

    /* Table styling stays the same */
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