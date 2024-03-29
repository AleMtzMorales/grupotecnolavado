<?php
// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
require_once '../../librerias/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

/*En esta linea de código mandamos a llamar las opciones de dpmpdf para 
usarlas al momento de usar imágenes */
use Dompdf\Options;



$id=$_GET['idventa'];
// Introducimos HTML de prueba
function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

 $html=file_get_contents("http://localhost/ventas/vistas/ventas/ticketVentaPdf.php?idventa=".$id);

//Aquí se crea el objeto a utilizar
$options = new Options();

//Y debes activar esta opción "TRUE"
$options->set('isRemoteEnabled', TRUE);
 
// Instanciamos un objeto de la clase DOMPDF.
$pdf = new DOMPDF($options);
 
// Definimos el tamaño y orientación del papel que queremos.
//$pdf->set_paper("letter", "portrait");
$pdf->set_paper(array(0,0,115,290));
 
// Cargamos el contenido HTML.
$pdf->load_html($html);
 
// Renderizamos el documento PDF.
$pdf->render();
 
// Enviamos el fichero PDF al navegador.
$pdf->stream('reporteVenta.pdf');



