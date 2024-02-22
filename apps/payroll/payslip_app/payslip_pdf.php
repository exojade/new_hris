<link rel="stylesheet" href="AdminLTE/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">

<style>

@font-face { 
	 font-family: 'Space Grotesk';
	 font-style: normal;
	 font-weight: 400;
	 font-display: swap;
	 src: url('file_folder/resources/fonts/SpaceGrotesk-Regular.ttf'); 
 }

 hr{
  margin-bottom: 2px;
  margin-top: 2px;
 }

html *
{
	font-family: 'Space Grotesk', sans-serif;
}
.tabular, .tabular th, .tabular td {
   border: 1px solid black !important;
}
.table td{
	font-size: 11.3px !important;
}

.table{
  margin-bottom:1px !important;
}

#fordtr{font-size:20px;}
#fordtr td{padding: 1px !important;}
#fordtr th{padding: 7px !important;}

p{
  margin:0px;
}
	</style>

<style>

</style>
<?php

$options = unserialize($_GET["options"]);
// dump($options);
if($options["category"] == "department"):
  $actual = query(
    "select p.*, e.FirstName, e.LastName, g.barcode, d.DeptCode from payroll_actual p 
            left join tblemployee e
            on e.Employeeid = p.employee_id
            left join payroll_group g
            on g.payroll_id = p.payroll_id
            left join tbldepartment d
            on d.Deptid = e.Deptid
            where p.year=? and p.month = ? and department_assigned = ?
            and g.payroll_type = 'SALARY'
            and g.payroll_status = 'done'
            order by e.LastName, e.FirstName",
            $options["year"], $options["month"], $options["option"]
    );
    elseif($options["category"] == "employee"):
      $actual = query(
        "select p.*, e.FirstName, e.LastName, g.barcode, d.DeptCode from payroll_actual p 
                left join tblemployee e
                on e.Employeeid = p.employee_id
                left join payroll_group g
                on g.payroll_id = p.payroll_id
                left join tbldepartment d
                on d.Deptid = e.Deptid
                where p.year=? and p.month = ? and p.employee_id = ?
                and g.payroll_type = 'SALARY'
                and g.payroll_status = 'done'
                order by e.LastName, e.FirstName",
                $options["year"], $options["month"], $options["option"]
        );
    endif;


// dump($actual);



						// $payroll_loans = query("select * from payroll_loans_actual where payroll_id = ?", $_GET["payroll_id"]);
// dump($actual);
?>
<div class="row">
<?php
$counter = 0;

foreach($actual as $a): 
// dump($a);
	

