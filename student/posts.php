<?php

include '../database.php';

include('../Carbon.php');

use Carbon\Carbon;

// Fetch posts
$selects = 'posts.id, userdata.fname, userdata.lname, posts.content, posts.created_at';
$joins = 'LEFT JOIN userdata ON posts.author_id = userdata.id';
$orders = 'ORDER BY created_at DESC';

$postsStmt = $connection->prepare("SELECT {$selects} FROM posts {$joins} {$orders}");
$postsStmt->execute();
$postsStmt->setFetchMode(PDO::FETCH_ASSOC);
$posts = $postsStmt->fetchAll();
?>

<div class="row">
    <?php foreach($posts as $post): ?>
        <?php
            $postedAt = Carbon::parse($post['created_at'])->format('F d, Y h:i A');
        ?>
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <strong> Posted by: <?php echo $post['fname'].' '.$post['lname']?> </strong>
                    </h5>
                    <p class="card-text"><?php echo $post['content'] ?></p>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6 text-right">
                            <small class="text-muted"> Sent: <?php echo $postedAt; ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <div style="background-color: #e2e8f0; width: 100%; height: 1px; margin-top: 10px; margin-bottom: 10px;"></div>
        </div>
    <?php endforeach; ?>
</div>
