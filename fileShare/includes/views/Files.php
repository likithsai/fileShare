<?php
    $fq = $db->query("SELECT * FROM tbl_files WHERE file_userid=? ORDER BY file_createddate DESC", array($user_data['user_id']));

    function getFileDetails($jsonStringFiles)
    {
        // calculate file size
        $files = array();
        $filecount = 0;
        $filesize = 0;

        foreach ((array) json_decode($jsonStringFiles) as $key => $value) {
            $floc = (array) $value;
            $filesize = $filesize + intval($floc['filesize']);
            $filecount = $filecount + 1;
        }

        $temp = array();
        $temp['filecount'] = $filecount;
        $temp['filesize'] =  $filesize;
        array_push($files, $temp);

        return $files;
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    if(isset($_GET['downloadfiles'])) {
        switch(strtolower($_GET['downloadfiles'])) {
            case 'downloadfiles':
                echo $_GET['fileid'];
                break;
        }
    }

    if (isset($_GET['task']) && isset($_GET['deleteid'])) {
        $deleteStatus = true;
        if (strtolower($_GET['task']) == 'files') {
            $files = $db->query("SELECT file_lists FROM tbl_files WHERE  file_id = ?", array($_GET['deleteid']));
            // delete files
            foreach (json_decode($files[0]['file_lists']) as $item) {
                $itemArray = (array) $item;
                unlink($itemArray['location']);
            }

            //  check if file exist
            foreach (json_decode($files[0]['file_lists']) as $item) {
                $itemArray = (array) $item;
                $status = true;

                if (file_exists($itemArray['location'])) {
                    $status = false;
                }

                if ($status) {
                    $db->delete("DELETE FROM tbl_files WHERE file_id = ?", array($_GET['deleteid']));
                }
            }
        }
    }

    if (isset($_POST['file_submit'])) {
        $filetitle = $_POST['files_title'];
        $filedesc = $_POST['files_desc'];
        $fileexpirydate = $_POST['expiry-date'];

        $fileshare = 0;
        $filelist = array();
        $fileuserid = $user_data['user_id'];

        foreach ($_FILES['addFiles']['tmp_name'] as $key => $value) {
            $tmpname = $_FILES['addFiles']['tmp_name'][$key];
            $filepath = FILE_UPLOAD_PATH . hash("sha256", $_FILES['addFiles']['name'][$key], false);

            if ($compress->compress($tmpname, $filepath)) {
                $file = array();
                $file['filename'] = $_FILES['addFiles']['name'][$key];
                $file['size'] = $_FILES['addFiles']['size'][$key];
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

        $db->insert("INSERT INTO tbl_files (file_title, file_description, file_lists, file_share, file_userid, file_expirydate) VALUES(?, ?, ?, ?, ?, ?)", array($filetitle, $filedesc, json_encode($filelist), $fileshare, $fileuserid, $fileexpirydate));
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
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Title</label>
                                <input type="text" name="files_title" class="form-control" id="formGroupExampleInput" placeholder="Enter Title" />
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Description</label>
                                <textarea name="files_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter Description" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Expiry Date</label>
                                <input name="expiry-date" id="startDate" class="form-control" type="date">
                            </div>
                            <div class="my-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="addFiles" class="form-label">Files</label>
                                </div>
                                <div class="row">
                                    <ol class="filelist overflow-auto">
                                        <li class="d-flex align-items-center justify-content-between my-1">
                                            <div><input type="file" name="addFiles[]" /></div>
                                            <button type="button" class="btn btn-danger btn-sm text-uppercase" class="deleteMultiFile">Delete</button>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <button id="addMultiFiles" name="file_submit" class="btn btn-primary btn-sm text-uppercase">Add Files</button>
                                <button type="submit" name="file_submit" class="btn btn-primary btn-sm text-uppercase">Upload Files</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 px-lg-4">
                <div class="d-flex align-items-center justify-content-between mb-2">    
                    <h3>Files</h3>
                    <div class="d-flex">
                        <button type="button" class="btn btn-primary mx-1" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-search"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm w-75" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="bi bi-plus-square m-1 m-lg-0 me-lg-2"></i>
                            <span class="text-uppercase d-none d-lg-inline-flex">Add Files</span>
                        </button>
                    </div>
                </div>
                <div class="row pb-1">
                    <div class="accordion accordion-flush overflow-auto">';
                
    if (count($fq) >= 1) {
        for ($x = 0; $x < count($fq); $x++) {
            echo '
                                
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed shadow-sm remove-dropdown" type="button" data-bs-toggle="collapse" data-bs-target="#fid' . $fq[$x]['file_id'] . '" aria-expanded="false" aria-controls="fid' . $fq[$x]['file_id'] . '">
                                                <div class="d-block lh-base w-100">
                                                    <div>
                                                        <span class="mb-2 fs-5 fw-bold">' . $fq[$x]['file_title'] . '</span>
                                                    </div>
                                                    <small class="me-3 text-muted d-block">' . $fq[$x]['file_description'] . '</small>
                                                    <div class="badge bg-primary mt-2">
                                                        <span>Total File Size: ' . formatSizeUnits(getFileDetails($fq[$x]['file_lists'])[0]['filesize']) . '</span>
                                                    </div>
                                                    <div class="badge bg-primary mt-2">
                                                        <span>Total File Count: ' . getFileDetails($fq[$x]['file_lists'])[0]['filecount'] . '</span>
                                                    </div>
                                                </div>
                                                <span class="badge bg-success ms-3 d-flex align-items-center">
                                                    <div class="spinner-grow spinner-grow-sm me-2" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <span>Active</span>
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="fid' . $fq[$x]['file_id'] . '" class="accordion-collapse collapse shadow-sm" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body p-2">
                                                <div class="row my-2 mx-1">
                                                    <form method="post" action="" enctype="multipart/form-data">
                                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link active" id="home-tab' . $fq[$x]['file_id'] . '" data-bs-toggle="tab" data-bs-target="#home' . $fq[$x]['file_id'] . '" type="button" role="tab" aria-controls="home' . $fq[$x]['file_id'] . '" aria-selected="true">
                                                                    <i class="bi bi-house me-1"></i>
                                                                    <span>Home</span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link" id="files-tab' . $fq[$x]['file_id'] . '" data-bs-toggle="tab" data-bs-target="#files' . $fq[$x]['file_id'] . '" type="button" role="tab" aria-controls="files' . $fq[$x]['file_id'] . '" aria-selected="false">
                                                                    <i class="bi bi-journals me-1"></i>
                                                                    <span>Files</span>
                                                                    <small class="badge bg-primary">' . count(json_decode($fq[$x]['file_lists'])) . '</small>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link" id="users-tab' . $fq[$x]['file_id'] . '" data-bs-toggle="tab" data-bs-target="#users' . $fq[$x]['file_id'] . '" type="button" role="tab" aria-controls="users' . $fq[$x]['file_id'] . '" aria-selected="false">
                                                                    <i class="bi bi-people me-1"></i>
                                                                    <span>Users</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content bg-light shadow-sm border rounded-bottom p-2" id="myTabContent">
                                                                <div class="tab-pane fade show active my-3 mx-2" id="home' . $fq[$x]['file_id'] . '" role="tabpanel" aria-labelledby="home-tab' . $fq[$x]['file_id'] . '">
                                                                        <div class="mb-3">
                                                                            <label for="formGroupExampleInput" class="form-label">Title</label>
                                                                            <input type="text" name="files_title" class="form-control" id="formGroupExampleInput" placeholder="Enter Title" value="' . $fq[$x]['file_title'] . '">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="formGroupExampleInput" class="form-label">Description</label>
                                                                            <textarea name="files_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter Description" rows="2">' . $fq[$x]['file_description'] . '</textarea>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="formGroupExampleInput" class="form-label">Expiry Date</label>
                                                                            <input name="expiry-date" id="startDate" class="form-control" type="date" value="' . date('Y-m-d', strtotime($fq[$x]['file_expirydate'])) . '">
                                                                        </div>
                                                                </div>
                                                                <div class="tab-pane fade my-3 mx-2" id="files' . $fq[$x]['file_id'] . '" role="tabpanel" aria-labelledby="files-tab' . $fq[$x]['file_id'] . '">
                                                                    <div class="d-flex align-items-center justify-content-between p-0 col-md-12">
                                                                        <div class="input-group input-group-sm mb-3 my-3">
                                                                            <input type="text" class="form-control" aria-label="Search for Users" aria-describedby="inputGroup-sizing-sm" placeholder="Search for Files ...">
                                                                        </div>
                                                                    </div>
                                                                    <div class="table-responsive">
                                                                        <table class="table">
                                                                            <tbody>';

                                                                            
                                                                            foreach (json_decode($fq[$x]['file_lists']) as $item) {
                                                                                $itemArray = (array) $item;
                                                                                
                                                                                echo '<tr>
                                                                                    <td class="align-middle w-90">
                                                                                        <div class="d-block">
                                                                                            <div class="fw-bold">' . $itemArray['filename'] . '</div>
                                                                                            <small class="text-muted">SHA : ' . sha1_file($itemArray['location'], false) . '</small><br />
                                                                                            <small class="text-muted">
                                                                                                <div class="badge bg-primary">
                                                                                                    <span>Filesize: ' . formatSizeUnits($itemArray['filesize']) . '</span>
                                                                                                </div
                                                                                            </small>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="align-middle w-100">
                                                                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                                                            <button type="button" class="btn btn-danger text-uppercase">
                                                                                                <i class="bi bi-trash-fill"></i>
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-primary text-uppercase">
                                                                                                <i class="bi bi-arrows-move"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>';
                                                                            }

                                                                            echo '</tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade my-3 mx-2" id="users' . $fq[$x]['file_id'] . '" role="tabpanel" aria-labelledby="users-tab' . $fq[$x]['file_id'] . '">
                                                                    <div class="d-flex align-items-center justify-content-between p-0 col-md-12">
                                                                        <div class="input-group input-group-sm mb-3 my-3">
                                                                            <input type="text" class="form-control" aria-label="Seach for users" aria-describedby="inputGroup-sizing-sm" placeholder="Search for Users ...">
                                                                        </div>
                                                                    </div>
                                                                    <div class="table-responsive">
                                                                        <table class="table">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="align-middle w-100">
                                                                                        <div class="d-block">
                                                                                            <div class="fw-bold">Mark</div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="align-middle">
                                                                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                                                            <button type="button" class="btn btn-danger text-uppercase">
                                                                                                <i class="bi bi-trash-fill"></i>
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-primary text-uppercase">
                                                                                                <i class="bi bi-arrows-move"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-center my-3 p-0">
                                                            <input type="submit" class="btn btn-sm btn-primary text-uppercase mx-1" value="Edit Files"></input>
                                                            <a href="dashboard.php?id=' . urlencode($id) . '&task=files&deleteid=' . $fq[$x]['file_id'] . '" class="btn btn-sm btn-danger text-uppercase mx-1" onclick="return confirm(\' you want to delete?\');">Delete Files</a>
                                                            <a href="dashboard.php?id=' . urlencode($id) . '&task=downloadfiles&fileid=' . $fq[$x]['file_id'] . '" class="btn btn-sm btn-primary text-uppercase mx-1">Download Files</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
        }
    } else {
        echo '</div>
        </div><div class="row h-100 align-items-center">
                        <div class="container">
                            <div class="jumbotron text-center align-items-center">
                                <h5>No files Found!</h5>
                            </div>
                        </div>
                    </div>';
    }
    echo '</div>';
