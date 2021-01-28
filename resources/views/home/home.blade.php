<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
	<script> 
        function confirmar() { 
			var t = confirm("TODOS OS DADOS SERÃO PERDIDOS! DESEJA CONTINUAR?");
            if(t == true ){
				return true;
			}else{
				return false;
			}
        } 
    </script> 
	<div class="jumbotron text-center" style="background-color:#39b167; color:#FFF">
	  <h1>Financeiro</h1>
	  <?php $sa =0; ?>
	  @foreach($exibe_saldo as $data_saldo)
	  <p>Saldo atual <b>R${{number_format($data_saldo->saldo, 2, ',', '.')}}</b></p>
	  <?php $sa = $data_saldo->saldo; ?>
	  <p>Saldo previsto <?php if(isset($_GET["mes"])) echo $_GET["mes"]; else echo date("m/Y"); ?> <b>R${{number_format($data_saldo->saldo+$data_saldo->fatura-$data_saldo->despesa, 2, ',', '.')}}</b></p>
	  @endforeach
	</div>
	
	<form action="" method="get">
	<div class="container">	
		  <div class="row">
			<div class="col-sm-4">
			 <label>Mês </label>
			 <select name="mes" class="form-control" > 
				@foreach($meses as $tempos)
				
				<option value="{{ $tempos->epoca }}" <?php if(isset($_GET["mes"]) && $_GET["mes"] == $tempos->epoca) echo "selected"; ?> > {{ $tempos->epoca }} </option>
				@endforeach
			 </select>
			</div>
		  </div>
		  <div class="row">
			<div class="col-sm-4">
			 <input type="submit" value="Exibir" name="exibir" />
			</div>
		  </div>
	</div>
	</form>
  
	<br>
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<button class='btn btn-block btn-primary btn-sm add-mobile'  data-toggle='modal' data-target='#novo'> Nova Transição </button>
				</div>
			</div>
		</div>
	<br>
		@foreach($financ as $valores)
		<div class="container">
		  <div class="row">
			<div class="col-sm-4">
			  <a href="#" data-toggle='modal' data-target="#edit{{$valores->id}}" ><h3>{{ $valores->nome }}</h3></a>
			  @if($valores->tipo ==1)
				<p style="color:#00FF00">+ R$ {{ number_format($valores->valor, 2, ',', '.') }}</p>
			  @else
				<p style="color:#FF0000">- R$ {{ number_format($valores->valor, 2, ',', '.') }}</p>
			  @endif
			  @if($valores->parcelas)
				  @if($valores->parcelas == "Mensal")
					<p>Mensal</p>
				  @else
					<p>{{ $valores->parcela_atual }}/{{ $valores->parcelas }}</p>
				  @endif
			  @endif
			  <p> Pagante:  <a href="#">{{ $valores->responsavel }}</a> </p>
			  <p>Data de Pagamento:  {{ date("d/m/Y", strtotime($valores->data_marcada)) }} </p>
			  @if($valores->ativo == 1)
				  <p style="color:#00FF00"> Pagamento Confirmado </p>
			  @else
				  <p> Pagamento Não Confirmado </p>
			  @endif
			</div>
		  </div>
		   <div class="row">
				<div class="col-sm-4">
					<a href="#" data-toggle='modal' data-target="#edit{{$valores->id}}" > Atualizar </a>
				</div>
			</div>
		  </div>
		  <br><br>
		@endforeach
	
	
	@foreach($financ as $valores)
		<form action="{{ url( 'edit' ) }}"  method="post" enctype="multipart/form-data"  >
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="modal fade" id="edit{{$valores->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Editar Transição </h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  
		  <div class="modal-body">
			<input type="hidden" name="id" value="{{$valores->id}}">
			<input type="hidden" name="soma" value="{{$valores->valor+$sa }}">
			<div class="form-group">
			  <label>Nome da Transição</label>
			  <input type="text" name="nome" required class="form-control" value="{{$valores->nome}}" />
			  
			</div>
			
			<div class="form-group">
			  <input type="radio" name="tipo" value="1" @if($valores->tipo == 1) checked @endif > <label>Crédito</label> <br>
			  <input type="radio" name="tipo" value="2" @if($valores->tipo == 2) checked @endif> <label>Débito</label>
			</div>
			
			<div class="form-group">
			  <label>Valor</label> (R$)
			  <input type="number" name="valor" required class="form-control" value="{{$valores->valor}}" />
			 
			</div>
			
			
			<div class="form-group">
			  <label>Responsável</label> 
			  <input type="text" name="responsavel" required class="form-control" value="{{$valores->responsavel}}" />
			 
			</div>
			
			<div class="form-group">
			  <label>Data de pagamento</label> 
			  <input type="date" name="data" required class="form-control" value="{{$valores->data_marcada}}" />
			 
			</div>
			
			@if($valores->ativo != 1)
			<div class="form-group">
			  <label>Pagamento Confirmado?</label> <br>
			  <input type="radio" name="ativo" value="1" @if($valores->ativo == 1) checked @endif /> Sim <br>
			  <input type="radio" name="ativo" value="2" @if($valores->ativo != 1) checked @endif /> Não
			 
			</div>
			@endif
			
			<div class="form-group">
				<a onclick="return confirm('TODOS OS DADOS SERÃO PERDIDOS! DESEJA CONTINUAR?');" href="{{ url( 'delete' ) }}?id={{$valores->id}}" ><img src="dist/img/lixeira.png" style="width:5%;"> Remover Transição</a>
			</div>
			
			@if($valores->parcelas > 1 && $valores->parcela_atual < $valores->parcelas && $valores->parcelas != "Mensal")
				<div class="form-group">
					<a onclick="return confirm('TODOS OS DADOS SERÃO PERDIDOS! DESEJA CONTINUAR?');" href="{{ url( 'deleteall' ) }}?nome={{$valores->nome}}&pa={{$valores->parcela_atual}}" ><img src="dist/img/lixeira.png" style="width:5%;"> Remover Todas as próximas parcelas</a>
				</div>
			@endif
			
		  </div>
		  
		  <div class="modal-footer">
			<a class="btn btn-danger" data-dismiss="modal">Fechar</a>
			<input type="submit" class="btn btn-success" value='Atualizar' name="remarcar" />
		  </div>
	
		</div>
	  </div>
	</div>
	</form>
		@endforeach
	<form action="{{ url( 'novo' ) }}"  method="post" enctype="multipart/form-data"  >
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="modal fade" id="novo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Nova Transição </h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  
		  <div class="modal-body">
			
			<div class="form-group">
			  <label>Nome da Transição</label>
			  <input type="text" name="nome" required class="form-control" />
			  
			</div>
			
			<div class="form-group">
			  <input type="radio" name="tipo" value="1" checked > <label>Crédito</label> <br>
			  <input type="radio" name="tipo" value="2"> <label>Débito</label>
			</div>
			
			<div class="form-group">
			  <label>Valor</label> (R$)
			  <input type="number" name="valor" required class="form-control" />
			 
			</div>
			
			<div class="form-group">
			  <label>Parcelas</label> 
			  <select name="parcelas" class="form-control" required>
				<option> </option>
				<option value="Mensal" > Mensal </option>
				<?php 
					for($a=1;$a<=36;$a++){
						echo "<option value='$a' > $a </option>";
					}
				?>
			  </select>
			 
			</div>
			
			<div class="form-group">
			  <label>Responsável</label> 
			  <input type="text" name="responsavel" required class="form-control" />
			 
			</div>
			
			<div class="form-group">
			  <label>Data de pagamento</label> 
			  <input type="date" name="data" required class="form-control" />
			 
			</div>
		  </div>
		  
		  <div class="modal-footer">
			<a class="btn btn-danger" data-dismiss="modal">Fechar</a>
			<input type="submit" class="btn btn-success" value='Confirmar' name="remarcar" />
		  </div>
	
		</div>
	  </div>
	</div>
	</form>
	
</body>
</html>
