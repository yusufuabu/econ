<?php
require_once("../../config.php");

if (isset($_GET['delete'])){
    $id = $_GET['delete'];

    $query = query("DELETE FROM orders WHERE order_id = '{$id}'");
    confirm($query);
    set_message("Order Deleted");
    redirect("../../../public/admin/index.php?orders");
    
} else {
    redirect("../../../public/admin/index.php?orders");
}

?>