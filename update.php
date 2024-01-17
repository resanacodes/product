<?php
include 'connection.php';

$id = $_GET['updateid'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/' . $_FILES['image']['name'];
        
        move_uploaded_file($image_tmp_name, $image_folder);
        $image = $_FILES['image']['name'];
    } else {
        // Keep the existing image if no new image is uploaded
        $image = $_POST['current_image'];
    }

    if (empty($name) || empty($price)) {
        $message[] = 'Please fill out all required fields.';
    } else {
        // Update data in the database
        $sql = "UPDATE products SET name='$name', description='$description', price='$price', image='$image' WHERE id=$id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            header('location: view_products.php');
            exit();
        } else {
            die(mysqli_error($conn));
        }
    }
}

// Fetch existing data for pre-populating the form
$sql = "SELECT * FROM products WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$name = $row['name'];
$description = $row['description'];
$price = $row['price'];
$image = $row['image'];
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Crud operation</title>
</head>

<body>
    <div class="container my-5">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="current_image" value="<?php echo $image; ?>">
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" placeholder="Enter name" name="name" autocomplete="off" required value="<?php echo $name; ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" required><?php echo $description; ?></textarea>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" step="0.01" class="form-control" placeholder="Enter price" autocomplete="off" required value="<?php echo $price; ?>">
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" id="myFile" name="image" class="form-control">
                <?php
                if (!empty($image)) {
                    echo '<img src="uploads/' . $image . '" alt="Product Image" class="mt-2" style="max-width: 200px;">';
                }
                ?>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>