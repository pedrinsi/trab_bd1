<?php
	include("public/lib/_conexao.php");
	include("public/lib/_manipulacao.php");

	$conexao = new Conexao;
	$conexao->open();
	
	$manipula = new Manipula;
	$manipula->setTabela("produto");
	$manipula->setChave("id");
	
	$manipula->addCampo("valor_unidade","","string");
	$manipula->addCampo("id_lote","","string");
	$manipula->addCampo("codigo_barras","","string");
	$manipula->addCampo("descricao","","string");
	
	$manipula->setAfterUpdate("produto.php");
	$manipula->setAfterInsert("produto.php");
	
	$manipula->execManipula();
	
	$lotes = $conexao->result("
		SELECT a.*
		FROM lote as a
	");
	
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
				
				$('#id_lote').change(function() {
					if($(this).val() == 1) {
						$('#opcao1').val('Estoque');
						$('#opcao2').val('Unidade Medida');						
					}
					if($(this).val() == 2) {
						$('#opcao1').val('Quantidade');
						$('#opcao2').val('Armazenamento');
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
				<img src="public/img/casa noturna.jpg" alt="" width="900" height="180" />
			</div>		
			<div class="conteudo">
				<div class="campo_conteudo"><h1>Cadastro de produto</h1>
					<form action="" method="post">
						<div class="cadastra">
							
							<label for="cpf">Lote</label>
							<select name="id_lote">
								<?php foreach($lotes as $l => $lote) { ?>
									<option value="<?=$lote['id']?>" <?if ($lote['id']==$manipula->getValorCampo("id_lote")) { ?> selected <?} ?>><?=$lote['descricao'].' - '.$lote['fornecedor']?></option>
								<?php } ?>
							</select><br />
							
							<label for="nome">Nome</label>
							<input class="nome" type="text" name="descricao" value="<?=$manipula->getValorCampo("descricao")?>"/>
							
							<label for="cpf">Valor Unidade</label>
							<input class="cpf" type="text" name="valor_unidade" value="<?=$manipula->getValorCampo("valor_unidade")?>"/>	

							<label for="cpf">Código Barra</label>
							<input class="cpf" type="text" name="codigo_barras" value="<?=$manipula->getValorCampo("codigo_barras")?>"/>

							<label for="nome">Tipo</label>
							<select name="id_lote" id="id_lote" onchange="change_tipo();">
									<option value="1">Matéria Prima</option>
									<option value="2">Comercialização Direta</option>
							</select><br /><br />
							
							<input style="color:red;" type="text" name="opcao1" id="opcao1" value="Estoque"/>
							<input class="cpf" type="text" name="estoque" value=""/>
							
							<input style="color:red;" type="text" name="opcao2" id="opcao2" value="Unidade Medida"/>
							<input class="cpf" type="text" name="unidade_medida" value=""/>

							<br />
							<br />
							<br />
							<br />
							
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