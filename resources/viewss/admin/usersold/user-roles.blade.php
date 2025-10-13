<?php include ('header.php')?>
<link href="http://localhost/afiaanyi-logistics/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="http://localhost/afiaanyi-logistics/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="http://localhost/afiaanyi-logistics/lib/Ionicons/css/ionicons.css" rel="stylesheet">
<link href="http://localhost/afiaanyi-logistics/lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
<link href="http://localhost/afiaanyi-logistics/lib/jquery-switchbutton/jquery.switchButton.css" rel="stylesheet">
<link href="http://localhost/afiaanyi-logistics/lib/highlightjs/github.css" rel="stylesheet">
<link href="http://localhost/afiaanyi-logistics/lib/select2/css/select2.min.css" rel="stylesheet">

<!-- Bracket CSS -->
<link rel="stylesheet" href="http://localhost/afiaanyi-logistics/css/bracket.css">
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel" style="position: center">

      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">Add User</h4>
      </div>

      <div class="br-pagebody" style="position: center" >
        <div class="br-section-wrapper" >

          <div class="row mg-t-10" style="position: center" >
            <div class="col-xl-6" style="position: center">
              <div class="form-layout form-layout-4" style="position: center">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add User Roles</h6>
                <div class="row">
                  <label class="col-sm-4 form-control-label">Role Name: <span class="tx-danger">*</span></label>
                  <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <input type="text" class="form-control" placeholder="Enter firstname">
                  </div>
                </div><!-- row -->


                  <div class="row mg-t-20">
                      <label class="col-sm-4 form-control-label"> <span>Active</span>    </label>                      <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                          <input type="checkbox">
                      </div>
                  </div>


                <div class="form-layout-footer mg-t-30">
                  <button class="btn btn-info">Submit Form</button>
                  <button class="btn btn-secondary">Cancel</button>
                </div><!-- form-layout-footer -->
              </div><!-- form-layout -->
            </div><!-- col-6 -->

          </div></div>






          <?php include ('footer.php')?>
    </div><!-- br-mainpanel -->


    <script src="http://localhost/afiaanyi-logistics/lib/jquery/jquery.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/popper.js/popper.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/bootstrap/bootstrap.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/moment/moment.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/jquery-ui/jquery-ui.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/jquery-switchbutton/jquery.switchButton.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/peity/jquery.peity.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/highlightjs/highlight.pack.js"></script>
    <script src="http://localhost/afiaanyi-logistics/lib/select2/js/select2.min.js"></script>

    <script src="http://localhost/afiaanyi-logistics/js/bracket.js"></script>
    <script>
      $(function(){
        'use strict'

        $('.form-layout .form-control').on('focusin', function(){
          $(this).closest('.form-group').addClass('form-group-active');
        });

        $('.form-layout .form-control').on('focusout', function(){
          $(this).closest('.form-group').removeClass('form-group-active');
        });

        // Select2
        $('#select2-a, #select2-b').select2({
          minimumResultsForSearch: Infinity
        });

        $('#select2-a').on('select2:opening', function (e) {
          $(this).closest('.form-group').addClass('form-group-active');
        });

        $('#select2-a').on('select2:closing', function (e) {
          $(this).closest('.form-group').removeClass('form-group-active');
        });

      });
    </script>
  </body>

<!-- Mirrored from themepixels.me/demo/bracket/app/form-layouts.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 21 Sep 2020 15:12:15 GMT -->
</html>
