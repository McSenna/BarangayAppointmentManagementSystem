<?php 
include_once '../connection.php';

try {

    if (isset($_REQUEST['residence_id']) && isset($_REQUEST['certificate_id'])) {

        $residence_id = $con->real_escape_string($_REQUEST['residence_id']);
        $certificate_id = $con->real_escape_string($_REQUEST['certificate_id']);

        $sql_request_status = "
            SELECT 
                certificate_request.*,
                residence_information.first_name,
                residence_information.middle_name,
                residence_information.last_name,
                residence_information.image,
                residence_information.image_path,
                residence_information.address,
                residence_information.gender,
                residence_information.age,
                residence_information.contact_number
            FROM certificate_request 
            INNER JOIN residence_information 
            ON certificate_request.residence_id = residence_information.residence_id 
            WHERE certificate_request.id = ? 
            AND certificate_request.residence_id = ?
        ";

        $stmt_request_status = $con->prepare($sql_request_status) or die($con->error);
        $stmt_request_status->bind_param('ss', $certificate_id, $residence_id);
        $stmt_request_status->execute();
        $result = $stmt_request_status->get_result();

        $row_request_status = $result->fetch_assoc();

        // Profile image check
        if (!empty($row_request_status['image'])) {
            $image = '<img class="img-circle elevation-2" src="' . $row_request_status['image_path'] . '" alt="User Avatar">';
        } else {
            $image = '<img class="img-circle elevation-2" src="../assets/dist/img/blank_image.png" alt="User Avatar">';
        }
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<style>
.modal-body {
    height: 74vh;
    overflow-y: auto;
}
.modal-body::-webkit-scrollbar {
    width: 5px;
}                         
.modal-body::-webkit-scrollbar-thumb {
    background: #6c757d;
}
.modal-body::-webkit-scrollbar-thumb:window-inactive {
    background: #6c757d;
}
</style>

<!-- Modal -->
<div class="modal fade" id="showStatusRequestModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="requestForm" method="post">

        <div class="modal-header">
            <h5 class="modal-title"><i class="far fa-user"></i> Profile</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <div class="container-fluid">

            <input type="hidden" name="residence_id" id="residence_id" value="<?= $residence_id ?>">
            <input type="hidden" name="certificate_id" id="certificate_id" value="<?= $certificate_id ?>">

            <div class="card card-widget widget-user-2">
              <div class="widget-user-header bg-black">
                <div class="widget-user-image">
                  <?= $image ?>
                </div>
                <h5 class="widget-user-desc pt-3">
                    <?= $row_request_status['first_name'] . ' ' . $row_request_status['last_name'] ?>
                </h5>
              </div>

              <div class="card-footer p-0">
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <span class="nav-link"><i class="fas fa-id-card-alt text-yellow text-lg"></i>
                      <span class="float-right"><?= $row_request_status['residence_id'] ?></span>
                    </span>
                  </li>
                  <li class="nav-item">
                    <span class="nav-link"><i class="fas fa-map-marker-alt text-yellow text-lg"></i>
                      <span class="float-right"><?= $row_request_status['address'] ?></span>
                    </span>
                  </li>
                  <li class="nav-item">
                    <span class="nav-link"><i class="fas fa-venus-mars text-yellow text-lg"></i>
                      <span class="float-right"><?= $row_request_status['gender'] ?></span>
                    </span>
                  </li>
                  <li class="nav-item">
                    <span class="nav-link"><i class="fa fa-child text-yellow text-lg"></i>
                      <span class="float-right"><?= $row_request_status['age'] ?></span>
                    </span>
                  </li>
                  <li class="nav-item">
                    <span class="nav-link"><i class="fa fa-phone text-yellow text-lg"></i>
                      <span class="float-right"><?= $row_request_status['contact_number'] ?></span>
                    </span>
                  </li>

                  <li class="nav-item">
                    <span class="nav-link"><i class="fa fa-exclamation text-yellow text-lg"></i>
                      <?php
                        $status = $row_request_status['status'];
                        if ($status == 'REJECTED') {
                            echo '<span class="float-right badge badge-danger">' . $status . '</span>';
                        } elseif ($status == 'PENDING') {
                            echo '<span class="float-right badge badge-warning">' . $status . '</span>';
                        } else {
                            echo '<span class="float-right badge badge-success">' . $status . '</span>';
                        }
                      ?>
                    </span>
                  </li>
                </ul>
              </div>
            </div>

            <div class="row">

              <div class="col-sm-12">
                <div class="form-group">
                  <label>Purpose</label>
                  <input type="text"
                    name="purpose"
                    id="purpose"
                    class="form-control text-uppercase"
                    value="<?= $row_request_status['purpose'] ?>"
                    <?= ($status != 'PENDING') ? 'disabled' : '' ?> >
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label>Message</label>
                  <textarea name="message" id="message" class="form-control" rows="2"><?= $row_request_status['message'] ?></textarea>
                </div>
              </div>

              <!-- ★★★ FIXED — READ-ONLY DATE ISSUED + Hidden Value ★★★ -->
              <div class="col-sm-6">
                <div class="form-group form-group-sm">
                  <label>Date Issued (Auto-generated)</label>

                  <input type="text" class="form-control"
                    value="<?= $row_request_status['date_issued'] ?>"
                    readonly
                    style="background:#black; cursor:not-allowed;">

                  <input type="hidden" 
                    name="date_issued"
                    value="<?= $row_request_status['date_issued'] ?>">
                </div>
              </div>

              <!-- Admin Editable: Date Expired -->
              <div class="col-sm-6">
                <div class="form-group form-group-sm">
                  <label>Date Expired</label>
                  <input type="date"
                    name="edit_date_expired"
                    id="edit_date_expired"
                    class="form-control"
                    value="<?= $row_request_status['date_expired'] ?>">
                </div>
              </div>

            </div>

          </div>
        </div>

        <div class="modal-footer">

          <?php if ($status == 'PENDING'): ?>
            <button type="button" class="btn btn-danger btn-flat rejectRequest px-3">
              <i class="fas fa-user-times"></i> REJECT
            </button>

            <button type="submit" class="btn btn-success btn-flat px-3">
              <i class="fas fa-user-check"></i> ACCEPT
            </button>
          <?php endif; ?>

          <button type="button" class="btn bg-black btn-flat px-3" data-dismiss="modal">
            <i class="fas fa-times"></i> CLOSE
          </button>

        </div>

      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {

    // Reject request
    $(document).on('click', '.rejectRequest', function () {

        var residence_id = $("#residence_id").val();
        var certificate_id = $("#certificate_id").val();
        var message = $("#message").val();
        var purpose = $("#purpose").val();

        if (message.trim() === "") {
            Swal.fire({
                title: '<strong class="text-danger">ERROR</strong>',
                type: 'error',
                html: '<b>Please provide a rejection message.</b>',
                width: '400px'
            });
            return false;
        }

        Swal.fire({
            title: '<strong class="text-warning">ARE YOU SURE?</strong>',
            html: "<b>You want to reject this request?</b>",
            type: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, Reject'
        }).then((result) => {
            if (result.value) {
                $.post(
                    'rejectRequest.php',
                    { residence_id, certificate_id, message, purpose },
                    function () {
                        Swal.fire({
                            title: '<strong class="text-success">Success</strong>',
                            type: 'success',
                            html: '<b>Request rejected successfully</b>',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            $("#certificateTable").DataTable().ajax.reload();
                            $("#showStatusRequestModal").modal('hide');
                        });
                    }
                );
            }
        });

    });

    // Accept Request
    $("#requestForm").submit(function (e) {
        e.preventDefault();

        Swal.fire({
            title: '<strong class="text-info">ARE YOU SURE?</strong>',
            html: "<b>You want to accept this request?</b>",
            type: 'info',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, Accept'
        }).then((result) => {
            if (result.value) {
                $.post(
                    'requestStatus.php',
                    $(this).serialize(),
                    function () {
                        Swal.fire({
                            title: '<strong class="text-success">Success</strong>',
                            type: 'success',
                            html: '<b>Request accepted successfully</b>',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            $("#certificateTable").DataTable().ajax.reload();
                            $("#showStatusRequestModal").modal('hide');
                        });
                    }
                );
            }
        });

    });

});
</script>
