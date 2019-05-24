<?php
require_once("../../config.php");

if (isset($_GET['delete'])){
    $id = $_GET['delete'];

    $query = query("DELETE FROM products WHERE product_id = '{$id}'");
    confirm($query);
    set_message("Product Deleted");
    redirect("../../../public/admin/index.php?products");
    
} else {
    redirect("../../../public/admin/index.php?products");
}

?>