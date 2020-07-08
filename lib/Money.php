<?php

abstract class Money{
    static function real($valor){
       return "R$ " . number_format($valor, 2, ",",".");
    }

    static function realSemSifrao($valor){
        return number_format($valor, 2, ",",".");
     }
}