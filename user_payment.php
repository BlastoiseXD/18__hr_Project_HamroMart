<?php include 'user_header.php'; ?>

<?php
// Execute SQL query to fetch bill details
$bill_query = mysqli_query($conn, "SELECT * FROM bill");

// Check if the query was successful
if ($bill_query) {
    //clicked order now and assigning the input values
    if(isset($_POST['payment'])) {
        $name = mysqli_real_escape_string($conn,$_POST['name']);
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $address = mysqli_real_escape_string($conn,$_POST['address']);
        $phone = mysqli_real_escape_string($conn,$_POST['phone']);
        $method = mysqli_real_escape_string($conn,$_POST['method']);
        $cart_total = 0;
        $cart_products = '';

        //execute query for the select cart items where user_id matched
        $cart_query = mysqli_query($conn,"SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');

        if(mysqli_num_rows($cart_query) > 0) {
            //if selected cart has more than 0 row
            while($cart_item = mysqli_fetch_assoc($cart_query)) {
                // fetch cart details
                $cart_products .= $cart_item['name'].' ('.$cart_item['quantity'].') ';
                //calculate sub total and grant total
                $sub_total = ($cart_item['price'] * $cart_item['quantity']);
                $cart_total += $sub_total;
            }
        }

        //execute query for the select orders where all condition is true
        $order_query = mysqli_query($conn,"SELECT * FROM `orders` WHERE name='$name' AND number='$phone' AND email='$email' AND method='$method' AND address='$address' AND total_products='$cart_products' AND total_price='$cart_total'") or die('query failed!');
        
        // show message if cart is empty
        if($cart_total == 0) {
            $message[] = 'Your cart is empty';
        } else {
            //orders row already exists
            if(mysqli_num_rows($order_query) > 0) {
                $message[] = 'Order already placed';
            } else {
                //execute query for the insert all details of orders
                mysqli_query($conn,"INSERT INTO `orders`(user_id,name,number,email,method,address,total_products,total_price) VALUES('$user_id','$name','$phone','$email','$method','$address','$cart_products','$cart_total')") or die('query failed!');
                $message[] = 'Order placed successfully';
                //delete the cart items from selected users id
                mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                //redirect user_order.php page
                header('location:user_order.php');
            }
        }
    }
} else {
    // Handle the case where the bill query failed
    echo "Error: " . mysqli_error($conn);
}
?>

<section>
    <!--payment section -->
    <div class="payment">
        <h2>Order Now</h2>
        <form action="" method="post">
            <div class="payment-details">
                <span><img src="./assets/icons/id-card.png" alt=""></span><input type="text" name="name" placeholder="Enter your name" required class="pay">
            </div>
            <div class="payment-details">
                <span><img src="./assets/icons/email.png" alt=""></span><input type="email" name="email" placeholder="Enter your email" required class="pay">
            </div>
            <div class="payment-details">
                <span><img src="./assets/icons/home-address.png" alt=""></span><input type="text" name="address" placeholder="Enter your address" required class="pay">
            </div>
            <div class="payment-details">
                <span><img src="./assets/icons/phone-call.png" alt=""></span><input type="text" name="phone" placeholder="Enter your phone" required class="pay">
            </div>
            <div class="payment-details">
                <span><img src="./assets/icons/debit-card.png" alt=""></span>
                <select name="method" id="" class="pay" required>
                    <option value="e-sewa">e-sewa</option>
                    <option value="cash on delivery">cash on delivery</option>
                </select>
            </div>
            <div class="payment-details">
                <span><img src="./assets/icons/order-now.png" alt=""></span>
                <input type="submit" name="payment" value="order now" class="pay pay-now">
            </div>
        </form>
    </div>
<style>
    .bill{
        padding: 20px;
    }
    #bill_head {
        text-align: center;
        padding-bottom: 15px;
    }
    .bill table{
        margin-left: 37%;
        border-collapse: collapse;
    }
    .bill td, th{
        padding: 20px;
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
    <!-- Bill section -->
    <div class="bill">
        <h2 id="bill_head">Bill</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
            // Initializing grand total
            $grand_total = 0;

            // Loop through each bill item and display details
            while ($row = mysqli_fetch_assoc($bill_query)) {
                // Calculate total for each item
                $total = $row['price'] * $row['quantity'];
                // Add to grand total
                $grand_total += $total;
            ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $total; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3">Grand Total</td>
                <td><?php echo $grand_total; ?></td>
            </tr>
        </table>
    </div>
</section>

<?php include 'user_footer.php'; ?>
