<?php
    $fq = $db->query("SELECT * FROM tbl_files WHERE file_userid=?", array($user_data['user_id']));

    if(isset($_POST['file_submit'])) {
        $filetitle = $_POST['files_title'];
        $filedesc = $_POST['files_desc'];
        $fileshare = 0;
        $filelist = array();
        $fileuserid = $user_data['user_id'];

        foreach ($_FILES['addFiles']['tmp_name'] as $key => $value) {
            $tmpname = $_FILES['addFiles']['tmp_name'][$key];
            $filepath = FILE_UPLOAD_PATH.hash("sha256", $_FILES['addFiles']['name'][$key], false);

            if($compress->compress($tmpname, $filepath)) {
                $file = array();
                $file['filename'] = $_FILES['addFiles']['name'][$key];
                $file['location'] = $filepath;
                $file['extension'] = pathinfo($_FILES['addFiles']['name'][$key])['extension'];
                $file['filesize'] = $_FILES['addFiles']['size'][$key];
                $file['mimetype'] = $_FILES['addFiles']['type'][$key];
                $file['publicshare'] = 'true';

                array_push($filelist, $file);
            }

            // if(move_uploaded_file($tmpname, $filepath)) {
            //     $file = array();
            //     $file['filename'] = $_FILES['addFiles']['name'][$key];
            //     $file['location'] = $filepath;
            //     $file['extension'] = pathinfo($_FILES['addFiles']['name'][$key])['extension'];
            //     $file['filesize'] = $_FILES['addFiles']['size'][$key];
            //     $file['mimetype'] = $_FILES['addFiles']['type'][$key];
            //     $file['publicshare'] = 'true';

            //     array_push($filelist, $file);
            // }
        }

        $db->insert("INSERT INTO tbl_files (file_title, file_description, file_lists, file_share, file_userid) VALUES(?, ?, ?, ?, ?)", array($filetitle, $filedesc, json_encode($filelist), $fileshare, $fileuserid));
    }

    echo '
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="mb-2">
                            <label for="formGroupExampleInput" class="form-label">Title</label>
                            <input type="text" name="files_title" class="form-control form-control-sm" id="formGroupExampleInput" placeholder="Enter Title" />
                        </div>
                        <div class="mb-2">
                            <label for="formGroupExampleInput" class="form-label">Description</label>
                            <textarea name="files_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter Description" rows="3"></textarea>
                        </div>
                        <div class="my-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <label for="addFiles" class="form-label">Files</label>
                            </div>
                            <div class="row">
                                <ol class="filelist overflow-auto">
                                    <li class="d-flex align-items-center justify-content-between my-1">
                                        <div><input type="file" name="addFiles[]" /></div>
                                        <button type="button" class="btn btn-danger btn-sm" class="deleteMultiFile">Delete</button>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <button id="addMultiFiles" name="file_submit" class="btn btn-primary btn-sm">Add Files</button>
                            <button type="submit" name="file_submit" class="btn btn-primary btn-sm">Upload Files</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-12 px-lg-4">
            <div class="d-flex align-items-center justify-content-between mb-2">    
                <h3>Files</h3>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus"></i>
                    <span class="text-uppercase">Add Files</span>
                </button>
            </div>';
            if(count($fq) >= 1) {     
                for ($x = 0; $x < count($fq); $x++) {
                    echo '<div class="row pb-1">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fid' . $fq[$x]['file_id'] . '" aria-expanded="false" aria-controls="fid' . $fq[$x]['file_id'] . '">
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" class="me-3" width="30" height="30" fill="currentColor" class="bi bi-folder-symlink-fill" viewBox="0 0 16 16">
                                                <path d="M3 2v4.586l7 7L14.586 9l-7-7H3zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586V2z"/>
                                                <path d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1v5.086z"/>
                                            </svg>

                                            <div class="d-block w-auto lh-sm">
                                                <div>
                                                    <span class="mb-2 fs-5 fw-bold">' . $fq[$x]['file_title'] . '</span>
                                                </div>
                                                <small class="me-3 text-muted d-block">' . $fq[$x]['file_description'] . '</small>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="fid' . $fq[$x]['file_id'] . '" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body">
                                            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aperiam error sequi corrupti officiis exercitationem eaque in doloremque temporibus rerum accusamus soluta, nobis perferendis dicta. Pariatur inventore vitae ut eum similique?</p>
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-sm btn-primary">Edit</button>
                                                <button type="button" class="btn btn-sm btn-danger">Delete</button>
                                                <button type="button" class="btn btn-sm btn-primary">Download Files</button>
                                            </div>
                                        </div>
                                    </div>
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
