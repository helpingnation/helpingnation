$(document).ready(function() {
    let table = $('#table_id').DataTable();
    if(window.screen.width < 600) {
        table.columns([2, 3 ,4]).visible(false);
        table.columns(5).visible(true);
    }
    else {
        table.columns([2, 3 ,4]).visible(true);
        table.columns(5).visible(false);
    }
});


var jQueryScript = document.createElement('script');  
jQueryScript.setAttribute('src','https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
document.head.appendChild(jQueryScript);

function openModal(person) {
    window.person = person;
    $('#modal_fullname').html(person.full_name);
    $('#modal_address').val(person.village + ", " + person.taluka + ", " + person.district);
    $('#modal_mobile_number').val(person.mobile_no);
}

function generateOTP()
{
    $('#generate_otp').addClass('d-none');
    $('#modal_top_div').removeClass("d-none");
    $('#modal_verify_otp').removeClass("d-none");
    $('#modal_amount').attr('disabled', true);
    $('#modal_aadhar_no').attr('disabled', true);
    $('#modal_mobile_number_div').addClass('d-none');
    
    $.ajax({
        type: "POST",
        url: "otp_generation.php",
        data: {
            "person_id": window.person.person_id,
            "mobile_number": $('#modal_mobile_number').val()
        },
        success:function(response) {
            console.log('otp sent to mobile');
        }
    }); 
}

function verify() {
    var enter_otp = document.getElementById('modal_otp').value;
      if(enter_otp.length!=6) {
        document.getElementById('valid_msg').style.display="block";
      }
      else {
        document.getElementById('valid_msg').style.display="none";
        $.ajax({
            type: "POST",
            url: "otp_verification.php",
            data: {
              "person_id": window.person.person_id,
            },
            success:function(response){
              var json_obj = $.parseJSON(response);
              if(json_obj.otp == enter_otp) {
                document.getElementById('modal_verify_otp').disabled=true;
                document.getElementById('modal_otp').readOnly = true;
                $('#modal_amount').attr('disabled', false);
                // document.getElementById('valid_msg').innerHTML = "OTP verification done successfully";
                // document.getElementById('valid_msg').style.color = 'green';
                // document.getElementById('valid_msg').style.display = "block";
                $('#benificiary_div').addClass('d-none');
                $('#contributor_div').removeClass('d-none');
              }
              else {
                document.getElementById('valid_msg').style.display="block";
              }
            }
          });  
      }
}
