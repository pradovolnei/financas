<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class Financas extends Cart
{
    protected $table = "financas";
    protected $fillable = [ "nome" ];
   // protected $guarded = [ "id" ];

    public function listar ()
    {
        //return self::orderBy( "nome" )->lists( "nome", "id" );
		$vacations = self::select( );
		if(isset($_GET["mes"])){
			$parte = explode("/",$_GET["mes"]);
			$vacations->whereRaw("month(data_marcada) = ".$parte[0]);
			$vacations->whereRaw("year(data_marcada) = ".$parte[1]);
		}
		return $vacations->orderBy("data", "desc")->get ();
    }
	
	
}