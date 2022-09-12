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
         <main id="content" role="main">
            <div class="container">
               <div class="main_panel">
                  <div class=""></div>
                  <div class="row">
                     <div class="col-sm-12">
                        <div class="panel panel-default">
                           <div class="" >
                           </div>
                           <div class="panel-body">
                              <div class="row">
                                 <div id="terms_conditions" class="terms_blk">
                                    <div class="col-sm-12 ter-cnt">
                                       <div class="main_heading">TERMS & CONDITIONS</div>
                                       <h4><strong>Terms and Conditions</strong></h4>
                                       <p>Thanks for being here. Please, read our Term and Conditions before you use our site. Using our site may imply that you accept and agree to abide by our Terms and Conditions stated in the agreement. If, in any case, you do not agree then we request you not to use our site.</p><br>
                                       <h4><strong>General Terms</strong></h4><br>
                                       <p>BoxOn Logistics reserves the right to make changes in the site from time-to-time or as required. Any portion of the site can be altered or removed, anything new can be added. Bringing the changes as required is at the sole discretion of the company, BoxOn Logistics, and you are requested to visit, revisit to know more of the additions, deletions, revisions or modifications etc as we may not put any sort of notifications on our site nor may we send personal notifications through email. It is your prime responsibility to keep yourself updated of what we have on our site for you and if you do not like what we added, removed, revised or modified etc then you must stop using our site as your continued use of our site may mean you agree to our updated terms and conditions directly or indirectly.</p><br>
                                    <h4><strong>Copyright - All rights reserved by BoxOn Logistics </strong></h4><br>
                                    <p>The website is protected by copyright (US Copyright Laws) and is owned by BoxOn Logistics. No part of this website should be reproduced in any form – its logo, images, videos, content, infographics should not be reproduced or modified or altered to use anywhere else without prior permission from us. Only our affiliates, marketing agents, partners have the right to do so as we have written agreement with them. Anyone using any part of our site in any form digital or non-digital may have to face legal consequences of their doings. You should not copy or store our site or information presented on our sites in any form and doing so is strictly prohibited and is deemed as legal offense. Anyone interested in using any portion of site – images, logos, graphics, content etc in order to spread information about us can do unless they do not use the information for their personal or commercial benefits.</p><br>
                                   <h4><strong>Warranties</strong></h4><br>
                                   <p>The information present on our website is for the general understanding of what we do or provide for the customers (we always go to the extent possible and beyond to satisfy our clients). Although the information present on our site may convince you to contact us yet we do not offer any sort of warranties. BoxOn Logistics doesn’t represent or endorse the accuracy or reliability of the information present on the site or that is being distributed through the site. You, as one of the users of our site, acknowledge that your reliance upon information present here on the site or through the site is at your own risk.</p><br>
                                    <h4><strong>Ethical Responsibilities</strong></h4><br>
                                    <p><i>Users of our website some ethical responsibilities.</i></p><br>
                                    <p>Users of the website should be at least 18 years old.</p>
                                    <p>Users should not upload, transmit, post or publish any part of the site anywhere else without permission from the copyright owners – that is us.</p>
                                    <p>Users should not stop anyone from using our site by spreading any sort of wrong information.</p>
                                    <p>Should not constitute or encourage behaviour that may lead to any sort of criminal offense or which may violate the law.</p>
                                    <p>Should not infringe the copyrights</p>
                                    <p>Transmit or upload virus that can have the potential to harm the site.</p>
                                    <p>Should not hold us responsible for information that they may not like on other sites which they may visit by clicking on the links present on our site (our web site may show external links but we do not own the sites that the link may take you to. We do not guarantee of the accuracy or reliability of the information present on those sites. We do not encourage you to click on the links nor do we endorse that).</p><br>
                                    <h4><strong>Information that we collect </strong></h4><br>
                                    <p>In order to continue using certain portion of the site, you may have to register, give out your details to us through “email” or “contact form” present on our site. The information that we may collect contain email address, phone number, address for correspondence etc. The information that we collect is always used as per the terms of our Privacy Policy. You can read more about our privacy policy here.</p><br>
                                    <h4><strong>Use of BoxOn Products / Software </strong></h4>
                                    <p>Software designed and developed by BoxOn is the copyrighted work of BoxOn and only we have the right to market, distribute, sell or run affiliation programs etc. Upon request from customers, the software can be available to download for them but they have no right to market it or use it for any other purpose except what they agreed on before downloading. The use of the software or other products is governed by the term and the license agreement. Downloading the software doesn’t entitle the users to own it or market it. Copying, distribution, reproduction, modification etc are strictly prohibited. And we take legal approach to protect our product and the violators may be prosecuted to the extent possible (you are requested to go through the terms stated in the license agreement about the use of the software and all other pertaining issues before using the software). It is also of importance to state that the technical documents which may come along with the software should be considered as the means to know more about the software but should not be deemed as the ultimate guide. We do not take the guarantee of the accuracy and reliability of those documents.</p><br>
                                    <h4><strong>Indemnifying</strong></h4>
                                    <p>You accept that you will defend, protect, indemnify us, our partners, employees, agents, representatives and everyone who are directly and indirectly connected to us in marketing our products against any sort of claims, penalties, demands, fines, attorneys, court costs, legal expenses and other stated and unstated liabilities. Under no circumstances shall we, BoxOn Logistics nor does its partners, employees, agents, representatives and everyone connected to us, be liable for any type of direct or indirect, stated, implied or consequential damages that might erupt because of the use of our site or our software. We do not hold any liability nor our partners or others who are connected to us hold any liability for the damage or loss caused or occurred due to the use of our site or software.</p><br>
                                    <h4><strong>Governing Laws</strong></h4>
                                    <p>The terms and conditions stated in this agreement are governed by and construed as per the laws of State of Florida. All legal matters are subject to jurisdiction of State of Florida.</p><br>
                                    <h4><strong>Modifications in Term and Conditions</strong></h4><br>
                                    <p>We may add, remove, modify the terms and conditions presented in this agreement from time-to-time so you are requested to visit, revisit this page so as to understand better about our use of the site and the software and other legal matters. Continuing the use of our site without making yourself aware of the updated terms will signify that you accept and abide by new terms and conditions.</p><br>
                                    <p>If you would like to know anything more about our Term and Conditions then please, write to us at info@boxonlogistics.com.</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- End Content -->
         </main>
      </section>
   </body>
</html>