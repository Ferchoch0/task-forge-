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

<div class="income--plans-container">

        <div class="plans-content">

        </div>
        <button id="submitSelectedPlan">Elegir este plan</button>

    </div>
</div>

</body>

<script src="../View/src/assets/js/plansLoading.js"></script>

<?php

require_once 'footer.php';

?>