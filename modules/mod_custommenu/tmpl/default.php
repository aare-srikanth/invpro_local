<?php

/**

 * Joomla! 3.8 module mod_custommenu

 * @author Iblesoft

 * @package Joomla

 * @subpackage Custommenu

 * Module helper

 * @license GNU/GPL

 * This module structure created by madan chunchu.

 */

// no direct access


defined('_JEXEC') or die('Restricted access');   
$session = JFactory::getSession();
$user=$session->get('user_casillero_id');
$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$enabledLangsList = ModProjectrequestformHelper::getLanguageList($domainDetails[0]->CompanyId);
$domainname = $domainDetails[0]->Domain;
$dynamicpages= ModProjectrequestformHelper::dynamicpages($domainDetails[0]->CompanyId);
$alerts= ModProjectrequestformHelper::getUsersorderscount($user);



$dynpage=array();
   foreach($dynamicpages as $dpage){
      $dynpage[$dpage->PageId]=array($dpage->PageDescription,$dpage->PageStatus,$dpage->PageId);
   }
   
//   echo '<pre>';
//   var_dump($dynpage);
//   exit;


// get domain details start

//   $clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
//   $clientConf = json_decode($clientConfigObj, true);
//   $clients = $clientConf['ClientList'];

    // foreach($clients as $client){ 
    //     if(strtolower($client['Domain']) == strtolower($domainname) ){ 
    //         $enabledLangs = $client['Enabled_languages'];
    //     }
    // }
    $enabledLangs = "";
    $langStr = '';
    foreach($enabledLangsList as $language){ 
        $langStr .= $language->LanguageId.",";
    }
    $enabledLangs = rtrim($langStr,",");
    
   // var_dump($enabledLangsList);exit;
    

if(strpos($_SERVER['REQUEST_URI'], '/index.php/') !== false){
    $strplace = strpos($_SERVER['REQUEST_URI'], '/index.php/');
    $langplace = $strplace + 11;
    $language = substr($_SERVER['REQUEST_URI'],$langplace,2);
    $session->set('lang_sel',$language);
}


