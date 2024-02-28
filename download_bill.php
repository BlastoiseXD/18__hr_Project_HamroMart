<?php
// Include database connection and header file
include 'config.php';

// Execute SQL query to fetch bill details
date_default_timezone_set('Asia/Kathmandu');
$bill_query = mysqli_query($conn, "SELECT * FROM bill");
$billDirectory = "bill";
if (!is_dir($billDirectory)) {
    mkdir($billDirectory);
}

// Check if the query was successful
if ($bill_query) {
    // Get the current date and time
    $currentDateTime = date('Y-m-d_H-i-s');
    
    // Create a new text file with the current date and time in the file name
    $file = fopen("$billDirectory/bill_$currentDateTime.txt", "w");

    // Check if the file was successfully created
    if ($file) {
        // Write bill details to the file
        fwrite($file, "Bill_$currentDateTime\n\n");
        
        // Initialize grand total
        $grandTotal = 0;
        
        // Iterate through each bill item
        while ($row = mysqli_fetch_assoc($bill_query)) {
            // Calculate total for each item
            $total = $row['price'] * $row['quantity'];
            
            // Add the total to the grand total
            $grandTotal += $total;

            // Write item details to the file
            fwrite($file, "Name: " . $row['name'] . "\n");
            fwrite($file, "Quantity: " . $row['quantity'] . "\n");
            fwrite($file, "Price: " . $row['price'] . "\n");
            fwrite($file, "Total: " . $total . "\n\n");
        }

        // Write the grand total to the file
        fwrite($file, "Grand Total: " . $grandTotal . "\n");

        // Close the file
        fclose($file);

        // Inform the user that the file has been created
        echo "Bill details have been saved to bill_$currentDateTime.txt";
    } else {
        // Handle the case where the file creation failed
        echo "Error: Unable to create file";
    }
} else {
    // Handle the case where the bill query failed
    echo "Error: " . mysqli_error($conn);
}

// Include footer file
?>
