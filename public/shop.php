<?php require_once("../resources/config.php"); ?>

<?php include(TEMPLATE_FRONT . DS . "header.php");?>

    <!-- Page Content -->
    <div class="container">

        <!-- Jumbotron Header -->
        <header>
            <h1>Shop</h1>
           
        </header>

        <hr>


        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">

          <?php 
          
          get_shop_product();
          ?>

         


            

        </div>
        <!-- /.row -->

        <?php include(TEMPLATE_FRONT . DS . "footer.php");?>
    