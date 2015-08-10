<html>
	<meta charset="UTF-8">
	<style type="text/css">
		table{
		  font-family: Arial;
		  border-style: solid;
		  border-width: 1px;
		  border-color: #000000;
		  margin-top: 10px;
		}
		table th {
		  font-size: 15px;
		  background: #80BF80;
		}
		table th #principal {
			font-size: 20px;
			background: #05B202;
		}
		td {
		  font-size: 12px;
		}
	</style>
	<body>
	<table >
		<tr><th id="principal" colspan="3">Reporte de Adeudos</th></tr>
		<tr><td>Fecha: </td><td colspan="2"><?php echo  date('d-m-Y'); ?></td></tr>
		<tr><td>Filtros </td><td colspan="2"> <?php foreach ($filters as $key => $value) {echo trim(str_replace("_"," ",ucfirst($value.', ')),',');} ?> </td> </tr>
	</table>
	<br>
	<br>
	<table border="1">
		<tr>
		<?php
			foreach ($adeudos["alumnos"] as $key => $adeudo) { ?>
			<?php foreach ($adeudo as $key_adeudo => $data_adeudo) { ?>
				 <?php  
				 	if (is_array($data_adeudo)) {
			 			foreach ($data_adeudo as $key_adeudo_row => $adeudo_row) {
							foreach ($adeudo_row as $key_cell => $adeudo_celda) {
								if(in_array($key_cell, $filters)) {
									echo "<th>";			
									echo  str_replace("_"," ",ucfirst("$key_adeudo"));
									echo "</th>";		
									break;
								}
							}
							break;
			 			}
				 	} else {
					 	if (in_array($key_adeudo,$filters)) {
					 		$posiciones[]=$key_adeudo;
					 		echo "<th>";			
							echo  str_replace("_"," ",ucfirst("$key_adeudo"));
							echo "</th>";
					 	}
					 }
				 ?> 
			<?php }
				break;
			  ?>
		<?php } ?>
		</tr>
		
		<?php foreach ($adeudos["alumnos"] as $key => $adeudo_info) { ?>
			<tr>
			<?php foreach ($adeudo_info as $key_adeudo_info => $data_adeudo) { ?>
				 <?php 
					if (is_array($data_adeudo)) {
						$bandera_array=0;
						foreach ($data_adeudo as $key_adeudo_row => $adeudo_row) {
							foreach ($adeudo_row as $key_cell => $adeudo_celda) {
								if (in_array($key_cell, $filters)) {
									$bandera_array=1;
									break;
								}
							}
							break;
			 			}
						if ($bandera_array==1) {
							echo "<td>";
								echo "<table border='1'>";
									foreach ($data_adeudo as $key_adeudo_row => $adeudo) {
										echo "<tr>";
											foreach ($adeudo as $key_adeudo => $adeudo_celda) {
												if (in_array($key_adeudo,$filters)) {
													echo "<th>";
														echo str_replace("_"," ",ucfirst("$key_adeudo"));
													echo "</th>";
												}
											}
										echo "</tr>";
										break;
									}
									foreach ($data_adeudo as $key_adeudo_row => $adeudo) {
										echo "<tr>";
											foreach ($adeudo as $key_adeudo => $adeudo_celda) {
												if (in_array($key_adeudo,$filters)) {
													echo "<td>";
														echo ucfirst("$adeudo_celda");
													echo "</td>";
												}
											}
										echo "</tr>";
									}
								echo "</table>";
							echo "</td>";
						}
					} else {
						if (in_array($key_adeudo_info,$filters)) {
											#echo "<tr>";
									echo "<td rowspan=".(intval(count($adeudo_info['adeudos'])) + 1).">";			
									echo  ucfirst($data_adeudo);
									echo "</td>";
									#echo "</tr>";
						}
					} 
				 
			 } ?>
			</tr>
		<?php  }?>

	</table>
	</body>
</html>