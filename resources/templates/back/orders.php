


                


        <div class="col-md-12">
<div class="row">
<h1 class="page-header">
   All Orders

</h1>
</div>

<div class="row">
<h2 class="bg-success"><?php display_message(); ?></h2>
<table class="table table-hover">
    <thead>

      <tr>
            <th>Order Id</th>
            <th>Transaction</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Currency</th>

      </tr>
    </thead>
    <tbody>
    <?php 
    display_orders();
    ?>
        

    </tbody>
</table>
</div>










