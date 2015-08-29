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
		  font-size: 10px;
		}
	</style>
	<body>
	<table border="1">
		<tr><th class="sprincipal" style="background: #FA5858;" colspan="5">REPORTE DE BECAS</th></tr>
		<tr><td>Fecha: </td><td colspan="4"><?php echo  date('d-m-Y'); ?></td></tr>
		<tr><td>Beca:  </td><td colspan="4"><?php if(isset($becas[0]['descripcion'])){echo $becas[0]['abreviatura']."-".$becas[0]['descripcion'];} ?> </td></tr>
	</table>
	<br>
	<br>
	<!-- Becas Activas LICENCIATURA  -->
	<table border="0" >
		<tr><th id="principal" colspan="5">LICENCIATURA BECAS ACTIVAS: </th></tr>
	</table>

	<table border="1">
		<tr>
		<?php
			foreach ($becas as $key => $beca) { ?>
			<?php  
				$filas=5;
				if (intval($beca['status'])==1 && intval($beca['idnivel'])==1) {
					echo "<tr>";
					while ($filas > 0) {
						echo "<td>";
							echo strtoupper($beca['matricula']);
						echo "</td>";
						$filas--;
					}	
					echo "</tr>";
				}
			?>
		<?php } ?>
		</tr>
	</table>
	<!-- Becas Canceladas LICENCIATURA  -->
	<br>
	<br>
	<table border="1">
		<tr><th id="principal" colspan="5">LICENCIATURA BECAS CANCELADAS: </th></tr>
	</table>

	<table border="1">
		<tr>
		<?php
			foreach ($becas as $key => $beca) { ?>
			<?php  
				$filas=5;
				if (intval($beca['status'])==0 && intval($beca['idnivel'])==1) {
					echo "<tr>";
					while ($filas > 0) {
						echo "<td>";
							echo strtoupper($beca['matricula']);
						echo "</td>";
						$filas--;
					}	
					echo "</tr>";
				}
			?>
		<?php } ?>
		</tr>
	</table>
	<!-- Becas Activas MAESTRIA  -->
	<br>
	<br>
	<table border="1">
		<tr id="principal" colspan="5" ><th>MAESTRIA BECAS ACTIVAS: </th></tr>
	</table>

	<table border="1">
		<tr>
		<?php
			foreach ($becas as $key => $beca) { ?>
			<?php  
				$filas=5;
				if (intval($beca['status'])==1 && intval($beca['idnivel'])!=1) {
					echo "<tr>";
					while ($filas > 0) {
						echo "<td>";
							echo strtoupper($beca['matricula']);
						echo "</td>";
						$filas--;
					}	
					echo "</tr>";
				}
			?>
		<?php } ?>
		</tr>
	</table>
	<!-- Becas Canceladas MAESTRIA  -->
	<br>
	<br>
	<table border="1" >
		<tr id="principal" colspan="5"><th>MAESTRIA BECAS CANCELADAS: </th></tr>
	</table>

	<table border="1">
		<tr>
		<?php
			foreach ($becas as $key => $beca) { ?>
			<?php  
				$filas=5;
				if (intval($beca['status'])==0 && intval($beca['idnivel'])!=1) {
					echo "<tr>";
					while ($filas > 0) {
						echo "<td>";
							echo strtoupper($beca['matricula']);
						echo "</td>";
						$filas--;
					}	
					echo "</tr>";
				}
			?>
		<?php } ?>
		</tr>
	</table>
	</body>
</html>	