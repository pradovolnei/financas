<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class Saldo extends Cart
{
    protected $table = "vw_saldo";
   // protected $fillable = [ "nome" ];
   // protected $guarded = [ "id" ];

   
	public function listar ()
    {
        //return self::orderBy( "nome" )->lists( "nome", "id" );
		$exibe_saldo = self::selectRaw("SUM(fatura) as fatura,SUM(despesa) as despesa,saldo,mes,ano");
		
		if(isset($_GET["mes"])){
			$parte = explode("/",$_GET["mes"]);
			$data_dia = $parte[1]."-".$parte[0]."-31";
			$dif = strtotime(date("Y-m-d")) - strtotime($data_dia);
			
			/*if($dif>0){
				
				$exibe_saldo->whereRaw("mes = ".$parte[0]);
				$exibe_saldo->whereRaw("ano = ".$parte[1]);
			}else{
				$exibe_saldo->whereRaw("mes = ".date("m"));
				$exibe_saldo->whereRaw("ano = ".date("Y"));
			}*/
			//$exibe_saldo->whereRaw("mes = ".$parte[0]);
			//$exibe_saldo->whereRaw("ano = ".$parte[1]);
			$exibe_saldo->whereRaw("referencia<='".$data_dia."'");
		}else{
			//$exibe_saldo->whereRaw("mes = ".date("m"));
			//$exibe_saldo->whereRaw("ano = ".date("Y"));
			$exibe_saldo->whereRaw("referencia<='".date("Y-m-d")."'");
		}
		
		return $exibe_saldo->get ();
    }
	
}