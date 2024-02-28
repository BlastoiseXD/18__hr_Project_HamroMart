<?php
$connect = mysql_connect("localhost","root","","HamroMart");
if(isset($_POST["query"]))
$output = '';
$query = "SELECT * FROM  products WHERE name LIKE '%".$_POST["query"]."%'";
$result = mysqli_query($connect,$query);
$output = '<ul class  = "list-unstyled">';
if(mysqli_num_rows($result)>0)
{
    while($row = mysqli_fetch_array($result))
    {
        $output.= '<li>'.$row["ItemList"].'</li>';
    }
}
else{
    $output .='<li>Item not found </li>';

}
$output .='</ul>';
echo $output;
?>