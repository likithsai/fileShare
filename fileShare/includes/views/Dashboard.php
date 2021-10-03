<?php
    echo '<div class="col-lg-9 col-md-12 px-lg-4">
            <h3>Dashboard</h3>
            <div class="card p-0 col-12 my-3">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">System information</button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <span class="fw-bold">Server:</span>
                                    <span>' . explode('/', $_SERVER['SERVER_SOFTWARE'])[0] . '</span>
                                </li>
                                <li class="list-group-item">
                                    <span class="fw-bold">PHP Version:</span>
                                    <span>' . phpversion() . '</span>
                                </li>
                                <li class="list-group-item">
                                    <span class="fw-bold">Memory Limit:</span>
                                    <span>' . ini_get('memory_limit') . '</span>
                                </li>
                                    <li class="list-group-item">
                                    <span class="fw-bold">Max execution time:</span>
                                    <span>' . ini_get('max_execution_time') . '</span>
                                </li>
                                <li class="list-group-item">
                                    <span class="fw-bold">Max POST Size:</span>
                                    <span>' . ini_get('post_max_size') . '</span>
                                </li>
                                <li class="list-group-item">
                                    <span class="fw-bold">Max Upload Size:</span>
                                    <span>' . ini_get("upload_max_filesize") . '</span>
                                </li>
                                <li class="list-group-item">
                                    <span class="fw-bold">Database Driver:</span>
                                    <span>SQLite</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

?>
