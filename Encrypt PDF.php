<?php

// Database Connection

$host="localhost";
$uname="root";
$pass="exampledbpassword";
$database = "example_db"; 

$connection=mysql_connect($host,$uname,$pass); 

echo mysql_error();

//or die("Database Connection Failed");
$selectdb=mysql_select_db($database) or 
die("Database could not be selected"); 
$result=mysql_select_db($database)
or die("database cannot be selected <br>");

// Fetch Record From Database

$output = "";
$table = ""; // Enter Your Table Name 
$sql = mysql_query("select * from $table");
$columns_total = mysql_num_fields($sql);

// Get Field Name

for ($i = 0; $i < $columns_total; $i++) {
$heading = mysql_field_name($sql, $i);
$output .= '"'.$heading.'",';
}
$output .="\n";

// Get Records From Table

while ($row = mysql_fetch_array($sql)) {
for ($i = 0; $i < $columns_total; $i++) {
$output .='"'.$row["$i"].'",';
}
$output .="\n";
}

// Export As Password Protected PDF File

$filename = "myFile.pdf";
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename='.$filename);

function pdfEncrypt ($origFile, $password, $destFile){
     require_once('fpdi/FPDI_Protection.php');
     $pdf =& new FPDI_Protection();
	 
// Set File Format Of Destinaton File To PDF
     $pdf->FPDF("P", "in", array('8.27','11.69'));
	 
	 
//Calculate No. Of Pages From Original PDF 

     $pagecount = $pdf->setSourceFile($origFile);

// Copy Pages From Original Unprotected PDF Into New PDF File

for ($loop = 1; $loop <= $pagecount; $loop++) {
     $tplidx = $pdf->importPage($loop);
     $pdf->addPage();
     $pdf->useTemplate($tplidx);
}

$pdf->SetProtection(array(),$password);
$pdf->Output($destFile,'F');
return $destFile;
}

//Password For PDF File

$password = "outputfilepassword";

//name of the original file
$origFile = "Report.pdf";

//name of the destination file 
$destFile ="Report_p.pdf";

//Encrypt File Contents And Create New Password Protected PDF

pdfEncrypt($origFile, $password, $destFile );

echo $output;
exit;

?>