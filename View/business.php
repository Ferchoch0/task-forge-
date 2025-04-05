<?php
    require_once 'head.php';
?>

<link rel="stylesheet" href="src/assets/css/sesion.css">
<link rel="stylesheet" href="src/assets/css/global.css">
<link rel="stylesheet" href="src/assets/css/icon.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>


</head>
<body>

<?php
    require_once 'navSesion.php';
?>

<div class="income--business-container">
    <div >
        <form method="post" class="business-form" id="addBusinessForm">
            <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo "<div class='error-message'>" . $_SESSION['error'] . "</div>";
                    unset($_SESSION['error']);
                }
            ?>

            <input type="hidden" name="action" value="addBusiness">

            <div class="input-container">
                <input placeholder="" type="text" class="input" id="business_name" name="business_name" required>
                <div class="cut"></div>
                <label class="iLabel" for="business_name">Nombre</label>
            </div>

            <div class="input-container">
                <input placeholder="" type="text" class="input" id="address" name="address" required>
                <div class="cut"></div>
                <label class="iLabel" for="address">Dirección</label>
            </div>

            <select id="category" name="category" class="select category-client">

            </select>

            <button type="submit"> Siguiente </button>
        </form> 
    </div>
</div>

</body>

<script src="../View/src/assets/js/businessLoading.js"></script>

<?php

require_once 'footer.php';

?>