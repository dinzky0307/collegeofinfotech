<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">                    
                    <li class="">
                        <a href="profile.php" class="dropdown-toggle"><i class="fa fa-user"></i> : <b><?php echo $_SESSION['name']; ?></b> </a>
                    </li>
                    <li class="active">
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> My Grades</a>
                    </li>
                    <li>
                        <a href="message.php"><i class="fa fa-comment"></i> Consultation</a> 
                    </li>
                    <li>
                        <a href="settings.php"><i class="fa fa-fw fa-gear"></i> Settings</a>
                    </li>
                    <li>
                        <a href="../logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                    </li>                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>