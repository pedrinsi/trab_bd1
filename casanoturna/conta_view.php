<?php
	include("public/lib/_conexao.php");
	include("_calcula_valor_conta.php");

	$conexao = new Conexao;
	$conexao->open();
	
	
	
	if(isset($_GET['string'])) {	
		$string = $_GET['string'];		
	}
	
	//RETIRAR PRODUTO DA CONTA
	if(isset($_GET['produto'])){
		$id_produto_historico = $_GET['produto'];
		
		$delete = $conexao->execute("
			DELETE FROM historico_conta
			WHERE id = $id_produto_historico
		");
		
		calcula_conta($conexao,$_GET['i']);	
	}
	
	$id = $_GET['i'];
	
	//$busca = (isset($string)) ? " WHERE a.id LIKE '%$string%' " : "";
	
	
	$contas = $conexao->result("
		SELECT 
			a.id,
			a.id_cliente,
			a.id_mesa,
			a.dt_abertura,
			a.situacao,
			a.valor_total,
			a.descricao,
			b.nome,
			c.tema
		FROM conta as a
		LEFT JOIN cliente b ON b.id = a.id_cliente
		LEFT JOIN mesa c ON c.id = a.id_mesa
	  WHERE a.id = $id "	
	);
	
	$conta = $contas[0];
	
	/*DELETAR*/
	if((isset($_GET['deletar']))&&($_GET['deletar']=="true")) {
	
		$deleta_conta = $conexao->execute("
			DELETE FROM conta
			WHERE id = ".$_GET['i']."
		");
?>
<script type="text/javascript">
	window.location = 'conta_manutencao.php?filter=-1';
</script>
<?
	
	}
	
	
	/*ENCERRAR*/
	if((isset($_GET['encerrar']))&&($_GET['encerrar']=="true")) {
	
		$deleta_conta = $conexao->execute("
			UPDATE conta			
				SET	
				situacao = 0
			WHERE id = ".$_GET['i']."
		");
?>
<script type="text/javascript">
	window.location = 'conta_manutencao.php?filter=-1';
</script>
<?
	
	}
	

	//REFERENCIA 0 para prato
	$pratos_conta = $conexao->result("
		SELECT 	a.id,
				c.nome,
				c.custo_preparo
		FROM historico_conta as a
		INNER JOIN cardapios_pratos as b on a.id_item_cardapio = b.id
		INNER JOIN prato as c on c.id = b.id_prato
		WHERE a.id_conta = $id AND a.id_referencia_cardapio = 0
	");
	
	//REFERENCIA 1 para comercializacao
	$produtos_conta = $conexao->result("
		SELECT 	a.id,
				d.descricao,
				d.valor_unidade
		FROM historico_conta as a
		INNER JOIN cardapios_comercializacao_direta as b on a.id_item_cardapio = b.id
		INNER JOIN comercializacao_direta as c on c.id = b.id_comercializacao_direta
		INNER JOIN produto as d on d.id = c.id_produto
		WHERE a.id_conta = $id AND a.id_referencia_cardapio = 1
	");
	
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
					  window.location = 'conta_view.php?filter=<?=$_GET['filter']?>';
					}
					else {
						if ($(this).val()!="") {
							window.location = 'conta_view.php?filter=<?=$_GET['filter']?>&string='+$(this).val();
						}
					}
				});
			});
			

			function encerra_conta(id_conta,valor_total) {
				var confirma = confirm("Deseja realmente encerrar esta conta ?\nValor Total : "+valor_total);
					if(confirma) {
						window.location = 'conta_view.php?filter='+<?=$_GET['filter']?>+'&encerrar=true&i='+<?=$_GET['i']?>;
					}
			
			}
			function deleta_item(id_produto) {
				var confirma = confirm("Deseja realmente remover o item da conta ?");
					if(confirma) {
						window.location = 'conta_view.php?produto='+id_produto+'&i='+<?=$_GET['i']?>+'&filter='+<?=$_GET['filter']?>;
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
				<div class="campo_conteudo"><h1>INFORMAÇÃO CONTA <?if($conta['situacao']==0){?><span style="color:red;">ENCERRADA</span><?}?></h1>

					<div class="campo_botoes">
						<form action="" class="busca">
							<fieldset>
								<span class="bg_busca">
									<input type="text" value="" class="busca" id="" />
								</span>
								<input type="button" value="" id="" />
							</fieldset>
						</form>
						<?if($conta['situacao']==1){?>	
						<a href="conta_inserir_itens.php?c=<?=$conta['id']?>">Inserir Item</a>
						<a href="javascript:encerra_conta(<?=$conta['id']?>,<?=$conta['valor_total']?>);" >Encerrar Conta</a>
						<?}?>
					</div>
					<div class="campo_botoes">
						<div>Cliente : <?=$conta['nome']?></div>
						<div>Mesa : <?=$conta['tema']?></div>
						<div>Valor Total :<?=$conta['valor_total']?></div>
						
					</div>
					<br />
						<br />
					
		
					<form action="">
						<fieldset>
							<table border="0">
								<thead>
									<tr>
										<th>DESCRIÇÃO</th>										
										<th>PREÇO</th>								
										<th class="opcoes">OPÇÕES</th>
									</tr>
								</thead>							
								<tbody>
									<!-- LISTAGEM DE PRATOS ASSOCIADOS A CONTA -->
									<?php foreach($pratos_conta as $c => $linha){
										if($c%2==0) {?>
									<tr>						
										<td><?=$linha['nome']?></td>								
										<td><?=$linha['custo_preparo']?></td>
										<td>											
											<a href="javascript:deleta_item(<?=$linha['id']?>);" ><img src="public/img/remove.png" alt="" title="Remover" width="16" height="16" />
										</td>
									</tr>
									<?php } else { ?>
									<tr class="impar">						
										<td><?=$linha['nome']?></td>								
										<td><?=$linha['custo_preparo']?></td>
										<td>
											<a href="javascript:deleta_item(<?=$linha['id']?>);" ><img src="public/img/remove.png" alt="" title="Remover" width="16" height="16" />
										</td>
									</tr>
									<? } } ?>
									<!-- LISTAGEM DE PRODUTOS ASSOCIADOS A CONTA -->
									<?php foreach($produtos_conta as $c => $linha){
										if($c%2==0) {?>
									<tr>							
										<td><?=$linha['descricao']?></td>								
										<td><?=$linha['valor_unidade']?></td>
										<td>											
											<a href="javascript:deleta_item(<?=$linha['id']?>);" ><img src="public/img/remove.png" alt="" title="Remover" width="16" height="16" />
										</td>
									</tr>
									<?php } else { ?>
									<tr class="impar">							
										<td><?=$linha['descricao']?></td>								
										<td><?=$linha['valor_unidade']?></td>
										<td>
											<a href="javascript:deleta_item(<?=$linha['id']?>);" ><img src="public/img/remove.png" alt="" title="Remover" width="16" height="16" />
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