<div class="header">
    <div class="logo">Blues</div>

    <div class="nav-menu">
        <div class="dropdown">
            <button class="dropbtn">
                <h2>Create</h2>
            </button>
            <div class="dropdown-content">
                <a href="create_book.php">Create Book</a>
                <a href="create_ebook.php">Create E-Book</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">
                <h2>List</h2>
            </button>
            <div class="dropdown-content">
                <a href="list_book.php">List Books</a>
                <a href="list_ebook.php">List E-Books</a>
                <a href="list_order.php">List Orders</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">
                <h2>Update</h2>
            </button>
            <div class="dropdown-content">
                <a href="update_stock.php">Update Stock</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">
                <h2>Delete</h2>
            </button>
            <div class="dropdown-content">
                <a href="delete_ebook.php">Delete Book</a>
                <a href="delete_order.php">Delete Order</a>
            </div>
        </div>
    </div>
</div>

<style>
    .header {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 5px;
        background-color: #ffffff;
        top: 0;
        z-index: 10;
        gap: 30px;
    }

    .logo {
        position: absolute;
        left: 30px;
        font-size: 40px;
        font-weight: bold;
        color: #8cbeffff;
    }

    .nav-menu {
        display: flex;
        gap: 20px;
    }

    .dropdown {
        position: relative;
        align-items: center;
    }

    .dropbtn {
        background-color: #ffffff;
        color: #333;
        padding: 10px 20px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        border-radius: 6px;
        transition: background-color 0.3s, color 0.3s;
    }

    .dropbtn:hover {
        background-color: #1a73e8;
        color: white;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #ffffff;
        min-width: 160px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 1;
        border-radius: 6px;
        overflow: hidden;
    }

    .dropdown-content a {
        color: #333;
        padding: 10px 15px;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s;
    }

    .dropdown-content a:hover {
        background-color: #f2f2f2;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>