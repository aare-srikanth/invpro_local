<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Userprofile
 * @author     madan <madanchunchu@gmail.com>
 * @copyright  2018 madan
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->setTitle("Boxon");
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');

require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
require_once JPATH_ROOT.'/components/com_userprofile/helpers/userprofile.php';

$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$domainEmail = $domainDetails[0]->PrimaryEmail;
$PrimaryPhone = $domainDetails[0]->PrimaryPhone;
$domainName =  $domainDetails[0]->Domain;

$clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
$clientConf = json_decode($clientConfigObj, true);
$clients = $clientConf['ClientList'];

if(!$user){
    $app =& JFactory::getApplication();
    $app->redirect('index.php?option=com_register&view=login');
}
$res=JRequest::getVar('res');
if($res==""){
    $jinput = JFactory::getApplication()->input;
    $stat=$jinput->get('invoice','', 'filter');
    $status=explode(":",$stat);
}else{
    $status=explode(":",base64_decode($res));
}


//UserprofileHelpersUserprofile::getInvoicedetailsId($user);

    $pay=JRequest::getVar('pay');
    $invoiceCountBef =JRequest::getVar('invc');
    $invoiceNo =JRequest::getVar('invoice');
    $page =JRequest::getVar('page');
    $invoiceType = JRequest::getVar('invoiceType');
    $invoiceCountRes = UserprofileHelpersUserprofile::GetInvoicesCount($user,$invoiceType);
    
    if($invoiceCountRes->Response){
        $invoiceCountAfter = $invoiceCountRes->Data;
    }
    
    
if($pay){
    
    if($page != "cod"){
        $status[0]= UserprofileHelpersUserprofile::getInvoicedetailsId($user);
    }else{
        $status[0] = $invoiceNo;
    }
    
}

?>
<?php include 'dasboard_navigation.php' ?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- 
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
-->  
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function() {
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var page = urlParams.get('page');
    
      $joomla(".order-info").hide();
      $joomla(".pp_load").show();
    
    var invcb = <?php echo $invoiceCountBef;  ?>;
    var invca = <?php echo $invoiceCountAfter;  ?>;
    
    if(invcb == invca){
       setTimeout(function(){ 
        window.location.reload();
       }, 5000);
    }else{
         $joomla(".order-info").show();
         $joomla(".pp_load").hide();
    }
    

});
</script>
<style type="text/css">
	/*payment success start*/
.paymentsuceesspage { margin-top: 3%; border: 1px solid #dedede; padding: 0;border-radius: 10px;background:#fff;margin-bottom:20px}
.paymentlogo { padding: 10px;border-bottom: 1px solid #dedede; text-align: center;}
.order-info {padding: 5px 30px;}
.order-info h3 {border-bottom: 1px solid #dedede;padding-bottom: 10px;}
label.ord {color: #2cace2;}
.paymen-btn a {color: #ffff;}
.goto-dash { text-align: right; margin-bottom: 15px;}
h2.box-co {color: #23bbed;font-size: 24px;}
/*payment success end*/

	</style>
<?php 
foreach($clients as $client){
        if(strtolower($client['Domain']) == strtolower($domainName) ){
            $Default_support_number = $client['Default_support_number'];
            $Dialscode = $client['Default_destcountry_dialcode'];
        }
}

?>	
	
	
<div class="container">
	<div class="main_panel persnl_panel">
		<div class="main_heading"><?php echo Jtext::_('ORDER_VIEW');?></div>
		<div class="panel-body">
			<div class="row">
			    
			 <!-- <div id="loader" style="display:none">	
			 <img src='<?php echo JURI::base();  ?>/components/com_userprofile/images/loader.gif'>
			 <p>We are processing your request. Please Wait....</p>
			</div> -->
			  <!-- Paypal Loader -->
			  <div class="pp_load" style="display:none;">
                 <span><i class="fa fa-spinner fa-spin" style="font-size:40px"></i> <br> Please wait we are processing your request.. </span>
              </div>
			 <!-- /Paypal Loader -->
			<?php 
                    $serverName = explode(".",$_SERVER['SERVER_NAME']);
                    $domainName = explode("-",$serverName[0]);
                ?>
			
				<div class="order-info">
            <h3> <?php echo Jtext::_('ORDER_CONFORMATION');?><Label ID="lblord" class="ord"><?php echo $status[0];?></Label> </h3>
            <h3> <?php echo strtoupper($domainName[0])." ".Jtext::_('ORDER_CONFORMATION_TANS'); ?> <Label ID="lblordno" class="ord"><?php echo $status[0];?></Label>  </h3>

            <h6> <?php echo Jtext::_('ORDER_CONFORMATION_HI');?> <Label ID="lblname"></Label> </h6>
            <p>
               <?php echo Jtext::_('ORDER_CONFORMATION_DESC');?>
            </p>
           
            <div id="an">
                <p>
                    <?php echo Jtext::_('ORDER_CONFORMATION_DESC1');?> 
                </p>
                
               
                <p><?php echo Jtext::_('ORDER_CONFORMATION_DESC2');?>  <Label ID="lblord" class="ord"><?php if($Default_support_number){  echo $Dialscode." ".$PrimaryPhone; }else{ echo $domainEmail; } ?></Label></p>
            </div>
             <p><?php echo Jtext::_('ORDER_CONFORMATION_SOON');?></p>
            <p></p>
            <h2 class="box-co"><?php echo strtoupper($domainName[0]); ?></h2>
            
            <div class="goto-dash">
                <a href="/index.php?option=com_userprofile&view=user"><input type="button" class="btn btn-primary"  value="<?php echo Jtext::_('ORDER_GOTO_DASHBOARD');?>"></a>
            </div>
        
        
        </div>
    
				
				
				
			</div>
		</div>
		</div>
	</div>
</div>