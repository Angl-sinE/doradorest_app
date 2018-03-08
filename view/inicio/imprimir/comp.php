<?php
require_once 'view/inicio/imprimir/num_letras.php';
require_once('assets/lib/pdf/cellfit.php');
$de = $_SESSION["datosempresa"];
$texto = 'Autoriza Resoluc. N.11-97, publicada en Gaceta 171 del 05-09-97';

class FPDF_CellFiti extends FPDF_CellFit
{
function AutoPrint($dialog=false)
{
	//Open the print dialog or start printing immediately on the standard printer
	$param=($dialog ? 'true' : 'false');
	$script="print($param);";
	$this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
	//Print on a shared printer (requires at least Acrobat 6)
	$script = "var pp = getPrintParams();";
	if($dialog)
		$script .= "pp.interactive = pp.constants.interactionLevel.full;";
	else
		$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
	$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
	$script .= "print(pp);";
	$this->IncludeJS($script);
}
}

$pdf = new FPDF_CellFiti('P', 'mm', array(74,180));
$pdf->AddPage();
$pdf->SetMargins(-20,-20,-20);
$pdf->AddFont('LucidaConsole','','lucidaconsole.php');
$pdf->SetFont('LucidaConsole','',9);
//DETALLE DE LA EMPRESA
foreach($de as $reg) {
	$pdf->SetXY(0, 5);//modificar solo esto
	$pdf->CellFitScale(58, 3,'RESTAURANTE Y MARISQUERIA', 0, 1, 'C');
	$pdf->SetXY(1, 8);//modificar solo esto
	$pdf->CellFitScale(60, 3,'EL DORADO', 0, 1, 'C');
	$pdf->SetXY(13, 12);//modificar solo esto
	$pdf->CellFitScale(35, 3,utf8_decode($reg['razon_social']), 0, 1, 'C');
	$pdf->SetXY(17, 15);//modificar solo esto
	$pdf->CellFitScale(25, 3,'Ced: '.utf8_decode($reg['ruc']), 0, 1, 'C');
	$pdf->SetXY(5, 18);//modificar solo esto
	$pdf->CellFitScale(50, 3,'Dir: '.utf8_decode($reg['direccion']), 0, 1, 'C');
	$pdf->SetXY(5, 21);//modificar solo esto
	$pdf->CellFitScale(50, 3,'Telf: '.utf8_decode($reg['telefono']), 0, 1, 'C');
	$pdf->SetFont('LucidaConsole','',9);
	$pdf->SetXY(5, 24);//modificar solo esto
	$pdf->CellFitScale(48, 3,utf8_decode($data->desc_td).' DE VENTA: '.utf8_decode($data->ser_doc).'-'.utf8_decode($data->nro_doc), 0, 1, 'C');
	$pdf->SetXY(5, 27);//modificar solo esto
	$pdf->CellFitScale(48, 3,'FECHA: '.date('d-m-Y h:i A',strtotime($data->fec_ven)), 0, 1, 'L');
	$pdf->SetXY(2, 29);//modificar solo esto
	$pdf->CellFitScale(70, 3,'----------------------------------------------', 0, 1, 'L');

	$pdf->SetXY(5, 31);//modificar solo esto
	$pdf->CellFitScale(15, 3,'CLIENTE: ', 0, 1, 'L');
	$pdf->SetXY(17, 31);//modificar solo esto
	$pdf->CellFitScale(55, 3,utf8_decode($data->Cliente->nombre), 0, 1, 'L');
	$pdf->SetXY(5, 34);//modificar solo esto
	$pdf->CellFitScale(15, 3,'Ced: ', 0, 1, 'L');
	$pdf->SetXY(15, 34);//modificar solo esto
	$pdf->CellFitScale(55, 3,$data->Cliente->dni.''.$data->Cliente->ruc, 0, 1, 'L');

	$pdf->SetXY(2, 36);//modificar solo esto
	$pdf->CellFitScale(70, 3,'----------------------------------------------', 0, 1, 'L');
	$pdf->SetFont('LucidaConsole','',9);
  $pdf->SetXY(5, 38);//modificar solo esto
	$pdf->CellFitScale(20, 3,'PRODUCTO', 0, 1, 'L');
	$pdf->SetXY(28, 38);//modificar solo esto
	$pdf->CellFitScale(7, 3,'CANT', 0, 1, 'L');
	$pdf->SetXY(35, 38);//modificar solo esto
	$pdf->CellFitScale(7, 3,'P.U', 0, 1, 'L');
	$pdf->SetXY(45, 38);//modificar solo esto
	$pdf->CellFitScale(8, 3,'IMP.', 0, 1, 'R');
	$pdf->SetXY(2, 40);//modificar solo esto
	$pdf->CellFitScale(70, 3,'----------------------------------------------', 0, 1, 'L');
	$y = 39;
	foreach($data->Detalle as $d){
		$pdf->SetXY(4, $y);//modificar solo esto
		$pdf->CellFitScale(27, 9,utf8_decode($d->Producto->nombre_prod).' '.utf8_decode($d->Producto->pres_prod), 0, 1, 'L');
		$pdf->SetXY(30, $y);//modificar solo esto
		$pdf->CellFitScale(3, 9,$d->cantidad, 0, 1, 'L');
		$pdf->SetXY(32, $y);//modificar solo esto
		$pdf->CellFitScale(11, 9,$d->precio, 0, 1, 'L');
		$pdf->SetXY(43, $y);//modificar solo esto
		$pdf->CellFitScale(11, 9,number_format(($d->cantidad * $d->precio),2), 0, 1, 'R');
		$y = $y + 3;
	}
	/*$y+...*/
	$pdf->SetXY(2, $y);//modificar solo esto
	$pdf->CellFitScale(70, 7,'----------------------------------------------', 0, 1, 'L');
	$pdf->SetXY(6, $y+3);//modificar solo esto
	$pdf->CellFitScale(34, 5,'Importe Total: ', 0, 1, 'R');
	$pdf->SetXY(39, $y+3);//modificar solo esto
	$pdf->CellFitScale(15, 5,number_format(($data->total),2), 0, 1, 'R');
	$pdf->SetXY(2, $y+6);//modificar solo esto
	$pdf->CellFitScale(70, 3,'----------------------------------------------', 0, 1, 'L');
	$z = 3;
	/*$y+6+$z...*/

	$sbt = ($data->total / (1 + $data->igv));
	$igv = (($sbt - $data->descu) * $data->igv);

	if($data->id_tdoc == 1){
		$pdf->SetXY(6, $y+6+$z);//modificar solo esto
		$pdf->CellFitScale(52, 3,'Dscto: '.$_SESSION["moneda"], 0, 1, 'R');
		$pdf->SetXY(58, $y+6+$z);//modificar solo esto
		$pdf->CellFitScale(15, 3,'-'.number_format(($data->descu),2), 0, 1, 'R');
		$a = 3;
	}else{
		$pdf->SetXY(6, $y+6+$z);//modificar solo esto
		$pdf->CellFitScale(34, 3,'SubTotal: ', 0, 1, 'R');
		$pdf->SetXY(39, $y+6+$z);//modificar solo esto
		$pdf->CellFitScale(15, 3,number_format(($sbt),2), 0, 1, 'R');
		$pdf->SetXY(6, $y+6+$z+3);//modificar solo esto
		$pdf->CellFitScale(34, 3,'Imp('.$data->igv.'): ', 0, 1, 'R');
		$pdf->SetXY(39, $y+6+$z+3);//modificar solo esto
		$pdf->CellFitScale(15, 3,number_format(($igv),2), 0, 1, 'R');
		$pdf->SetXY(6, $y+6+$z+6);//modificar solo esto
		$pdf->CellFitScale(34, 3,'Dscto: ', 0, 1, 'R');
		$pdf->SetXY(39, $y+6+$z+6);//modificar solo esto
		$pdf->CellFitScale(15, 3,'-'.number_format(($data->descu),2), 0, 1, 'R');
		$a = 9;
	}
	/*$y+6+$z+$a...*/
	$pdf->SetXY(2, $y+6+$z+$a);//modificar solo esto
	$pdf->CellFitScale(70, 3,'----------------------------------------------', 0, 1, 'L');
	$pdf->SetXY(6, $y+6+$z+$a+3);//modificar solo esto
	$pdf->CellFitScale(34, 3,'TOTAL A PAGAR: ', 0, 1, 'R');
	$pdf->SetXY(39, $y+6+$z+$a+3);//modificar solo esto
	$pdf->CellFitScale(15, 3,number_format(($data->total - $data->descu),2), 0, 1, 'R');
	$pdf->SetXY(2, $y+6+$z+$a+6);//modificar solo esto
	$pdf->CellFitScale(70, 3,'----------------------------------------------', 0, 1, 'L');
	$pdf->SetXY(7, $y+6+$z+$a+9);//modificar solo esto
	$pdf->CellFitScale(45, 3,'SON: '.numtoletras($data->total - $data->descu), 0, 1, 'L');
	$pdf->SetXY(9, $y+6+$z+$a+13);//modificar solo esto
	$pdf->MultiCell(45, 3,'Gracias por su preferencia',0,'C',0,15);
	$pdf->SetFont('LucidaConsole','',7);
	$pdf->SetXY(4, $y+6+$z+$a+20);//modificar solo esto
	$pdf->MultiCell(50, 3,$texto,0,'J',0,15);
}
$pdf->AutoPrint(true);
$pdf->Output();
?>
