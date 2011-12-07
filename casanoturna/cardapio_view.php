<?php
	include("public/lib/_conexao.php");
	include("public/lib/_manipulacao.php");
	
	$conexao = new Conexao;
	$conexao->open();

	
	
		//Listagem do cardápio
		$cardapio_sql = $conexao->result("
			SELECT a.*
			FROM cardapio as a
			WHERE a.id = ".$_GET['i']."
		");
		
		//Listagem dos produtos (bebida)
	
			
		// Listagem dos produtos que ja estão no cardapio		
		$produtoBebida  = $conexao->result("
			SELECT
				a.descricao,
				a.valor_unidade,
				b.classificacao
			FROM produto as a
			INNER JOIN comercializacao_direta AS b on b.id_produto = a.id
			INNER JOIN bebida d ON d.id_comercializacao_direta = b.id
			INNER JOIN cardapios_comercializacao_direta c ON c.id_comercializacao_direta = d.id_comercializacao_direta
			WHERE 
				id_cardapio =   ".$_GET['i']."
		");	
		/*
		SELECT DISTINCT
				a.descricao,
				a.valor_unidade,
				b.classificacao
			FROM produto as a
			INNER JOIN comercializacao_direta AS b on b.id_produto = a.id
			INNER JOIN cardapios_comercializacao_direta c ON c.id_comercializacao_direta =  b.id
			WHERE 
				(b.classificacao = 'bebidas geladas' OR
				b.classificacao = 'bebidas quentes') AND
				id_cardapio =  ".$_GET['i']."
		*/
		// Listagem dos produtos que ja estão no cardapio		
		$produtoPrato = $conexao->result("
			SELECT 
				b.nome,
				b.custo_preparo
			FROM cardapios_pratos a
			INNER JOIN prato b ON b.id = a.id_prato
			WHERE id_cardapio =  ".$_GET['i']."
		");
		
		//Listagem dos produtos (outros itens de comercialização direta)
		$produtoComerci = $conexao->result("
			SELECT 
				a.descricao,
				a.valor_unidade,
				b.classificacao
			FROM produto as a
			INNER JOIN comercializacao_direta AS b on b.id_produto = a.id
			INNER JOIN cardapios_comercializacao_direta c ON c.id_comercializacao_direta =  b.id
			WHERE 
				b.classificacao <> 'bebidas geladas' AND
				b.classificacao <> 'bebidas quentes' AND
				id_cardapio =  ".$_GET['i']."	
			
		");

		
	$cardapio = $cardapio_sql[0]['descricao'];
	$id_cardapio = $_GET['i'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>View de Cardapio</title>
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
					<h1 style="text-align:center;font-size:36px;"><?php echo $cardapio; ?></h1>
					<form action="" method="post">
						<div class="cadastra">
							
								<div>
									<h1>Pratos</h1>
										<form action="">
										<fieldset>
											<table border="0">
												<thead>
													<tr>		
														<th>Descrição</th>	
														<th>Valor</th>													
													</tr>
												</thead>							
												<tbody>
													<?php foreach($produtoPrato as $c => $linha){  
															
														if($c%2==0) {?>
													<tr >			
														<td><?=$linha['nome']?></td>
														<td><?=$linha['custo_preparo']?></td>												
													</tr>
													<?php } else { ?>
													<tr class="impar">
														<td><?=$linha['nome']?></td>
														<td><?=$linha['custo_preparo']?></td>
													</tr>
													<? } } ?>
												</tbody>
											</table>
										</fieldset>
									</form>
									
								</div>
								<div>
									<h1>Bebidas</h1>
									<form action="">
										<fieldset>
											<table border="0">
												<thead>
													<tr>										
														<th>Descrição</th>
														<th>Valor</th>
													</tr>
												</thead>							
												<tbody>
													<?php foreach($produtoBebida as $c => $linha){
													
															
														if($c%2==0) {?>
													<tr>										
														<td><?=$linha['descricao']?></td>
														<td><?=$linha['valor_unidade']?></td>
													</tr>
													<?php } else { ?>
													<tr class="impar">
														<td><?=$linha['descricao']?></td>
														<td><?=$linha['valor_unidade']?></td>
													</tr>
													<? } } ?>
												</tbody>
											</table>
										</fieldset>
									</form>								
								</div>
								
								<div>
									<h1>Comercialização Direta</h1>
										<form action="">
										<fieldset>
											<table border="0">
												<thead>
													<tr>		
														<th>Descrição</th>	
														<th>Valor</th>													
													</tr>
												</thead>							
												<tbody>
													<?php foreach($produtoComerci as $c => $linha){  
														
														if($c%2==0) {?>
													<tr >			
														<td><?=$linha['descricao']?></td>
														<td><?=$linha['valor_unidade']?></td>
													</tr>
													<?php } else { ?>
													<tr class="impar">
														<td><?=$linha['descricao']?></td>
														<td><?=$linha['valor_unidade']?></td>
													</tr>
													<? } } ?>
												</tbody>
											</table>
										</fieldset>
									</form>
									
								</div>
								
						
													
							<input class="cancelar" href="cardapio" style="margin:0 -105px auto;" type="image" src="public/img/cancelar.png" value="Cancelar" id="cancelar"/>
							<input class="salvar" style="margin:0 104px auto;" type="image" src="public/img/salvar.png" value="Salvar" id="salvar"/>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- RODAPE -->
		<?php include("_rodape.php"); ?>
	</body>
</html>
<?$conexao->close();?>