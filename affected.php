<?php include('./libraries.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Maharashra Flood Relief</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="affected.css" />
  <script type="text/javascript" src="affected.js"></script>
</head>
<body>
  <?php include('./navbar.php') ?>
  <div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header card-header-color text-white text-center">
                    <a>Register Affected People</a>
                </div> 
                <div class="card-body">
                    <form action="affected.php" method="post">
                        <div class="form-group">
                            <label for="fullname">Full Name :</label>
                            <input type="text" class="form-control" id="fullname" name="fullname">
                        </div>
                        <div class="form-group">
                            <label for="mobilenumber">Mobile Number :</label>
                            <input type="text" class="form-control" id="mobilenumber" name="mobilenumber">
                        </div>
                        <div class="form-group">
                            <label for="city">Village/City :</label>
                            <input type="text" class="form-control" id="village" name="village">
                        </div>
                        <div class="form-group">
                            <label for="taluka">Taluka :</label>
                            <input type="text" class="form-control" id="taluka" name="taluka">
                        </div>
                        <div class="form-group">
                            <label for="district">District :</label>
                            <input type="text" class="form-control" id="district" name="district">
                        </div>
                        <button type="submit" class="btn btn-success form-control mt-3" id="register" name="register">Register</button>
                    </form>
                </div>   
                <div class="card-footer text-center">
                    Charity brings to life again those who are spiritually dead.
                </div>  
            </div>
        </div>
    </div>
  </div>
</body>
</html>

<?php
    if(isset($_REQUEST['register']))
    {
        require_once('connection.php');
        $con = mysqli_connect($host,$name,$pass,$db);
        if($con != null) {
            $fullName = $_REQUEST['fullname'];
            $mobileNumber = $_REQUEST['mobilenumber'];
            $village = $_REQUEST['village'];
            $taluka = $_REQUEST['taluka'];
            $district = $_REQUEST['district'];
          
            $sql = "insert into person(full_name, mobile_no) values('$fullName','$mobileNumber')";
            $result = mysqli_query($con, $sql);
            echo $result;
          
            $sql = "select Max(person_id) as max from person";
            $rows = mysqli_query($con, $sql);
            $lastInsertIndex = -1;
            
            foreach($rows as $row) {
                $lastInsertIndex = $row['max'];
            }

            echo $lastInsertIndex;
            echo $taluka;
            echo $district;
            echo $village;
            if($lastInsertIndex != -1) {
                $sql = "insert into address(person_id, village, taluka, district) values('$lastInsertIndex','$village', '$taluka', '$district')";
                $result = mysqli_query($con, $sql);

                echo $result;
            }
        //   if($result > 0)
            // echo "<script>alert('Booking request has been sent successfully! you will be notified soon on your email ...'); window.location='dashboard.php'</script>";   
        }
    }
?>