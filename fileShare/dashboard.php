<?php
    include_once 'includes/Header.php';

    //  check if session exists
    //  if not, then redirect to login page
    if(!$session->has('FS_CONFIG')) {
      header("Location: index.php");
    }

    if (isset($_GET['id'])) {
      $id = urldecode($_GET['id']);
      $user_data = json_decode($crypto->decrypt($id, SEC_KEY), true);
    }

    if(isset($_GET['task'])) {
      switch(strtolower($_GET['task'])) {
        case 'logout':
            //  Delete the session
            $session->destroy();
            header("Location: index.php");
            break;
      }
    }

    function getSelectedMenu($task) {
        return (strtolower($_GET['task']) == $task) ? 'accordion-active' : '';
    }

    echo '<body cz-shortcut-listen="true" class="h-100 flex-column d-flex">
      <header class="sticky-top shadow-sm">
        <div class="collapse" id="navbarToggleExternalContent">
          <div class="bg-dark py-3">
            <div class="accordion accordion-flush shadow-sm" id="accordionFlushExample">
              <div class="accordion-item px-3 py-4 bg-dark">
                <div>
                  <h5 class="mb-0 text-primary font-weight-bold">45.5 GB <span class="float-end text-secondary">50 GB</span></h5>
                  <div class="progress my-2">
                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <p class="mb-0"><span class="text-secondary">Used</span><span class="float-end text-primary">Upgrade</span></p>
                </div>
              </div>
              <div class="accordion-item bg-dark">
                <a href="dashboard.php?id=' . $id . '&task=dashboard" class="accordion-item text-decoration-none">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed remove-dropdown bg-dark text-white" type="button" data-bs-toggle="collapse">
                      <i class="bi bi-speedometer me-3"></i>
                      <span>Dashboard</span>
                    </button>
                  </h2>
                </a>
              </div>
              <div class="accordion-item bg-dark">
                <a href="dashboard.php?id=' . $id . '&task=files" class="accordion-item text-decoration-none">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed remove-dropdown bg-dark text-white" type="button" data-bs-toggle="collapse">
                      <i class="bi bi-folder me-3"></i>
                      <span>Files</span>
                    </button>
                  </h2>
                </a>
              </div>
              <div class="accordion-item bg-dark">
                <a href="dashboard.php?id=' . $id . '&task=shared" class="accordion-item text-decoration-none">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed remove-dropdown bg-dark text-white" type="button" data-bs-toggle="collapse">
                      <i class="bi bi-share me-3"></i>
                      <span>Shared</span>
                    </button>
                  </h2>
                </a>
              </div>
              <div class="accordion-item bg-dark">
                <a href="dashboard.php?id=' . $id . '&task=categories" class="accordion-item text-decoration-none">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed remove-dropdown bg-dark text-white" type="button" data-bs-toggle="collapse">
                      <i class="bi bi-journal-bookmark me-3"></i>
                      <span>Categories</span>
                    </button>
                  </h2>
                </a>
              </div>
              <div class="accordion-item bg-dark">
                <a href="dashboard.php?id=' . $id . '&task=users" class="accordion-item text-decoration-none">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark  text-white" type="button" data-bs-toggle="collapse">
                      <i class="bi bi-person-circle me-3"></i>
                      <span>Users</span>
                    </button>
                  </h2>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="navbar navbar-dark bg-dark shadow-sm py-3">
          <div class="container">
            <a href="#" class="navbar-brand d-flex align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gem me-2" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"></path>
              </svg>
              <strong>' . APP_NAME . '</strong>
            </a>
            <button class="navbar-toggler d-block d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-none d-lg-block">
              <ul class="navbar-nav">
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">' . $user_data['user_name'] .'</a>
                  <ul class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="navbarDarkDropdownMenuLink">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i><span>Settings</span></a></li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="dashboard.php?task=logout"><i class="bi bi-box-arrow-right me-1"></i> <span>Logout</span></a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </header>

      <main class="container flex-shrink-0">
        <div class="row">
          <div class="col-md-12 d-lg-flex my-4 my-lg-5">
            <div class="col-lg-3 d-none d-lg-block">
              <div class="accordion accordion-flush shadow-sm" id="accordionFlushExample">
                <div class="accordion-item px-3 py-4">
                  <div>
                    <h5 class="mb-0 text-primary font-weight-bold">45.5 GB <span class="float-end text-secondary">50 GB</span></h5>
                    <div class="progress my-2">
                      <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mb-0"><span class="text-secondary">Used</span><span class="float-end text-primary">Upgrade</span></p>
                  </div>
                </div>
                <div class="accordion-item">
                  <a href="dashboard.php?id=' . $id . '&task=dashboard" class="accordion-item text-decoration-none">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed remove-dropdown ' . getSelectedMenu('dashboard') . '" type="button" data-bs-toggle="collapse">
                        <i class="bi bi-speedometer me-3"></i>
                        <span>Dashboard</span>
                      </button>
                    </h2>
                  </a>
                </div>
                <div class="accordion-item">
                  <a href="dashboard.php?id=' . $id . '&task=files" class="accordion-item text-decoration-none">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed remove-dropdown ' . getSelectedMenu('files') . '" type="button" data-bs-toggle="collapse">
                        <i class="bi bi-folder me-3"></i>
                        <span>Files</span>
                      </button>
                    </h2>
                  </a>
                </div>
                <div class="accordion-item">
                  <a href="dashboard.php?id=' . $id . '&task=shared" class="accordion-item text-decoration-none">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed remove-dropdown ' . getSelectedMenu('shared') . '" type="button" data-bs-toggle="collapse">
                        <i class="bi bi-share me-3"></i>
                        <span>Shared</span>
                      </button>
                    </h2>
                  </a>
                </div>
                <div class="accordion-item">
                  <a href="dashboard.php?id=' . $id . '&task=categories" class="accordion-item text-decoration-none">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed remove-dropdown ' . getSelectedMenu('categories') . '" type="button" data-bs-toggle="collapse">
                        <i class="bi bi-journal-bookmark me-3"></i>
                        <span>Categories</span>
                      </button>
                    </h2>
                  </a>
                </div>
                <div class="accordion-item">
                  <a href="dashboard.php?id=' . $id . '&task=users" class="accordion-item text-decoration-none">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed remove-dropdown ' . getSelectedMenu('users') . '" type="button" data-bs-toggle="collapse">
                        <i class="bi bi-person-circle me-3"></i>
                        <span>Users</span>
                      </button>
                    </h2>
                  </a>
                </div>
              </div>
            </div>';
            
            switch(strtolower($_GET['task'])) {
                case 'dashboard':
                  include_once 'includes/views/Dashboard.php';
                  break;

                case 'files':
                  include_once 'includes/views/Files.php';
                  break;

                case 'shared':
                  include_once 'includes/views/Shared.php';
                  break;

                case 'categories':
                  include_once 'includes/views/Categories.php';
                  break;

                case 'users':
                  include_once 'includes/views/Users.php';
                  break;
            }

          '</div>
        </div>
      </main>
    </body>';

    include 'includes/Footer.php';
?>
