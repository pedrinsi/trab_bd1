<?php
	include("public/lib/_conexao.php");

	$conexao = new Conexao;
	$conexao->open();
	
	if(isset($_GET['string'])) {	
		$string = $_GET['string'];		
	}
	
	$busca = (isset($string)) ? " WHERE a.id LIKE '%$string%' " : "";
	
	$contas = $conexao->result("
		SELECT 
			*,
			b.nome,
			c.tema
		FROM conta as a
		INNER JOIN cliente b ON b.id = a.id_cliente
		INNER JOIN mesa c ON c.id = a.id_mesa
		$busca
	");
	
	if((isset($_GET['deletar']))&&($_GET['deletar']=="true")) {
	
		$deleta_conta = $conexao->execute("
			DELETE FROM conta
			WHERE id = ".$_GET['i']."
		");
?>
<script type="text/javascript">
	window.location = 'conta_manutencao.php';
</script>
<?
	
	}
	
	$conexao->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Contas</title>
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
					  window.location = 'conta_manutencao.php';
					}
					else {
						if ($(this).val()!="") {
							window.location = 'conta_manutencao.php?string='+$(this).val();
						}
					}
				});
			});
			
			function deleta_conta(id_conta){
					var confirma = confirm("deseja realmente deletar este usuário ?");
					if(confirma) {
						window.location = 'conta_manutencao.php?deletar=true&i='+id_conta;
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
				<div class="campo_conteudo"><h1>CONTAS</h1>

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

					<fieldset>
						
						<label style="top:-5px;">Filtros</label>
						<select width="300px" >
							<option>Criar option</option>
						</select>	
						
					</fieldset>
				</div>
			</div>
		</div>
		
		<!-- RODAPE -->
		<?php include("_rodape.php"); ?>
	</body>
</html>