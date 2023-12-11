<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse ">
    <ul class="nav navbar-nav side-nav hidden-print">

        <!-- <li class="" style="font-size: 15px; text-decoration: none; margin-left: 15px;">
                        <i class="fa fa-user"></i> : <b><?php echo $_SESSION['name']; ?></b>
                    </li>                     -->

        <li class="active">
            <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>

        <li>
            <a href="studentlist.php" style="background-color: white;"><i class="fa fa-fw fa-users"></i> Students</a>
        </li>
        <li>
            <a href="subject.php"><i class="fa fa-fw fa-bar-chart-o"></i> Subjects</a>
        </li>
        <li>
            <a href="teacherlist.php"><i class="fa fa-fw fa-user"></i> Instructors</a>
        </li>

        <li>
            <a href="class.php"><i class="fa fa-fw fa-table"></i> Class Info</a>
        </li>
        <!-- <li>
                        <a href="message.php"><i class="fa fa-comment"></i> Message</a> 
                    </li> -->
        <li>
            <a href="list.php"><i class="fa fa-envelope"></i> Consultation</a>
        </li>
        <li>
            <a href="report.php"><i class="fa fa-fw fa-folder"></i> Generate Reports</a>
        </li>
        <!-- <li>
                        <a href="studentsubject.php"><i class="fa fa-fw fa-folder-open"></i> Grading</a>
                    </li> -->

        <li>
            <a href="users.php"><i class="fa fa-fw fa-users"></i> Users</a>
        </li>




        <li>
            <a href="settings.php"><i class="fa fa-fw fa-gear"></i> Settings</a>
        </li>

                <li>
            <a href="#" data-toggle="modal" data-target="#downloadModal"><i class="fa fa-fw fa-download"></i>
                Downloadables</a>
        </li>

        <!-- <li>
                        <a href="logs.php"><i class="fa fa-fw fa-archive"></i> Recent Activity </a>
                    </li> -->
        <li>
            <a href="../logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
        </li>
    </ul>


    <!-- Modal for Downloadables -->
    <div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="downloadModalLabel">Download CSV Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Click the button below to download the CSV template for student data:</p>
                    <button id="downloadButton" class="btn btn-success">Download</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.navbar-collapse -->
</nav>
<script>
    document.getElementById('downloadButton').addEventListener('click', function () {
        // Trigger the download process
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'generate_csv.php', true);
        xhr.responseType = 'blob';

        xhr.onload = function () {
            if (this.status === 200) {
                var blob = new Blob([this.response], { type: 'text/csv' });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'student_data_template.csv';
                link.click();
            }
        };

        xhr.send();
    });
</script>
