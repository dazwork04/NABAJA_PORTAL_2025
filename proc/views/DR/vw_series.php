<?php include_once('../../../config/config.php');

$objtype = $_GET['objtype'];
$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; SELECT Series, SeriesName, NextNumber,'' AS 'BPLId' FROM NNM1 WHERE ObjectCode = '$objtype' ORDER BY Series");
while (odbc_fetch_row($qry)) {
	echo '<li>
			<a class="series" val-series="'.odbc_result($qry, 'Series').'" val-seriesname="'.odbc_result($qry, 'SeriesName').'" val-nextnum="'.odbc_result($qry, 'NextNumber').'" val-bplid="'.odbc_result($qry, 'BPLId').'">'.odbc_result($qry, 'SeriesName').'</a>
		 </li>';
}
odbc_free_result($qry);
odbc_close($MSSQL_CONN);

?>
