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
                                                <div class="col-sm-12 prvcy-blk">
                                                     <div class="main_heading">Política de privacidad</div><br>
                                                     <h4><strong>Política de privacidad</strong></h4>
                                                     <p>Respetamos la privacidad de nuestros clientes.</p><br>
                                                     <h4><strong>Recabando información</strong></h4><br>
                                                     <p>Los usuarios interesados en contratar los servicios/productos deberán facilitarnos sus datos. A menudo recibimos información a través de nuestro "formulario de contacto" presente en el sitio o por correo electrónico y, a veces, por teléfono. La información que recopilamos puede incluir el nombre del cliente, edad, número de teléfono fijo, número de teléfono móvil, dirección física, URL del sitio web, dirección de correo electrónico, fax y otra información comercial relacionada. Solicitamos u obtenemos esta información porque queremos entender mejor a quién le estamos brindando nuestros servicios. Nos resulta fácil brindar mejores servicios a nuestros clientes si recopilamos la mayor cantidad de información posible. </p><br>
                                                   <h4><strong>¿Qué hacemos con la información? </strong></h4><br>
                                                   <p>La información que recopilamos de nuestros clientes nos ayuda a brindar mejores servicios a nuestros clientes. Además de las solicitudes de modelos de servicio habituales, también podemos utilizar los datos con fines de marketing interno. Por ejemplo, podemos enviarle un correo electrónico que puede informarle más sobre nuestro software o cambios recientes en el software, otras actualizaciones nuevas, noticias sobre nuestra empresa, etc. (Nuestros clientes tienen derecho a darse de baja en cualquier momento que lo deseen). Siempre usamos la información de manera ética. </p><br>
                                                    <h4><strong>Divulgación de la información </strong></h4><br>
                                                    <p>Nunca revelamos información de nuestros clientes a nadie. Pero en algunos casos, si alguien quiere sus datos, podemos comunicarnos con usted primero y hacer lo que usted sugiera y recomiende. Pero, en asuntos legales, donde tenemos que cumplir con la ley y para protegernos a nosotros mismos, a nuestros empleados, socios, agencias y a todos aquellos que están directa o indirectamente conectados con nosotros, podemos divulgar la información. </p>
                                                    <p>¿Vendemos tus datos? </p>
                                                    <p>No nosotros no. No vendemos sus datos a ningún tercero. Respetamos su privacidad y hacemos todo lo posible para mantenerla a salvo de otros ojos de marketing. </p><br>
                                                    <h4><strong>Seguridad de los datos </strong></h4><br>
                                                    <p>La información que recopilamos de nuestros clientes siempre está segura con nosotros. Tenemos un sistema de gestión de base de datos seguro sin acceso de terceros. Aparte de esto, contamos con un equipo de expertos en seguridad que ejecuta varios programas de seguridad para mantener los datos 100% seguros y protegidos. </p><br>
                                                   <p>Si desea obtener más información sobre nuestra Política de privacidad, envíenos un correo electrónico a info@boxonlogistics.com. </p>
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
