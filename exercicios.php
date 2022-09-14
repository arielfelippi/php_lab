<?php

// primos
// intervalo de 1 até 10 primos nesse intervalo (2, 3, 5, 7);
$primos = []; // array();
// tudo esta incrementando um.
// $i++
// $i += 1
// $i = $i + 1

for ($i = 3; $i <= 10000; $i++) {
    $contarDivisoes = 0;
    $numeroAnterior = ($i -1); // i: 4, a :3

    for ($a = $numeroAnterior; $a > 1; $a--) {
        $resto = $i % $a; // 4 / 2 = 0

        if ($resto == 0 && $a < $i) { // o numero que to olhando nao e primo.
            $contarDivisoes++;
            break; // paramos de analisar o numero em questao
        }

    }

    if ($contarDivisoes == 0) {
        $primos[] = $i;
    }

}

echo "os numeros primos sao: " . implode("<br/>", $primos);
