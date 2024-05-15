<?php

namespace App\Utils;

use Rurounize\NumerosALetras;
use Illuminate\Support\Str;
class ConvertirALetra{
    public function convertir($numero):string
    {
        $numeroALetra = NumerosALetras::getInstance();
        $numeroALetra->setMascaraSalidaDecimal("00/100 M.N.")
                  ->setSeparadorDecimalSalida("pesos")
                  ->setApocoparUnoParteEntera(true)
                  ->setLetraCapital(true);
        return Str::upper($numeroALetra->convertirALetras($numero));
    }
}
