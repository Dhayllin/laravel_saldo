<?php

namespace App\Models;
//use App\User;
use Illuminate\Database\Eloquent\Model;
use DB;

class Balance extends Model
{
    public $timestemp = null;

    public function deposit(float $value) : Array
    {
        DB::beginTransaction(); 

        $beforeTotal =  $this->amout ? $this->amout : 0 ;
        $this->amout += number_format($value,2,'.','');
        $deposit = $this->save();
        
        $historic = auth()->user()->historics()->create([
            'type'          => 'I', 
            'amout'         =>  $value, 
            'total_before'  => $beforeTotal,
            'total_after'   => $this->amout,           
            'date'          => date('Ymd')
        ]);

        if($deposit && $historic){
            DB::commit();

            return[
                'success' => 'true',
                'message' => 'Sucesso ao recarregar.'
            ];

        }else{
            DB::rollBack();
            return[
                'success' => 'false',
                'message' => 'Falha ao recarregar.'
            ];
        }     
    }
}
