<style>
    .modalCenter{
        top: 17% !important;
        transform: translateY(-17%) !important;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

<script>
    // Add event listener to the subject select element
    $(document).ready(function() {
        $('select[name="subject"]').change(function() {
            // Get the selected subject's title
            var subjectTitle = $(this).find('option:selected').data('title');
            // Update the description input with the subject title
            $('input[name="description"]').val(subjectTitle);

            // Get the selected subject's year level
            var yearLevel = $(this).find('option:selected').data('year');
            // If year level is available, update the year select element
            if (yearLevel) {
                $('select[name="year"]').val(yearLevel);
            }
        });
    });
</script>
<script>
    // Add event listener to the subject select element
    $(document).ready(function() {
        $('select[name="subject"]').change(function() {
            // Get the selected subject's title
            var subjectTitle = $(this).find('option:selected').data('title');
            // Update the description input with the subject title
            $('input[name="description"]').val(subjectTitle);

            // Get the selected subject's year level
            var yearLevel = $(this).find('option:selected').data('year');
            // If year level is available, update the year select element
            if (yearLevel) {
                $('select[name="year"]').val(yearLevel);
                // Disable the year select element
                $('select[name="year"]').prop('disabled', true);
            } else {
                // If year level is not available, enable the year select element
                $('select[name="year"]').prop('disabled', false);
            }
        });
    });
</script>


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

