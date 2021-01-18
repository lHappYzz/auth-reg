<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Authorization&Registration</title>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <?php if($_SESSION['userId']): ?>
            <h1>
                <?php
                    echo 'The authorized user currently has the following email address: ' . $_SESSION['userEmail'];
                ?>
            </h1>
        <?php else: ?>
            <h1>There is currently no an authorized user</h1>
        <?php endif; ?>
    </div>
    <?php if ($_SESSION['errorMessage']): ?>
        <div class="alert">
            <p>
                <?php
                    echo $_SESSION['errorMessage'];
                    unset($_SESSION['errorMessage']);
                ?>
            </p>
        </div>
    <?php endif; ?>
    <div class="body">
        <?php if ($_SESSION['userId']): ?>
            <form action="server.php" method="post">
                <button type="submit" name="logOutBtn">Log out</button>
                <button type="submit" name="userExportExcelBtn">Export users</button>
            </form>
        <?php else: ?>
            <form action="server.php" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="registerBtn">Register</button>
                <button type="submit" name="loginBtn">Login</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="footer"></div>
</div>
</body>
</html>