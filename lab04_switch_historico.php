<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Lab04-Histórico switch</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta http-equiv="refresh" content="30"> 	<!-- Refresh automático -->
	<?php 
		$file_prefix = "lab04_switch";
	?>	
</head>

<body>
	<h1>Lab04 - Histórico das transições de estado do switch</h1>
	
	<div>
		<h3>Estado (Data de atualização)</h3>
		<p>
			<?php 
				echo(nl2br(file_get_contents("files/" . $file_prefix . "_historico.txt"))); 
			?>
		</p>
	</div>
		
	<br />
	<br />
	<br />
	<a href="index.html">Página inicial</a>	
</body>

</html>