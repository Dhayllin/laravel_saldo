<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
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

    public function withdraw(float $value) : Array
    {
        if($this->amout < $value)
            return [
                'success' => 'false',
                'message' => 'Saldo insuficiente.'
            ];
        
        DB::beginTransaction(); 

        $beforeTotal =  $this->amout ? $this->amout : 0 ;
        $this->amout -= number_format($value,2,'.','');
        $withdraw = $this->save();
        
        $historic = auth()->user()->historics()->create([
            'type'          => 'I', 
            'amout'         =>  $value, 
            'total_before'  => $beforeTotal,
            'total_after'   => $this->amout,           
            'date'          => date('Ymd')
        ]);

        if($withdraw && $historic){
            DB::commit();

            return[
                'success' => 'true',
                'message' => 'Sucesso ao retirar.'
            ];

        }else{
            DB::rollBack();
            return[
                'success' => 'false',
                'message' => 'Falha ao retirar.'
            ];
        }     
    }

    public function transfer(float $value, User $sender) : Array
    {
        if($this->amout < $value)
            return [
                'success' => 'false',
                'message' => 'Saldo insuficiente.'
            ];
        
        DB::beginTransaction(); 
        // Atualiza o próprio saldo.
        $beforeTotal =  $this->amout ? $this->amout : 0 ;
        $this->amout -= number_format($value,2,'.','');
        $transfer = $this->save();
        
        $historic = auth()->user()->historics()->create([
            'type'               => 'T', 
            'amout'              =>  $value, 
            'total_before'       => $beforeTotal,
            'total_after'        => $this->amout,           
            'date'               => date('Ymd'),
            'user_id_trasaction' => $sender->id
        ]);

          // Atualiza o saldo do recebedor.
          $senderBalance = $sender->balance()->firstOrCreate([]);
          $totalBeforeSender = $senderBalance->amout ? $senderBalance->amout : 0 ;
          $senderBalance->amout += number_format($value,2,'.','');
          $transferSender = $senderBalance->save();
          
          $historicSender = $sender->historics()->create([
              'type'               => 'T', 
              'amout'              =>  $value, 
              'total_before'       => $totalBeforeSender,
              'total_after'        => $senderBalance->amout,           
              'date'               => date('Ymd'),
              'user_id_trasaction' => auth()->user()->id
          ]);

        if($transfer && $historic && $transferSender && $historicSender){
            DB::commit();

            return[
                'success' => 'true',
                'message' => 'Sucesso ao Transferir.'
            ];

        }else{
            DB::rollBack();
            return[
                'success' => 'false',
                'message' => 'Falha ao Transferir.'
            ];
        }        
    }
}