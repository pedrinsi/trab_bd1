<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Cadastro de Cliente</title>
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
			<div class="banner_secundario">
				<img src="public/img/bebida.jpg" alt="" width="900" height="180" />
			</div>		
			<div class="conteudo">
				<div class="campo_conteudo">
					<h1>Cadastro de cliente</h1>
					<form action="cadastro" method="post">
						<div class="cadastra">
							<label for="nome">Nome</label>
							<input class="nome" type="text" name="nome" value=""/>
							
							<label for="sobrenome">Sobrenome</label>
							<input class="sobrenome" type="text" name="sobrenome" value=""/>
							
							<label for="cpf">CPF</label>
							<input class="cpf" type="text" name="cpf" value=""/>
							
							<label for="dt_nascimento">Data de Nascimento</label>
							<input class="dt_nascimento" type="text" name="dt_nascimento" value=""/>
							
							<label for="end_logradouro">Logradouro</label>
							<input class="end_logradouro" type="text" name="end_logradouro" value=""/>
							
							<label for="end_numero">Número</label>
							<input class="end_numero" type="text" name="end_numero" value=""/>
							
							<label for="end_complemento">Complemento</label>
							<input class="end_complemento" type="text" name="end_complemento" value=""/>
							
							<label for="end_municipio">Munícipio</label>
							<input class="end_municipio" type="text" name="end_municipio" value=""/>
							
							<label for="end_uf">UF</label>
							<input class="end_uf" type="text" name="end_uf" value=""/>
							
							<label for="end_cep">CEP</label>
							<input class="end_cep" type="text" name="end_cep" value=""/>
							
							<label for="telefone">Telefone</label>
							<input class="telefone" type="text" name="telefone" value=""/>
							
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