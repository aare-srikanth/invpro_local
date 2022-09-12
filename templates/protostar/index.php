<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$env = explode(".",$_SERVER['REQUEST_URI']);


require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';

if (strpos($env[1], "newpassword") !== false) {
   require_once JPATH_ROOT.'/components/com_userprofile/classes/controlbox.php';
}
if (strpos($env[1], "project-request-form") !== false) {
   require_once JPATH_ROOT.'/components/com_userprofile/classes/controlbox.php';
}

/** @var JDocumentHtml $this */


$app  = JFactory::getApplication();
$user = JFactory::getUser();

// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;

$domainDetails = ModProjectrequestformHelper::getDomainDetails();

// echo '<pre>';
// var_dump($domainDetails);
// exit;

$CompanyId = $domainDetails[0]->CompanyId;
$CompanyName = $domainDetails[0]->CompanyName;
$CompanyLogo = $domainDetails[0]->CompanyLogo;
$domain = strtolower($domainDetails[0]->Domain);
$menuAccesses = $domainDetails[0]->menuAccesses;

$menuAccessStr='';
foreach($menuAccesses as $access){
    $menuAccessStr .= $access->MenuItem.",".$access->Access.":";
}

$menuCustData = explode(":",$menuAccessStr);
$maccarr=array();
foreach($menuCustData as $menuaccess){
    $macess = explode(",",$menuaccess);
    $maccarr[$macess[0]]=$macess[1];
}

$fullfillment = $maccarr['FulFillment'];

if(!isset($fullfillment)){
    $fullfillment = "False";
}
            
$clientConfigObj = file_get_contents(JURI::base().'/client_config.json');
$clientConf = json_decode($clientConfigObj, true);
$clients = $clientConf['ClientList'];


// get footer content

    $config = JFactory::getConfig();
    $this->db = mysqli_connect($config->get('host'),$config->get('user'),$config->get('password'),$config->get('db'));
    if (mysqli_connect_errno())
    {
        $footerContent = "Failed to connect to MySQL";
    }else{
        
        if($CompanyId != ''){
            $query = "SELECT * FROM bnce7_footers WHERE client_id='".$domain."'";
        }else{
            $query = "SELECT * FROM bnce7_footers WHERE client_id='boxon'";
        }
             //$query = "SELECT * FROM bnce7_footers";
             
            $results  = $this->db->query($query);
            
            if($results->num_rows == 0){
                $results  = $this->db->query("SELECT * FROM bnce7_footers WHERE client_id='boxon'");
            }
            
                foreach ($results as $row)
                    {
                        $footerContent = $row['html_content'];
                    }
            
    }
    
    $check_img = stripos($footerContent,'/images/logo.png');
    $check_domain = stripos($footerContent,'##DomainName##');
    $baseImgUrl = JUri::base(true).'/images/logo.png';
    if($check_img){
        $footerContent = str_replace('/images/logo.png',$baseImgUrl,$footerContent);
    }
    if($check_domain){
        $footerContent = str_replace('##DomainName##',strtolower($CompanyName),$footerContent);
    }
    

// end 

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Add Stylesheets
JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'relative' => true));

