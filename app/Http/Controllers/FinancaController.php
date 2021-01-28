<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Financas;
use App\Models\Carteira;
use App\Models\Saldo;


class FinancaController extends Controller
{
	public function __construct (Financas $financas, Carteira $carteira, Saldo $saldo)
	{
		//$this->admin = Financas::byAppUser ()->admin;
		$this->financas = $financas;
		$this->carteira = $carteira;
		$this->saldo = $saldo;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function consultation( )
	{

		$financ = $this->financas->listar();
		$saldo = $this->carteira->select()->orderBy("ano", "desc", "mes", "desc")->get();
		$meses = $this->financas->selectRaw("DATE_FORMAT(data_marcada, '%m/%Y') as epoca")->orderBy("data_marcada", "desc")->distinct()->get();
		$exibe_saldo = $this->saldo->listar();
		

		return view( 'home.home' )->with( [ "financ" => $financ, "meses" => $meses, "exibe_saldo" => $exibe_saldo ] );
	}
	
	public function novoCadastro( )
	{

		$this->financas->insert([ "nome" => $_POST["nome"], "valor" => $_POST["valor"], "parcelas" => $_POST["parcelas"], "parcela_atual" => 1, "data" => date("Y-m-d H:i:s"), "responsavel" => $_POST["responsavel"], "tipo" => $_POST["tipo"], "data_marcada" => $_POST["data"] ]);
		if($_POST["parcelas"] != "Mensal" && $_POST["parcelas"] >1 ){
			for($x=1;$x<$_POST["parcelas"];$x++){
				$data_pos = date("Y-m-d", strtotime($_POST["data"]."+ $x months"));
				$this->financas->insert([ "nome" => $_POST["nome"], "valor" => $_POST["valor"], "parcelas" => $_POST["parcelas"], "parcela_atual" => $x+1, "data" => date("Y-m-d H:i:s"), "responsavel" => $_POST["responsavel"], "tipo" => $_POST["tipo"], "data_marcada" => $data_pos ]);
			}
		}

		return back ()->with( "updated", "Demanda atualizada com sucesso!" );
	}
	
	public function edit( )
	{

		$this->financas->where("id", $_POST["id"])->update(["nome" => $_POST["nome"], "valor" => $_POST["valor"], "responsavel" => $_POST["responsavel"], "tipo" => $_POST["tipo"], "data_marcada" => $_POST["data"] ]);
		if(isset($_POST["ativo"]) && $_POST["ativo"] == 1){
			$this->financas->where("id", $_POST["id"])->update([ "ativo" => $_POST["ativo"] ]);
			$this->carteira->whereRaw("mes = ".date("m")." AND ano=".date("Y"))->update([ "saldo" => $_POST["soma"] ]);
		}
		
		return back ()->with( "updated", "Demanda atualizada com sucesso!" );
	}
	
	public function excluir( )
	{

		$this->financas->where("id", $_GET["id"])->delete();
		
		return back ()->with( "updated", "Demanda atualizada com sucesso!" );
	}
	
	public function excluirTodos( )
	{

		$this->financas->where("nome", $_GET["nome"])->where("parcelas", ">", $_GET["pa"])->where("data_marcada", ">=", date("Y-m-01"))->delete();
		
		return back ()->with( "updated", "Demanda atualizada com sucesso!" );
	}
	
	
	

}
