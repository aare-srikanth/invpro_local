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
$document->setTitle("Invoices in Boxon Pobox Software");
defined('_JEXEC') or die;
$config = JFactory::getConfig();
$backend_url=$config->get('backend_url');

$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$CompanyId = Controlbox::getCompanyId();
if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
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
<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function(){
    
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    $joomla('a.btn-primary').click(function(e){
        e.preventDefault();
        var hostname=window.location.hostname;
        hostArr=hostname.split('.');
       
        service_url = "<?php echo $backend_url; ?>";
        
        //$joomla('.form-group').html('<div id="loading-image" ><img src="/components/com_userprofile/images/loader.gif"></div>');
        console.log($joomla(this).data('id'));
        
        var url=service_url+'/ASPX/Tx_Invoice_Receipt.aspx?bid='+$joomla(this).data('id')+'&type=Invoice&companyid=<?php echo $CompanyId; ?>';
        console.log("Url:::"+url);
        
        window.open(url, "_blank");
        
        //$joomla('.form-group').html('<iframe src="'+url+'" width="700px" height="500px"></iframe>');
    });
});
</script>

<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading"><?php echo $assArr['invoices'];?></div>
		<div class="panel-body">

	        
	    <?php  
            
            Controlbox::getInvoicedetailsListCsv($user);
            
        ?>
	        
	        <div class="row">
               <div class="col-sm-12 inventry-item">
                   <div class="col-sm-6">
                        <h3 class=""><strong><?php echo Jtext::_('COM_USERPROFILE_INV_SUB_TITLE');?></strong></h3>
                     </div>
                    <div class="col-sm-6 form-group text-right">
                        <a style="color:white;" href="<?php echo $assArr['eXPORT_CSV']; ?>/csvdata/invoice_list.csv" class="btn btn-primary csvDownload export-csv"><?php echo $assArr['eXPORT_CSV'];?></a>
                    </div>
                </div>
        </div>
	        
	        <div class="row">
	        	<div class="col-md-12">
	        		<table class="table table-bordered theme_table" id="j_table">
	        			<thead>
							<tr>
								<th><?php echo $assArr['sNo'];?></th>
								<th><?php echo $assArr['invoice#'];?></th>
								<th><?php echo $assArr['inhouse#'];?></th>
								<th><?php echo $assArr['generated_in']; ?></th>
								<th><?php echo $assArr['consignee'];?></th>
								<th><?php echo $assArr['invoice_Type'];?></th>
								<th><?php echo $assArr['view'];?></th>
								
							</tr>
	        			</thead>	<tbody>
<?php

    $ordersView= UserprofileHelpersUserprofile::getInvoicedetailsList($user);
    $arrOrders = json_decode($ordersView); 
    
    // echo '<pre>';
    // var_dump($ordersView);exit;
    
    $i=1;
    foreach($ordersView as $rg){
      echo '<tr><td>'.$i.'</td><td>'.$rg->InvoiceNumber.'</td><td>'.$rg->FormNumber.'</td><td>'.$rg->Date.'</td><td>'.$rg->ConsigneeName.'</td><td>'.$rg->InvoiceType.'</td><td class="action_btns"><a href="#" class="btn btn-primary" data-backdrop="static" data-keyboard="false" data-toggle="modal"  data-id="'.$rg->InvoiceNumber.'" ><i class="fa fa-eye"></i></a></td></tr>';
    $i++;
        
    }
?>						
						</tbody>
	        		</table>
	        	</div>
	        </div>
</div>
</div>
</div>



<!-- Modal -->
<form name="userprofileFormOne" id="userprofileFormOne" method="post" action="" enctype="multipart/form-data">
  <div id="inv_view" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <input type="button" data-dismiss="modal" value="X" class="btn-close1">
          <h4 class="modal-title"><strong><?php echo Jtext::_('COM_USERPROFILE_INV_MODAL_TITLE');?></strong></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group"></div>
            </div>
      </div>
    </div>
  </div>
</form>