<?php
    if(!empty($_POST))
    {
        require_once('connection.php');
        $person_id = $_POST['person_id'];
        $con = mysqli_connect($host,$name,$pass,$db);
        $sql = "select * from otp where person_id='$person_id'";
        $rows = mysqli_query($con, $sql);

        foreach($rows as $row) {
            echo json_encode(array("otp" => $row['otp_pin']));
        }
    }
?>
