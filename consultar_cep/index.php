<?php
    if(isset($_GET['cep'])){
       $cep = array('cep' => preg_replace("/[^0-9]/", "",$_GET['cep']));
	   $dados = consultarCEP($cep);
	}
    else{
       $dados = "";
	}
	
	function consultarCEP($cep){
		try{
			$url = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';
			$soap = new SoapClient($url,array(
				'stream_context'=>stream_context_create(
					array('http'=>
						array(
							'protocol_version'=>'1.0',
							'header' => 'Connection: Close'
						)
					)
				)
			));
			$resultado= $soap->consultaCEP($cep);
			$resultado= (array) $resultado->return;
			$retorno = (object) array_merge(array("callback" => "Sucesso"),$resultado);
			return $retorno;
		}
		catch (SoapFault $E) { 
			$retorno = (object) array("callback" => "Erro", "erro" => $E->faultstring);
			return $retorno;
		} 

	}	
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar CEP</title>
</head>
<body>
    <form method="GET">
        <input type="text" name="cep">
        <input type="submit" value="Consultar CEP">
    </form>
	<div id="resultado">
		<?php
			if($dados != ""){
				if($dados->callback == "Sucesso"){
					echo '<br>';
					echo 'CEP: '.$dados->cep . '<br>';
					echo 'Logradouro: '.$dados->end . '<br>';
					echo 'Complemento: '.$dados->complemento2 . '<br>';
					echo 'Bairro: '.$dados->bairro . '<br>';
					echo 'Cidade: '.$dados->cidade . '<br>';
					echo 'UF: '. $dados->uf . '<br>';
				}
				else if($dados->callback == "Erro"){
					echo '<br>';
					echo 'Erro: '.$dados->erro . '<br>';
				}
			}
		?>
	</div>
</body>
</html>