<?php

    $fq = $db->query("SELECT * FROM tbl_categories WHERE category_userid=? ORDER BY category_createddate DESC", array($user_data['user_id']));
    if(isset($_POST['category_submit'])) {
        $cat_title = $_POST['category_title'];
        $cat_desc = $_POST['category_desc'];
        $cat_userid = $user_data['user_id'];
        $db->insert("INSERT INTO tbl_categories(category_name, category_desc, category_userid) VALUES(?, ?, ?)", array($cat_title, $cat_desc, $cat_userid));
    }

    echo '
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Categories</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="mb-2">
                                <label for="formGroupExampleInput" class="form-label">Category Title</label>
                                <input type="text" name="category_title" class="form-control form-control-sm" id="formGroupExampleInput" placeholder="Enter Title" />
                            </div>
                            
                            <div class="mb-4">
                                <label for="formGroupExampleInput" class="form-label">Category Description</label>
                                <textarea name="category_desc" class="form-control" placeholder="Enter Category Description" rows="3"></textarea>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <button type="submit" name="category_submit" class="btn btn-primary btn-sm text-uppercase">Add Categories</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 px-lg-4">
            <div class="d-flex align-items-center justify-content-between mb-2">    
                <h3>Categories</h3>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i class="bi bi-plus"></i>
                    <span class="text-uppercase">Add Categories</span>
                </button>
            </div>';

            if (count($fq) >= 1) {
                for ($x = 0; $x < count($fq); $x++) {
                    echo '<div class="row pb-1">
                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button collapsed shadow-sm remove-dropdown" type="button" aria-expanded="false">
                                                        <div class="d-block w-100 lh-base">
                                                            <div>
                                                                <span class="mb-2 fs-5 fw-bold">' . $fq[$x]['category_name'] . '</span>
                                                            </div>
                                                            <small class="me-3 text-muted d-block">' . $fq[$x]['category_desc'] . '</small>
                                                        </div>
                                                        <a href="dashboard.php?id=' . urlencode($id) . '&task=categories&deleteid=' . $fq[$x]['category_id'] . '" class="btn btn-sm btn-danger text-uppercase" onclick="return confirm(\' you want to delete?\');">Delete</a>
                                                    </button>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>';
                }
            } else {
                echo '<div class="row h-100 align-items-center">
                                <div class="container">
                                    <div class="jumbotron text-center align-items-center">
                                        <h5>No files Found!</h5>
                                    </div>
                                </div>
                            </div>';
            }
           
        echo '</div>';
?>