// Use of Google Font
if ($this->params->get('googleFont'))
{
	JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=' . $this->params->get('googleFontName'));
	$this->addStyleDeclaration("
	h1, h2, h3, h4, h5, h6, .site-title {
		font-family: '" . str_replace('+', ' ', $this->params->get('googleFontName')) . "', sans-serif;
	}");
}

// Template color
if ($this->params->get('templateColor'))
{
	$this->addStyleDeclaration('
	body.site {
		border-top: 3px solid ' . $this->params->get('templateColor') . ';
		background-color: ' . $this->params->get('templateBackgroundColor') . ';
	}
	a {
		color: ' . $this->params->get('templateColor') . ';
	}
	.nav-list > .active > a,
	.nav-list > .active > a:hover,
	.dropdown-menu li > a:hover,
	.dropdown-menu .active > a,
	.dropdown-menu .active > a:hover,
	.nav-pills > .active > a,
	.nav-pills > .active > a:hover,
	.btn-primary {
		background: ' . $this->params->get('templateColor') . ';
	}');
}

// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Adjusting content width
$position7ModuleCount = $this->countModules('position-7');
$position8ModuleCount = $this->countModules('position-8');

if ($position7ModuleCount && $position8ModuleCount)
{
	$span = 'span6';
}
elseif ($position7ModuleCount && !$position8ModuleCount)
{
	$span = 'span9';
}
elseif (!$position7ModuleCount && $position8ModuleCount)
{
	$span = 'span9';
}
else
{
	$span = 'span12';
}

// Logo file or site title param

if($CompanyLogo !=''){
    $logo = '<img src="'.$CompanyLogo.'" alt="' . $sitename . '" />';
}
else if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . JUri::root() . $this->params->get('logoFile') . '" alt="' . $sitename . '" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}

// Add Stylesheets
//JHtml::_('stylesheet', 'bootstrap.min.css', array('version' => 'auto', 'relative' => true));
// Add Stylesheets
//JHtml::_('stylesheet', 'style.css', array('version' => 'auto', 'relative' => true));

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
   
<head>
    <title/><?php echo $CompanyName; ?></title>
<link rel="icon" href="<?php echo JURI::base() ?>/templates/protostar/clients/<?php echo $domain; ?>/cropped-fav.png" sizes="192x192" />

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">

	<jdoc:include type="head" />

	<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/protostar/css/bootstrap.min.css">
	
	<?php //$stylesheet="style-".$CompanyName.".css";  ?>
	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/protostar/css/style_global.css">
	<link rel="stylesheet" href="<?php echo JURI::base() ?>/templates/protostar//clients/<?php echo $domain; ?>/css/style.css">
    
    <!-- <script src="templates/protostar/js/bootstrap.min.js"></script>-->

</head>
<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '')
	. ($this->direction === 'rtl' ? ' rtl' : '');
?>">
    
    <div class="page_loader" style="display:none;">
          <span> Loading... <i class="fa fa-spinner fa-spin" style="font-size:40px"></i> </span>
    </div>
    
    <div class="page_loader_paypal" style="display:none;">
          <span> We are processing your request. Please wait... <i class="fa fa-spinner fa-spin" style="font-size:40px"></i> </span>
    </div>

	<div class="body" id="top">

			<!-- Header -->
			<header>
				<div class="top_header">
		  			<div class="container">
			  			<div class="row">
			  				<div class="col-sm-6 hidden-xs">
				  				<div class="social_icons">
				  					<ul>
				  						<li>
				  							<a href="#"><i class="fa fa-facebook"></i></a>
				  						</li>
				  						<li>
				  							<a href="#"><i class="fa fa-twitter"></i></a>
				  						</li>
				  						<li>
				  							<a href="#"><i class="fa fa-google-plus"></i></a>
				  						</li>
				  						<li>
				  							<a href="#"><i class="fa fa-instagram"></i></a>
				  						</li>
				  					</ul>
				  				</div>
			  				</div>
			  				<div class="col-sm-6 text-right">
			  					<jdoc:include type="modules" name="position-0" style="none" />
			  				</div>
			  			</div>
		  			</div>
	  			</div>
				<nav class="navbar theme_nav sticky">
				  <div class="container">
				  <div class="row">
				  	<div class="col-md-4">
						<div class="navbar-header">
						  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#theme_nav" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						  </button>
						  <a href="/">
							<?php echo $logo; ?>
							<?php if ($this->params->get('sitedescription')) : ?>
								<?php echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription'), ENT_COMPAT, 'UTF-8') . '</div>'; ?>
							<?php endif; ?>
							
							 <?php 
							 
                                foreach($clients as $client){ 
                                    if(strtolower($client['Domain']) == strtolower($domain) ){   
                                        $logo_text=$client['Logo_text'];
                                    }
                                }
                  
                            ?>
						<span class="logo-txt">
						    <?php echo $logo_text;  ?>
						</span>
						  </a>
						</div>
					</div>
					<div class="col-md-8">
						<div class="collapse navbar-collapse navbar-right" id="theme_nav">
							<jdoc:include type="modules" name="banner" style="xhtml" />
						</div>
					</div>
				  </div>
				  </div>
				</nav>
			</header>
			<!-- Header -->

			<!-- <div class="<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">
				<?php if ($this->countModules('position-1')) : ?>
					<jdoc:include type="modules" name="position-1" style="none" />
				<?php endif; ?>
			</div> -->

			<!-- Page Content -->
			<section class="page_content">
				<?php if ($position8ModuleCount) : ?>
					<!-- Begin Sidebar -->
					<div id="sidebar" class="span3">
						<div class="sidebar-nav">
							<jdoc:include type="modules" name="position-8" style="xhtml" />
						</div>
					</div>
					<!-- End Sidebar -->
				<?php endif; ?>
				<main id="content" role="main">

					<!-- Begin Content -->
					<jdoc:include type="modules" name="position-3" style="xhtml" />
					<div class="theme_alert">
						<div class="container">
							<jdoc:include type="message" />
						</div>
					</div>
					<jdoc:include type="component" />
					<jdoc:include type="modules" name="position-2" style="none" />
					<!-- End Content -->
					
				</main>
				<?php if ($position7ModuleCount) : ?>
					<div id="aside" class="span3">
						<!-- Begin Right Sidebar -->
						<jdoc:include type="modules" name="position-7" style="well" />
						<!-- End Right Sidebar -->
					</div>
				<?php endif; ?>
			</section>
			<!-- Page Content -->
			
            <!--Footer-->
			<?php  echo $footerContent; ?>
			<!--Footer-->
	</div>

	<jdoc:include type="modules" name="debug" style="none" />
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
<script type="text/javascript">
    // sticky header
    /* $(window).scroll(function(){
      var sticky = $('.sticky'),
          scroll = $(window).scrollTop();

      if (scroll >= 100) sticky.addClass('fixed');
      else sticky.removeClass('fixed');
    });*/
    jQuery(document).ready(function() {
        
        var fullfillment = "<?php echo $fullfillment; ?>";
       
        if(fullfillment == "False"){
                $joomla(".moduletable_menu ul li").each(function(){
                    
                    var reqlink = $joomla(this).find('a').attr("href");
                    var requrlArr = reqlink.split("/");
                    
                    if($joomla.inArray('project-request-form', requrlArr) > 0){
                        $joomla(this).hide();
                    }
                    
                });
        }
        
    $joomla('.custommenu a, .dash_option_blck a, .navbar-nav a, .nav-tabs li a, .pageloader_link, .lang_menu a, .support_ticket .create_ticket,.support_ticket .edit_ticket').not( '.nav-tabs li a.docpage').on('click',function(){
        
        $joomla('.page_loader').show();
        var eleClass = $joomla(this).attr("class");
        if(eleClass == "helpLink"){
            $joomla('.page_loader').hide();
        }
    });
    
     $joomla(".alert-info").addClass("alert-success").removeClass("alert-info");
    
    
        
      setTimeout(function() {
        jQuery('.alert-info').slideUp("slow");
    }, 3600);
    var pf="<?php echo $_GET['id'];?>";
    if(pf==8){
        console.log('hi')
        jQuery('.page_content').css('background-image','none'); 
    }
  }); 
    jQuery('.navbar-toggle').click(function(){
      jQuery('#theme_nav').css('height', 'auto');
      jQuery('.custommenu').toggle('collapse');
	  jQuery('.moduletable_menu').toggle(); 
    });

</script>

<script src="//code.tidio.co/sgniylftg4q85rsgol0lmcfggmaea3al.js" async></script>

<?php if(strtolower($domain) == "kupiglobal"){ ?>
<script src="//code.tidio.co/2ovbcwa9deojw5akf9mi21yrtijgtjz3.js" async></script>
<?php } ?>

</body>
</html>