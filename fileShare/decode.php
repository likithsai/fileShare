<?php   
    include 'includes/Header.php';

    //  get last URL Param
    $lastURLParam = $utils->getLastURLParam($utils->currentPageURL());
    
    echo '
    <div class="d-flex vh-100 text-center text-dark bg-light">
        <div class="container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="mb-5 align-items-center d-flex justify-content-between">
            <div class="d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gem me-2" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"></path>
                </svg>
                <strong class="fs-3">' . APP_NAME . '</strong>
            </div>
            <div>
            <button type="button" class="btn btn-primary text-uppercase btn-sm">Download Files</button>
            </div>
        </header>
        
        <main>
            <div class="card text-center shadow-sm">
                <div class="card-header">
                    <strong class="fs-3">Files</strong>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </main>
        
        <footer class="mt-auto text-dark-50">
            <p>Cover template for <a href="https://getbootstrap.com/" class="text-dark">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-dark">@mdo</a>.</p>
        </footer>
        </div>
    </div>
    ';
    
    include 'includes/Footer.php';
