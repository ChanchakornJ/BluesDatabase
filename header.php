<!DOCTYPE html>
<html>

<head>
    <title>Blues</title>
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
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            text-align: left;
        }

        .container h2 {
            display: block;
            text-align: center;       /* centers the text */
            margin: 0 auto 25px auto; /* centers the element itself */
            color: #222;
            border-bottom: 3px solid #1a73e8;
            width: fit-content;       /* only as wide as the text */
            padding-bottom: 6px;
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

        .header {
            background-color: #ffffff;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .header a {
            color: #333;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 5px;
            font-weight: bold;
            transition: color 0.3s, background-color 0.3s;
        }

        .header a:hover {
            background-color: #e8e8e8;
            color: #000;
            border-radius: 6px;
        }
        /* General layout */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Header bar (already exists but cleaner) */
        .header {
            background-color: #ffffff;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header a {
            color: #333;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 5px;
            font-weight: bold;
            transition: color 0.3s, background-color 0.3s;
            border-radius: 6px;
        }

        .header a:hover {
            background-color: #1a73e8;
            color: #fff;
        }

        /* Container for list */
        .page-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        /* Title */
        .page-container h2 {
            text-align: center;
            color: #222;
            margin-bottom: 20px;
            border-bottom: 3px solid #1a73e8;
            display: inline-block;
            padding-bottom: 6px;
        }

        /* Search bar */
        .search-box {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 8px;
        }

        .search-box input[type="text"] {
            padding: 8px 12px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-box button {
            background-color: #1a73e8;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-box button:hover {
            background-color: #0b59d0;
        }

        /* Table styles */
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

        /* Responsive */
        @media (max-width: 768px) {
            .page-container {
                width: 95%;
                padding: 15px;
            }

            .search-box {
                flex-direction: column;
                align-items: center;
            }

            .search-box input[type="text"] {
                width: 100%;
            }
        }

    </style>

</head>


<body>

    <div class="header">
        <a href="create_book.php">
            Create Book
        </a>
        <a href="create_ebook.php">
            Create E-Book
        </a>
        <a href="list_book.php">
            List Books
        </a>
        <a href="list_ebook.php">
            List E-Books
        </a>
        <a href="list_order.php">
            List Orders
        </a>
        <a href="update_stock.php">
            Update Stock
        </a>
        <a href="delete_ebook.php">
            Delete Book
        </a>
        <a href="delete_order.php">
            Delete Order
        </a>
    </div>