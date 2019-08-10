<?php
	if(!empty($_POST)) {
        $mobile_number = $_POST['mobile_number'];
        $person_id =  $_POST['person_id'];
        require('./textlocal/textlocal.class.php');
        $textlocal = new Textlocal('thehelpingnation@gmail.com', '526415ec476f4387d7b762ade6123fbe87aeb6c79f924279520c6d75697e8082', 'eczipm8kHOE-NI8YVzJx50wST6Mx6DZxrc9Za7k5iN');

        $numbers = array($mobile_number);
        $sender = 'TXTLCL';
        $otp = rand(111111, 999999);
        $message = 'Your OTP is '.$otp;

        require_once('connection.php');
        $con = mysqli_connect($host,$name,$pass,$db);
        if($con != null) {
            $sql = "select person_id from otp where person_id='$person_id'";
            $rows = mysqli_query($con, $sql);
            if(mysqli_num_rows($rows) == 0) {
                $sql = "insert into otp(person_id, otp_pin, mobile_no) values('$person_id','$otp','$mobile_number')";    
            }
            else {
                $sql = "update otp set otp_pin='$otp', mobile_no='$mobile_number' where person_id='$person_id'";
            }
            $result = mysqli_query($con, $sql);

            try {
                // $result = $textlocal->sendSms($numbers, $message, $sender);
                // print_r($result);
            } 
            catch (Exception $e) {
                die('Error: ' . $e->getMessage());
            }
        }
    }
?>