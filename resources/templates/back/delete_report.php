<?php
require_once("../../config.php");

if (isset($_GET['delete'])){
    $id = $_GET['delete'];

    $query = query("DELETE FROM reports WHERE report_id = '{$id}'");
    confirm($query);
    set_message("Report Deleted");
    redirect("../../../public/admin/index.php?reports");
    
} else {
    redirect("../../../public/admin/index.php?reports");
}

?>