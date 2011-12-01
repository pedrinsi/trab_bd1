<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Clube do Whisky</title>
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
		<link rel="stylesheet" type="text/css" href="public/css/padrao.css" />.
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
				
				$('.busca').focus(function(){ 
					if($(this).val() == 'Buscar...')
					{
					  $(this).val('');
					}
				});
			  
				$('.busca').blur(function(){
					if($(this).val() == '')
					{
					  $(this).val('Buscar...');
					} 
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
				<img src="public/img/WHISKY.jpg" alt="" width="900" height="180" />
			</div>		
			<div class="conteudo">
				<div class="campo_conteudo"><h1>CLIENTES CLUBE WHISKY</h1>

					<div class="campo_botoes">
						<form action="" class="busca">
							<fieldset>
								<span class="bg_busca">
									<input type="text" value="Buscar..." class="busca" id="" />
								</span>
								<input type="button" value="" id="" />
							</fieldset>
						</form>
						<a href="#">Cadastrar cliente</a>
					</div>

					<form action="">
						<fieldset>
							<table border="0">
								<thead>
									<tr>
										<th><input type="checkbox" /></th>
										<th>ID</th>
										<th>NOME</th>										
										<th>SOBRENOME</th>										
										<th>CPF</th>										
										<th>OPÇÕES</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><input type="checkbox" /></td>
										<td>1</td>
										<td>Tarcísio</td>										
										<td>Fonseca Lima</td>										
										<td>082.911.004-18</td>										
										<td>
											<a href="#"><img src="public/img/icon_editar.png" alt="" title="Editar" width="16" height="16" />
											<a href="#"><img src="public/img/icon_log.png" alt="" title="Log" width="16" height="16" />
											<a href="#"><img src="public/img/icon_deletar.png" alt="" title="Remover" width="16" height="16" />
										</td>
									</tr>
									<tr class="impar">
										<td><input type="checkbox" /></td>
										<td>2</td>
										<td>Tarcísio</td>	
										<td>Fonseca Lima</td>	
										<td>082.911.004-18</td>											
										<td>
											<a href="#"><img src="public/img/icon_editar.png" alt="" title="Editar" width="16" height="16" />
											<a href="#"><img src="public/img/icon_log.png" alt="" title="Log" width="16" height="16" />
											<a href="#"><img src="public/img/icon_deletar.png" alt="" title="Remover" width="16" height="16" />
										</td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="4">
											<div class="paginacao">
												<div><a href="#">&laquo;</a></div>
												<div><a href="#">1</a></div>
												<div>2</div>
												<div><a href="#">3</a></div>
												<div><a href="#">4</a></div>
												<div><a href="#">5</a></div>
												<div><a href="#">&raquo;</a></div>
											</div>
										</td>
									</tr>
								</tfoot>
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