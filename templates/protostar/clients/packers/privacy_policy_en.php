<?php

$urlpath = explode("/",$_SERVER['REQUEST_URI']);
if(count(explode("/",$_SERVER['REQUEST_URI'])) == 7){
    $projectFolder = $urlpath[1];
    $domain = $urlpath[5];
}

?>

<!Doctype html>
<html>
   <head>
      <title>Terms & Conditions</title>
      <link rel="stylesheet" href="<?php if(isset($projectFolder)){ echo '/'.$projectFolder; } ?>/templates/protostar/css/bootstrap.min.css">
      <!-- Theme CSS -->
      <link rel="stylesheet" href="<?php if(isset($projectFolder)){ echo '/'.$projectFolder; } ?>/templates/protostar/css/style_global.css">
      <link rel="stylesheet" href="<?php if(isset($projectFolder)){ echo '/'.$projectFolder; } ?>/templates/protostar/clients/<?php echo $domain; ?>/css/style.css">
      <style>
         .ter-cnt{
         padding:25px;
         }
      </style>
   </head>
    <body>
        <section class="page_content">
              <div class="container">
                  <div class="main_panel">
                       <div class=""></div>
                         <div class="row">
                             <div class="col-sm-12">
                                    <div class="panel panel-default">
                                         <div class=""></div>
                                        <div class="panel-body">
                                             <div class="row">
                                                 <div class="col-sm-12">
                                                      <div class="main_heading">Privacy Policy</div><br>
                                                      <h4><strong>Privacy Policy</strong></h4>
                                                       <p>We respect privacy of our clients.<br></p>
                                                              <h4><strong>Collecting Information </strong></h4>
                                                             <p>Users interested in taking the services / products will have to provide their details to us. We often receive information through our “contact form” present on the site or through email and sometimes through call. The information that we collect may include name of the customer, age, landline number, mobile number, physical address, website url, email address, fax and other connected business information. We request or obtain this information because we want to understand better who we are providing our services to. It becomes easy for us to provide better services for our customers if we gather as much information as we can.</p><br>
                                                            <h4><strong>What do we do with the information?</strong></h4><br>
                                                            <p>The information we collect from our customers help us provide better services to our customers. Apart from usual service model requests, we may also use the data for internal marketing purposes. For instance, we can send you an email which can let you know more of our software or recent changes in the software, other new updates, news about our company etc (Our customers have the right to unsubscribe anytime they want). We always use the information in an ethical manner.</p><br>
                                                            <h4><strong>Disclosing the information </strong></h4><br>
                                                            <p>We never disclose information of our customers to anyone. But in some cases, if anybody wants your details then we may contact you first and do as suggested and recommended by you. But, in legal matters, where we have to abide by the law and to protect ourselves, our employees, partners, agencies and all those who are directly and indirectly connected to us, we may disclose the information.
                                                             <br> Do we sell your data?</p>
                                                            <p>No, we do not. We do not sell your data to any third-party. We respect your privacy and go to any extent possible to keep it safe from other marketing eyes.</p><br>
                                                            <h4><strong>Security of the data </strong></h4><br>
                                                            <p>The information that we collect from our customers is always safe with us. We have secured database management system with no third-party access. Apart from this, we have a team of experts in security who run various security programs to keep the data 100% safe and secure.</p><br>
                                                            <p>Want to know more about our Privacy Policy, please, email us to info@boxonlogistics.com.</p><br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                          </section>
                       </body>
                 </html>