<html>
	<meta charset="UTF-8">
	<body>
		<table border="1">
			<tr>
				<td colspan="6">
				</td>
				<td class="titulos">Año</td>
			</tr>
			<tr>
				<td colspan="6">
				</td>
				<td class="titulos">Mes</td>
			</tr>
			<tr>
				<td colspan="6"></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="6">
				
				</td>
				<td colspan="4">
					<?php echo date('Y',strtotime('now')); ?>
				</td>
			</tr>
			<tr>
				<td colspan="6">
					
				</td>
				<td colspan="4" >
					<?php echo $meses[date('m', strtotime('now'))-1]; ?>
				</td>
			</tr>
		</table>
		<table border="1">
			<tr>
				<td>Periodo</td>
				<td>Sub Concepto</td>
				<td>Clave</td>
				<td>Matricula</td>
				<td>Alumno</td>
				<td>Mes</td>
				<td>Alumnos</td>
				<td>Importe</td>
				<td>Recargos</td>
				<td>Beca</td>
				<td>Desvuentos</td>
				<td>Total</td>
			</tr>
			<?php
				foreach ($adeudos['periodos'] as $key_periodo => $periodo) {
					echo "<tr>";
					echo "	<td rowspan='".($periodo["num_adeudos"]+count($periodo['subconceptos']) + 1)."'>"; #
					echo $periodo['periodo'];
					echo "	</td>";
					foreach ($periodo["subconceptos"] as $key_subconcepto => $subconcepto) {
						#echo "<td rowspan='".(count($subconcepto['adeudo_info']))."'>";
						echo "<tr>";
							echo "<td rowspan='".(count($subconcepto['adeudo_info'])+1)."'>";
								echo $subconcepto['sub_concepto'];
							echo "</td>";
						
						foreach ($subconcepto['adeudo_info'] as $key_adeudo => $adeudo_info) {
							echo "<tr>";
							if (isset($adeudo_info['importe_total'])) {
								echo "<td colspan='4'>";
								echo "Total";
								echo "</td>";
								foreach ($adeudo_info as $key_info => $info) {
									if ($key_info=="alumnos_total") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="importe_total") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="recargo_total") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="beca_total") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="descuento_total") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="total") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
								}
							} else {
								foreach ($adeudo_info as $key_info => $info) {
									if ($key_info=="clave") {
										echo "<td>";
										echo $info;
										echo "</td>";
									}
									if ($key_info=="matricula") {
										echo "<td>";
										echo $info;
										echo "</td>";
									}
									if ($key_info=="nombre") {
										echo "<td>";
										echo $adeudo_info['nombre']." ".$adeudo_info['apellido paterno'].' '.$adeudo_info['apellido materno'];
										echo "</td>";	
									}
									if ($key_info=="fecha_limite") {
										echo "<td>";
										echo $meses[date('m', strtotime($info))-1];
										echo "</td>";	

										echo "<td>";
										echo 1;
										echo "</td>";		
									}
									if ($key_info=="importe") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="recargo") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="beca") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="descuento") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
									if ($key_info=="total") {
										echo "<td>";
										echo $info;
										echo "</td>";	
									}
								}
							}
							echo "</tr>";
						}
						echo "</tr>";
						#echo "</td>";
					
					}
					echo "</tr>";
				}
			 ?>
		</table>

	</body>
</html>