<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">                    
                    <ul class="" style="font-size: 15px; text-decoration: none;">
                        <i class="fa fa-user"></i> : <b><?php echo $_SESSION['name']; ?></b>
                    </ul>
                    <li class="active">
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="subject.php"><i class="fa fa-fw fa-bar-chart-o"></i> My Subjects</a>
                    </li>
<!--                     <li>
                        <a href="student.php"><i class="fa fa-fw fa-table"></i> My Students</a>
                    </li> -->
                    <!-- <li>
                        <a href="message.php"><i class="fa fa-comment"></i> Message</a> 
                    </li> -->
                    <li>
                        <a href="list.php"><i class="fa fa-envelope"></i> Consultation</a> 
                    </li>
                    <li>
                        <a href="settings.php"><i class="fa fa-fw fa-gear"></i> Change Password</a>
                    </li>
                    <li>
                        <a href="../logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                    </li>                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
