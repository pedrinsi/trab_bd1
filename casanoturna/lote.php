<?php
	include("public/lib/_conexao.php");

	$conexao = new Conexao;
	$conexao->open();
	
	if(isset($_GET['string'])) {	
		$string = $_GET['string'];		
	}
	
	$busca = (isset($string)) ? " WHERE a.descricao LIKE '%$string%' " : "";
	
	$lotes = $conexao->result("
		SELECT 
			*
		FROM lote as a
		$busca
	");
	
	if((isset($_GET['deletar']))&&($_GET['deletar']=="true")) {
	
		$deleta_cliente = $conexao->execute("
			DELETE FROM lote
			WHERE id = ".$_GET['i']."
		");
?>
<script type="text/javascript">
	window.location = 'lote.php';
</script>
<?
	
	}
	
	$conexao->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Lotes</title>
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
			
			  
				$('.busca').blur(function(){
					if($(this).val() == '')
					{
					  $(this).val('Buscando...');
					  window.location = 'lote.php';
					}
					else {
						if ($(this).val()!="") {
							window.location = 'lote.php?string='+$(this).val();
						}
					}
				});
			});
			
			function deleta_lote(id_lote){
					var confirma = confirm("deseja realmente deletar este usuário ?");
					if(confirma) {
						window.location = 'lote.php?deletar=true&i='+id_lote;
					}
				} 
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
				<div class="campo_conteudo"><h1>LOTES</h1>

					<div class="campo_botoes">
						<form action="" class="busca">
							<fieldset>
								<span class="bg_busca">
									<input type="text" value="" class="busca" id="" />
								</span>
								<input type="button" value="" id="" />
							</fieldset>
						</form>
						<a href="lote_form.php">Cadastrar lote</a>
					</div>

					<form action="">
						<fieldset>
							<table border="0">
								<thead>
									<tr>
										<th>DESCRICAO</th>										
										<th>FORNECEDOR</th>
										<th class="opcoes">OPÇÕES</th>
									</tr>
								</thead>							
								<tbody>
									<?php foreach($lotes as $c => $linha){
										if($c%2==0) {?>
									<tr>
										<td><?=$linha['descricao']?></td>
										<td><?=$linha['fornecedor']?></td>
										<td>
											<a href="lote_form.php?i=<?=$linha['id']?>"><img src="public/img/icon_editar.png" alt="" title="Editar" width="16" height="16" />
											<a href="javascript:deleta_lote(<?=$linha['id']?>);" ><img src="public/img/icon_deletar.png" alt="" title="Remover" width="16" height="16" />
										</td>
									</tr>
									<?php } else { ?>
									<tr class="impar">
										<td><?=$linha['descricao']?></td>
										<td><?=$linha['fornecedor']?></td>
										<td>
											<a href="lote_form.php?i=<?=$linha['id']?>"><img src="public/img/icon_editar.png" alt="" title="Editar" width="16" height="16" />
											<a href="javascript:deleta_lote(<?=$linha['id']?>);" ><img src="public/img/icon_deletar.png" alt="" title="Remover" width="16" height="16" />
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