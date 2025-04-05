<?php
session_start();
require_once '../Model/connection.php';
require_once '../Model/businessModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

$businessModel = new BusinessModel($conn);
$userId = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST['action'];

    switch($action) {
        case 'addBusiness':
            try{
                if (isset($_POST['business_name'], $_POST['address'], $_POST['category'])) {
                    $businessName = $_POST['business_name'];
                    $address = $_POST['address'];
                    $categoryId = $_POST['category'];
            
                
                    $businessId = $businessModel->addBusiness($businessName, $userId, $categoryId);

                    if($businessId){
                        $_SESSION['business_id'] = $businessId;
                        $locals = $businessModel->addLocation($businessName, $address, $businessId);
                        if($locals){
                            echo json_encode([
                                "status" => "success", 
                                "message" => "Negocio creado", 
                                "redirect" => "plans.php"]);
                        }
                    } else {
                        echo json_encode([
                            "status" => "error",
                            "message" => "Error al intentar insertar Empresa."
                        ]);
                    }
            
                    
                } else  {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Datos incompletos para procesar la compra."
                    ]);
                    exit;
                }
            }catch (Exception $e) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al registrar la venta: " . $e->getMessage()
                ]);
            }
            break;

        case 'addPlans':
            try{
                if (isset($_POST['plan_id'])) {
                    $planId = $_POST['plan_id'];
                    $status = "pending";
                    $businessId = $_SESSION['business_id'];


                    $result = $businessModel->addSubscription($status, $businessId, $planId);


                    if($result){
                        echo json_encode([
                            "status" => "success", 
                            "message" => "Plan agregado correctamente", 
                            "redirect" => "../View/pay.php"]);
                    } else {
                        echo json_encode([
                            "status" => "error",
                            "message" => "Error al intentar seleccionar el plan."
                        ]);
                    }

                    
                } else  {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Datos incompletos para procesar la seleccion."
                    ]);
                    exit;
                }
            }catch (Exception $e) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al registrar la venta: " . $e->getMessage()
                ]);
            }
            break;

        default:
            throw new Exception("Acción no válida");
    }
}


if ($_SERVER["REQUEST_METHOD"] === "GET"){

    $action = $_GET['action'];

    

    switch($action) {
        case 'getCategories':
            $categories = $businessModel->getCategories();
            echo "<option value='' disabled selected>Seleccionar</option>";
            foreach ($categories as $category) {
                echo "<option value='{$category['category_id']}'>{$category['name']}</option>";
            }
            break;

        case 'getPlans':
            $plans = $businessModel->getPlans();
            foreach ($plans as $plan) {
                echo "<form method='post' class='business-form-2'>
                    <input type='hidden' name='price' value='{$plan['price']}'>
                    <input type='hidden' name='max_local' value='{$plan['max_local']}'>
                    <input type='hidden' name='max_cash' value='{$plan['max_cash']}'>
                    <input type='hidden' name='plan_id' value='{$plan['plan_id']}'>
                    <input type='checkbox' name='' id='' class='business-select--checkbox' style='display: none;'>                

                    <div class='business-plans'>
                        <div class='business-title--container'>
                            <span>{$plan['name']}</span>
                        </div>
            
                        <ul class='business-plans--container'>
                            <li class='business-plans--list'>
                                <div class='businnes-plans--attributes'>
                                    <div class='default-attributes--title'>Precio Mensual</div>
                                    <div class='default-attributes--data'>{$plan['price']}</div>
                                </div>
                            </li>

                            <li class='business-plans--list'>
                                <div class='businnes-plans--attributes'>
                                    <div class='default-attributes--title'>Nro. Maximo de Locales</div>
                                    <div class='default-attributes--data'>{$plan['max_local']}</div>
                                </div>
                            </li>

                            <li class='business-plans--list'>
                                <div class='businnes-plans--attributes'>
                                    <div class='default-attributes--title'>Nro. Maximo de Cajas</div>
                                    <div class='default-attributes--data'>{$plan['max_cash']}</div>
                                </div>
                            </li>
                        </ul>

                        <div class='business-select--container'>
                        </div>
                    </div>

                    </form>";
                    }
            break;

        default:
            throw new Exception("Acción no válida");
        }    
}
?>

