
<!-- Configuration-->

<?php require_once("../resources/config.php");

if(isset($_GET['add'])){
    $query = query("SELECT * FROM products WHERE product_id =" . escape_string($_GET['add']). " ");
    confirm($query);
    while ($row = fetch_array($query)){
        if ($row['product_quantity'] != $_SESSION['product_' .$_GET['add']] ){
            $_SESSION['product_' .$_GET['add']] += 1;
            redirect("checkout.php");
        }else {
            set_message("We only have ". $row['product_quantity']. " Available" );
    redirect("checkout.php");
}
        }
    } 
    if(isset($_GET['remove'])){
        $_SESSION['product_' .$_GET['remove']]--;
        if ( $_SESSION['product_' .$_GET['remove']] < 1){
            unset($_SESSION['item_total']);
       unset($_SESSION['item_quantity']);
            redirect("checkout.php");
        }
        redirect("checkout.php");
    }

    if(isset($_GET['delete'])){
        $_SESSION['product_' .$_GET['delete']] = '0';
       unset($_SESSION['item_total']);
       unset($_SESSION['item_quantity']);
            redirect("checkout.php");
    }

    function cart() {
        $total=0;
        $item_quantity = 0;
        foreach ($_SESSION as $name => $value) {
            if($value > 0){
                if(substr($name, 0, 8) == 'product_'){
                    $length = strlen($name - 8);
                    $id = substr($name, 8, $length); 

           
                $query = query("SELECT * FROM products WHERE product_id = {$id}");
                    confirm($query);
                    while ($row = fetch_array($query)){
                        $sub = $row['product_price'] * $value;
                         $item_quantity += $value;    
                        $product = <<<DELIMETER
                        <tr>
                            <td>{$row['product_title']}<br> <img src="../resources/uploads/{$row['product_image']}" width="100" ></td>
                            <td>&#36;{$row['product_price']}</td>
                            <td>{$value}</td>
                            <td>&#36;{$sub}</td>
                            
                            <td><a class="btn btn-warning" href="cart.php?remove={$row['product_id']}"> <span class="glyphicon glyphicon-minus"></span> </a> <a class="btn btn-primary" href="cart.php?add={$row['product_id']}"> <span class="glyphicon glyphicon-plus"></span> </a> <a class="btn btn-danger" href="cart.php?delete={$row['product_id']}"> <span class="glyphicon glyphicon-remove"></span> </a></td>
                          
                        </tr>
DELIMETER;
            echo $product;
            $_SESSION['item_total'] = $total += $sub;
                

            }
            $_SESSION['item_quantity'] = $item_quantity;
            

        }
    }
}
}


function process_transaction() {
    if(isset($_GET['tx'])){
        $tx = $_GET['tx'];
        $amt = $_GET['amt'];
        $status = $_GET['status'];
        $cc = $_GET['cc'];
    
   
    $total=0;
    $item_quantity = 0;
    foreach ($_SESSION as $name => $value) {
        if($value > 0){
            if(substr($name, 0, 8) == 'product_'){
                $send_query = query("INSERT INTO orders (order_tx,	order_status,	order_amount,	order_cc)   VALUES ('{$tx}','{$status}', '{$amt}','{$cc}')");
                $last_id = last_id();
                confirm($send_query);

                $length = strlen($name - 8);
                $id = substr($name, 8, $length); 

       
            $result = query("SELECT * FROM products WHERE product_id = {$id}");
                confirm($result);
                while ($row = fetch_array($result)){
                    $product_price = $row['product_price'];
                    $product_title = $row['product_title'];
                    $sub = $row['product_price'] * $value;
                     $item_quantity += $value;    
                     $query = query("INSERT INTO reports (product_id,product_price,order_id,product_quantity,product_title)   VALUES ('{$id}','{$product_price}', '{$last_id}','{$value}','{$product_title}')");
                     
                     confirm($query);
                  
        $total += $sub;
            

        }
        echo $item_quantity;
        

    }
}
}
session_destroy();
} else{
    redirect('index.php');
}
}
    





?>


