<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
$document = JFactory::getDocument();
$document->setTitle("Order Process in Boxon Pobox Software");
defined('_JEXEC') or die;
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
if($_GET['r']==1){
    $app = JFactory::getApplication();
    $app->enqueueMessage("Suessfully payment done", 'success');
}
// get labels
    $lang=$session->get('lang_sel');
    $res=Controlbox::getlabels($lang);
    $assArr = [];
    
    foreach($res->data as $response){
    $assArr[$response->id]  = $response->text;
    
     }

 
?>
<?php include 'dasboard_navigation.php' ?>

<script type="text/javascript" src="<?php echo JUri::base(true); ?>/components/com_userprofile/js/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->

<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {

    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

});
</script>
<div class="container">
  <div class="main_panel persnl_panel">
    <div class="main_heading">My Packages</div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12 tab_view">
          <ul class="nav nav-tabs">
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocessalerts">My Pre-Alerts</a> </li>
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=orderprocess">Pending Shipments</a> </li>
            <li> <a class="active" href="index.php?option=com_userprofile&view=user&layout=hold">Hold Shipments</a> </li>
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=shiphistory">Shipments History</a> </li>
            <li> <a class="" href="index.php?option=com_userprofile&view=user&layout=cod">COD</a> </li>
          </ul>
        </div>
      </div>
      <div id="tabs2">
        <div class="row">
          <div class="col-sm-12">
            <h3 class="mx-1"><strong>Inventory items</strong></h3>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered theme_table" id="j_table" data-page-length='100'>
                <thead>
                  <tr>
                    <th class="action_btns">Actions#</th>
                    <th>Werehouse Receipt#</th>
                    <th>Item Description</th>
                    <th>Merchant Name</th>
                    <th>Quantity</th>
                    <!--<th>Shipping Quantity</th>-->
                    <th>Shipment Type</th>
                    <th>Source Hub</th>
                    <th>Destination</th>
                  </tr>
                </thead>
                <tbody>
    <?php
    $ordersPendingView= UserprofileHelpersUserprofile::getOrdersHoldList($user);
    $idf=1;
    foreach($ordersPendingView as $rg){

      if($rg->ItemQuantity>0){
      $volres=$rg->height*$rg->width*$rg->length*UserprofileHelpersUserprofile::getShippmentDetailsValues($rg->MeasureUnits,$rg->shipment_type,$rg->ServiceType,$rg->source,$rg->destination);
      if($rg->ItemImage==""){
          $sim=1;      
      }else{
          $sim=str_replace(":","#",$rg->ItemImage);      
      }
      echo '<tr>
      		<td class="action_btns"></td>
      		<td><input type="hidden" name="txtbiladdress" value="'.UserprofileHelpersUserprofile::getBindShipingAddress($user,$rg->BillFormNo).'">'.$rg->BillFormNo.'</td>
      		<td>'.$rg->ItemName.'</td>
      		<td>'.$rg->SupplierId.'</td>
      		<td><input type="hidden" class="form-control" readonly name="txtQty" value="'.$rg->Quantity.'"><input type="hidden" name="txtItemQty" value="'.$rg->ItemQuantity.'">'.$rg->ItemQuantity.'</td>
      		<!--<td></td>-->
      		<td>'.$rg->shipment_type.'</td>
      		<td><div id="sourcehub" style="display:none">'.$rg->source_hub.'</div>'.$rg->source_hub.'</td>
      		<td>'.$rg->destination_name.'</td>
      		<!--<td class="action_btns"><a class="ship_img" target="-blank" href="'.$rg->ItemImage.'" ><i class="fa fa-eye"></i></a></td>-->
      		</tr>';
      }
        $idf++;
    }
    ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
<div>
  

    
    
      </div> 
      </div>
    </div>
  </div>

</div>

