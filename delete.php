<?php
include 'connection.php';
if(isset($_GET['deleteid'])){
    $id=$_GET['deleteid'];
    $sql="delete from `products` where id=$id";
    $result=mysqli_query($conn,$sql);
    if($result){
      //  echo"deleted successfull";
      header('location:view_products.php');
    }else{
        die(mysqli_error($conn));
    }
}
?>