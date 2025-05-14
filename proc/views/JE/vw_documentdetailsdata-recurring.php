<?php  include_once('../../../config/config.php');

$docentry = $_GET['docentry'];
$ctr = 1;
$item_index = 0;

$qry = odbc_exec($MSSQL_CONN, "USE [".$_SESSION['mssqldb']."]; 
																		SELECT 
																			T0.TransId, 
																			CASE WHEN T1.Account = T1.ShortName THEN T1.Account ELSE T1.ShortName END AS Account,
																			CASE WHEN T1.Account = T1.ShortName THEN T2.AcctName ELSE (SELECT CardName FROM OCRD WHERE CardCode = T1.ShortName) END AS AcctName,
																			T1.Debit,
																			T1.Credit,
																			T1.ProfitCode,
																			T4.PrcName AS DeptName,
																			T1.Project,
																			T5.PrjName AS PrjName,
																			T1.OcrCode2,
																			T6.PrcName AS EmpName,
																			T1.OcrCode3,
																			T7.PrcName AS EquipName,
																			CASE WHEN T1.WTLiable = 'N' THEN 'No' ELSE 'Yes' END AS WTLiable, 
																			T1.VatGroup,
																			T1.VatLine,
																			T1.LineMemo
																		FROM OJDT T0
																		INNER JOIN JDT1 T1 ON T0.TransId = T1.TransId
																		LEFT JOIN OACT T2 ON T1.Account = T2.AcctCode
																		LEFT JOIN OPRC T4 ON T1.ProfitCode = T4.PrcCode
																		LEFT JOIN OPRJ T5 ON T1.Project = T5.PrjCode
																		LEFT JOIN OPRC T6 ON T1.OcrCode2 = T6.PrcCode
																		LEFT JOIN OPRC T7 ON T1.OcrCode3 = T7.PrcCode
																		WHERE T0.TransId = $docentry
																		ORDER BY T1.Line_ID ASC");

while (odbc_fetch_row($qry)) 
{
	$TransId = odbc_result($qry, "TransId");
	$Account = odbc_result($qry, "Account");
	$AcctName = utf8_encode(odbc_result($qry, "AcctName"));
	$Debit = number_format(odbc_result($qry, "Debit"),2);
	$Credit = number_format(odbc_result($qry, "Credit"),2);
	$ProfitCode = utf8_encode(odbc_result($qry, "ProfitCode"));
	$DeptName = utf8_encode(odbc_result($qry, "DeptName"));
	$Project = utf8_encode(odbc_result($qry, "Project"));
	$PrjName = utf8_encode(odbc_result($qry, "PrjName"));
	$OcrCode3 = utf8_encode(odbc_result($qry, "OcrCode2"));
	$EmpName = utf8_encode(odbc_result($qry, "EmpName"));
	$OcrCode4 = utf8_encode(odbc_result($qry, "OcrCode3"));
	$EquipName = utf8_encode(odbc_result($qry, "EquipName"));
	$WTLiable = utf8_encode(odbc_result($qry, "WTLiable"));
	$VatGroup = utf8_encode(odbc_result($qry, "VatGroup"));
	$VatLine = utf8_encode(odbc_result($qry, "VatLine"));
	$LineMemo = utf8_encode(odbc_result($qry, "LineMemo"));
	
	if($VatLine == 'Y')
	{
		$lineno = 1;
	}
	else
	{
		$lineno = 0;
	}
	
	echo '
		<tr>
			<td style="padding-top: 2px;  padding-bottom: 2px;" class="rowno text-center">
				 '.$ctr.'
			</td>
			<td style="padding-top: 2px;  padding-bottom: 2px;">
				<div class="input-group acctcodeCont">
					<span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#BPModal"><span class="glyphicon glyphicon-user"></span></span>
					<input class="form-control input-sm acctcode required"  value="'.$Account.'" disabled/>
					<input type="hidden" class="form-control input-sm cat" disabled/>
					<span style="height: 18px; padding: 0 4px; margin: 0;" class="input-group-addon" data-toggle="modal" data-target="#AcctModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
					
			</td>
			<td style="padding-top: 2px;  padding-bottom: 2px;">
				<input class="form-control input-sm acctname required" value="'.$AcctName.'" readonly/>
			</td>
			<td style="padding-top: 2px;  padding-bottom: 2px;">
				<input class="form-control input-sm debit" value="'.$Debit.'"/>
			</td>
			<td style="padding-top: 2px;  padding-bottom: 2px;">
				<input class="form-control input-sm credit" value="'.$Credit.'"/>
			</td>
			<td style="padding-top: 2px;  padding-bottom: 2px;">
				<select class="form-control input-sm taxgroup">
						<option value="'.$VatGroup.'" val-rate="12.00">'.$VatGroup.'</option>
					</select>
				</td>
			<td style="padding-top: 2px;  padding-bottom: 2px;">
				<select class="form-control input-sm wtax">
					<option value="">'.$WTLiable.'</option>
				</select>
			</td>
			<td style="padding-top: 2px;  padding-bottom: 2px;">
				<input class="form-control input-sm linememo" value="'.$LineMemo.'" maxlength="254">
			</td>
			<td>
				<div class="input-group departmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm departmentcode" value="'.$ProfitCode.'" readonly/>
					<input class="form-control input-sm departmentname" value="'.$DeptName.'" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#DepartmentModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td>
				<div class="input-group projectCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm projectcode" value="'.$Project.'" readonly/>
					<input class="form-control input-sm projectname" value="'.$PrjName.'" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#ProjectModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td>
				<div class="input-group employeeCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm employeecode" value="'.$OcrCode3.'" readonly/>
					<input class="form-control input-sm employeename" value="'.$EmpName.'" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EmployeeModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td>
				<div class="input-group equipmentCont" style="height: 18px; padding: 0 4px; margin: 0;">
					<input type="hidden" class="form-control input-sm equipmentcode" value="'.$OcrCode4.'" readonly/>
					<input class="form-control input-sm equipmentname" value="'.$EquipName.'" readonly/>
					<span class="input-group-addon" style="height: 18px; padding: 0 4px; margin: 0;" data-toggle="modal" data-target="#EquipmentModal"><span class="glyphicon glyphicon-list"></span></span>
				</div>
			</td>
			<td class="hidden">
				<input type="hidden" class="form-control input-sm lineno" value="'.$lineno.'" readonly/>
				<input type="hidden" class="form-control input-sm item_index" value="'.$item_index.'" readonly/>
				<input type="hidden" class="form-control input-sm wtaxindex" value="0" readonly/>
			</td>';

	$ctr += 1;
	$item_index += 1;
}

odbc_free_result($qry);
odbc_close($MSSQL_CONN);


