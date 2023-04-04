<?php

include '../database.php';


use Carbon\Carbon;

if(isset($_POST['receiver_id'])) {
    $sql = "INSERT INTO messages (sender_id, receiver_id, content)
    VALUES (?, ?, ?)";
  
    $connection->prepare($sql)->execute([
        $_SESSION['user_id'], 
        $_POST['receiver_id'], 
        $_POST['content']
    ]);

    echo "<script type='text/javascript'>";
    echo "Swal.fire({
       title: 'Message sent successfully',
       icon: 'success',
     })";
    echo "</script>";
}

function consultationMessages()   {
    $userId = $_SESSION['user_id'];

    $selects = 'messages.id, userdata.fname, userdata.lname, messages.content, messages.created_at, message_replies.sender_id, message_replies.content as reply';
    $joins = 'LEFT JOIN messages ON userdata.id = messages.receiver_id LEFT JOIN message_replies ON messages.id = message_replies.message_id';
    $orders = 'ORDER BY created_at DESC';

    $query = "SELECT {$selects} FROM userdata {$joins} where messages.sender_id = {$userId} {$orders}";
    $rows = mysql_query($query);

    return $rows; 
}

$messages = consultationMessages();

// Fetch administrators
$adminStmt = $connection->prepare("SELECT id, fname, lname FROM userdata WHERE level = 'admin'");
$adminStmt->execute();
$adminStmt->setFetchMode(PDO::FETCH_ASSOC);
$administrators = $adminStmt->fetchAll();

?>

<div class="row" x-data="Consultation()">
    <div class="row">
             <?php while($message = mysql_fetch_array($messages)): ?>
                <?php
                    $sentAt = Carbon::parse($message['created_at'])->format('F d, Y h:i A');
                ?>
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <strong> To: <?php echo $message['fname'].' '.$message['lname']?> </strong>
                            </h5>
                            <p class="card-text"><?php echo $message['content'] ?></p>
                            <?php if ($message['sender_id']): ?>
                                <div class="alert alert-success" role="alert">
                                    <h5 class="alert-heading "><strong>Replied: </strong></h5>
                                    <p><?php echo $message['reply']; ?> </p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6 text-right">
                                    <small class="text-muted"> Sent: <?php echo $sentAt; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="background-color: #e2e8f0; width: 100%; height: 1px; margin-top: 10px; margin-bottom: 10px;"></div>
                </div>
            <?php endwhile; ?>
        </div>
        <button type="button" class="btn btn-primary" @click="compose()">Add Consulation</button>
        <form style="display: none" action="" method="post" x-ref="consultation-form">
            <textarea name="content" cols="30" rows="10" x-ref="consultation-message"></textarea>
            <input type="hidden" x-ref="receiver-id" name="receiver_id" />
        </form>
    </div>
</div>

<script>
const options = <?php echo json_encode($administrators); ?>;
window.Consultation = () => {
    return {
        async compose() {
            const inputOptions = {}

            options.forEach((option, key) => {
                inputOptions[option.id] = `${option.fname} ${option.lname}`
            })

            const { value: teacher } = await Swal.fire({
                inputLabel: 'Select Instructor',
                input: 'select',
                inputOptions,
                inputPlaceholder: 'Select a Instructor',
                showConfirmButton: true,
                confirmButtonText: 'Next',
                showCancelButton: true
            })
            
            if (!teacher) {
                return
            }

            const { value: consultation } = await Swal.fire({
                input: 'textarea',
                inputLabel: `Consultation to ${inputOptions[teacher]}`,
                inputPlaceholder: 'Type your consultation message...',
                inputAttributes: {
                    'aria-label': 'Type your consultation message...'
                },
                showConfirmButton: true,
                confirmButtonText: 'Send',
                showCancelButton: true
            })

            if (!consultation) {
                return
            }

            const form = this.$refs['consultation-form']
            const content = this.$refs['consultation-message']
            const receiver = this.$refs['receiver-id']

            content.value = consultation
            receiver.value = teacher

            form.submit()
        }
    }
}
</script>