<?php
include 'connection.php';

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category'];
    $subcategory_id = $_POST['subcategory'];

    // Check if the files are uploaded successfully
    if(isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
        $image_folder = 'uploads/';

        // Loop through each uploaded file
        for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
            $image_tmp_name = $_FILES['image']['tmp_name'][$i];
            $image_file = basename($_FILES['image']['name'][$i]);
            $target_file = $image_folder . $image_file;

            if (empty($name) || empty($price) || empty($category_id) || empty($subcategory_id)) {
                $message[] = 'Please fill out all required fields.';
            } else {
                // Move the uploaded file to the destination folder
                move_uploaded_file($image_tmp_name, $target_file);

                // Insert data into the database
                $sql = "INSERT INTO products (name, description, price, category_id, subcategory_id, image) VALUES ('$name', '$description', '$price', '$category_id', '$subcategory_id', '$target_file')";
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    die(mysqli_error($conn));
                }
            }
        }

        header('location: view_products.php');
    } else {
        $message[] = 'Error uploading the files.';
    }
}

// Fetch categories and subcategories for the dropdown lists
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);

$subCategory_query = "SELECT * FROM subcategories";
$subCategory_result = mysqli_query($conn, $subCategory_query);

if (!$category_result || !$subCategory_result) {
    die(mysqli_error($conn));
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Product Management</title>
</head>
<body>
<?php
include "naav.php";
?>

<div class="container my-5">
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product name</label>
            <input type="text" class="form-control" placeholder="Enter name" name="name" autocomplete="off" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="number" name="price" step="0.01" class="form-control" placeholder="Enter price" autocomplete="off" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select class="form-control" name="category" required>
                <?php
                while ($category_row = mysqli_fetch_assoc($category_result)) {
                    echo "<option value='{$category_row['id']}'>{$category_row['name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Subcategory</label>
            <select class="form-control" name="subcategory" required>
                <?php
                while ($subCategory_row = mysqli_fetch_assoc($subCategory_result)) {
                    echo "<option value='{$subCategory_row['id']}'>{$subCategory_row['name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Images</label>
            <input type="file" id="myFile" name="image[]" class="form-control" multiple>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- Bootstrap JS and jQuery (add these at the end of the body) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>
