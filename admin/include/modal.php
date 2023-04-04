<style>
    .modalCenter{
        top: 17% !important;
        transform: translateY(-17%) !important;
    }
</style>
<!-- add modal for subject -->
<div class="modal fade" id="addsubject" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modalCenter">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Subject</h3>
        </div>
        <div class="modal-body">
            <form action="" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="code" placeholder="Subject Code" required/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="title" placeholder="Subject Description" required/>
                </div>
                <div class="form-group">
                    <input type="number" min="1" max="5" class="form-control" name="lecunit" placeholder='Lec Units' required />
                </div>
                <div class="form-group">
                    <input type="number" min="1" max="5" class="form-control" name="labunit" placeholder='Lab Units' required />
                </div>
                <div class="form-group">
                    <input type="number" min="1" max="10" class="form-control" name="totalunit" placeholder='Total Units' required />
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="pre" placeholder="Pre-requisites/s" required/>
                </div>
                <div class="form-group">
                    <select name="year" class="form-control" required>
                        <option value="">Select Year level...</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="semester" class="form-control" required>
                        <option value="">Select Semester...</option>
                        <option value="1">First Semester</option>
                        <option value="2">Second Semester</option>
                        <option value="3">Summer</option>
                    </select>
                </div>
               
               
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" name="addSubject" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
            </form>
        </div>
    </div>
  </div>
</div>

<!-- add modal for class info -->
<div class="modal fade" id="addclass" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modalCenter">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Class Info</h3>
        </div>
        <div class="modal-body">
            <form action="" method="post">
                <div class="form-group">  
                    <select name="subject" class="form-control" required>
                        <option value="">Select Subject Code...</option>
                    <?php 
                        $r = mysql_query("select * from subject");
                        while($row = mysql_fetch_array($r)):
                    ?>
                        <option value="<?php echo $row['code']; ?>"><?php echo $row['code']; ?></option>
                    <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">  
                    <select name="description" class="form-control" required>
                        <option value="">Select Subject Title...</option>
                    <?php 
                        $r = mysql_query("select * from subject");
                        while($row = mysql_fetch_array($r)):
                    ?>
                        <option value="<?php echo $row['title']; ?>"><?php echo $row['title']; ?></option>
                    <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="teacher" class="form-control" required>
                        <option value="">Select Teacher...</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?php echo $teacher['id']; ?>">
                                <?php echo "{$teacher['fname']} {$teacher['lname']}"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="year" class="form-control" required>
                        <option value="">Select Year level...</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="section" class="form-control" required>
                        <option value="">Select Section...</option>
                        <option>North</option>
                        <option>South</option>
                        <option>East</option>
                        <option>West</option>
                        <option>Norteast</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="sem" class="form-control" required>
                        <?php 
                            $r = mysql_query("select * from ay");
                            while($row = mysql_fetch_array($r)):
                        ?>
                            <option value="<?php echo $row['semester']; ?>"><?php echo $row['semester']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <select name="sy" class="form-control" required>
                        <?php 
                            $r = mysql_query("select * from ay");
                            while($row = mysql_fetch_array($r)):
                        ?>
                            <option value="<?php echo $row['academic_year']; ?>">Academic Year : <?php echo $row['academic_year']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="addClass" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
            </form>
        </div>
    </div>
  </div>
</div>

<!-- add modal for student -->
<div class="modal fade" id="addstudent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modalCenter">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fa fa-user"></i> Add Student</h3>
        </div>
        <div class="modal-body">
            <form action="" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="studid" placeholder="student ID" required/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="lname" placeholder="Lastname" required/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="fname" placeholder="Firstname" required/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="mname" placeholder="Middlename" required/>
                </div>
                <div class="form-group">
                    <select class="form-control" name="year" placeholder="Year level" required>
                    <option value="">Select Year level...</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" name="section" placeholder="Section" required>
                    <option value="">Select Section...</option>
                        <option>North</option>
                        <option>South</option>
                        <option>East</option>
                        <option>West</option>
                        <option>Norteast</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" name="semester" placeholder="Semester" required>
                        <option value="">Select Semester...</option>
                        <option>First Semester</option>
                        <option>Second Semester</option>
                        <option>Summer</option>
                    </select>
                </div>
                <input type="hidden" class="form-control" name="addStudent" value="addStudent" />
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
            </form>
        </div>
    </div>
  </div>
</div>

<!-- add modal for instructor -->
<div class="modal fade" id="addteacher" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modalCenter">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fa fa-user"></i> Add Instructor</h3>
        </div>
        <div class="modal-body">
            <form action="" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="teachid" placeholder="Instructor ID" required/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="lname" placeholder="Lastname" required/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="fname" placeholder="Firstname" required/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="mname" placeholder="Middlename" required/>
                </div>
                <div class="form-group">
                    <select class="form-control" name="sex" placeholder="Gender" required>
                    <option value="">Select Gender...</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required/>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="addTeacher" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
            </form>
        </div>
    </div>
  </div>
</div>
<div class="modal fade" id="message" tabindex="-1" role="dialog" aria-labelledby="message-tab" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fa fa-comment"></i> Send Message</h3>
            </div>
            <div class="modal-body">
                <form action="data/student_model.php?q=addstudent" method="post">
                <textarea id="chat-textarea" name="message_to_admin" class="form-control" placeholder="Enter your message" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" name="message" class="btn btn-primary"> Send</button>
            </div>
        </div>
    </div>
</div>

