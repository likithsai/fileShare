<?php
    $fq = $db->query("SELECT * FROM tbl_roles WHERE role_user=? ORDER BY role_id DESC", array($user_data['user_id']));


    // Check if user has submitted the form
    if(isset($_POST['add_userroles'])) {
        $rolename = $_POST['user_rolename'];
        $roledesc = $_POST['user_roledesc'];
        $roleuserid = $user_data['user_id'];

        $db->insert("INSERT INTO tbl_roles (role_name, role_desc, role_user) VALUES(?, ?, ?)", array($rolename, $roledesc, $roleuserid));
        header('location: dashboard.php?id=' . urlencode($id) . '&task=userroles');
    }

    //  Edit userroles
    if(isset($_POST['edit_userroles'])) {
        $rolename = $_POST['user_rolename'];
        $roledesc = $_POST['user_roledesc'];
        $roleuserid = $user_data['user_id'];

        $db->update("UPDATE tbl_roles SET role_name = ?, role_desc = ? WHERE role_user=?", array($rolename, $roledesc, $roleuserid));
        header('location: dashboard.php?id=' . urlencode($id) . '&task=userroles');
    }

    echo '<div class="modal fade" id="userRolesModal" tabindex="-1" aria-labelledby="userRolesModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add User Roles</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <form method="post">
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Role Name</label>
                                <input type="text" name="user_rolename" class="form-control" placeholder="Enter User Role Name" />
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Role Description</label>
                                <textarea name="user_roledesc" class="form-control" placeholder="Enter Role Description" rows="2"></textarea>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <button type="submit" name="add_userroles" class="btn btn-primary btn-sm text-uppercase">Add User Roles</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 px-lg-4">
            <div class="d-flex align-items-center justify-content-between">    
                <h3>User Roles</h3>
                <button type="button" class="btn btn-primary btn-sm"  data-bs-toggle="modal" data-bs-target="#userRolesModal">
                    <span class="text-uppercase">Add User Roles</span>
                </button>
            </div>
            <div class="row pb-1 my-2">
                    <div class="accordion accordion-flush overflow-auto">';

                if (count($fq) >= 1) {
                    for ($x = 0; $x < count($fq); $x++) {
                        echo '<div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed shadow-sm remove-dropdown" type="button" data-bs-toggle="collapse" data-bs-target="#fid' . $fq[$x]['role_id'] . '" aria-expanded="false" aria-controls="fid' . $fq[$x]['role_id'] . '">
                                        <div class="d-block lh-base w-100">
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fs-5 fw-bold">' . $fq[$x]['role_name'] . '</span>
                                                </div>
                                                <small class="me-3 text-muted d-block">' . $fq[$x]['role_desc'] . '</small>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="fid' . $fq[$x]['role_id'] . '" class="accordion-collapse collapse shadow-sm" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body p-2">
                                        <div class="row my-2 mx-1">
                                            <form method="post" action="" enctype="multipart/form-data" class="px-0 px-lg-2">
                                                <div class="mb-3">
                                                    <label for="formGroupExampleInput" class="form-label fw-bold">Role Name</label>
                                                    <input type="text" name="user_rolename" class="form-control" placeholder="Enter User Role Name" value="' . $fq[$x]['role_name'] . '" />
                                                </div>
                                                <div class="mb-3">
                                                    <label for="formGroupExampleInput" class="form-label fw-bold">Role Description</label>
                                                    <textarea name="user_roledesc" class="form-control" placeholder="Enter Role Description" rows="2">' . $fq[$x]['role_desc'] . '</textarea>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-center my-3 p-0">
                                                    <input type="submit" name="edit_userroles" class="btn btn-sm btn-primary text-uppercase mx-1" value="Save User Roles">
                                                    <a href="#" class="btn btn-sm btn-danger text-uppercase mx-1" onclick="return confirm("you want to delete?");">Delete User Roles</a>
                                                </div>
                                            </form>
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
                                <h5>No User Roles Found!</h5>
                            </div>
                        </div>
                    </div>';
                }
        echo '</div>';
?>
