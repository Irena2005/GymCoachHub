<?php
require '../components/config.php';
$page_title = "Login";
?>
<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>
<?php
if (isset($_SESSION['user_number'])) {
    header("Location: my-profile.php");
    exit();
}

?>

    <header>
        <p class="tagline"> 𝐍𝐨 𝐞𝐱𝐜𝐮𝐬𝐞𝐬, 𝐠𝐲𝐦 𝐧𝐨𝐰.</p>
    </header>

    <hr class="custom-line">

    <div class="center-container">
        <div class="welcome-box">
            <h1>Welcome</h1>
            <div class="buttons">
                <a href="login.php" class="btn login-btn">Log In</a>
                <a href="signup.php" class="btn signup-btn">Sign Up</a>
            </div>
        </div>
    </div>

<?php include '../components/footer.php'; ?>