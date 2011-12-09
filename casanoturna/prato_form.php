<?php
	include("public/lib/_conexao.php");
	include("public/lib/_manipulacao.php");
	
	$conexao = new Conexao;
	$conexao->open();
	
	$materias_primas = $conexao->result("
		SELECT 
			p.valor_unidade,
			p.descricao,
			m.id,
			m.unidade_medida,
			m.estoque
		FROM produto as p
		INNER JOIN materia_prima m ON p.id = m.id_produto
	");
	
	$conexao->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<title>Pratos</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robot" content="index,follow" />
	<meta name="copyright" content="" />
	<meta name="author" content="" />
	<meta name="language" content="pt-br" />
	<meta name="revisit-after" content="7 days" />
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="public/css/formulario.css" />
	<link rel="stylesheet" type="text/css" href="public/css/padrao.css" />
	
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
		
		$('.remover').hide();
	
		$('.add_mp').click(function(){
		
			var id = $('.materia_prima').val();
			
			if(id != "nenhuma"){
				if ($('.'+id+'').hasClass("remover")) {

					$('.'+id+'').show();
					$('.'+id+'').removeClass("remover");
					
					var valor = parseFloat($('.'+id+'').find('.valor_unidade').html());
					//alert(valor);
					
					var total = parseFloat($('#custo_total').val());
					$('#custo_total').val(total + valor +".00");
				
				}

			}
			
		});
		
		$('.esconde').click(function(){
		
			var valor = parseFloat($(this).parent().find('.valor_unidade').html());
			var rel = $(this).parent().find('.quantidade').attr("rel");
			//alert(valor);
			var total = parseFloat($('#custo_total').val());
			//alert(total);
			var subtracao = (total - (valor*rel));
			//alert(subtracao);
			$('#custo_total').val(subtracao + ".00");
			
			$(this).parent().find('.quantidade').attr("rel",1);
			$(this).parent().find('.quantidade').attr("value",1);
			
			$(this).parent().addClass("remover");
			$(this).parent().hide();
			
			return false;
		});
		
		$("form").submit(function(){
			$('.remover').remove();
			return true;
		});
		
		$('.quantidade').change(function(){
			var rel = $(this).attr("rel");
			//alert(rel);
			var mult = $(this).val();
			var estoque = parseFloat($(this).parent().find('.estoque').html());
			//alert(estoque);
			//alert(mult);
			if (mult > estoque) {
				mult = estoque;
				$(this).val(mult);
			}
			var valor = parseFloat($(this).parent().find('.valor_unidade').html());
			//alert(valor);
			var soma = ((valor*mult)-(valor*rel));
			//alert(soma);
			var total = parseFloat($('#custo_total').val());
			//alert(total);
			$('#custo_total').val(total+soma+".00");
			$(this).attr("rel",mult);
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
		
			<h1>Cadastro de pratos</h1>
			<select class="materia_prima">
				<option value="nenhuma" rel="-">Selecione uma materia prima</option>
				<?php foreach ($materias_primas as $i => $materia) { ?>
					<option value="<?php echo $materia['id']; ?>"><?php echo $materia['descricao']; ?></option>
				<?php } ?>
				<!--
				<option value="0" rel="10">Produto 0</option>
				<option value="1" rel="50">Produto 1</option>
				<option value="2" rel="20">Produto 2</option>
				-->
			</select>

			<button class="add_mp">Adicionar produto</button>
			
			<br />
			<h3 style="color:red;"><B>Detalhes do prato:</B></h3>
			<form action="prato_save.php" method="post">
			
				<div class="info_prato">
				
					<label for="nome">Nome do prato:</label>
					<input class="nome" type="text" name="nome"/>
					
					<label for="codigo">Codigo atribuido a este prato:</label>
					<input class="codigo" type="text" name="codigo"/>
					
					<label for="tempo">Tempo de preparo:</label>
					<input class="tempo" type="text" name="tempo_preparo"/>
					
				</div>
				
				<h3 style="color:red;"><B>Produtos Selecionados:</B></h3>
				
				<div id="lista_produtos">
				
					<?php foreach ($materias_primas as $j => $materia) { ?>
					
						<div class="<?php echo $materia['id']; ?> remover" style="height: 50px;">
							<input class="mp_id" type="hidden" name="materias_primas[<?php echo $j; ?>][id_materia_prima]" value="<?php echo $materia['id']; ?>"/>
							<span style="width: 222px; position: absolute;"><B><?php echo $materia['descricao']; ?></B></span>
							<span style="width: 100px; position: absolute; margin-left: 226px;">Valor unitário:</span>
							<span class="valor_unidade" style="width: 100px; position: absolute; margin-left: 326px;"><?php echo $materia['valor_unidade']; ?></span>
							<span style="width: 60px; position: absolute; margin-left: 427px;">Estoque:</span>
							<span class="estoque" style="width: 50px;; position: absolute; margin-left: 490px;"><?php echo $materia['estoque']; ?></span>
							<label for="quantidade" style="position: absolute; margin-left: 551px; width: 200px;">Quantidade em <?php echo $materia['unidade_medida']; ?>:</label>
							<input class="quantidade" type="text" name="materias_primas[<?php echo $j; ?>][quantidade_utilizada]" rel="1" value="1" style="position: absolute; margin-left: 751px;"/>
							<button class="esconde" style="color:blue; position: absolute; margin-left: 1024px;">X</button> <br />
						</div>
					
					<?php } ?>
					<!--
					<div class="0 remover">
							<input class="mp_id" type="hidden" name="id_materia_prima" value="0"/>
							<span>Nome do produto 0</span>
							<span>Valor unitário:</span>
							<span class="valor_unidade">40.00</span>
							<label for="quantidade">Quantidade em <?php ?>:</label>
							<input class="quantidade" type="text" name="quantidade_utilizada" rel="1" value="1"/>
							<button class="esconde" style="color:blue;">X</button> <br />
					</div>
					
					<div class="1 remover">
						<input class="mp_id" type="hidden" name="id_materia_prima" value="1"/>
						<span>Nome do produto 1</span>
						<span>Valor unitário:</span>
						<span class="valor_unidade">80.00</span>
						<label for="quantidade">Quantidade em <?php ?>:</label>
						<input class="quantidade" type="text" name="quantidade_utilizada" rel="1" value="1"/>
						<button class="esconde" style="color:blue;">X</button>
					</div>
					
					<div class="2 remover">
						<input class="mp_id" type="hidden" name="id_materia_prima" value="2"/>
						<span>Nome do produto 2</span>
						<span>Valor unitário:</span>
						<span class="valor_unidade">55.00</span>
						<label for="quantidade">Quantidade em <?php ?>:</label>
						<input class="quantidade" type="text" name="quantidade_utilizada" rel="1" value="1"/>
						<button class="esconde" style="color:blue;">X</button>
					</div>
					-->
					
				</div>
				
				<label for="custo_total">Custo total de preparo do prato:</label>
				<input id="custo_total" type="text" readonly="readonly" name="custo_preparo" value="0.00"/>
				<br />
				<input class="salvar" style="margin:0 104px auto;" type="image" src="public/img/salvar.png" value="Salvar" id="salvar"/>
			</form>
		</div>
	</div>
</div>
<!-- RODAPE -->
<?php include("_rodape.php"); ?>
</body>
</html>