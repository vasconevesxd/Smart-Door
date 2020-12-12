<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Vigilância</title>
	<link rel="stylesheet" type="text/css" href="style.css">	
	<meta http-equiv="refresh" content="100">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy"
	    crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
	    crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
	    crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4"
	    crossorigin="anonymous"></script>
	<?php 
		$file_prefix = "object_type";
	?>
</head>

<body>
	<div class="container-fluid" style="background-image:url('images/surveillance.jpg');">
	</div>

	<div class="jumbotron" style="background-image:url('images/surveillance.jpg');background-repeat:no-repeat;background-size:cover;">
	
	</div>

	<div class="container-fluid">
		<h1 class="text-center">Vigilância</h1>
	</div>
	
	<div>
	<div class="container-fluid">
		<div class="row">
			<div class="span6" style="float: none; margin: 0 auto;">
				<h3>Estado atual:</h3>
				<form method="GET" action="lab04_switch.php">
					<input type="submit" class="btn btn-primary" name="on" value="Turn On">
				</form>
				<a href="index.html">Página inicial</a>	

		<p>	
		<?php
			if(isset($_GET['on'])){
				shell_exec("sudo python api/object_detection/real_time_object_detection.py --prototxt api/object_detection/MobileNetSSD_deploy.prototxt.txt --model api/object_detection/MobileNetSSD_deploy.caffemodel");													
				$file = "api/object_detection/" . $file_prefix . "_value.txt";				
				if (file_exists($file)){
					$file_value = file_get_contents($file);					
					$status_object = "";
					if ($file_value == "Human")
						$status_object = "Human";												
			?>
				<img src="images/<?php echo($status_object) ?>.png" alt="Object: <?php echo($status_object); ?>">									
			<?php
				}
				else
						echo("(erro: não foi possível obter dados!)"); 		
			}								
			?>
			
		</p>
		<h3>Data atualização:</h3>
		<p>
			<?php 
				$file = "files/lab04_switch_data.txt";
				if (file_exists($file))
					echo(file_get_contents($file)); 
				else
					echo("(erro: não foi possível obter dados!)"); 					
			?>
		</p>
		</div>
		</div>
	</div>
	
	
</body>

</html>