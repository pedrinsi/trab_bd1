<?php
	include("public/lib/_conexao.php");

	$conexao = new Conexao;
	$conexao->open();
	
		$produtos = $conexao->result("
			SELECT 
				b.fornecedor,
				a.*
			FROM produto as a
			LEFT JOIN lote as b on b.id = a.id_lote
		");
	
	if((isset($_GET['deletar']))&&($_GET['deletar']=="true")) {
	
		$deleta_produto = $conexao->execute("
			DELETE FROM produto
			WHERE id = ".$_GET['i']."
		");
?>
<script type="text/javascript">
	window.location = 'produto.php';
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
			
			function deleta_produto(id){
				var confirma = confirm("deseja realmente deletar este usuário ?");
				if(confirma) {
					window.location = 'produto.php?deletar=true&i='+id;
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
				<div class="campo_conteudo"><h1>PRODUTOS</h1>

					<div class="campo_botoes">
						<a href="produto_form.php">Cadastrar Produto</a>
					</div>

					<form action="">
						<fieldset>
							<table border="0">
								<thead>
									<tr>
										<th>ID</th>
										<th>NOME</th>
										<th>Valor Unit</th>
										<th>Fornecedor</th>
										<th class="opcoes">OPÇÕES</th>
									</tr>
								</thead>							
								<tbody>
								<?php foreach($produtos as $p => $produto){
										if($p%2==0) { 	?>
									<tr>
										<td><?=$produto['id']?></td> 
										<td><?=$produto['descricao']?></td>
										<td><?=$produto['valor_unidade']?></td>
										<td><?=$produto['fornecedor']?></td>
										<td>
											<a href="produto_form.php?i=<?=$produto['id']?>"><img src="public/img/icon_editar.png" alt="" title="Editar" width="16" height="16" />
											<a href="javascript:deleta_produto(<?=$produto['id']?>);" ><img src="public/img/icon_deletar.png" alt="" title="Remover" width="16" height="16" />
										</td>
									</tr>
									<?php } else { ?>
									<tr class="impar">
										<td><?=$produto['id']?></td> 
										<td><?=$produto['descricao']?></td>
										<td><?=$produto['valor_unidade']?></td>										
										<td><?=$produto['fornecedor']?></td>										
										<td>
											<a href="produto_form.php?i=<?=$produto['id']?>"><img src="public/img/icon_editar.png" alt="" title="Editar" width="16" height="16" />
											<a href="javascript:deleta_produto(<?=$produto['id']?>);" ><img src="public/img/icon_deletar.png" alt="" title="Remover" width="16" height="16" />
										</td>
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