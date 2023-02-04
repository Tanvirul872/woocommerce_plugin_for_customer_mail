<?php
wp_head();



function bbloomer_customer_list() {
    $customer_query = new WP_User_Query(
        array(
            'fields' => 'ID',
            'role' => 'customer',
        )
    );
    return $customer_query->get_results();
}


foreach ( bbloomer_customer_list() as $customer_id ) {
    $customer = new WC_Customer( $customer_id );

    $billing_email = $skip_duplicate = array();
    $customer_orders = get_posts( array(
        'post_type'   => 'shop_order',
        'post_status' =>'wc-processing','wc-completed', // change accordingly
//            'posts_per_page' => '3'
    ) );


    foreach( $customer_orders as $order){

        $order_id = $order->ID;

        if($order_id){

            $order = wc_get_order( $order_id );
            $bemail  = $order->get_billing_email();

            if(!in_array($bemail, $skip_duplicate)){
                $billing_email[] = $bemail;
                array_push($skip_duplicate,$bemail);
            }

        }
    }

       echo "<pre>";print_r($billing_email);


}


?>


    <h1> Customer Email </h1>

    <form action="http://localhost/decantworld/wp-admin/admin.php?page/edit" id="enquiry">


   
 <div class="form-row">
    <div class="form-group col-md-9">
      <label for="inputtitle">Title</label>
      <input type="text" id="inputtitle" class="form-control"  name="temp_title" placeholder="Title">
    </div>


    <div class="form-group col-md-9">
      <label for="inputdesc">Description</label>
      <?php
            $content   = '';
            $settings = array( 'textarea_name' => 'temp_desc' ); 
            wp_editor( $content, 4 , $settings );
        ?>
  </div>


  <div class="form-row">

    <div class="form-group col-md-9">
      <label for="inputState"> Customer Email </label>
    
      <select  class="js-example-basic-multiple" name="emails[]" id="enquiry" multiple="multiple">
            <?php foreach($billing_email as $billing_emails){ ?>
                <option value="<?php echo $billing_emails ; ?>"><?php echo $billing_emails ; ?></option>
            <?php  }  ?>
        </select>
    </div>
   
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
   
   
    </form>



<?php

?>


<?php
wp_footer();