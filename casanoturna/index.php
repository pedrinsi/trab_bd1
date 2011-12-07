<?php
	include("public/lib/_conexao.php");
	
	$conexao = new Conexao;
	$conexao->open();
	
	$bebidas = $conexao->result("
		SELECT DISTINCT
			a.valor_unidade,
			a.descricao
		FROM produto as a
		INNER JOIN comercializacao_direta as b on b.id_produto = a.id
		INNER JOIN bebida as c on c.id_comercializacao_direta = b.id
		INNER JOIN cardapios_comercializacao_direta as d on b.id = c.id_comercializacao_direta
		WHERE d.id_cardapio = 16
	");
	
	$comidas = $conexao->result("
		SELECT DISTINCT
			a.valor_unidade,
			a.descricao
		FROM produto as a
		INNER JOIN comercializacao_direta as b on b.id_produto = a.id
		INNER JOIN cardapios_comercializacao_direta as c on c.id_comercializacao_direta = b.id
		WHERE c.id_cardapio = 16 AND b.id NOT IN ( SELECT id_comercializacao_direta from bebida )
	");
	
	$pratos = $conexao->result("
		SELECT DISTINCT
			a.nome,
			a.custo_preparo
		FROM prato as a
		INNER JOIN cardapios_pratos as b on b.id_prato = a.id
		WHERE b.id_cardapio = 16
	");
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
		<link rel="stylesheet" type="text/css" href="public/css/home.css" />
		<link rel="stylesheet" type="text/css" href="public/css/cardapio.css" />
		
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
			<div class="banner_secundario">
				<img src="public/img/casa noturna.jpg" alt="" width="900" height="180" />
			</div>		
			<div class="conteudo">
				<div class="campo_conteudo">
					<div class="texto">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin quam odio, egestas at placerat vel, 
						adipiscing sit amet tortor. In eu libero purus, non eleifend purus. Nunc lobortis, massa et condimentum placerat, odio nisl scelerisque ante, 
						id pretium ligula quam vel sapien. Donec dignissim, felis eu lobortis fringilla, lectus ligula feugiat dui, a vehicula lectus nulla sit amet sem. 
						Nulla facilisi. Duis tempor blandit mollis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Pellentesque congue, 
						velit nec ultrices consequat, eros sem mollis tellus, ut pellentesque urna mauris et turpis.
					</div>
					<div class="teste">
						<h1 style="text-align:center;">Cardapio Semanal - Delavuu</h1>
						<h2>BEBIDAS</h2>						
							<?php foreach($bebidas as $b => $bebida){ ?>
							<dl>
								<dt><?=$bebida['descricao']?></dt>
								<dd class="price">R$: <?=$bebida['valor_unidade']?></dd>
							</dl>
							<? } ?>
						
						<h2>COMIDAS</h2>
						<?php foreach($comidas as $c => $comida){ ?>
							<dl>
								<dt><?=$comida['descricao']?></dt>
								<dd class="price">R$: <?=$comida['valor_unidade']?></dd>
							</dl>
							<? } ?>
							<?php foreach($pratos as $p => $prato){ ?>
							<dl>
								<dt><?=$prato['nome']?></dt>
								<dd class="price">R$: <?=$prato['custo_preparo']?></dd>
							</dl>
							<? } ?>
					</div>
				</div>
			</div>
		</div>
		
		<!-- RODAPE -->
		<?php include("_rodape.php"); ?>
	</body>
</html>