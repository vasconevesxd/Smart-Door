<?php
	require "configs.php";
	header('Content-Type: text/html; charset=utf-8');
	
	
	//--- Permitir apenas o método POST  -------------------------------
	if($_SERVER['REQUEST_METHOD'] != "POST")
	{
		http_response_code(403);
		echo('{"erro": "Método ' . $_SERVER['REQUEST_METHOD'] . ' não é permitido!"}' . PHP_EOL);
		exit();		
	}		
	//------------------------------------------------------------------
	

	//--- html:POST - verificar dados enviados  ------------------------
	$DATA = json_decode(file_get_contents('php://input'), true);	
	if (!isset($DATA['auth']) || !isset($DATA['key']) || !isset($DATA['value']) || !isset($DATA['date']))
	{			
		http_response_code(400);				
		echo('{"erro": "Falta de parâmetros ao chamar o serviço!"}' . PHP_EOL);	
		exit();		
	}
	//------------------------------------------------------------------
	
	
	//--- html:POST - obter dados enviados  ----------------------------
	$user_pwd = $DATA['auth'];
	$user_key = $DATA['key'];
	$user_value = $DATA['value'];
	$user_date = $DATA['date'];
	$lab_name = "lab04_";	
	//------------------------------------------------------------------

	
	//--- verificar as credênciais (password) do utilizador ------------
	if ($user_pwd != $config_auth_password)
	{	
		http_response_code(401);		
		echo('{"erro": "Erro de autenticação!"}' . PHP_EOL);		
		exit();
	}
	//------------------------------------------------------------------

	
	//--- verificar chave admitida: "switch" ---------------------------
	if ($user_key != "switch")
	{
		http_response_code(400);					
		echo('{"erro": "Serviço apenas disponível para a chave: \'switch\'."}' . PHP_EOL);
		exit();					
	}	
	//------------------------------------------------------------------

	
	//--- Atualização dos ficheiros  -----------------------------------
	$path_file_value = "$config_uploads_dir/$lab_name$user_key$config_uploads_sufix_value";
	$path_file_date = "$config_uploads_dir/$lab_name$user_key$config_uploads_sufix_date";		
	$path_file_history = "$config_uploads_dir/$lab_name$user_key$config_uploads_sufix_history";		
	
	// atualizar ficheiro com o valor
	$r1 = file_put_contents($path_file_value, $user_value);
	
	// atualizar o ficheiro com a data atual
	$r2 = file_put_contents($path_file_date, $user_date);
	
	// atualizar o ficheiro com o histórico (order by date ASC)
	$new_line = $user_value . "\t(" . $user_date . ")" . PHP_EOL;
	$r3 = file_put_contents($path_file_history, $new_line, FILE_APPEND);
	
	// verificar se não foi possível atualizar pelo menos um ficheiro
	if ($r1 == FALSE || $r2 == FALSE || $r3 == FALSE)
	{
		http_response_code(404);					
		echo('{"erro": "Não foi possível atualizar ficheiros."}' . PHP_EOL);				
		exit();					
	}		

	// tudo atualizado com sucesso
	$json = array("status" => "OK", "key" => $user_key, "value" => $user_value, "date" => $user_date);
	echo(json_encode($json) . PHP_EOL);	
	
	//------------------------------------------------------------------	
?>
