<?php require_once("../resources/config.php"); ?>

<?php include(TEMPLATE_FRONT . DS . "header.php");?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!---Categories --->
            <?php include(TEMPLATE_FRONT . DS . "categories.php");?>

            <div class="col-md-9">

                <div class="row carousel-holder">

                    <div class="col-md-12">
                    <?php include(TEMPLATE_FRONT . DS . "slider.php");?>
                    </div>

                </div>
                   
                <div class="row">
                    <?php
                        get_product();
                    ?>
                    

                   

                    

                   
                </div>

            </div>

        </div>
        </div>
    <!-- /.container -->

        <?php include(TEMPLATE_FRONT . DS . "footer.php");?>
    
