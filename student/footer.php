</div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/myscript.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../js/plugins/morris/raphael.min.js"></script>
    <script src="../js/plugins/morris/morris.min.js"></script>
    <script src="../js/plugins/morris/morris-data.js"></script>

   


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://jacoblett.github.io/bootstrap4-latest/bootstrap-4-latest.min.js" crossorigin="anonymous"></script>



<script type="text/javascript">
$("#menu-toggle").click(function(e) {
  e.preventDefault();
  $("#wrapper").toggleClass("toggled");
});

</script>



<!-- //nav tab -->
<script type="text/javascript">
$(".content").hide();
$(function() {
  $(".but").on("click",function(e) {
    e.preventDefault();
    $(".content").hide();
    $("#"+this.id+"div").show();
  });
});
</script>


<!-- get info by button -->
<script type="text/javascript">
$('.btninfo').click(function () {

    var id = $(this).attr('id');
    var photo = $('#row-'+id+' '+'#photo').val();
    var full_name = $('#row-'+id+' '+'#get_name').html();
    var contact = $('#row-'+id+' '+'#contact').val();
    var company = $('#row-'+id+' '+'#company').val();
    var email = $('#row-'+id+' '+'#get_email').html();
    var req_1 = $('#row-'+id+' '+'#req_1').val();
    var req_2 = $('#row-'+id+' '+'#req_2').val();
    var req_3 = $('#row-'+id+' '+'#req_3').val();
    var req_4 = $('#row-'+id+' '+'#req_4').val();
    var req_5 = $('#row-'+id+' '+'#req_5').val();
    var req_6 = $('#row-'+id+' '+'#req_6').val();

    $('.modalName').html(full_name);
    $('.modalPhoto').attr("src","images/"+photo);
    $('.modalContact').html("Contact: "+contact);
    $('.modalEmail').html("Company: "+company);

    $('.req_1').attr("href", req_1);
    $('.req_2').attr("href", req_2);
    $('.req_3').attr("href", req_3);
    $('.req_4').attr("href", req_4);
    $('.req_5').attr("href", req_5);
    $('.req_6').attr("href", req_6);

    $('.pmessage').attr("href","message-view.php?id="+id);
})
</script>




<script type="text/javascript">
$('.btninfotask').click(function () {

    var id = $(this).attr('id');
    var gettasktitle = $('#row-'+id+' '+'#gettasktitle').val();
    var internsfile = $('#row-'+id+' '+'#internsfile').val();
    var comment = $('#row-'+id+' '+'#comment').val();
    var full_name = $('#row-'+id+' '+'#get_name').html();

    $('.modalIName').html(full_name);
    $('.modaltaskTitle').html(gettasktitle);
    $('.modalFile').attr("href", internsfile);
    $('.modalFile').html(internsfile);
    $('.modalComment').html("Comment: "+comment);
})
</script>



<script type="text/javascript">
  //counting time
$(document).ready(function() {
  clockUpdate();
  setInterval(clockUpdate, 1000);
})

function clockUpdate() {
  var date = new Date();
  function addZero(x) {
    if (x < 10) {
      return x = '0' + x;
    } else {
      return x;
    }
  }

  function twelveHour(x) {
    if (x > 12) {
      return x = x - 12;
    } else if (x == 0) {
      return x = 12;
    } else {
      return x;
    }
  }

  var h = addZero(twelveHour(date.getHours()));
  var m = addZero(date.getMinutes());
  var s = addZero(date.getSeconds());

  $('.digital-clock').text(h + ':' + m + ':' + s)
}
</script>





<!-- time in -->
<script>


function showmodal() {
    $('.AlertModal').css('bottom', '20px');
}
function hidemodal() {
    $('.AlertModal').css('bottom', '-300px');
}



$(function(){
$(document).on('click','.timein_now',function(){


var id = '<?php echo $login_session; ?>';
var task = '<?php echo $tasks; ?>';
var time_in = '<?php echo date("H:i"); ?>';
var date = '<?php echo date("Y-m-d"); ?>';


  $.ajax({
    url: "time-in.php",
    type: "POST",
    data: {
      id: id,
      task: task,
      time_in: time_in,
      date: date,
    },
    dataType: 'json',
    success: function(data) {
      // alert("SuccessFully Time In");
      showmodal();
      // $('.icon-box').html(data.modalIcon);
      $('.AlertModalTitle').html(data.modalTitle);
      $('.AlertModalBody').html(data.modalBody);
      $('.timein_now').html(data.timein);
      $('.timein_now').attr("disabled", "disabled");
        }
    });

});
});

