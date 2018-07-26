<?php

/**
 * @author Boomer
 * @copyright 2014
 */


echo link_tag(array('href' => 'assets/custom/receita_medica.css','rel' => 'stylesheet','type' => 'text/css')); # CRIADO POR TIAGO

?>
<!DOCTYPE html>
<html>
<head>
    <title>Receita Médica</title>
    <?php
    echo "<script type='text/javascript' src='".base_url('assets/js/jquery.js')."'></script>";
    ?>
</head>
<body>
<hr />
<div id="Divlogo">
<p id="pPriLogo">CONSULTÓRIO ONLINE</p>
<p id="pSecLogo">Facilitando a sua vida</p>
</div>
<div id="titulos">
    <h1 id="tituloPri"><?php echo utf8_decode($cliente[0]->nome_cliente);?></h1>
    
    <?php
    foreach($telefoneCliente as $tCli){
        $telefones[] = $tCli->numero_telefone_cliente;
    }
    $telefonesFormatado = implode(' / ',$telefones);
    ?>
    
    <h2 id="tituloSec"><?php echo $cliente[0]->endereco_cliente.', '.$cliente[0]->numero_endereco_cliente.' - '.$cliente[0]->cidade_cliente.'<br>TEL: '.$telefonesFormatado; ?></h2>
</div>
<hr />
<strong>Paciente: <?php echo $atendimento[0]->nome_paciente; ?></strong>
<hr />
<div id="conteudo"><?php echo utf8_decode($dadosReceita[0]->conteudo_receita_medica); ?></div>
<p id="assinatura"><?php echo $atendimento[0]->nome_funcionario; ?></p>
<!--
<p id="rodape">Realizado no dia <?php echo substr($atendimento[0]->data_agenda, 8, 2); ?> de <?php echo $nomeMes; ?> de <?php echo substr($atendimento[0]->data_agenda, 0, 4); ?> às <?php echo date('h:i:s'); ?></p>
-->
</body>
</html>
<script type="text/javascript">

$(document).ready(function() {
	//$(‘a#print’).click(function() {
		window.print();
		return false;
	//});
});

</script>