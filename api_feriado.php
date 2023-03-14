<?php

header('Content-Type: application/json');

// essa funcao ela que apareca em todos os dias que foram adicionados os feriados
$feriados = array(
    '01-01' => 'Ano Novo',
    '02-20'  => 'Carnaval',
    '02-21'  => 'Carnaval',
    '02-22'  => 'Carnaval',
    '04-21' => 'Tiradentes',
    '05-01' => 'Dia do Trabalho',
    '09-07' => 'Independência do Brasil',
    '10-12' => 'Nossa Senhora Aparecida',
    '11-02' => 'Finados',
    '11-15' => 'Proclamação da República',
    '12-25' => 'Natal'
);

// essa funcao ela que apareca em todos os anos os feriados
function getFeriados($ano) {
    $feriados = array(
        '01-01' => 'Ano Novo',
        '02-20'  => 'Carnaval',
        '02-21'  => 'Carnaval',
        '02-22'  => 'Carnaval',
        '04-21' => 'Tiradentes',
        '05-01' => 'Dia do Trabalho',
        '09-07' => 'Independência do Brasil',
        '10-12' => 'Nossa Senhora Aparecida',
        '11-02' => 'Finados',
        '11-15' => 'Proclamação da República',
        '12-25' => 'Natal'
    );

    $result = array();

    // aqui ele faz que apareca a data e nome em dias uteis e aos finais de semana
    foreach ($feriados as $data => $nome) {
        $dataCompleta = $ano . '-' . $data;
        $result[] = array(
            'data' => $dataCompleta,
            'nome' => $nome
        );
    }

    return $result;
}

// Nessa funcao pegamos os anos que qeuremos que sejam exibidos todos os feriados
function getFeriadosTodosAnos($anoInicial, $anoFinal) {
    $feriadosTodosAnos = array();
    for ($ano = $anoInicial; $ano <= $anoFinal; $ano++) {
        $feriados = getFeriados($ano);
        $feriadosTodosAnos = array_merge($feriadosTodosAnos, $feriados);
    }
    return $feriadosTodosAnos;
}

// Aqui podemos definir o inicio que definir a data de inicio e fim ( quanto mais a data de inicio e fim mais tempo para carregar a pagina )
$anoInicial = isset($_GET['ano_inicial']) ? $_GET['ano_inicial'] : 2000;
$anoFinal = isset($_GET['ano_final']) ? $_GET['ano_final'] : 2030;
$resultado = getFeriadosTodosAnos($anoInicial, $anoFinal);

echo json_encode($resultado);
