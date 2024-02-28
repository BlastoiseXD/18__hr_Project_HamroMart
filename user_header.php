<?php
// include config file
include 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header('location: login.php');
    exit; // Stop further execution
}

// Establish database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$output = '';

// Check if the "query" parameter is set in the POST request
if(isset($_POST["query"])) {
    // Sanitize the input to prevent SQL injection
    $searchQuery = mysqli_real_escape_string($conn, $_POST["query"]);

    // Construct the SQL query with a placeholder
    $query = "SELECT * FROM products WHERE name LIKE '%$searchQuery%'";

    // Execute the SQL query
    $result = mysqli_query($conn, $query);

    // Start building the output HTML
    $output .= '<ul class="list-unstyled" style="list-style-type:none;">';

    // Check if there are any rows returned by the query
    if(mysqli_num_rows($result) > 0) {
        // Fetch rows and add them to the output HTML
        while($row = mysqli_fetch_array($result)) {
            $output .= '<li>' . $row["name"] . '</li>';
        }
    } else {
        // If no rows are found, display "Item not found"
        $output .= '<li>Item not found</li>';
    }

    // Close the unordered list
    $output .= '</ul>';

    // Output the final HTML
    echo $output;
    
    // Store the query in a file
    if (!empty($searchQuery)) {
        // File path to store the query
        $file = 'js_query.txt';

        // Open the file in write mode
        $fp = fopen($file, 'w');

        // Write the query to the file
        fwrite($fp, $searchQuery);

        // Close the file
        fclose($fp);
    }
    exit; // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamro Mart</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/user_cart.css">
    <link rel="stylesheet" href="./css/user_order.css">
    <link rel="stylesheet" href="./css/user_pay.css">
    <link rel="icon" type="image/x-icon" sizes="180x180" href="./assets/icons/logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <script>
        // Function to handle search
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
        });
    </script> -->
</head>

<body>
    <!--header section-->
    <section id="header">
        <div class="nav">
            <div class="logo">
                <img src="./assets/icons/logo.png" alt="" style="width:80px">
            </div>

            <div class="links">
                <ul>
                    <li><a href="index.php"><img src="./assets/icons/home.png" alt=""></a></li>
                    <li><a href="user_order.php"><img src="./assets/icons/order.png" alt=""></a></li>

                </ul>
            </div>
            <!-- <div class="Searchbox">
                <input type="text" id="search" placeholder="Enter product name...">
                <input type="button" id="search_box" value="Search">
                <div id="search-results"></div> Dropdown container
            </div> -->
            <div class="profile">
                <?php 
                    $user_id=$_SESSION['user_id'];
                     $select_cart_number = mysqli_query($conn,"SELECT * FROM `cart` WHERE user_id= '$user_id'") or die('query failed!');
                     $cart_row=mysqli_num_rows($select_cart_number);

                ?>
                <a href="user_cart.php"><img id="cart-image" src="./assets/icons/cat.png" alt=""><span style="color:black">[<?php echo $cart_row; ?>]</span></a>

                <img id="profile" src="./assets/icons/man.png" alt="">
            </div>
            <div class="profile-details" id="display">
                <p>Name: <?php echo $_SESSION['user_name']?></p>
                <p>E-mail: <?php echo $_SESSION['user_email']?></p>
                
                <a href="logout.php"><button><img src="./assets/icons/check-out.png"
                            alt=""> Logout</button></a>
            </div>
        </div>
    </section>

</body>

</html>
