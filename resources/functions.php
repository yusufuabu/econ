<?php 

################################# HELPER FUNCTIONS ###########################################
function set_message($msg){
    if(!empty($msg)){
        $_SESSION['message'] = $msg;
    } else {
        $msg = "";
    }
}

function display_message(){
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    
   
}
function redirect($location) {
    header ("location: $location "); 
}

function query ($sql){
    global $conn;
    return mysqli_query($conn, $sql);
}

function confirm ($result){
    global $conn;
    if(!$result){
        die("QUERY FAILED " . mysqli_error($conn));
    }
}

function escape_string($string){
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

function fetch_array($result){
    return mysqli_fetch_array($result);
}

function last_id() {
    global $conn;
    return mysqli_insert_id($conn);
}

################################# FRONT END FUNCTIONS ###########################################
function get_product() {
    $query = "SELECT * FROM products WHERE product_quantity >= 1";
    $result = query($query);
    confirm($result);
    while ($row = fetch_array($result)){
     
   $products = <<<DELIMETER
   <div class="col-sm-4 col-lg-4 col-md-4">
       <div class="thumbnail">
           <a href="item.php?id={$row['product_id']}"><img src="../resources/uploads/{$row['product_image']}" alt=""></a>
           <div class="caption">
               <h4 class="pull-right">&#36;{$row['product_price']}</h4>
               <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
               </h4>
               <p>{$row['short_desc']}</p>
               <a class="btn btn-primary" href="cart.php?add={$row['product_id']}">Add To Cart</a>
           </div>
           
       </div>
   </div>

DELIMETER;

echo $products;
}
}

function get_category_product($id) {
    $query = "SELECT * FROM products WHERE 	product_category_id = '{$id}' && product_quantity >= 1";
    $result = query($query);
    confirm($result);
    while ($row = fetch_array($result)){
     
   $category_products = <<<DELIMETER


   <div class="col-md-3 col-sm-6 hero-feature">
   <div class="thumbnail">
   <a href="item.php?id={$row['product_id']}"><img src="../resources/uploads/{$row['product_image']}" alt=""></a>
       <div class="caption">
           <h3><a href="item.php?id={$row['product_id']}">{$row['product_title']}</h3></a>
           <p>{$row['short_desc']}</p>
           <p>
               <a href="cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
           </p>
       </div>
   </div>
</div>

DELIMETER;

echo $category_products;
}
}

function get_categories() {
    $query = "SELECT * FROM categories";
    $result = query($query);
    confirm($result);
    while ($row = fetch_array($result)){
        $category_links = <<<DELIMETER
      <a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>
DELIMETER;
echo $category_links;
    }
}

function get_shop_product() {
    $query = "SELECT * FROM products WHERE product_quantity >= 1";
    $result = query($query);
    confirm($result);
    while ($row = fetch_array($result)){
     
   $category_products = <<<DELIMETER


   <div class="col-md-3 col-sm-6 hero-feature">
   <div class="thumbnail">
   <a href="item.php?id={$row['product_id']}"><img src="../resources/uploads/{$row['product_image']}" alt=""></a>
       <div class="caption">
           <h3><a href="item.php?id={$row['product_id']}">{$row['product_title']}</h3></a>
           <p>{$row['short_desc']}</p>
           <p>
               <a href="item.php?id={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
           </p>
       </div>
   </div>
</div>

DELIMETER;

echo $category_products;
}
}

function login_user() {
    $username = $_POST['username'];
    $password =  $_POST['password'];
    $query = query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' ");
    confirm($query);
    if(mysqli_num_rows($query) == 0){
        set_message("Username or password is wrong");
        // redirect("login.php");
    } else {
        $_SESSION['username']= $username;
        redirect("admin");
    }
}

function send_message() {
    if(isset($_POST['submit'])){
        $to = "yusufuabu@yahoo.com";
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $headers = "FROM: {$name} {$email}";

        $result = mail($to, $subject, $message, $headers);
        if(!$result){
            echo "error";
        }else {
            echo "sent";
        }
    }
}

################################# BACK END FUNCTIONS ###########################################

function display_orders(){
    $query = query("SELECT * FROM orders");
    confirm($query);
    while ($row = fetch_array($query)) {
        $orders = <<<DELIMETER
        <tr>
            <td>{$row['order_id']}</td>
            <td>{$row['order_tx']}</td>
            <td>{$row['order_status']}/td>
            <td>{$row['order_amount']}</td>
           <td>{$row['order_cc']}</td>
           <td><a class="btn btn-danger" href="../../resources/templates/back/delete_order.php?delete={$row['order_id']}"> <span class="glyphicon glyphicon-remove"></span> </a></td>
        </tr>


DELIMETER;
echo $orders;


    }
}

################################# Admin View  Product ###########################################

function get_product_in_admin() {
    $query = "SELECT * FROM products";
    $result = query($query);
    confirm($result);
    while ($row = fetch_array($result)){
     $category = show_category_title($row['product_category_id']);
   $products = <<<DELIMETER
    <tr>
        <td>{$row['product_id']}</td>
        <td><a href="index.php?edit_product&id={$row['product_id']}">{$row['product_title']} <br>
            <img src="../../resources/uploads/{$row['product_image']}" width="100" alt=""> </a>
        </td>
        <td>$category</td>
        <td>{$row['product_price']}</td>
        <td>{$row['product_quantity']}</td>
        <td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?delete={$row['product_id']}"> <span class="glyphicon glyphicon-remove"></span> </a></td>
    </tr>

DELIMETER;

echo $products;
}
}

################################# Admin add Product ###########################################

function admin_add_product() {
    $dir = UPLOAD_DIR;
    if(is_dir($dir)){
        echo "yes dir";
    }
    if(isset($_POST['publish'])){
        $product_title        = escape_string($_POST['product_title']);
        $product_category_id  = escape_string($_POST['product_category_id']);
        $product_price        = escape_string($_POST['product_price']);
        $product_description  = escape_string($_POST['product_description']);
        $product_quantity     = escape_string($_POST['product_quantity']);
        $short_desc           = escape_string($_POST['short_desc']);
        $product_image        = time().($_FILES['file']['name']);
        $image_temp_location  = $_FILES['file']['tmp_name'];
      
        if(move_uploaded_file($image_temp_location , UPLOAD_DIR . DS . $product_image)) {
            $query = query("INSERT INTO products (product_title, product_category_id, product_price, product_description, product_quantity, short_desc, product_image) VALUES ('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$product_quantity}', '{$short_desc}', '{$product_image}')");
            $last_id = last_id(); 
            confirm($query);
        } else {
           echo "false";
        }

        
        // set_message("New product added");
        // redirect("index.php?products");
    }
}

function get_categories_for_add_product() {
    $query = "SELECT * FROM categories";
    $result = query($query);
    confirm($result);
    while ($row = fetch_array($result)){
        $category_options = <<<DELIMETER
      
      <option value="{$row['cat_id']}">{$row['cat_title']}</option>
DELIMETER;
echo $category_options;
    }
}

function show_category_title($product_category_id) {
$query = query("SELECT * FROM categories WHERE cat_id = {$product_category_id}");
confirm($query);
while ($row = fetch_array($query)){
    return $row['cat_title'];
}
}

################################# Update Product ###########################################

function admin_update_product() {
    if(isset($_POST['update'])){
        $id                   = escape_string($_GET['id']);
        $product_title        = escape_string($_POST['product_title']);
        $product_category_id  = escape_string($_POST['product_category_id']);
        $product_price        = escape_string($_POST['product_price']);
        $product_description  = escape_string($_POST['product_description']);
        $product_quantity     = escape_string($_POST['product_quantity']);
        $short_desc           = escape_string($_POST['short_desc']);
        $product_image        = time().($_FILES['file']['name']);
        $image_temp_location  = ($_FILES['file']['tmp_name']);

        move_uploaded_file($image_temp_location , UPLOAD_DIR . DS . $product_image);

        $query = "UPDATE  products SET ";
        $query .= "product_title         = '{$product_title}'        , ";
        $query .= "product_category_id   = '{$product_category_id}'  , ";
        $query .= "product_price         = '{$product_price}'        , ";
        $query .= "product_description   = '{$product_description}'  , ";
        $query .= "product_quantity      = '{$product_quantity}'     , ";
        $query .= "product_image         = '{$product_image}'        , ";
        $query .= "short_desc               = '{$short_desc}'             ";
        $query .= "WHERE product_id      = '{$id}'                     ";
        $send_update_query = query($query);
        confirm($send_update_query);
        set_message("Product has been Updated");
        redirect("index.php?products");
    }
}
################################# Show Categories In admin  ###########################################

function show_categories_in_admin() {
    $query = query('SELECT * FROM categories');
    confirm($query);

    while ($row = fetch_array($query)){
        $show_categories =<<<DELIMETER
        <tr>
            <td>{$row['cat_id']}</td>
            <td>{$row['cat_title']}</td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?delete={$row['cat_id']}"> <span class="glyphicon glyphicon-remove"></span> </a></td>
        </tr>
DELIMETER;
echo $show_categories;
    }
}

################################# Add Categories In admin  ###########################################
function add_categories_in_admin() {
    if(isset($_POST['submit'])){
        $cat_title = $_POST['cat_title'];
        $query = query("INSERT INTO categories (cat_title) VALUES ('$cat_title') ");
    confirm($query);
    set_message("Category Added");
    }
    

}

################################# Admin Users ###########################################

function display_users() {
    $query = query('SELECT * FROM users');
    confirm($query);

    while ($row = fetch_array($query)){
       $user_id = $row['user_id'];
       $username = $row['username'];
       $email = $row['email'];
        $users =<<<DELIMETER
        <tr>
            <td>$user_id</td>
            <td>$username</td>
            <td>$email</td>
            <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?delete={$row['user_id']}"> <span class="glyphicon glyphicon-remove"></span> </a></td>
        </tr>
DELIMETER;
echo $users;
    }
}

function add_user() {
    if (isset($_POST['add_user'])){
        $username = escape_string($_POST['username']);
        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);

        $query = query("INSERT INTO users(username,email,password) VALUES ('{$username}','{$email}','{$password}')");
        confirm($query);
        set_message("USER CREATED");
        redirect("index.php?users");
    }
}

function get_reports() {
    $query = "SELECT * FROM reports";
    $result = query($query);
    confirm($result);
    while ($row = fetch_array($result)){
    
   $products = <<<DELIMETER
    <tr>
        <td>{$row['report_id']}</td>
        <td>{$row['product_id']}</td>
        <td>{$row['order_id']}</td>
        <td>{$row['product_price']}</td>
        <td>{$row['product_title']}</td>
        <td>{$row['product_quantity']}</td>
        <td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?delete={$row['report_id']}"> <span class="glyphicon glyphicon-remove"></span> </a></td>
    </tr>

DELIMETER;

echo $products;
}
}
?>