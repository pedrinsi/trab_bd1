<?php
	include("public/lib/_conexao.php");

	$conexao = new Conexao;
	$conexao->open();
	
	if(isset($_GET['string'])) {	
		$string = $_GET['string'];		
	}
	$id_clube = (isset($_GET['c'])) ? $_GET['c'] : ""; 
	
	$busca = (isset($string)) ? " WHERE a.nome LIKE '%$string%' " : "";
	
	$clientes = $conexao->result("
		SELECT 
			c.nome as nome_clube,
			b.id_clube,
			a.*
		FROM cliente as a
		LEFT JOIN socio as b ON a.id = b.id_cliente
		LEFT JOIN clube as c ON c.id = b.id_clube
		$busca
	");
	
	//ASSOCIAR UM CLIENTE A UM CLUBE
	
	if((isset($_GET['c']))&&(isset($_GET['i']))) {
		$id_cliente = $_GET['i'];
		$id_clube = $_GET['c'];
	
		$associa_cliente = $conexao->execute("
			INSERT INTO socio(
				id_cliente,
				id_clube
			)
			VALUES(
				$id_cliente,
				$id_clube
			)
		");
?>
<script type="text/javascript">
	var clube = <?=$id_clube?>;
	if(clube==1){
		window.location = 'clube_cachaca.php';
	}
	if(clube==2){
		window.location = 'clube_vinho.php';
	}
	if(clube==3){
		window.location = 'clube_whisky.php';
	}
</script>
<?
	
	}
	
	$conexao->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Casa Noturna</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description"  content="" />
		<meta name="keywords" content="" />
		<meta name="robot" content="index,follow" />
		<meta name="copyright" content="" />
		<meta name="author" content="" />
		<meta name="language" content="pt-br" />
		<meta name="revisit-after" content="7 days" />
		
		<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="public/css/padrao.css" />
		<link rel="stylesheet" type="text/css" href="public/css/formulario.css" />
		<link rel="stylesheet" type="text/css" href="public/css/tabela.css" />
		
		<script type="text/javascript" src="public/js/jquery-1.7.min.js"></script>
		<script type="text/javascript" src="public/js/superfish.js"></script>
		
		<script type="text/javascript">
			$(document).ready (function(){
				$(".externo").attr("target","_blank");
				
				$('ul.sf-menu').superfish({
					delay:       0,                             // one second delay on mouseout 
					animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
					speed:       'normal',                          // faster animation speed 
					autoArrows:  false,                           // disable generation of arrow mark-up 
					dropShadows: false                            // disable drop shadows 
				});
			});
			
			function associa_cliente(id_clube,id_cliente){
				var confirma = confirm("deseja asassociar este cliente?");
					if(confirma) {
						window.location = 'clube_form.php?c='+id_clube+'&i='+id_cliente;
					}
			}
		</script>
		
	</head>
	
	<body>
		
		<!-- TOPO -->
		<?php include("_topo.php"); ?>		
		
		<!-- CONTEUDO -->
		<div id="Corpo">		
			<div class="banner_secundario">
				<img src="public/img/casa noturna.jpg" alt="" width="900" height="180" />
			</div>		
			<div class="conteudo">
				<div class="campo_conteudo"><h1>Associar Clientes</h1>

					<div class="campo_botoes">
						<form action="" class="busca">
							<fieldset>
								<span class="bg_busca">
									<input type="text" value="" class="busca" id="" />
								</span>
								<input type="button" value="" id="" />
							</fieldset>
						</form>
					</div>

					<form action="">
						<fieldset>
							<table border="0">
								<thead>
									<tr>										
										<th>ID Cliente</th>
										<th>Nome</th>
										<th>CPF</th>
										<th>Opçoes</th>
									</tr>
								</thead>							
								<tbody>
									<?php foreach($clientes as $c => $cliente){
										if($c%2==0) {?>
									<tr>
										
										<td><?=$cliente['id']?></td>
										<td><?=$cliente['nome']?></td>
										<td><?=$cliente['cpf']?></td>
										<td><a href="javascript:associa_cliente(<?=$id_clube?>,<?=$cliente['id']?>);"><img src="public/img/check.png" alt="" title="Adicionar" width="16" height="16" /></td>
									</tr>
									<?php } else { ?>
									<tr class="impar">
										<td><?=$cliente['id']?></td>
										<td><?=$cliente['nome']?></td>
										<td><?=$cliente['cpf']?></td>
										<td><a href="javascript:associa_cliente(<?=$id_clube?>,<?=$cliente['id']?>);"><img src="public/img/check.png" alt="" title="Adicionar" width="16" height="16" /></td>
									</tr>
									<? } } ?>
								</tbody>
							</table>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		
		<!-- RODAPE -->
		<?php include("_rodape.php"); ?>
	</body>
</html>