<?php

    $user = $db->query("SELECT * FROM tbl_user WHERE user_uid=? ORDER BY user_updatedate DESC", array($user_data['user_id']));
    $userroles = $db->query("SELECT * FROM tbl_roles WHERE role_user=? ORDER BY role_id DESC", array($user_data['user_id']));

    // echo var_export($userroles);

    if(isset($_POST['file_user_submit'])) {
        $u_name = $_POST['user_name'];
        $u_username = $_POST['user_username'];
        $u_password = $_POST['user_password'];
        $u_email = $_POST['user_email'];
        $u_roles = $_POST['user_roles'];
        $u_id = $user_data['user_id'];

        $db->insert("INSERT INTO tbl_user (user_personname, user_email, user_loginname, user_pass, user_uid, user_role) VALUES(?, ?, ?, ?, ?, ?)", array($u_name, $u_email, $u_username, $u_password, $u_id, 1));
        header('location: dashboard.php?id=' . urlencode($id) . '&task=users');
    }

    echo '
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="user_name" class="form-control" placeholder="Enter User" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">User Name</label>
                                <input type="text" name="user_username" class="form-control" placeholder="Enter Username" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="user_password" class="form-control" placeholder="Enter password" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">User Email</label>
                                <input type="text" name="user_email" class="form-control" placeholder="Enter email" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">User Roles</label>
                                <select name="user_roles" class="form-select" aria-label="Default select example">
                                    <option selected> ---------- Select User Roles ---------- </option>
                                    <option value="1">System Admin</option>
                                    <option value="2">Custom</option>';

                                    // foreach($students as $userroles) {
                                    //     echo '<option value="1">System Admin</option>';
                                    // }

                                echo '</select>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <button type="submit" name="file_user_submit" class="btn btn-primary btn-sm text-uppercase">Create Users</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 px-lg-4">
            <div class="d-flex align-items-center justify-content-between mb-2">    
                <h3>Users</h3>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus"></i>
                    <span class="text-uppercase">Add Users</span>
                </button>
            </div>
            <div class="row pb-1">
                <div class="accordion accordion-flush overflow-auto">';

                if (count($user) >= 1) {
                    for ($x = 0; $x < count($user); $x++) {
                        echo '<div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed shadow-sm remove-dropdown" type="button" data-bs-toggle="collapse" data-bs-target="#fid' . $user[$x]['user_id'] . '" aria-expanded="false" aria-controls="fid' . $user[$x]['user_id'] . '">
                                        <div class="d-block lh-base w-100">
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5 fw-bold">' . $user[$x]['user_personname'] . '</span>
                                                </div>
                                                <small class="me-3 text-muted d-block">' . $user[$x]['user_email'] . '</small>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="fid' . $user[$x]['user_id'] . '" class="accordion-collapse collapse shadow-sm" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-2">
                                        <div class="row my-2 mx-1">
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    echo '</div>
                    </div>
                    <div class="row h-100 align-items-center">
                        <div class="container">
                            <div class="jumbotron text-center align-items-center">
                                <h5>No files Found!</h5>
                            </div>
                        </div>
                    </div>';
                }
    echo '</div>';
?>
