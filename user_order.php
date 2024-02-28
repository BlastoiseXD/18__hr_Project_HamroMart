<?php
//include user_header.php
include 'user_header.php';
include 'download_bill.php';
// Delete contents from the bill table when the page load
?>
<!---order section-->
<div class="order">
    <br><br>
    <h2>Orders Placed</h2>
    <br>
    <div class="order-container" style="height:fill-content;">
        <table>
            <tr>
                <th>Product Name Quantity</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Order Status</th>
                <th>Order Date</th>
            </tr>
            <?php
            // execute query for the select all from orders
            $select_oreders = mysqli_query($conn,"SELECT * FROM `orders` WHERE user_id='$user_id'") or die('query failed');
            if(mysqli_num_rows($select_oreders) > 0) {
                //if orders row is more than 0
                while($fetch_orders = mysqli_fetch_assoc($select_oreders)) {
                    $order_status = $fetch_orders['order_status'];
                    // fetch all details of orders
            ?>
            <tr>
                <!--print  details of orders-->
                <td><?php echo $fetch_orders['total_products'];?></td>
                <td><?php echo $fetch_orders['total_price'];?></td>
                <td><?php echo $fetch_orders['method'];?></td>
                <td><?php echo $fetch_orders['order_status'] ?></td>
                <!-- php echo $fetch_orders['date']; -->
                <td><?php 
                       $current_date = date('Y-m-d');
                       echo $current_date
                     ?>
                </td>
                <td><a href="user_order.php?delete=<?php echo $fetch_orders['id'];?>" onclick="return confirm('Are you sure?')" class="cancel <?php echo (strcmp($order_status,'pending'))? :'';?>">Cancel</a></td>  
            </tr>
            <?php
                }
            } else {
                echo'<p>no order placed yet</p>';
            }
            ?>
        </table>    
    </div>
</div>

<?php
mysqli_query($conn, "DELETE FROM `bill`") or die('Failed to delete bill contents');
//include admin footer file
include 'user_footer.php'
?>
