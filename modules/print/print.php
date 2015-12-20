<?php

function print_pdf($pdf_filename, $pdf_body){

	// Include the main TCPDF library (search for installation path).
	require_once(ABSPATH.'lib/tcpdf/tcpdf.php');
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Περιφέρεια Δυτικής Μακεδονίας');
	$pdf->SetTitle('Περιφέρεια Δυτικής Μακεδονίας');
	$pdf->SetSubject('Περιφέρεια Δυτικής Μακεδονίας');
	$pdf->SetKeywords('Περιφέρεια Δυτικής Μακεδονίας');

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// ---------------------------------------------------------
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);

	// set font
	$pdf->SetFont('freeserif', '', 11);

	// add a page el
	$pdf->AddPage();
	$pdf->Write(0, '', '', 0, 'L', true, 0, false, false, 0);
	$pdf->writeHTML($pdf_body, true, false, false, false, '');

	//Close and output PDF document
	$pdf->Output($pdf_filename, 'F');
	
}