</script>


<script type="text/javascript">
  $(function(){
$(document).on('click','.timeout_now',function(){

var id = '<?php echo $login_session; ?>';
var task = '<?php echo $tasks; ?>';
var time_out = '<?php echo date("H:i"); ?>';
var date = '<?php echo date("Y-m-d"); ?>';

  $.ajax({
    url: "time-out.php",
    type: "POST",
    data: {
      id: id,
      task: task,
      time_out: time_out,
      date: date,

    },
    dataType: 'json',
    success: function(data) {
        }
    });

});
});
</script>


<!-- messages box -->
<script type="text/javascript">

$(document).on('click','.send-chat',function(){

  var message = $('#chat-textarea').val();
  var id = '<?php echo $login_session; ?>';
  $('#responsecontainer').append('<div class="alert alert-success" role="alert">'+message+'</div>');

$.ajax({
    url: "message.php",
    type: "POST",
    data: {
      id: id,
      message: message,
    },
    dataType: 'json',
    success: function(data) {
      // alert("SuccessFully Time In");
      showmodal();
      // $('.icon-box').html(data.modalIcon);
      $('.AlertModalTitle').html(data.modalTitle);
      $('.AlertModalBody').html(data.modalBody);
        }
    });


});


</script>


<!-- messages to all -->
<script type="text/javascript">

$(document).on('click','.send-chat-all',function(){

  var message = $('#chat-all').val();

$.ajax({
    url: "message-all.php",
    type: "POST",
    data: {
      message: message,
    },
    dataType: 'json',
    success: function(data) {
      
        }
    });

alert('Successfully Send to All Students');
window.location.reload();

});


</script>


<!-- send chat message view -->
<script type="text/javascript">

$('#responsecontainer').scroll();
$("#responsecontainer").animate({
  scrollTop: 2000
});

$(document).on('click','.send-chat-admin',function(){

var message = $('#chat-textarea').val();

  var id = '<?php echo $_GET['id']; ?>';
  $('#responsecontainer').append('<div class="alert alert-danger" role="alert">'+message+'</div>');

$.ajax({
    url: "message-admin.php",
    type: "POST",
    data: {
      id: id,
      message: message,
    },
    dataType: 'json',
    success: function(data) {
      // alert("SuccessFully Time In");
      showmodal();
      // $('.icon-box').html(data.modalIcon);
      $('.AlertModalTitle').html(data.modalTitle);
      $('.AlertModalBody').html(data.modalBody);

        }
    });
$('#responsecontainer').scroll();
$("#responsecontainer").animate({
  scrollTop: 2000
});


});


</script>



<script>
$(function(){
$(document).on('click','.approve',function(){

var id = $(this).attr('id');

  $.ajax({
    url: "approve.php",
    type: "POST",
    data: {
      id: id,     
    },
    success: function(data) {
         alert("Successfully Approved");
         window.location.reload();
        }
    });

});
});
</script>



<!-- print -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js" integrity="sha512-t3XNbzH2GEXeT9juLjifw/5ejswnjWWMMDxsdCg4+MmvrM+MwqGhxlWeFJ53xN/SBHPDnW0gXYvBx/afZZfGMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
$(function(){
$(document).on('click','.print',function(){

$(".printhere").print();

});
});
</script>






<script type="text/javascript">
  //send button animation
  var sendButton = function() {
  var button = $('.sendButton');
  button.on('click', function() {
    $(this).hide().html('Sending <span class="loading"></span>').fadeIn('fast');
    setTimeout( function() {
      button.hide().html('Message sent &#10003;').fadeIn('fast');
    }, 1000);
  });
};

sendButton();

</script>







<script>

//change pic
  document.querySelector('input[type="file"]').addEventListener('change', function() {
      if (this.files && this.files[0]) {
          var img = document.querySelector('#myImg');
          img.onload = () => {
              URL.revokeObjectURL(img.src);  // no longer needed, free memory
          }

          img.src = URL.createObjectURL(this.files[0]); // set src to blob url
      }
  });

</script>

</body>
</html>


<?php ob_end_flush(); ?>

</body>

</html>