if($session->get('user_casillero_id')){
    

?>

<div class="navbar-collapse navbar-right custommenu" id="theme_nav">
  <div class="moduletable_menu">
    <ul class="nav menu nav navbar-nav custmenu-in">
        
        <?php 
        
         $app	= JFactory::getApplication();
         $params = $app->getParams();
         $vars=json_decode($params,true);
        

//var_dump($vars['page_title']);exit;

        if($vars['page_title'] == "Register" || $vars['page_title'] == "Registrarse" || $vars['page_title'] == "Registracija"){
            $dashboard = Jtext::_('COM_REGISTER_DASHBOARD');
            $myAcc = Jtext::_('COM_REGISTER_MYACCOUNT');
            $changePass = Jtext::_('COM_REGISTER_CHANGE_PASSWORD');
            $logout = Jtext::_('COM_REGISTER_LOGOUT');
            $tickets = Jtext::_('COM_REGISTER_TICKETS');
        }else{
            $dashboard = Jtext::_('COM_USERPROFILE_DASHBOARD');
            $myAcc = Jtext::_('COM_USERPROFILE_MYACCOUNT');
            $changePass = Jtext::_('COM_USERPROFILE_CHANGE_PASSWORD');
            $logout = Jtext::_('COM_USERPROFILE_LOGOUT');
            $tickets = Jtext::_('COM_USERPROFILE_TICKETS');
            
        }
        
        ?>
        
        
      <li class="loader"><a href="<?php echo JRoute::_('index.php?option=com_userprofile&view=user'); ?>" ><?php  echo $dashboard; ?></a></li>
      <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#"> User Profile<span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li class="loader"><a href="<?php echo JRoute::_('index.php?option=com_userprofile&view=user&layout=personalinformation'); ?>"><?php  echo $myAcc; ?></a></li>
          <?php if($dynpage["ChangePassword"][1]=="ACT"){ ?>
          <li class="loader"><a href="<?php echo JRoute::_('index.php?option=com_userprofile&view=user&layout=changepassword'); ?>"><?php  echo $changePass ?></a></li>
          <?php } ?>
          <li class="loader"><a href="<?php echo JRoute::_('index.php?option=com_userprofile&task=user.logout'); ?>"><?php  echo $logout ?></a></li>
       </ul>
      </li>
     
      <?php if($dynpage["SupportTickets"][1]=="ACT"){ ?>
      <li class="loader"><a href="<?php echo JRoute::_('index.php?option=com_userprofile&&view=user&layout=support_ticket'); ?>"><?php  echo $tickets; ?></a></li>
      <?php } ?>
      <li class="" ><a target="_blank" class="helpLink" href="https://lms.iblesoft.com/">Help</a></li>
      <li class="">
      <div class="dropdown">
            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Alerts <span class="caret"></span>
            </button>           
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li class="rnotify_sm_list"><a href="#">Repack<span class="badge badge-warning"></a></li>
                <li><a href="#">Inprogress <span class="badge badge-warning"><?php echo $alerts->RepackorConsolidationInprogressCount; ?></span></a></li>
                <li><a href="#">Completed <span class="badge badge-success"><?php echo $alerts->RepackorConsolidationInprogressCount; ?></span></a></li>
              <li class="cnotify_sm_list"><a href="#">Consolidation </a></li>
              <li><a href="#">Inprogress <span class="badge badge-warning"><?php echo $alerts->RepackorConsolidationCompletedCount; ?></span></a></li>
              <li><a href="#">Completed <span class="badge badge-success"><?php echo $alerts->RepackorConsolidationCompletedCount; ?></span></a></li>
            </ul>           
        </div>
      </li>
      
   
    </ul>
  </div>
</div>
<?php
}
?>

  <script type="text/javascript">
  jQuery(document).ready(function() {
      
     var domainName = '<?php echo strtolower($domainname); ?>';
     var language = '<?php echo $language; ?>';
     var enabledLangs = '<?php echo $enabledLangs ?>';
     var langArr = enabledLangs.split(",");
     var reqUrl = "<?php echo $_SERVER['REQUEST_URI']; ?>";
     const EXPIRE_TIME = 1000*60*60*24;
     
     if(localStorage.getItem('loadfirstpage') === null){
          
          jQuery.getJSON('<?php echo JURI::base(); ?>/client_config.json', function(jd) {
              //console.log(jd.ClientList);
              var iterator = jd.ClientList.values();
                for (let elements of iterator) {
                    if(elements.Domain.toLowerCase() == domainName ){
                      var requrl = window.location.pathname+window.location.search;
                      if(language == 'en'){
                          var resurl = requrl.replace('/en','/'+elements.Def_language);
                              window.location.href= resurl;
                              localStorage.setItem("loadfirstpage", JSON.stringify({
                                time: new Date(),
                                data: true
                            }));
                      }
                      
                    }else{
                        localStorage.setItem("loadfirstpage", false);
                    }
                }
          });
          
      }
      
            setTimeout(function() {
                localStorage.removeItem('loadfirstpage');
            }, EXPIRE_TIME);


    jQuery('.lang_menu').html(''); 
    
    var menuContent = "<div class='lang_menu'>";
        menuContent+= "<a title='English' data-id='en' href=''><img src='<?php echo JURI::base(true); ?>/media/mod_languages/images/en_us.png' alt='English'></a>";
        menuContent+= "<a title='Spanish' data-id='es' href=''><img src='<?php echo JURI::base(true);?>/media/mod_languages/images/es.png' alt='Spanish'></a>";
        menuContent+= "<a title='bosnian' data-id='bs' href=''><img src='<?php echo JURI::base(true);?>/media/mod_languages/images/bs_ba.png' alt='Spanish'></a>";
        
    jQuery("#theme_nav").before(menuContent);
    
    jQuery('.lang_menu a').each(function(){
        var lang = jQuery(this).attr("data-id");
        if(langArr.includes(lang)){
            jQuery(this).show();
        }else{
             jQuery(this).hide();
        } 
    });
    
    jQuery(document).on('click','.lang_menu a',function(e){
        e.preventDefault();
         var resurl = reqUrl.replace('/'+language,'/'+jQuery(this).attr("data-id"));
         window.location.href= resurl;
    });
    
    
  });  
  
  


</script>

