<?php
require_once("../../config.php");

if (isset($_GET['delete'])){
    $id = $_GET['delete'];

    $query = query("DELETE FROM users WHERE user_id = '{$id}'");
    confirm($query);
    set_message("User Deleted");
    redirect("../../../public/admin/index.php?users");
    
} else {
    redirect("../../../public/admin/index.php?users");
}

?>