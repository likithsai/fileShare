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


    //  edit files
    if(isset($_POST['edit_files'])) {
        $ftitle = $_POST['files_title'];
        $fdesc = $_POST['files_desc'];
        $fexpiryDate = $_POST['files_expiry_date'];
        $fsharedUsers = filter_input(INPUT_POST, 'files_shared_users', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

        $db->update("UPDATE tbl_files SET file_title = ?, file_description = ?, file_expirydate=?, file_shareduser = ?", array($ftitle, $fdesc, $fexpiryDate, json_encode($fsharedUsers)));
        header('location: dashboard.php?id=' . urlencode($id) . '&task=files');
    }

    //  Add files to the category
    if(isset($_POST['file_addToFileCategory'])) {
        
    }

    if (isset($_GET['task']) && isset($_GET['deleteid'])) {
        $deleteStatus = true;
        if (strtolower($_GET['task']) == 'files') {
            $files = $db->query("SELECT file_lists FROM tbl_files WHERE file_id = ?", array($_GET['deleteid']));
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

            //  redirect to files tab
            header('location: dashboard.php?id=' . urlencode($id) . '&task=files');
        }
    }

    // upload file ajax
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (isset($_POST['file_submit'])) {
            $user_data = json_decode($crypto->decrypt($_GET['id'], SEC_KEY), true);
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
                    array_push($filelist, $file);
                }
            }

            $filelinkid = base_convert(rand(00000, 99999), 20, 36);
            $db->insert("INSERT INTO tbl_files (file_title, file_description, file_lists, file_share, file_linkid, file_userid, file_expirydate) VALUES(?, ?, ?, ?, ?, ?, ?)", array($filetitle, $filedesc, json_encode($filelist), 0, $filelinkid, $fileuserid, $fileexpirydate));
            header('location: dashboard.php?id=' . urlencode($id) . '&task=files');
        }
    }

    echo '
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Category</h5>
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
                                <button type="submit" name="file_submit" id="file_upload" class="btn btn-primary btn-sm text-uppercase">Upload Files</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="fileUploadModal" tabindex="-1" aria-labelledby="fileUploadModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Files</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <form method="post" action="dashboard.php?id=' . urlencode($id) . '&task=files" enctype="multipart/form-data">
                            <div class="my-3">
                                <div class="row">
                                    <ol class="uploadList overflow-auto">
                                        <li class="d-flex align-items-center justify-content-between my-1">
                                            <div><input type="file" name="addFiles[]" /></div>
                                            <button type="button" class="btn btn-danger btn-sm text-uppercase" class="deleteMultiUploadFile">Delete</button>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <button id="addMultiUploadFiles" name="file_submit" class="btn btn-primary btn-sm text-uppercase">Add Files</button>
                                <button type="submit" name="file_addToFileCategory" class="btn btn-primary btn-sm text-uppercase">Upload Files</button>
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
            echo '<div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button collapsed shadow-sm remove-dropdown" type="button" data-bs-toggle="collapse" data-bs-target="#fid' . $fq[$x]['file_id'] . '" aria-expanded="false" aria-controls="fid' . $fq[$x]['file_id'] . '">
                            <div class="d-block lh-base w-100">
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fs-5 fw-bold">' . $fq[$x]['file_title'] . '</span>
                                        <div class="badge bg-success mt-2 ms-3">
                                            <span>Active</span>
                                        </div>
                                    </div>
                                    <small class="me-3 text-muted d-block">' . $fq[$x]['file_description'] . '</small>
                                </div>
                                <div>
                                    <div class="badge bg-primary mt-2">
                                        <span>Total File Size: ' . $utils->formatSizeUnits(getFileDetails($fq[$x]['file_lists'])[0]['filesize']) . '</span>
                                    </div>
                                    <div class="badge bg-primary mt-2">
                                        <span>Total File Count: ' . getFileDetails($fq[$x]['file_lists'])[0]['filecount'] . '</span>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="fid' . $fq[$x]['file_id'] . '" class="accordion-collapse collapse shadow-sm" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body p-2">
                            <div class="row my-2 mx-1">
                                <form method="post" action="" enctype="multipart/form-data" class="px-0 px-lg-2">
                                    <ul class="nav nav-tabs flex-nowrap text-center d-flex justify-content-between justify-content-lg-start" id="myTab" role="tablist">
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
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="users-tab' . $fq[$x]['file_id'] . '" data-bs-toggle="tab" data-bs-target="#users' . $fq[$x]['file_id'] . '" type="button" role="tab" aria-controls="users' . $fq[$x]['file_id'] . '" aria-selected="false">
                                                <i class="bi bi-people me-1"></i>
                                                <span>Users</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content bg-light shadow-sm border rounded-bottom" id="myTabContent">
                                        <div class="tab-pane fade show active my-3 mx-2" id="home' . $fq[$x]['file_id'] . '" role="tabpanel" aria-labelledby="home-tab' . $fq[$x]['file_id'] . '">
                                            <div class="mb-3">
                                                <div for="formGroupExampleInput" class="form-label fw-bold">
                                                    <i class="bi bi-input-cursor-text me-1"></i>
                                                    <span>Title</span>
                                                </div>
                                                <input type="text" name="files_title" class="form-control" id="formGroupExampleInput" placeholder="Enter Title" value="' . $fq[$x]['file_title'] . '">
                                            </div>
                                            <div class="mb-3">
                                                <div for="formGroupExampleInput" class="form-label fw-bold">
                                                    <i class="bi bi-justify-left me-1"></i>
                                                    <span>Description</span>
                                                </div>
                                                <textarea name="files_desc" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter Description" rows="2">' . $fq[$x]['file_description'] . '</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <div for="formGroupExampleInput" class="form-label fw-bold">
                                                    <i class="bi bi-calendar2-check me-1"></i>
                                                    <span>Expiry Date</span>
                                                </div>
                                                <input name="files_expiry_date" id="startDate" class="form-control" type="date" value="' . date('Y-m-d', strtotime($fq[$x]['file_expirydate'])) . '">
                                            </div>                                                                        
                                        </div>
                                        <div class="tab-pane fade mb-3" id="files' . $fq[$x]['file_id'] . '" role="tabpanel" aria-labelledby="files-tab' . $fq[$x]['file_id'] . '">
                                            <div class="alert alert-warning shadow-sm border-bottom rounded-0" role="alert">
                                                <div class="d-lg-flex align-item-center justify-content-lg-between">
                                                    <div class="mb-2">
                                                        <div for="formGroupExampleInput" class="form-label fw-bold m-0">
                                                            <i class="bi bi-journals me-1"></i>
                                                            <span>Files</span>
                                                        </div>
                                                        <p class="text-muted m-0">Shows all the files available in this category</p>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-primary btn-sm text-uppercase" data-bs-toggle="modal" data-bs-target="#fileUploadModal">Upload Files</button>
                                                    </div>
                                                </div>
                                                <div class="p-0 col-md-12">
                                                    <div class="input-group my-3 mb-0 d-flex justify-content-between shadow-sm">
                                                        <input type="text" class="form-control file-search" data-table-target="files' . $fq[$x]['file_id'] . '" aria-label="Search for Users" aria-describedby="inputGroup-sizing-sm" placeholder="Search for Files ...">
                                                    </div>
                                                </div>       
                                            </div>
                                            <div class="m-2">
                                                <div class="table-responsive">
                                                    <table id="files' . $fq[$x]['file_id'] . '" class="table">
                                                        <tbody>';
                                                        foreach (json_decode($fq[$x]['file_lists']) as $item) {
                                                            $itemArray = (array) $item;

                                                            echo '<tr>
                                                                    <td class="align-middle w-90">
                                                                        <div class="d-block">
                                                                            <div class="fw-bold"><a href="dashboard.php?id=' . urlencode($id) . '&task=downloadfiles&fid=' . $fq[$x]['file_id'] . '">' . $itemArray['filename'] . '</a></div>
                                                                            <small class="text-muted">SHA : ' . sha1_file($itemArray['location'], false) . '</small><br />
                                                                            <small class="text-muted">
                                                                                <div class="badge bg-primary">
                                                                                    <span>Filesize: ' . $utils->formatSizeUnits($itemArray['filesize']) . '</span>
                                                                                </div>
                                                                                <div class="badge bg-primary">
                                                                                    <span>Total Downloads: 100</span>
                                                                                </div>
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
                                        </div>
                                        <div class="tab-pane fade mb-3" id="users' . $fq[$x]['file_id'] . '" role="tabpanel" aria-labelledby="users-tab' . $fq[$x]['file_id'] . '">
                                            <div class="mb-3">
                                                <div class="alert alert-warning d-lg-flex align-item-center justify-content-lg-between shadow-sm border-bottom rounded-0" role="alert">
                                                    <div>
                                                        <div for="formGroupExampleInput" class="form-label fw-bold m-0">
                                                            <i class="bi bi-people me-1"></i>
                                                            <span>Users</span>
                                                        </div>
                                                        <p class="text-muted m-0">Shows the users to which the files are shared.</p>
                                                    </div>
                                                </div>
                                                <div class="m-3">
                                                    <select name="files_shared_users[]" class="form-select js-example-basic-multiple" multiple aria-label="multiple select example" multiple>
                                                        <option value="One">One</option>
                                                        <option value="Two">Two</option>
                                                        <option value="Three">Three</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center my-3 p-0">
                                        <input type="submit" name="edit_files" class="btn btn-sm btn-primary text-uppercase mx-1" value="Save"></input>
                                        <a href="dashboard.php?id=' . urlencode($id) . '&task=files&deleteid=' . $fq[$x]['file_id'] . '" class="btn btn-sm btn-danger text-uppercase mx-1" onclick="return confirm(\' you want to delete?\');">Delete</a>
                                        <a href="dashboard.php?id=' . urlencode($id) . '&task=downloadfiles&fileid=' . $fq[$x]['file_id'] . '" class="btn btn-sm btn-primary text-uppercase mx-1">Download</a>
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
                    <h5>No files Found!</h5>
                </div>
            </div>
        </div>';
    }
    echo '</div>';
?>
