<?php 

require_once JPATH_ROOT.'/modules/mod_projectrequestform/helper.php';
$domainDetails = ModProjectrequestformHelper::getDomainDetails();
$domain = strtolower($domainDetails[0]->Domain);


// get labels
    
if(strpos($_SERVER['REQUEST_URI'], '/index.php/') !== false){
    $strplace = strpos($_SERVER['REQUEST_URI'], '/index.php/');
    $langplace = $strplace + 11;
    $language = substr($_SERVER['REQUEST_URI'],$langplace,2);
}
    $res=Controlbox::getlabels($language);
    $assArr = [];
    
    foreach($res->data as $response){
    $assArr[$response->id]  = $response->text;
    
     }

?>

<link rel="stylesheet" type="text/css" href="<?php echo JUri::base(); ?>/components/com_userprofile/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo JUri::base(); ?>/components/com_userprofile/css/style_global.css">
<link rel="stylesheet" type="text/css" href="<?php echo JUri::base(); ?>/components/com_userprofile/clients/<?php echo $domain; ?>/css/style.css">
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_userprofile/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
var $joomla = jQuery.noConflict(); 
$joomla(document).ready(function(){
    
    // var previousPageText="<?#php echo Jtext::_('COM_USERPROFILE_PREVIOUS_PAGE');?>";
    // var nextPageText="<?#php echo Jtext::_('COM_USERPROFILE_NEXT_PAGE');?>";
    // var showEntText="<?#php echo $assArr['show_entries'];?>";
    // var searchText="<?#php echo $assArr['search'];?>";
    // var showingEntriesText="<?#php echo $assArr['show_entries'];?>";
    // var showingEmptyEntriesText="<?#php echo Jtext::_('COM_USERPROFILE_SHOWING_EMPTY_ENTRIES');?>";
    // var nodataText = "<?#php echo Jtext::_('COM_USERPROFILE_NO_DATA');?>";
     var previousPageText="<?php echo Jtext::_('COM_USERPROFILE_PREVIOUS_PAGE');?>";
    var nextPageText="<?php echo Jtext::_('COM_USERPROFILE_NEXT_PAGE');?>";
    var showEntText="<?php echo $assArr['show_entries']; ?>";
   var searchText="<?php echo $assArr['search'];?>";
    var showingEntriesText="<?php echo Jtext::_('COM_USERPROFILE_SHOWING_ENTRIES');?>";
    var showingEmptyEntriesText="<?php echo Jtext::_('COM_USERPROFILE_SHOWING_EMPTY_ENTRIES');?>";
    var nodataText = "<?php echo Jtext::_('COM_USERPROFILE_NO_DATA');?>";
    
    $joomla('#a_table').DataTable({
        "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }
    });  
    $joomla('#j_table').DataTable({
         "ordering": false,
         "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
         },
            "lengthMenu": [50, 75, 100],
            "iDisplayLength": 50,
            "order": [],
            "columnDefs": [
            { 
                "targets": [0], //first column / numbering column
                "orderable": false, //set not orderable
            }
            ]
            
    });  
    //$joomla('#M_table').DataTable();  
    $joomla('#N_table').DataTable({
        "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }
        ,
    //     columnDefs: [
    //     { "width": "20%", "targets": [0] },
    //     { targets: '_all', visible: true }
    // ]
    
    });
	$joomla( "#view_image,#exampleModal2,#exampleModal1,#exampleModal,#inv_view,#shipdetailsModal,#logModal,#ord_delete,#ord_keep,#ord_return,#ord_ship,#ord_edit" ).on('shown', function(){
	 $joomla( "html" ).css("overflow","hidden");
	});	
	$joomla( "#view_image,#exampleModal2,#exampleModal1,#exampleModal,#inv_view,#shipdetailsModal,#logModal,#ord_delete,#ord_keep,#ord_return,#ord_ship,#ord_edit" ).on('hidden', function(){
	 $joomla( "html" ).css("overflow","visible");
	});	
	
	
	$joomla('#M_table').DataTable({ "ordering": false,
	 "language": {
            "lengthMenu": showEntText,
            "search": searchText,
            "info": showingEntriesText,
            "infoEmpty": showingEmptyEntriesText,
            "emptyTable":nodataText,
            "paginate": {
              "previous": previousPageText,
              "next": nextPageText
            }
        }});
});
$joomla(".modal").modal({backdrop: 'static',keyboard: false,show: true});

</script>
  
  <!--<div class="modal fade" id="myAlertModal" role="dialog">-->
  <!--  <div class="modal-dialog">-->
    
  <!--     Modal content-->
  <!--    <div class="modal-content">-->
  <!--      <div class="modal-header">-->
  <!--        <button type="button" class="close" data-dismiss="modal">&times;</button>-->
  <!--        <h4 class="modal-title">Message Box</h4>-->
  <!--      </div>-->
  <!--      <div class="modal-body">-->
  <!--        <p id="error"></p>-->
  <!--      </div>-->
  <!--      <div class="modal-footer">-->
  <!--        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
  <!--      </div>-->
  <!--    </div>-->
      
  <!--  </div>-->
  <!--</div>-->


        
        
        