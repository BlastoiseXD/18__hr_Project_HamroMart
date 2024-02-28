   <?php
       //include header file
         include 'admin_header.php';
              

          // check add products is set or not
         if(isset($_POST['add_product']))
          {
            // if isset true then assining input values
            $name = mysqli_real_escape_string($conn,$_POST['name']);
            $price=$_POST['price'];
            $type=$_POST['type'];
            $image=$_FILES['image']['name'];
            $image_size=$_FILES['image']['size'];
            $image_tmp_name=$_FILES['image']['tmp_name'];
            $image_folder='uploaded_img/'.$image;
           // execute query to select  product name
            $select_product_name= mysqli_query($conn,"SELECT name FROM `products` WHERE name='$name'") or die('query failed');
         // check the produvt already exist or not
            if(mysqli_num_rows($select_product_name)>0){
                $message[]='Product name already exists';

            }
            // not exist then insertt the data into products table
            else{
              //execute query ti insert the product details
              $add_product_query=mysqli_query($conn,"INSERT INTO `products`(name,price,image,type) VALUES('$name',
              '$price','$image','$type')") or die('query failed!');
           
              if($add_product_query){
                // check size of image
                if($image_size>2000000)
                {
                  $message[]='Image size too large';
                }
                else{
                  // move the uploaded imag ein uploaded_img folder
                  move_uploaded_file($image_tmp_name,$image_folder);
                  $message[]='product added successfully';
                  header('location:admin_products.php');
                }
              }
              else{
                $message[]='Product could not be added';
              }
            }

          }

     ?>
          


<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add-products</title>
    <link rel="stylesheet" href="./css/login.css">
    <style>
      .product-container {
        margin-top: 12px;
      }
    </style>
    <style>
    .absolute_bottom_admin_footer{
        position:fixed;
        bottom:0;
        width:100%;
    }
</style>
  </head>

  <body>
    <div class="product-container">
        <h2 align="center" style="color:purple">Add Product</h2>
      <form action="" method="post" enctype="multipart/form-data">
        <?php
          if(isset($message)){
              foreach($message as $message){
                 echo'<div class="form" style="color:red"> <span>'.$message.'</span>
                          <img src="./assets/icons/close.png" onclick="this.parentElement.remove();">
                       </div>';      
                 }
              }
          ?>
       <div class="form">
          <span><img src="./assets/icons/name.png" alt=""></span> <input type="text" name="name"
           placeholder="Enter product name" class="box" required>
       </div>
       <div class="form">
          <span><img src="./assets/icons/get-money.png" alt=""></span> <input type="number" name="price"
          placeholder="Enter product price" class="box" required min="1">
       </div>
       <div class="form">
          <span><img src="./assets/icons/type.png" height="16" width="16" alt=""></span> <input type="text" name="type"
           placeholder="Enter product type" class="box" required>
       </div>
       <div class="form">
          <span><img src="./assets/icons/image-files.png" alt=""></span> <input type="file" name="image"
          accept="image/jpeg, image/png, image/jpg" class="box" required>
       </div>
       <div class="form">
         <input type="submit" name="add_product" value="Add" class="box btn">
       </div>


    </form>

  </div>
 </body>

</html>

<?php
//include admin footer file
   include 'admin_footer.php';
?>