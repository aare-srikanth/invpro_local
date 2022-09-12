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
                           <div class=""></div>
                           <div class="panel-body">
                              <div class="row">
                                  <div id="terms_conditions" class="terms_blk">
                                    <div class="col-sm-12 ter-cnt">
                                       <div class="main_heading">Términos y condiciones </div>
                                       <h4><strong>Terms and Conditions </strong></h4><br>
                                       <p>Gracias por estar aqui. Por favor, lea nuestros Términos y Condiciones antes de utilizar nuestro sitio. El uso de nuestro sitio puede implicar que usted acepta y acepta cumplir con nuestros Términos y condiciones establecidos en el acuerdo. Si, en cualquier caso, no está de acuerdo, le solicitamos que no utilice nuestro sitio. </p><br>
                                       <h4><strong>Términos generales </strong></h4><br>
                                       <p>BoxOn Logistics se reserva el derecho de realizar cambios en el sitio de vez en cuando o según sea necesario. Cualquier parte del sitio se puede modificar o eliminar, se puede agregar cualquier cosa nueva. Traer los cambios según sea necesario queda a discreción exclusiva de la empresa, BoxOn Logistics, y se le solicita que visite, vuelva a visitar para saber más sobre las adiciones, eliminaciones, revisiones o modificaciones, etc., ya que es posible que no coloquemos ningún tipo de notificación en nuestro sitio. ni podemos enviar notificaciones personales a través de correo electrónico. Es su principal responsabilidad mantenerse actualizado sobre lo que tenemos en nuestro sitio para usted y si no le gusta lo que agregamos, eliminamos, revisamos o modificamos, etc., debe dejar de usar nuestro sitio, ya que su uso continuo de nuestro sitio puede significar usted acepta nuestros términos y condiciones actualizados directa o indirectamente. </p><br>
                                       <h4><strong>Copyright- Todos los derechos reservados por BoxOn Logistics </strong></h4><br>
                                       <p>El sitio web está protegido por derechos de autor (leyes de derechos de autor de EE. UU.) y es propiedad de BoxOn Logistics. Ninguna parte de este sitio web debe reproducirse de ninguna forma: su logotipo, imágenes, videos, contenido e infografía no deben reproducirse, modificarse o alterarse para usarse en ningún otro lugar sin nuestro permiso previo. Solo nuestros afiliados, agentes de marketing y socios tienen derecho a hacerlo, ya que tenemos un acuerdo por escrito con ellos. Cualquiera que use cualquier parte de nuestro sitio de cualquier forma digital o no digital puede tener que enfrentar las consecuencias legales de sus actos. No debe copiar ni almacenar nuestro sitio o la información presentada en nuestros sitios de ninguna forma y hacerlo está estrictamente prohibido y se considera un delito legal. Cualquier persona interesada en usar cualquier parte del sitio (imágenes, logotipos, gráficos, contenido, etc.) para difundir información sobre nosotros puede hacerlo, a menos que no use la información para sus beneficios personales o comerciales. </p><br>
                                    <h4><strong>Garantías </strong></h4><br>
                                    <p>La información presente en nuestro sitio web es para la comprensión general de lo que hacemos o brindamos a los clientes (siempre vamos en la medida de lo posible y más allá para satisfacer a nuestros clientes). Aunque la información presente en nuestro sitio puede convencerlo de que se comunique con nosotros, no ofrecemos ningún tipo de garantía. BoxOn Logistics no representa ni respalda la precisión o confiabilidad de la información presente en el sitio o que se distribuye a través del sitio. Usted, como uno de los usuarios de nuestro sitio, reconoce que su confianza en la información presente aquí en el sitio o a través del sitio es bajo su propio riesgo. </p><br>
                                    <h4><strong>Responsabilidades éticas </strong></h4><br>
                                    <p><i>Los usuarios de nuestro sitio web algunas responsabilidades éticas. </i></p><br>
                                    <p>Users of the website should be at least 18 years old.</p>
                                    <p>Los usuarios no deben cargar, transmitir, publicar ni publicar ninguna parte del sitio en ningún otro lugar sin el permiso de los propietarios de los derechos de autor, es decir, nosotros. </p>
                                    <p>Los usuarios no deben impedir que nadie use nuestro sitio difundiendo cualquier tipo de información incorrecta. </p>
                                    <p>No debe constituir o alentar comportamientos que puedan dar lugar a cualquier tipo de delito o que puedan violar la ley. </p>
                                    <p>No debe infringir los derechos de autor. </p>
                                    <p>Transmitir o cargar virus que puedan tener el potencial de dañar el sitio. </p>
                                    <p>No debe responsabilizarnos por la información que puede no gustarles en otros sitios que pueden visitar haciendo clic en los enlaces presentes en nuestro sitio (nuestro sitio web puede mostrar enlaces externos, pero no somos dueños de los sitios a los que el enlace puede llevarlo). No garantizamos la precisión o confiabilidad de la información presente en esos sitios. No lo alentamos a que haga clic en los enlaces ni lo respaldamos). </p><br>
                                    <h4><strong>Información que recopilamos </strong></h4><br>
                                    <p>Para continuar usando cierta parte del sitio, es posible que deba registrarse, proporcionarnos sus datos a través del "correo electrónico" o el "formulario de contacto" presente en nuestro sitio. La información que podemos recopilar contiene dirección de correo electrónico, número de teléfono, dirección para correspondencia, etc. La información que recopilamos siempre se utiliza según los términos de nuestra Política de privacidad. Puedes leer más sobre nuestra política de privacidad aquí. </p><br>
                                    <h4><strong>Uso de productos/software de BoxOn </strong></h4><br>
                                    <p>El software diseñado y desarrollado por BoxOn es un trabajo protegido por derechos de autor de BoxOn y solo nosotros tenemos el derecho de comercializar, distribuir, vender o ejecutar programas de afiliación, etc. A pedido de los clientes, el software puede estar disponible para su descarga, pero no tienen derecho a comercializarlo o usarlo para cualquier otro propósito que no sea el acordado antes de descargarlo. El uso del software u otros productos se rige por el término y el acuerdo de licencia. La descarga del software no da derecho a los usuarios a poseerlo o comercializarlo. La copia, distribución, reproducción, modificación, etc. están estrictamente prohibidas. Y tomamos un enfoque legal para proteger nuestro producto y los infractores pueden ser procesados ​​en la medida de lo posible (se le solicita que siga los términos establecidos en el acuerdo de licencia sobre el uso del software y todos los demás asuntos relacionados antes de usar el software). También es importante señalar que los documentos técnicos que pueden venir con el software deben considerarse como los medios para saber más sobre el software, pero no como la guía definitiva. No tomamos la garantía de la exactitud y confiabilidad de esos documentos. </p><br>
                                    <h4><strong>indemnizando </strong></h4><br>
                                    <p>Usted acepta que nos defenderá, protegerá e indemnizará a nosotros, a nuestros socios, empleados, agentes, representantes y a todos los que estén directa o indirectamente conectados con nosotros en la comercialización de nuestros productos contra cualquier tipo de reclamo, sanción, demanda, multa, abogado, costas judiciales. , gastos legales y otros pasivos declarados y no declarados. Bajo ninguna circunstancia nosotros, BoxOn Logistics ni sus socios, empleados, agentes, representantes y todos los relacionados con nosotros, seremos responsables de ningún tipo de daño directo o indirecto, declarado, implícito o consecuente que pueda surgir debido al uso de nuestro sitio. o nuestro software. No asumimos ninguna responsabilidad ni nuestros socios u otros que están conectados con nosotros tienen ninguna responsabilidad por el daño o la pérdida causada u ocurrida debido al uso de nuestro sitio o software. </p><br>
                                    <h4><strong>Governing Laws</strong></h4><br>
                                    <p>Los términos y condiciones establecidos en este acuerdo se rigen e interpretan de acuerdo con las leyes del Estado de Florida. Todos los asuntos legales están sujetos a la jurisdicción del Estado de Florida.</p><br>
                                    <h4><strong>Modificaciones en Términos y Condiciones </strong></h4><br>
                                    <p>Podemos agregar, eliminar, modificar los términos y condiciones presentados en este acuerdo de vez en cuando, por lo que se le solicita que visite, vuelva a visitar esta página para comprender mejor nuestro uso del sitio y el software y otros asuntos legales. Continuar el uso de nuestro sitio sin conocer los términos actualizados significará que acepta y cumple con los nuevos términos y condiciones. </p><br>
                                    <p>Si desea obtener más información sobre nuestros Términos y condiciones, escríbanos a info@boxonlogistics.com. </p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Kraj sadržaja -->
         </main>
      </section>
   </body>
</html>