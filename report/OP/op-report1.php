
<?php
session_start();
include_once('../../config/config.php');

require_once __DIR__ . '/../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../mpdf/custom/temp/dir/path']);

$mpdf->AddPageByArray([

    'margin-top' => 15,
]);

$empid = $_SESSION['SESS_EMP'];
$PreparedBy = $_SESSION['SESS_NAME'];

$docentry = $_GET['docentry'];

$date = date('m/d/Y');
$htmlheader = '';
$htmldetails = '';
$htmlremarks = '';
$html = '';
$remarks = '';
$no = 1;

$qry = odbc_exec($MSSQL_CONN, " USE [".$_SESSION['mssqldb']."]; 
									SELECT
									   A.CheckKey,
									   A.CheckDate,
									   CASE WHEN B.U_RemitTo IS NULL THEN A.VendorName
											WHEN  B.U_RemitTo = '' THEN A.VendorName 
									   ELSE B.U_RemitTo END AS VendorName,
									   A.CheckSum,
									   B.CounterRef
								FROM OCHO A
								LEFT JOIN OVPM B ON A.TransRef = B.DocNum
								WHERE B.DocEntry = $docentry
								ORDER BY A.CheckKey ");
			 
while (odbc_fetch_row($qry)) 
{
	$CheckKey = odbc_result($qry, 'CheckKey');
	$VendorName = odbc_result($qry, 'VendorName');
	$CheckSum = odbc_result($qry, 'CheckSum');
	$CounterRef = odbc_result($qry, 'CounterRef');
	$CheckDate = date('F d, Y' ,strtotime(odbc_result($qry, 'CheckDate')));
}

/*  function numberTowords($CheckSum)
{
  $ones = array(        
    1 => "one", 
    2 => "two", 
    3 => "three", 
    4 => "four", 
    5 => "five", 
    6 => "six", 
    7 => "seven", 
    8 => "eight", 
    9 => "nine", 
    10 => "ten", 
    11 => "eleven", 
    12 => "twelve", 
    13 => "thirteen", 
    14 => "fourteen", 
    15 => "fifteen", 
    16 => "sixteen", 
    17 => "seventeen", 
    18 => "eighteen", 
    19 => "nineteen" 
    ); 
    $tens = array( 
    1 => "ten",
    2 => "twenty", 
    3 => "thirty", 
    4 => "forty", 
    5 => "fifty", 
    6 => "sixty", 
    7 => "seventy", 
    8 => "eighty", 
    9 => "ninety" 
); 

$hundreds = array( 
    "hundred", 
    "thousand", 
    "million", 
    "billion", 
    "trillion", 
    "quadrillion" 
);  

$num = number_format($CheckSum,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr); 
$rettxt = ""; 

foreach($whole_arr as $key => $i){ 

    if($i < 20){ 
        $rettxt .= $ones[$i]; 
    }elseif($i < 100){ 
        $rettxt .= $tens[substr($i,0,1)]; 
        @$rettxt .= " ".$ones[substr($i,1,1)]; 
    }else{ 
        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        $rettxt .= " ".$tens[substr($i,1,1)]; 
        $rettxt .= " ".$ones[substr($i,2,1)]; 
    } 
    if($key > 0){ 
        $rettxt .= " ".$hundreds[$key]." "; 
    } 
} 
if($decnum == 0)
{ 
    $rettxt .= " AND XX / 100 PESOS** "; 
	if($decnum < 20)
	{
		$rettxt .= $ones[intval( $decnum) ]; 
	}
	elseif( $decnum < 100)
	{ 
		$rettxt .= $tens[substr($decnum,0,1)]; 
		$rettxt .= " ".$ones[substr($decnum,1,1)]; 
	} 
 } 
 else
 {
	 $rettxt .= " AND ".$decnum." / 100 PESOS** "; 
 }
 return $rettxt; 
}  */

function numberTowords($num)
{

    $ones = array(
        0 =>"ZERO",
        1 => "ONE",
        2 => "TWO",
        3 => "THREE",
        4 => "FOUR",
        5 => "FIVE",
        6 => "SIX",
        7 => "SEVEN",
        8 => "EIGHT",
        9 => "NINE",
        10 => "TEN",
        11 => "ELEVEN",
        12 => "TWELVE",
        13 => "THIRTEEN",
        14 => "FOURTEEN",
        15 => "FIFTEEN",
        16 => "SIXTEEN",
        17 => "SEVENTEEN",
        18 => "EIGHTEEN",
        19 => "NINETEEN"
    );

    $tens = array( 
        0 => "ZERO",
        1 => "TEN",
        2 => "TWENTY",
        3 => "THIRTY", 
        4 => "FORTY", 
        5 => "FIFTY", 
        6 => "SIXTY", 
        7 => "SEVENTY", 
        8 => "EIGHTY", 
        9 => "NINETY" 
    ); 

    $hundreds = array( 
        "HUNDRED", 
        "THOUSAND", 
        "MILLION", 
        "BILLION", 
        "TRILLION", 
        "QUARDRILLION" 
    ); /*limit t quadrillion */

    $num = number_format($num,2,".",","); 
    $num_arr = explode(".",$num); 
    $wholenum = $num_arr[0]; 
    $decnum = $num_arr[1]; 

    $whole_arr = array_reverse(explode(",",$wholenum)); 
    krsort($whole_arr,1); 

    $rettxt = ""; 
    foreach($whole_arr as $key => $i)
	{
        while(substr($i,0,1)=="0")
            $i=substr($i,1,5);
        if($i < 20){ 
            $rettxt .= $ones[$i]; 
        }elseif($i < 100){ 
            if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
            if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
        } else{ 
             if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
            if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
            if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
        } 
        if($key > 0){ 
            $rettxt .= " ".$hundreds[$key]." "; 
        }
    } 
    if($decnum > 0)
	{
        $rettxt .= " AND ";
        if($decnum < 20) 
		{
            $rettxt .= $decnum." / 100 PESOS**";
        } 
		elseif($decnum < 100) 
		{
            $rettxt .= " ". $decnum. "  / 100 PESOS**";
        }
    }
	else
	{
		$rettxt .= " AND XX / 100 PESOS**"; 
	}
		
    return $rettxt;
}

$html .= '
		<div class="row">
            <div class="col-lg-12">
				<table border="0">
					<tbody>
						<tr>
							<td width="60px"><div></div></td>
							<td width="450px"><div><b></b></div></td>
							<td width="170px" align="left"><div><b>'.$CheckDate.'</b></div></td>
						</tr>
						<tr>
							<td width="60px"><div></div></td>
							<td width="450px" style="padding-top:10px; padding-bottom:10px;"><div><b>'.$VendorName.'</b></div></td>
							<td width="170px" align="left"><div><b>'.number_format($CheckSum,2).'</b></div></td>
						</tr>
						<tr>
							<td width="690px" colspan="3"><div><b><span style="font-size:9pt;">**'.strtoupper(numberTowords($CheckSum)).'</span></b></div></td>
						</tr>
					</tbody>
				</table>
				<br>
				<br>
			</div>
          </div>
        ';

$mpdf->SetWatermarkText('');
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;


$stylesheet = file_get_contents('../../mpdf/mpdf_css/cv-report.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML(utf8_encode($html));

$mpdf->Output();
exit;

?>