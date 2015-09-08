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
		table th .sprincipal {
			font-size: 25px;
			background: #FA5858;
		}
		td {
		  font-size: 12px;
		}
	</style>
	<body>
	<table >
		<tr><th class="sprincipal" style="background: #FA5858;" colspan="3">Reporte</th></tr>
		<tr><td>Fecha: </td><td colspan="2"><?php echo  date('d-m-Y'); ?></td></tr>
		<tr><td>Filtros </td><td colspan="2"> <?php foreach ($filters as $key => $value) {echo trim(str_replace("_"," ",ucfirst($value.', ')),',');} ?> </td> </tr>
	</table>
	<br>
	<br>
	<table>
		<tr>
		<?php
			foreach ($data as $key_data => $data_row) { ?>
			<?php foreach ($data_row as $key_field => $field) { ?>
				 <?php  
				 	if (is_array($field)) {
			 			foreach ($field as $key_field_row => $field_row) {
							foreach ($field_row as $key_sub_field => $sub_field) {
								if(in_array($key_sub_field, $filters)) {
									echo "<th>";			
									echo  str_replace("_"," ",ucfirst("$key_field"));
									echo "</th>";		
									break;
								}
							}
							break;
			 			}
				 	} else {
					 	if (in_array($key_field,$filters)) {
					 		$posiciones[]=$key_field;
					 		echo "<th>";			
							echo  str_replace("_"," ",ucfirst("$key_field"));
							echo "</th>";
					 	}
					 }
				 ?> 
			<?php }
				break;
			  ?>
		<?php } ?>
		</tr>		


		<?php foreach ($data as $key_data => $row) { ?>
			<tr>
			<?php foreach ($row as $key_row => $field) { ?>
				 <?php 
					if (is_array($field)) {
						$bandera_array=0;
						foreach ($field as $key_field_row => $field_row) {
							foreach ($field_row as $key_cell => $field_celda) {
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
									foreach ($field as $key_field_row => $field_row) {
										echo "<tr>";
											foreach ($field_row as $key_field_celda => $field_celda) {
												if (in_array($key_field_celda,$filters)) {
													echo "<th>";
														echo str_replace("_"," ",ucfirst("$key_field_celda"));
													echo "</th>";
												}
											}
										echo "</tr>";
										break;
									}
									foreach ($field as $key_field_row => $field_row) {
										echo "<tr>";
											foreach ($field_row as $key_field_celda => $field_celda) {
												if (in_array($key_field_celda,$filters)) {
													echo "<td>";
														echo ucfirst("$field_celda");
													echo "</td>";
												}
											}
										echo "</tr>";
									}
								echo "</table>";
							echo "</td>";
						}
					} else {
						if (in_array($key_row,$filters)) {
											#echo "<tr>";
								
									echo "<td>";			
									echo  ucfirst($field);
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