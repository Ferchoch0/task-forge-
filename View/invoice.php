<?php
session_start();
require_once 'head.php';
require_once '../Model/userModel.php';
require_once '../Model/stockModel.php';
require_once '../Model/connection.php';



if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$stockModel = new StockModel($conn);
$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];

if (!$userModel->isEmailVerified($userId)) {
  header("Location: logout.php");
  exit();
}

$transactions = $stockModel->getUserTransaction($userId);
$username = $_SESSION['username'];


?>


<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">
<link rel="stylesheet" href="src/assets/css/stock.css">
<link rel="stylesheet" href="src/assets/css/cash.css">




</head>


<body>


<?php require_once 'nav.php'; ?>
<div class="dashboard-container">
    <div class="dashboard-container--ajust">

    </div>
</div>
</body>

<?php

    require_once 'footer.php';

?>