<html>
	<style type="text/css">
		table{
		  font-family: Arial;
		  border-style: solid;
		  border-width: 1px;
		}
		th {
		  font-size: 15px;
		  background: #80BF80;
		}
		td {
		  font-size: 12px;
		}
	</style>
	<table>
		<tr>
		<?php foreach ($adeudos as $key => $adeudo) { ?>
			<?php foreach ($adeudo as $key_adeudo => $data_adeudo) { ?>
				<th> <?php  echo  ucfirst($key_adeudo); ?> </th>
			<?php }
				break;
			  ?>
		<?php }  ?>
		</tr>
		
		<?php foreach ($adeudos as $key => $adeudo) { ?>
			<tr>
			<?php foreach ($adeudo as $key_adeudo => $data_adeudo) { ?>
				<td> <?php  echo  ucfirst($data_adeudo); ?> </td>
			<?php } ?>
			</tr>
		<?php } ?>
	</table>
</html>