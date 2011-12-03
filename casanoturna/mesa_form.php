<?php
	include("public/lib/_conexao.php");
	include("public/lib/_manipulacao.php");
	
	$conexao = new Conexao;
	$conexao->open();
	
	$next_id = $conexao->result("SHOW TABLE STATUS LIKE 'mesa'");
	$id_cliente = (isset($_GET['i'])) ? $_GET['i'] : $next_id[0]['Auto_increment'];
	
	$manipula = new Manipula;
	$manipula->setTabela("mesa");
	$manipula->setChave("id");	
	
	$manipula->addCampo("tema","","string");
	$manipula->addCampo("numero","","nontxt");	 
	$manipula->addCampo("lugares","","nontxt");
	
	
	$manipula->setAfterUpdate("mesa.php");
	$manipula->setAfterInsert("mesa.php");
	
	
	$manipula->execManipula();
	
	
	$conexao->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Cadastro de Mesas</title>
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
		</script>
		
	</head>
	
	<body>		
		<!-- TOPO -->
		<?php include("_topo.php"); ?>		
		
		<!-- CONTEUDO -->
		<div id="Corpo">		
			<div class="banner_secundario" align="center">
				<img src="public/img/casa noturna.jpg" alt="" width="900" height="180" />
			</div>		
			<div class="conteudo">
				<div class="campo_conteudo">
					<h1>Cadastro de Mesas</h1>
					<form action="" method="post">
						<div class="cadastra">
							
							<label for="tema">Tema</label>
							<input class="tema" type="text" name="tema" value="<?=$manipula->getValorCampo("nome")?>"/>
							
							<label for="lugares">Lugares</label>
							<input class="lugares" type="text" name="lugares" value="<?=$manipula->getValorCampo("lugares")?>"/>
							
							<label for="numero">Numero</label>
							<input class="numero" type="text" name="numero" value="<?=$manipula->getValorCampo("numero")?>"/>
						
							<input type="hidden" name="trigger" id="trigger" value="<?php if($manipula->mode=="e"){ echo "edita"; } else { echo "insere"; }?>"/>
							
							<input class="salvar" style="margin:0 104px auto;" type="image" src="public/img/salvar.png" value="Salvar" id="salvar"/>
							<input class="cancelar" style="margin:0 -105px auto;" type="image" src="public/img/cancelar.png" value="Cancelar" id="cancelar"/>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- RODAPE -->
		<?php include("_rodape.php"); ?>
	</body>
</html>