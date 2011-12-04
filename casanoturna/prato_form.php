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
	<link rel="stylesheet" type="text/css" href="public/css/padrao.css" />
	<link rel="stylesheet" type="text/css" href="public/css/cardapio.css" />
	
	<script type="text/javascript" src="public/js/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="public/js/superfish.js"></script>
	<script type="text/javascript" src="public/js/jquery.js"></script>
	
	
	<script type="text/javascript">
	$(document).ready (function(){
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
		
		$('#salvar').click(function(){
			//alert("foi");
			$('.remover').remove();
		});
		
		$('.quantidade').change(function(){
			var rel = $(this).attr("rel");
			//alert(rel);
			var mult = $(this).val();
			//alert(mult);
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
<div id="Corpo" style="background: none repeat scroll 0 0 white;">
	<h2>Pratos</h2>
	<select class="materia_prima">
		<option value="nenhuma" rel="-">Selecione uma materia prima</option>
		<option value="0" rel="10">Produto 0</option>
		<option value="1" rel="50">Produto 1</option>
		<option value="2" rel="20">Produto 2</option>
	</select>

	<button class="add_mp">Adicionar produto</button>
	
	<br />
	<h3 style="color:red;"><B>Produtos Selecionados:</B></h3>
	<form>
	
		<div class="info_prato">
		
			<label for="nome">Nome do prato:</label>
			<input class="nome" type="text" name="nome"/>
			
			<label for="codigo">Codigo atribuido a este prato:</label>
			<input class="codigo" type="text" name="codigo"/>
			
			<label for="tempo">Tempo de preparo:</label>
			<input class="tempo" type="text" name="tempo_preparo"/>
			
		</div>
		
		<div id="lista_produtos">
		
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
			
		</div>
		
		<label for="custo_total">Custo total de preparo do prato:</label>
		<input id="custo_total" type="text" readonly="readonly" name="custo_preparo" value="0.00"/>
		<br />
		<input class="salvar" style="margin:0 104px auto;" type="image" src="public/img/salvar.png" value="Salvar" id="salvar"/>
	
	</form>

</div>
<!-- RODAPE -->
<?php include("_rodape.php"); ?>
</body>
</html>