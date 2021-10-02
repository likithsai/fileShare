<?php
include_once 'includes/Header.php';

//  check if session exists
if($session->has('FS_CONFIG')) {
    $id = $session->get('FS_CONFIG');
    header("Location: dashboard.php?id=" . urlencode($id) . "&task=dashboard");
}

if (isset($_POST['submit'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = $db->query("SELECT * FROM tbl_user WHERE user_name=? AND user_pass=?", array($username, $password));
    if (count($query) == 1) {
        $id = $crypto->encrypt(json_encode(array(
            'user_id' => $query[0]['user_id'], 
            'user_name' => $query[0]['user_name']
        )), SEC_KEY);
        $session->set('FS_CONFIG', $id);
        header("Location: dashboard.php?id=" . urlencode($id) . "&task=dashboard");
    }
}

echo '<body class="bg-light">
        <section class="container col-md-4 col-xs-12 my-5 py-5">
            <div class="row">
                <div class="col-md-12 text-center my-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#0d6efd" class="bi bi-telegram" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
                    </svg>
                    <h1>' . APP_NAME . '</h1>
                </div>
            </div>
            <form method="post">
                <div>
                    <div class="form-floating">
                        <input type="text" name="username" class="form-control border-bottom-0 rounded-0 rounded-top" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">
                            <span>Username</span>
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control rounded-0 rounded-bottom" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">
                            <span>Password</span>
                        </label>
                    </div>
                </div>
                <input type="submit" value="Submit" name="submit" class="btn btn-primary col-12 py-3"></input>
            </form>
            <p class="text-center my-3">Forgot your password? <a href="register">Set up a new one</a>.</p>
        </section>
    </body>';

include_once 'includes/Footer.php';
