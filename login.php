
<?php
require 'inc/bootstrap.php';

if (user()) go('dashboard.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check();

    $name = trim($_POST['name'] ?? '');
    $pw = $_POST['password'] ?? '';

    if ($name === '' || $pw === '') {
        flash('All fields are required.', true);
    } else {
      $u = q(
    'SELECT * FROM Users WHERE TRIM(LOWER(First_Name)) = TRIM(LOWER(?)) AND Is_Active = 1',
    [$name]
)->fetch();

        if ($u && $u['Locked_Until'] && strtotime($u['Locked_Until']) > time()) {
            flash('Account locked for 15 minutes after 5 failed attempts.', true);
        } elseif ($u && password_verify($pw, $u['Password_Hash'])) {
            session_regenerate_id(true);

            q('UPDATE Users SET Failed_Attempts=0, Locked_Until=NULL WHERE User_ID=?', [$u['User_ID']]);

            $_SESSION['u'] = [
                'id' => $u['User_ID'],
                'name' => $u['First_Name'] . ' ' . $u['Last_Name'],
                'role' => $u['Role']
            ];

            $_SESSION['last'] = time();
            audit('login');
            go('dashboard.php');
        } else {
            if ($u) {
                q(
                    'UPDATE Users SET Failed_Attempts=Failed_Attempts+1, Locked_Until=IF(Failed_Attempts>=5,DATE_ADD(NOW(),INTERVAL 15 MINUTE),NULL) WHERE User_ID=?',
                    [$u['User_ID']]
                );
            }

            flash('Invalid name or password.', true);
        }
    }

    go('login.php');
}

head('Login');
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h4>Sign in</h4>
                <form method="post">
                    <?=tok()?>

                    <label class="form-label mt-2">Name</label>
                    <input class="form-control" name="name" required>

                    <label class="form-label mt-2">Password</label>
                    <input class="form-control" type="password" name="password" required>

                    <button class="btn btn-primary mt-3">Sign in</button>
                    <a href="register.php" class="btn btn-link mt-3">Create an account</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php foot(); ?>
