<?php

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

    $fq = $db->query("SELECT * FROM tbl_files WHERE file_userid=? ORDER BY file_createddate DESC", array($user_data['user_id']));

    if (isset($_POST['file_submit'])) {
        $filetitle = $_POST['files_title'];
        $filedesc = $_POST['files_desc'];
        $fileshare = 0;
        $filelist = array();
        $fileuserid = $user_data['user_id'];

        foreach ($_FILES['addFiles']['tmp_name'] as $key => $value) {
            $tmpname = $_FILES['addFiles']['tmp_name'][$key];
            $filepath = FILE_UPLOAD_PATH . hash("sha256", $_FILES['addFiles']['name'][$key], false);

            if ($compress->compress($tmpname, $filepath)) {
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
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="bi bi-plus"></i>
                        <span class="text-uppercase">Add Files</span>
                    </button>
                </div>
                
                ';
    if (count($fq) >= 1) {
        for ($x = 0; $x < count($fq); $x++) {
            echo '<div class="row pb-1">
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#fid' . $fq[$x]['file_id'] . '" aria-expanded="false" aria-controls="fid' . $fq[$x]['file_id'] . '">
                                                <div class="d-block w-auto lh-base">
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
                                            </button>
                                        </h2>
                                        <div id="fid' . $fq[$x]['file_id'] . '" class="accordion-collapse collapse shadow-sm" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    
                                                </div>
                                                <div class="mt-3">
                                                    <a href="dashboard.php?id=' . urlencode($id) . '&task=files&deleteid=' . $fq[$x]['file_id'] . '" class="btn btn-sm btn-danger text-uppercase" onclick="return confirm(\' you want to delete?\');">Delete</a>
                                                    <button type="button" class="btn btn-sm btn-primary text-uppercase">Download Files</button>
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
