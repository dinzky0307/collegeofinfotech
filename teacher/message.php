<?php
    include('include/header.php');
    include('include/sidebar.php');
    include('data/messages_model.php');
    include('post.php');
    include('../Carbon.php');

    use Carbon\Carbon;
    
    $search = isset($_POST['search']) ? $_POST['search']: null;
    $messages = getMessages();
    $posts = getPosts();

?>
<div id="page-wrapper" x-data="Messages()">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <div class="row page-header">
                    <div class="col-lg-6">
                        <h1>
                            Consultation
                        </h1>
                    </div>
                    <div class="col-lg-6 text-right">
                        <button style="height: 30px;" type="button" class="btn btn-primary" @click="post()"><i class="fa-solid fa-message"></i>   Post</button>
                        <form style="display: none " action="" method="post" x-ref="post-form">
                            <textarea name="content" cols="30" rows="10" x-ref="post-content"></textarea>
                            <input type="hidden" value="<?php echo $_SESSION['user_id']; ?>" name="posted" />
                        </form>
                    </div>
                </div>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i> <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        Consultation
                    </li>
                </ol>
            </div>
        </div>

        <hr />
        <div class="row">
             <?php while($message = mysql_fetch_array($messages)): ?>
                <?php
                    $sentAt = Carbon::parse($message['created_at'])->format('F d, Y h:i A');
                ?>
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <strong> <?php echo $message['lname'].', '.$message['fname']?> </strong>
                            </h5>
                            <p class="card-text"><?php echo $message['content'] ?></p>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button 
                                            type="button" 
                                            class="btn btn-<?php echo $message['sender_id'] ? 'secondary' : 'success'; ?>" 
                                            @click="replyMessage('<?php echo  $message['id'] ?>', '<?php echo "{$message['fname']} {$message['lname']}" ?>')"
                                            <?php echo $message['sender_id'] ? 'disabled' : ''; ?>
                                        > 
                                            <?php echo $message['sender_id'] ? 'Replied' : 'Reply'; ?>
                                        <form style="display: none" action="" method="post" x-ref="<?php echo  'message-reply-form-' . $message['id'] ?>">
                                            <input type="hidden" value="<?php echo $message['id']; ?>" name="message_id" />
                                            <textarea name="content" cols="30" rows="10" x-ref="<?php echo  'message-reply-content-' . $message['id'] ?>"></textarea>
                                            <input type="hidden" value="<?php echo $message['id']; ?>" name="replyMessage" />
                                        </form>
                                        </button>
                                        
                                        <button type="button" class="btn btn-outline-danger" @click="deleteMessage('<?php echo  'message-' . $message['id'] ?>')">
                                            Delete
                                            <form style="display: none" action="" method="post" x-ref="<?php echo  'message-' . $message['id'] ?>">
                                                <input type="hidden" value="<?php echo $message['id']; ?>" name="message_id" />
                                                <input type="hidden" value="<?php echo $message['id']; ?>" name="deleteMessage" />
                                            </form>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <small class="text-muted"> <?php echo $sentAt; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="row">
        <div class="col-lg-6">
                        <h1>
                            Posts
                        </h1>
                    </div>
             <?php while($post = mysql_fetch_array($posts)): ?>
                <?php
                    $sentAt = Carbon::parse($post['created_at'])->format('F d, Y h:i A');
                ?>
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <strong> <?php echo $post['lname'].', '.$post['fname']?> </strong>
                            </h5>
                            <p class="card-text"><?php echo $post['content'] ?></p>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                       
                                        <button type="button" class="btn btn-outline-danger" @click="deletePost('<?php echo  'post-' . $post['id'] ?>')">
                                            Delete
                                            <form style="display: none" action="" method="post" x-ref="<?php echo  'post-' . $post['id'] ?>">
                                                <input type="hidden" value="<?php echo $post['id']; ?>" name="post_id" />
                                                <input type="hidden" value="<?php echo $post['id']; ?>" name="deletePost" />
                                            </form>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <small class="text-muted"> <?php echo $sentAt; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <!-- /.container-fluid -->

</div>

<script>
window.Messages = () => {
    return {
        async post() {
            const { value: text } = await Swal.fire({
                input: 'textarea',
                inputLabel: `Post message`,
                inputPlaceholder: 'Type your post message',
                inputAttributes: {
                    'aria-label': 'Type your post messag...'
                },
                showConfirmButton: true,
                confirmButtonText: 'Post',
                showCancelButton: true
            })

            if (text) {
                const form = this.$refs['post-form']
                const content = this.$refs['post-content']
                content.value = text

                form.submit()
            }
        },
        async deleteMessage(message) {
            const confirm = await Swal.fire({
                title: 'Are you sure want to delete message?',
                icon: 'error',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Nope'
            })

            if (! confirm.isConfirmed) {
                return;
            }

            const form = this.$refs[message]

            form.submit()
        },
        async deletePost(post) {
            const confirm = await Swal.fire({
                title: 'Are you sure want to delete post?',
                icon: 'error',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Nope'
            })

            if (! confirm.isConfirmed) {
                return;
            }

            const form = this.$refs[post]

            form.submit()
        },
        async replyMessage(message, user) {
            const { value: text } = await Swal.fire({
                input: 'textarea',
                inputLabel: `Reply to ${user}`,
                inputPlaceholder: 'Type your consultation reply...',
                inputAttributes: {
                    'aria-label': 'Type your consultation reply...'
                },
                showConfirmButton: true,
                confirmButtonText: 'Send',
                showCancelButton: true
            })

            if (text) {
                const form = this.$refs[`message-reply-form-${message}`]
                const content = this.$refs[`message-reply-content-${message}`]
                content.value = text
                // console.log(form, content)
                form.submit()
            }
        }
    }
}
</script>
<!-- /#page-wrapper -->    
<?php include('include/footer.php');