?>
	<div class="col-md-6 col-xs-6 col-sm-6" style="page-break-inside: avoid; page-break-after: always; height: 650px; border:1px solid black;">

		<div class="watermarked">
		<!-- <img style="position: absolute;" src="pdf/background.png" /> -->
            <div class="box-body">
			<b><p class="text-left" style="font-size: 12px;">CITY GOVERNMENT OF PANABO <span class="pull-right">CODE: <?php echo($a["barcode"]); ?></span></p></b>
			<table class="table" style="border: none !important; width:100%;" style="margin-bottom:5px !important;">
			<style>
				td{
					border:none !important;
					padding:1px !important;
					font-size:40px;
				}
			</style>
				<tr>
					<td width="15%">Name</td>
					<td>: <?php echo($a["LastName"] . ", " . $a["FirstName"]); ?></td>
				</tr>
				<tr>
					<td>Position</td>
					<td>: <?php echo($a["position_title"]) ?></td>
				</tr>
				<tr>
					<td>Office</td>
					<td>: <?php echo($a["DeptCode"]) ?></td>
				</tr>
				<tr>
					<td>Period</td>
					<td>: <?php echo(to_full_month($options["month"]) . ", " . $options["year"]); ?></td>
				</tr>
			</table>
			
      <div class="row">
        <div class="col-xs-6">
          <table class="table" style="border: none !important; width:100%;">
            <tr>
              <td>Basic Salary:</td>
              <td style="text-align: right;"> <?php echo(to_peso($a["salary"])); ?></td>
            </tr>
            <tr>
              <td>PERA:</td>
              <td style="text-align: right;"> <?php echo(to_peso($a["pera"])); ?></td>
            </tr>

            <?php if($a["representation_allowance"] != ""): ?>
            <tr>
              <td>Representation:</td>
              <td style="text-align: right;"> <?php echo(to_peso($a["accrued_ra"])); ?></td>
            </tr>
            <?php endif; ?>
          </table>
        </div>


        <div class="col-xs-6">
          <table class="table" style="border: none !important; width:100%;">
            <tr>
              <td>Salary Earned:</td>
              <td style="text-align: right;"> <?php echo(to_peso($a["accrued_salary"])); ?></td>
            </tr>
            <tr>
              <td>PERA Earned:</td>
              <td style="text-align: right;"> <?php echo(to_peso($a["accrued_pera"])); ?></td>
            </tr>

            

            <?php if($a["travel_allowance"] != ""): ?>
            <tr>
              <td>Travel:</td>
              <td style="text-align: right;"> <?php echo(to_peso($a["accrued_ta"])); ?></b></td>
            </tr>
            <?php endif; ?>
          </table>
        </div>
      </div>
			
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<table class="table" style="border: none !important; width:100%;">
						<td colspan="2"><b>PERSONAL SHARE</b></td>
						<?php
						$personal_deductions = unserialize($a["personal_deductions"]);
						foreach($personal_deductions as $row):
              if($row["amount"] != ""):
						?>
						<tr>
							<td><?php echo($row["title"]); ?></td>
							<td style="text-align: right;"><?php echo(to_peso($row["amount"])); ?></td>
						</tr>
						<?php endif; ?>
						<?php endforeach; ?>
					</table>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6">
					<table class="table" style="border: none !important; width:100%;">
						<td><b>EMPLOYER SHARE</b></td>
						<?php
						$government_deductions = unserialize($a["government_deductions"]);
						foreach($government_deductions as $row):
              if($row["amount"] != ""):
						?>
						<tr>
							<td><?php echo($row["title"]); ?></td>
							<td style="text-align: right;"><?php echo(to_peso($row["amount"])); ?></td>
						</tr>
						<?php endif; ?>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
      <hr>
      <?php 
						$others = unserialize($a["other_deductions"]);
			
						if(!empty($others)): ?>
			<div class="row">
          <?php
              $leftColumn = array();
              $rightColumn = array();
              $arraySize = count($others);
              // dump($others);
              $midpoint = ceil($arraySize / 2);
              
              for ($i = 0; $i < $arraySize; $i++) {
                  if ($i < $midpoint) {
                      $leftColumn[] = $others[$i];
                  } else {
                      $rightColumn[] = $others[$i];
                  }
              }
              // dump($rightColumn);
            ?>
				<div class="col-md-6 col-sm-6 col-xs-6">
					<table class="table" style="border: none !important; width:100%;">
						<td colspan="2"><b>OTHERS</b></td>
            <?php foreach($leftColumn as $row): ?>
            <tr>
							<td><?php echo($row["title"]); ?></td>
							<td style="text-align: right;"><?php echo(to_peso($row["amount"])); ?></td>
						</tr>
            <?php endforeach; ?>
					</table>
				</div>
        <div class="col-md-6 col-sm-6 col-xs-6">
					<table class="table" style="border: none !important; width:100%;">
						<td colspan="2"><b>OTHERS</b></td>
            <?php foreach($rightColumn as $row): ?>
            <tr>
							<td><?php echo($row["title"]); ?></td>
							<td style="text-align: right;"><?php echo(to_peso($row["amount"])); ?></td>
						</tr>
            <?php endforeach; ?>
					</table>
				</div>
			</div>
      <hr>
      <?php endif; ?>
      

			<div class="row" style="margin-top:10px;">
				<div class="col-md-6 col-xs-6">
				<table class="table" style="border: none !important; width:100%;">
						<tr>
							<td>1st Quincena</td>
							<td style="text-align: right;"><?php echo(number_format((float)$a["first_quincena"],2, ".", ",")); ?></td>
						</tr>
						<tr>
							<td>2nd Quincena</td>
							<td style="text-align: right;"><?php echo(number_format((float)$a["second_quincena"],2, ".", ",")); ?></td>
						</tr>
					</table>
				</div>
				
						<div class="col-md-6 col-xs-6">
						<table class="table" style="border: none !important; width:100%;">
              <?php if($a["lwop"] != ""): ?>
                <?php if($a["lwop"] != 0): ?>
                  <tr>
                    <td>LWOP (<?php echo($a["lwop"]); ?> days)</td>
                    <td style="text-align: right;"><?php echo(number_format((float)$a["salary"] - $a["accrued_salary"],2, ".", ",")); ?></td>
                  </tr>
                  <tr>
                    <td>LWOP PERA</td>
                    <td style="text-align: right;"><?php echo(number_format((float)$a["pera"] - $a["accrued_pera"],2, ".", ",")); ?></td>
                  </tr>
                <?php endif; ?>
              <?php endif; ?>
                  <tr>
                    <td>Net:</td>
                    <td style="text-align: right;"><b> <?php echo(to_peso(to_amount($a["accrued_ra"]) + to_amount($a["accrued_ta"]) + to_amount($a["accrued_salary"]) + to_amount($a["accrued_pera"]) - to_amount($a["personal_total"]) - to_amount($a["other_total"]))); ?></b></td>
                  </tr>
					</table>
				</div>

			</div>

		<br>

			<div style="position:absolute; bottom:0; ">
			<p class="text-left" style="font-size: 12px; width:95%;">I HEREBY CERTIFY that the above name personnel received					
				the earnings in full for the pay period stated above.					
			</p>
			<br>
			<br>
			<div class="row" style="padding-right: 20px; margin-bottom:10px;">
				<div class="col-md-5 col-sm-5 col-xs-5">
            <img src="file_folder/payslip/hr_signature.png" style="width: 80%; height:auto; position:absolute; top:-40;left:15;" >
						<p class="text-center" style="font-size: 12px; border-bottom: 1px solid black;">JAN MARI G. CAFE</p>
						<p class="text-center" style="font-size: 12px; ">CGDH I - CHRMO</p>
				</div>
				<div class="col-md-7 col-sm-7 col-xs-7">
						<p class="text-center" style="font-size: 12px; border-bottom: 1px solid black;"><?php echo($a["FirstName"] . " " . $a["LastName"]); ?></p>
						<p class="text-center" style="font-size: 12px; ">Signature of Employee</p>
				</div>
			</div>
			</div>
            </div>
          </div>
  </div>
  <?php
 $counter++; 
  if($counter % 4 == 0): ?>
	<div style="clear: both;
        page-break-after: always;"></div>
	<?php endif; ?>
<?php endforeach; ?>
  </div>
<script>
$(".select2").select2();
</script>
