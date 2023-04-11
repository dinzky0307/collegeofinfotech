<?php 
    include('../config.php'); 

    include '../DatabaseService.php';

    use Database\DatabaseService;

    $dbService = new DatabaseService();

    $users = $dbService->fetch("SELECT* from userdata WHERE level = 'admin' OR level = 'teacher'");

    $level = isset($_SESSION['level']) ? $_SESSION['level']: null;

    if($level == null){
        header('location:../index.php');
    }else if($level != 'student'){
        header('location:../'.$level.'');
    }
    if (isset($_GET['submit'])) {
        header('Content-Type: application/json; charset=utf-8');

        $dbService->inserToConsultation($_POST);

        echo json_encode([
            'success' => true
        ]);

        return;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../img/mcc.png">

    <title>InfoTech</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/font-awesome.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="mystyle.css" />

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <script>
	  		$( function() {
	   			$( "#date" ).datepicker({
	   				minDate: 0
	   			});
	  		});
	  	</script>

  </head>
    <style>
        @media print{
                .print-container {
                    position: center;
                    margin-top: 0px;
                }
                @media print 
                {
                    .print-container{
                        width: 290mm;
                        font-size: 15px;
                        margin-left: -90px;
                    }
                }
                @page { 
                    size: auto;
                    margin:10px;
                }

            }
            .center {
  margin-left: auto;
  margin-right: auto;
}
th, td {
    text-align: left;
    border: 1px solid black;
    border-collapse: collapse;
    width: 25%;
}
table{
    border: 1px solid black;
    border-collapse: collapse;
    width: 25%;
}
input {
    background-color: transparent;
    border: 0px solid;
    width: 70%;
    border-left: none; 
    border-right: none; 
    border-top: none; 
    border-bottom: none;
}
select {
    background-color: transparent;
    border: 0px solid;
    width: 70%;
    border-left: none; 
    border-right: none; 
    border-top: none; 
    border-bottom: none;
}
textarea:focus, input:focus{
    outline: none;
}
input[type=checkbox] {
    margin-right: 5px;
    margin-left: 5px;
  cursor: pointer;
  width: 15px;
  height: 15px;
}
input[type=radio] {
    margin-right: 5px;
    margin-left: 5px;
  cursor: pointer;
  width: 15px;
  height: 15px;
}

    </style>
  <body>
    

   <div x-data="ConsultationForm">
   <form class="container" method="POST" action="" style="margin-top:60px;">
        <!-- Example row of columns -->
        <div class="row print-container">
        <table class="center" style="width: 80%; border: 3px solid;">
        <tr style="line-height: 100px;">
            <th colspan="3" style="text-align: center;">
                <h1 style="font-size: 40px; padding-top: 15px; padding-bottom: 20px;  font-family: Verdana, Geneva, Tahoma, sans-serif;">BSIT CONSULTATION FORM</h1>
            </th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="2"></th>
            <th>Select Consultant : 
            <select name="section" placeholder="Section" required x-model="form.consultant_id"  style=" width: 200px; ">
                <option value="">Select to Consult</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"> <?php echo $user['fname'] . ' ' . $user['lname']; ?></option>
                <?php endforeach; ?>
            </select>
            </th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th> Who requested this consultation?</th>
            <th> Type of consultation:</th>
            <th> Thru:</th>
        </tr>
        <tr style="line-height: 30px;">
            <th><input type="radio" id="requested_by" x-model="form.requested_by" name="requested_by" value="Student">Student</th>
            <th><input type="radio" id="form.type" name="form.type" x-model="form.type" value="Scheduled">Scheduled</th>
            <th><input type="radio" id="form.thru" x-model="form.thru" name="form.thru" value="Online">Online</th>
        </tr>
        <tr style="line-height: 30px;">
            <th><input type="radio" id="requested_by" name="requested_by"  x-model="form.requested_by"value="Parents/Guardian">Parents/Guardian</th>
            <th><input type="radio" id="form.type" name="form.type" x-model="form.type" value="Urgent">Urgent</th>
            <th><input type="radio" id="form.thru" name="form.thru" value="F2F">F2F</th>
        </tr>
        <tr style="line-height: 30px;">
            <th><input type="radio" id="requested_by" x-model="form.requested_by" name="requested_by" value="Colleague">Colleague</th>
            <th colspan="3"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="2"></th>
<!--             <th>Date of Consultation : <input type="text" style="line-height: 28px; width: 50px;"></th> -->
            <th>Date of Consultation : <input type="text" style="line-height: 28px; width: 50px;" name="date" id="date" x-model="form.date" title="Choose your desired date"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3">Consultation Requested by: <input type="text" style="line-height: 28px;" class="input "  id="fname" name="fname" x-model="form.requested_by_name"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3">Other Persons in Attendance:<input type="text" style="line-height: 28px; " class="input long" id="lname" name="lname" x-model="form.person_attendance"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3">In Compliance with the Consultation Policy, this meetings is numbered:<input type="text" style="line-height: 28px; width: 30%;" class="input" id="lname" name="lname" x-model="form.meeting_number" readonly></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"></th>
        </tr>
        <tr style="line-height: 20px;">
            <th colspan="3" style="text-align: center;">
                <h3>AREAS OF CONCERN</h3>
            </th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"><input type="checkbox" id="form.areas_concern" name="form.areas_concern" value="Concerns about academic performance" x-model="form.areas_concern">Concerns about academic performance</th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"><input type="checkbox" id="vehicle2" name="form.areas_concern" value="Concerns about behavior" x-model="form.areas_concern">Concerns about behavior</th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"><input type="checkbox" id="form.areas_concern" name="form.areas_concern" value="Concerns about recent social changes such as family and or peer relationships" x-model="form.areas_concern">Concerns about recent social changes such as family and or peer relationships</th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3">Other Concers, Please specify:<input type="text" style="line-height: 28px; " class="input long" id="lname" name="lname" x-model="form.other"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"></th>
        </tr>
        <tr style="line-height: 20px;">
            <th colspan="3" style="text-align: center;">
                <h3>NOTES ON ACTIONS TAKEN/ADVICES/RECOMMENDATIONS</h3>
                <input type="text" style="line-height: 50px; width: 99%; text-align: center;" class="input long" id="lname" name="lname" x-model="form.recommendation" readonly>
            </th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="3"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="2.5">Consultation requested by:<input type="text" style="line-height: 28px; width: 70%; " class="input" id="lname" name="lname" x-model="form.requested_by_name"></th>
            <th>Signature: <input type="text" style="line-height: 28px; width: 70%; border-left: none; " class="input " id="fname" name="fname"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="2">Any Person in Attendance:<input type="text" style="line-height: 28px;  width: 70%;" class="input" id="lname" name="lname" x-model="form.person_attendance"></th>
            <th>Signature: <input type="text" style="line-height: 28px; width: 70%;" class="input" id="lname" name="lname"></th>
        </tr>
        <tr style="line-height: 30px;">
            <th colspan="2">Conducted by: <input type="text" style="line-height: 28px; width: 70%; " class="input" id="lname" name="lname" x-model="form.conducted_by"></th>
            <th>Signature: <input type="text" style="line-height: 28px; width: 70%; " class="input" id="lname" name="lname"></th>
                </tr>
        <tr style="line-height: 100px;">
            <th colspan="3" style="text-align: center; padding-top: 20px; text-align: right; padding-right: 2%;" class="hidden-print">
            <button onclick="window.print();" type="button" class="btn btn-primary">
                Print</button>
            <button type="button" class="btn btn-danger" name="cancel"><a href="index.php" style="text-decoration:none; color: white;">Back</a></button>
                <button type="buton" class="btn btn-success" name="consultation-form" @click="submit">Submit</button>
            </th>
        </tr>
      </table>
        <br/>
        <br/>
        <!-- <div style="padding-top: 2px; text-align: right; padding-right: 9%;" class="hidden-print">
            <button type="button" class="btn btn-danger" name="cancel"><a href="index.php" style="text-decoration:none; color: white;">Cancel</a></button>
            <button type="buton" class="btn btn-success" name="consultation-form" @click="submit">Submit</button>
        </div> -->
        <br/>
        <br/>
        <br/>
        <br/>
    
    </form>
   </div>

<div class="AlertModal">
  <div class="AlertModalContent">
    <h1 class="AlertModalTitle"></h1>
    <p  class="AlertModalBody"></p>
  </div>
  <div onclick="hidemodal()" class="hide fas fa-times">X</div>
</div>






</form>
    <script>
        window.ConsultationForm = () => {
            return {
                form: {
                    requested_by: null,
                    type: null,
                    thru: null,
                    date: null,
                    requested_by_name: null,
                    person_attendance: null,
                    meeting_number: null,
                    areas_concern: [],
                    recommendation: null,
                    other: '',
                    consultant_id: null,
                    conducted_by: '',
                    student_id: <?php echo $_SESSION['user_id']; ?>
                },
                async postData(url, data) {
                    const response = await fetch(url, {
                            method: 'POST', 
                            cache: 'no-cache',
                            headers: {
                            'Accept': 'application/json, text/plain, */*',
                            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
                        },
                        redirect: 'follow',
                        referrerPolicy: 'no-referrer',
                        body: data
                    })

                    return response.json()
                },
                submit() {
                    this.form.areas_concern.push(this.form.other)
                    this.postData(`${window.location}?submit=true`, new URLSearchParams(this.form).toString())
                        .then((data) => {
                            Swal.fire({
                                title: 'Consultation',
                                text: 'Submitted successfully!',
                                position: 'center',
                            }).then(() =>window.location.href = 'http://localhost/InfoTech/student/' )

                            setTimeout(() => {
                                window.location.href = 'http://localhost/InfoTech/student/'
                            }, 3000)
                        })
                },
            }
        }
    </script>
  </body>
</html>
