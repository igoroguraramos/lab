<?php 

$dados = consultarDolar();

function consultarDolar(){
    $url = 'https://ptax.bcb.gov.br/ptax_internet/consultarUltimaCotacaoDolar.do';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $page = curl_exec($curl);  
    curl_close($curl);  
    $doc = new DOMDocument;
    $doc->preserveWhiteSpace = false;
    @$doc->loadHTML($page);
    $xpath = new DOMXpath($doc);
    $filtered = $xpath->query("//tr[@class='fundoPadraoBClaro2']");
    if ($filtered->length > 0) {//se tem conteudo ou seja length > que 0
        $tds = $filtered->item(0)->getElementsByTagName('td');
        foreach ($tds as $td) {
            $resultado[] = $td->nodeValue;
        }
    }
    $retorno = (object) array('callback' => 'Sucesso','data_cotacao' => trim($resultado[0]), 'dolar_compra' => trim($resultado[1]), 'dolar_venda' => trim($resultado[2]));
    return $retorno;   
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotação Dolar</title>
    <div>
        <?php
            if($dados->callback == "Sucesso"){
                echo "Data da Cotação: " . $dados->data_cotacao . "<br>";
                echo "Taxa de Compra: R$ " . $dados->dolar_compra . "<br>";
                echo "Taxa de Venda: R$ " . $dados->dolar_venda . "<br>";
            }
            else{
                echo "Erro ao consultar, tente mais tarde"; 
            }
        ?>    
    </div>

</head>
<body>
    
</body>
</html>