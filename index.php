<?php
// include user header file
include 'user_header.php';
?>

<?php 
    //if clicked add to cart button and assigning input value
    if(isset($_POST['addcart'])){
        $product_name=$_POST['product_name'];
        $product_price=$_POST['product_price'];
        $product_quantity=$_POST['quantity'];
        $product_name=$_POST['product_name'];
        $product_image=$_POST['product_image'];
        //execute query to select all items from cart where product name and user_id match
        $check_cart_number=mysqli_query($conn,"SELECT * FROM `cart` WHERE name='$product_name' AND user_id='$user_id'") or die('query failed');

        if(mysqli_num_rows($check_cart_number)>0){
            // if the same details row is already in table
            $message[]= 'product already added';
        }
        else{
            //execute query to insert cart values or details
            mysqli_query($conn,"INSERT INTO `cart`(user_id,name,price,quantity,image) 
            VALUES('$user_id','$product_name','$product_price','$product_quantity','$product_image')") or die('query failed!');
            $message[]='product added successfully';
            //redirect to index.php page
            header('location:index.php');
        }
    }
?>

<?php
// print message
if(isset($message)){
    foreach($message as $msg){
        echo'<div class="form" style="color:green"> <span>'.$msg.'</span>
        <img src="./assets/icons/close.png" onclick="this.parentElement.remove();">
            </div>';             
    }
}
?>

<section>
    <div class="banner">
        <img src="./assets/sliders/index_bg_1.jpg" height="500px" alt="">
    </div>
</section>  

<!--products section-->
<section id="products">
    <h2 style="padding-top:20px;">Our Products</h2>
    <div class="Searchbox" style = "margin-left:80%; padding:20px;">
        <input type="text" id="search" placeholder="Enter product name..." style="height:25px;width:200px; border:1px solid black; padding:10px;">
        <input type="button" id="search_box" value="Search" class="add-to-cart">
        <div id="search-results"></div>
    </div>
    <div class="products">      
        <?php 
            //execute query to select all details of products
            $select_products=mysqli_query($conn,"SELECT * FROM `products`") or die('query failed!');
            if(mysqli_num_rows($select_products)>0){
                //if product row is greater than 0
                while($fetch_products=mysqli_fetch_assoc($select_products)){
        ?>    
            <!--fetch product details and make the details as input-->
            <div class="product-details" id="product_<?php echo str_replace(' ', '_', $fetch_products['name']); ?>">
                <form action="" method="post" enctype="multipart/form-data">
                <img src="./uploaded_img./<?php echo $fetch_products['image']; ?>" alt="" class="image">
                <h4><?php echo $fetch_products['name']; ?></h4>
                <p>Rs. <?php echo $fetch_products['price']; ?>/-</p>
                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <span>Quantity:</span><input type="number" name="quantity" min="1" value="1">
                <div class="button">
                    <input style="width:90px" type="submit" value="Add to cart" name="addcart" class="add-to-cart">
                </div>
                </form>
            </div>
        <?php 
                }
            }
            else{
                echo 'No product available !';
            }
        ?>
    </div>
</section>

<?php
//include user_footer.php file
 include 'user_footer.php';
 ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("#search").keyup(function () {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "index.php",
                    method: "POST",
                    data: {
                        query: query
                    },
                    success: function (data) {
                        $('#search-results').html(data);
                        $('#search-results').show(); // Show the dropdown
                    }
                });
            } else {
                $('#search-results').html('');
                $('#search-results').hide(); // Hide the dropdown if input is empty
            }
        });

        // Hide dropdown when clicking outside the input and dropdown
        $(document).click(function (event) {
            if (!$(event.target).closest('#search-results').length && !$(event.target).is('#search')) {
                $('#search-results').hide();
            }
        });

        // Set selected item in input when clicking on dropdown item
        $(document).on('click', '#search-results ul li', function () {
            var value = $(this).text();
            $('#search').val(value); // Update the value of the search input field
            $('#search-results').hide();
            
            // Scroll to the searched item
            var elementId = "product_" + value.replace(/\s/g, '_'); // Replace spaces with underscores in the value
            var element = document.getElementById(elementId);
            if (element) {
                $('html, body').animate({
                    scrollTop: $(element).offset().top
                }, 1000);
            }
        });
        
        // Scroll to the searched item when search_box button is clicked
        $("#search_box").click(function () {
            var query = $("#search").val();
            var elementId = "product_" + query.replace(/\s/g, '_'); // Replace spaces with underscores in the value
            var element = document.getElementById(elementId);
            if (element) {
                $('html, body').animate({
                    scrollTop: $(element).offset().top
                }, 1000);
            }
        });
    });
</script>
