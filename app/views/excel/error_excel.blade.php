<html>
	<meta charset="UTF-8">
	<style type="text/css">
		.contenedor-mensaje {
			width: 60%;
			height: 12rem;
			margin: calc(30% - 12rem) auto;
			background-color: #F2F2F2;
			border-style: solid;
			border-width: 2px;
			border-color: #BDBDBD;
		}
		.titulo-mensaje {
			margin-left: 5%;
		}
		.mensaje-error {
			margin-left: 2%;
		}
	</style>
	<body>
		<div class="contenedor-mensaje">
			<h1 class="titulo-mensaje">¡Error!</h1>
			<h3 class="mensaje-error">Key(reporte) incorrecta o caduca: <?php echo $key; ?></h3>
		</div>
	</body>
</html>