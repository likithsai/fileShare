<?php   
    include 'includes/Header.php';

    //  get last URL Param
    $lastURLParam = $utils->getLastURLParam($utils->currentPageURL());
    
    echo '
        <div class="container my-5">
            <h1>Download Files</h1>
        </div>
    ';
    
    include 'includes/Footer.php';
?>