<?php
include 'connection.php';

// Fetch categories for the filter dropdown
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);

if (!$category_result) {
    die(mysqli_error($conn));
}

// Check if a category filter is applied
$category_filter = isset($_GET['category']) ? $_GET['category'] : null;

// Construct the SQL query based on the category filter
$sql = "SELECT p.*, c.name AS category_name, s.name AS subcategory_name
        FROM products p
        INNER JOIN categories c ON p.category_id = c.id
        INNER JOIN subcategories s ON p.subcategory_id = s.id";

if ($category_filter) {
    $sql .= " WHERE p.category_id = " . mysqli_real_escape_string($conn, $category_filter);
}

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Product Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <button class="btn btn-primary my-5"><a href="add_products.php" class="text-light">Add products</a></button>

        <form method="get" class="mb-3">
            <div class="form-group">
                <label for="categoryFilter">Filter by Category:</label>
                <select class="form-control" id="categoryFilter" name="category">
                    <option value="">All Categories</option>
                    <?php
                    while ($category_row = mysqli_fetch_assoc($category_result)) {
                        $selected = ($category_filter == $category_row['id']) ? 'selected' : '';
                        echo "<option value='{$category_row['id']}' $selected>{$category_row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Apply Filter</button>
        </form>

        <table class="table">

            <thead>
                <tr>
                    <th scope="col">SI no</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Category</th>
                    <th scope="col">Subcategory</th>
                    <th scope="col">Image</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $category = $row['category_name'];
                        $subcategory = $row['subcategory_name'];
                        $image = $row['image'];

                        echo '<tr>
                            <th scope="row">' . $id . '</th>
                            <td>' . $name . '</td>
                            <td>' . $description . '</td>
                            <td>' . $price . '</td>
                            <td>' . $category . '</td>
                            <td>' . $subcategory . '</td>
                            <td><img src="' . $image .'" alt="Product Image" style="max-width: 100px;"></td>
                            <td>
                                <button class="btn btn-primary" onclick="location.href=\'update.php?updateid=' . $id . '\'" class="text-light">Update</button>
                                <button class="btn btn-danger" onclick="location.href=\'delete.php?deleteid=' . $id . '\'" class="text-light">Delete</button>
                            </td>
                        </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
