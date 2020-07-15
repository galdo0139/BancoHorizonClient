<?php

abstract class Money{
   static function real($valor){
      return "R$ " . number_format($valor, 2, ",",".");
   }

   static function realSemSifrao($valor){
      return number_format($valor, 2, ",",".");
   }

   static function valor($valor){
      $valor = str_replace(".","", $valor);
      $valor = str_replace(",",".", $valor);
      
      $valor = abs($valor);
      var_dump($valor);
      return $valor;
   }
}

