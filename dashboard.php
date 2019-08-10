<?php 
  include('./libraries.php');
  include('./jquery-datatable.php');
?>
<!DOCTYPE html>
<html>
<head>
  <title>Maharashra Flood Relief</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script type="text/javascript" src="dashboard.js"></script>
  <link rel="stylesheet" type="text/css" href="dashboard.css"></link>
</head>
<body>
  <?php include('./navbar.php') ?>
  <div class="container-fluid">
    <div class="row mt-5 mb-5">
      <div class="col-md-11 mx-auto">
      <div class="table-responsive">
        <table id="table_id" class="table table-striped table-bordered text-center">
            <thead class="bg-secondary text-white">
              <tr>
                <th>Full Name</th>
                <th>Mobile Number</th>
                <th>Village/City</th>
                <th>Taluka</th>
                <th>District</th>
                <th>Address</th>
                <th>Amount Received</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
                require_once('connection.php');
                $con = mysqli_connect($host,$name,$pass,$db);
                if($con != null) {
                  $sql = 'SELECT person.person_id, person.full_name, person.mobile_no,person.amount_received, address.village,address.taluka, address.district '.
                  'FROM person '.
                  'INNER JOIN address ON person.person_id = address.person_id';
                  $rows = mysqli_query($con, $sql);
                  foreach($rows as $row) {
                    ?>
                      <tr>
                        <td><?php echo $row['full_name'] ?></td>
                        <td><?php echo $row['mobile_no'] ?></td>
                        <td><?php echo $row['village'] ?></td>
                        <td><?php echo $row['taluka'] ?></td>
                        <td><?php echo $row['district'] ?></td>
                        <td><?php echo $row['village'].", ".$row['taluka'].", ".$row['district']  ?></td>
                        <td><?php echo $row['amount_received'] ?></td>
                        <td><button onclick="openModal(<?php echo str_replace('"', "'", json_encode($row)); ?>)" data-toggle="modal" data-target="#donateModal" class="btn btn-success text-white form-control">Donate</button></td>
                      </tr>
                    <?php
                  }
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="container">
    <div class="modal fade" id="donateModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5>Donate to <a id="modal_fullname"></a></h5>
            <i id="close" class="fa fa-window-close fa-2x close-modal-button" data-dismiss="modal"></i>
          </div>
          <div class="modal-body">
            <form action="dashboard.php" method="post">
              <input type="hidden" class="form-control" id="person_id" name="person_id">
              <div class="row">
                <div class="col" id="benificiary_div">
                  <p class="p-2 bg-success text-white text-center">Benificiary Details</p>
                  <div class="form-group">
                    <label for="`modal_address">Address :</label>
                    <input type="text" class="form-control" id="modal_address" name="modal_address" disabled>
                  </div>
                  <div class="form-group">
                    <label for="modal_amount">Enter Amount :</label>
                    <input type="text" class="form-control" id="modal_amount" name="modal_amount">
                  </div>
                  <div class="form-group">
                    <label for="`modal_aadhar_no">Enter Aadhar Number :</label>
                    <input type="text" class="form-control" id="modal_aadhar_no" name="modal_aadhar_no">
                  </div>
                  <div class="form-group" id="modal_mobile_number_div">
                    <label for="`modal_mobile_number">Mobile Number :</label>
                    <input type="text" class="form-control" id="modal_mobile_number" name="modal_mobile_number" disabled>
                  </div>
                  <button type="button" class="btn btn-success mb-2" id="generate_otp" name="generate_otp" onclick="generateOTP()">Generate OTP</button>
                  <div class="form-group d-none" id="modal_top_div">
                    <label for="`modal_otp">Enter OTP :</label>
                    <input type="number" class="form-control" id="modal_otp" name="modal_otp">
                    <small style="display: none; color: #d9534f;" id="valid_msg">Enter Valid OTP</small>
                  </div>
                  <button type="button" class="btn btn-success d-none" id="modal_verify_otp" name="modal_verify_otp" onclick="verify()">Verify</button>
                </div>
                <div id="contributor_div" class="col d-none">
                  <p class="p-2 bg-success text-center text-white">Contributor Detais</p>
                  <div class="form-group">
                    <label for="modal_contributor_name">Your Name :</label>
                    <input type="text" class="form-control" id="modal_contributor_name" name="modal_contributor_name" />
                  </div>
                  <div class="form-group">
                    <label for="modal_contributor_mobile_no">Your Mobile Number :</label>
                    <input type="text" class="form-control" id="modal_contributor_mobile_no" name="modal_contributor_mobile_no" />
                  </div>
                  <button type="submit" class="btn btn-success" id="modal_contribute_button" name="modal_contribute_button">Contribute</button>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>  
  </div>
</body>
</html>

<?php 
  if(isset($_REQUEST['modal_contribute_button'])) {
      require_once('connection.php');
      $con = mysqli_connect($host,$name,$pass,$db);
      if($con != null) {
          $contr_name = $_REQUEST['modal_contributor_name'];
          $mobile_no = $_REQUEST['modal_contributor_mobile_no'];
          $modal_amount = $_REQUEST['modal_amount'];
          $person_id = $_REQUEST['person_id'];

          $sql = "insert into contributor(contr_name, amount_contributed, mobile_no) values('$contr_name','$modal_amount', '$mobile_no')";
          $result = mysqli_query($con, $sql);

          $sql = "select Max(contributor_id) as max from contributor";
          $rows = mysqli_query($con, $sql);
          $lastInsertIndex = -1;
          
          foreach($rows as $row) {
              $lastInsertIndex = $row['max'];
          }

          $sql = "insert into contributions(contributor_id,person_id,amount_contributed) values('$lastInsertIndex','$person_id','$modal_amount') ";
          $result = mysqli_query($con, $sql);
          
          $sql = "select amount_received from person where person_id = '$person_id' ";
          $rows = mysqli_query($con, $sql);
          $curr_amount = 0;
          foreach($rows as $row){
            $curr_amount = $row['amount_received'];
          }
          $curr_amount += $modal_amount;

          $sql = "update person set amount_received = '$curr_amount' where person_id = '$person_id' ";
          $rows = mysqli_query($con, $sql);
          echo "<script>window.location='dashboard.php'</script>";
      }
  }
?>
