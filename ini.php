<?php
// =================   =================  Area de configuração básica da API para competições =================    =================

$apiKey = 'XXXXXXXXXXXXXXXXXXXXXX';  //apiKey
$baseUrl = 'https://api.football-data.org/v4/';  //Url da api
$endpoint = 'competitions';  // Endpoint para ligas

// Cabeçalhos para a requisição da API
$headers = [
    "X-Auth-Token: $apiKey"
];

// cURL
$ch = curl_init();

// Configurando a requisição cURL
curl_setopt($ch, CURLOPT_URL, $baseUrl . $endpoint); //onde conectar
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //nao exibir direto na tela
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //o que sera enviado

$responseCompetitions = curl_exec($ch); // executar

if ($responseCompetitions === false) {
    echo "Erro na requisição: " . curl_error($ch);
} else {
    $data = json_decode($responseCompetitions, true);
}
//echo '<pre>';
//print_r($data);  // Exibe o conteúdo para verificação
//echo '</pre>';
curl_close($ch);

// =================   ================= Fim Area de configuração básica da API para competições =================     =================



$selectedTeam =  '';
$selectedTeamId = isset($_REQUEST['selectedTeam']) ? $_REQUEST['selectedTeam'] : '';
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'not';
$selectedLeagueId = isset($_REQUEST['selectedLeagueId']) ? $_REQUEST['selectedLeagueId'] : '';
if ($op == 'Pre') {
    $selectedCountry = isset($_REQUEST['buscaRegiao']) ? $_REQUEST['buscaRegiao'] : '';
    $leaguename = isset($_REQUEST['leagueId']['name']) ? $_REQUEST['leagueId']['name'] : '';
    $selectedTeamId = '';
    $selectedLeagueId = '';


} else if ($op == 'not') {
    $selectedCountry = '';

}
else if ($op == 'Clear') {
    $selectedCountry = '';
    $selectedTeamId = '';
    $selectedLeagueId = '';

    $selectedTeam =  '';

} 
else if ($op == 'a') { 
    
    $selectedCountry = isset($_REQUEST['selectedCountry']) ? $_REQUEST['selectedCountry'] : '';
    $selectedLeagueId = isset($_REQUEST['leagueId']) ? $_REQUEST['leagueId'] : '';
    $endpointMatches = "competitions/$selectedLeagueId/matches";
    $leaguename = isset($_REQUEST['leagueId']['name']) ? $_REQUEST['leagueId']['name'] : '';
    print $leaguename;
    // 
    $chMatches = curl_init();

    curl_setopt($chMatches, CURLOPT_URL, $baseUrl . $endpointMatches);
    curl_setopt($chMatches, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($chMatches, CURLOPT_HTTPHEADER, $headers); // Reutilizando o mesmo header de autenticação

    //requisicao
    $matchesResponse = curl_exec($chMatches);
    curl_close($chMatches);

    // Verificando se a resposta foi obtida corretamente
    if (!$matchesResponse) {
        echo "Erro na requisição das partidas: " . curl_error($chMatches);
    }

    // pegando os dados para a variavel
    $matchesData = json_decode($matchesResponse, true);

    // Depuração
    //echo '<pre>';
    //print_r($matchesData);  // Exibe o conteúdo para verificação
    //echo '</pre>';



}
else if ($op == 'SelectT') {
    $selectedTeam = isset($_REQUEST['selectedTeam']) ? $_REQUEST['selectedTeam'] : '';
    $selectedCountry = isset($_REQUEST['selectedCountry']) ? $_REQUEST['selectedCountry'] : '';
    $selectedLeagueId = isset($_REQUEST['leagueId']) ? $_REQUEST['leagueId'] : '';
    $endpointMatches = "competitions/$selectedLeagueId/matches";
    $leaguename = isset($_REQUEST['leagueId']['name']) ? $_REQUEST['leagueId']['name'] : '';
    print $leaguename;
    
    $chMatches = curl_init();

    curl_setopt($chMatches, CURLOPT_URL, $baseUrl . $endpointMatches);
    curl_setopt($chMatches, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($chMatches, CURLOPT_HTTPHEADER, $headers); // Reutilizando o mesmo header de autenticação

  
    $matchesResponse = curl_exec($chMatches);
    curl_close($chMatches);

 
    if (!$matchesResponse) {
        echo "Erro na requisição das partidas: " . curl_error($chMatches);
    }

    
    $matchesData = json_decode($matchesResponse, true);

    // Depuração
    //echo '<pre>';
    //print_r($matchesData);  // Exibe o conteúdo para verificação
    //echo '</pre>';
    

}



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa Avançada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../estilo.css" media="screen" />
</head>

<style>
    body {
        background-color: #dad9d3;
    }

    #PBASE {
        display: none;
    }

    .btn:hover {
        color: black;
        background-color: lightblue;
    }

    #form1 {
        border: 2px solid #ccbb46;
        justify-content: center;
        border-radius: 23px;
    }

    #form11 {
        align-items: center;
        padding: 15px;
        justify-content: center;
    }






    option,
    td,
    th {
        text-align: center;
    }

    .btnLFiltro:hover {
        background-color: #ccbb46;
        color: white;
        transition: 0.5s;
    }

    .btnLFiltro {
        max-width: 300px;
        margin: 10px;
        padding: 5px;
        border-radius: 15px;
        text-decoration: none;
        color: black;
        text-align: center;
    }

    .btnLFiltro.click {
        color: #a33939;
    }

    #btnLFiltro {
        align-items: center;
        justify-content: center;
    }

    .rrw {
        margin: 10px;
    }


    table {
        width: 100%;
        table-layout: auto;
        word-wrap: break-word;
    }

    .scrollable-table-wrapper {
        height: 100%;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }
</style>




</body>




   <!-- ===================== =====================  Filtro por região  ===================== ===================== -->
<div class="row justify-content-center" style="min-height: 100vh; max-height: 100vh">
    <div class="col-12 col-sm-6 col-md-4">

        <div class="row justify-content-center rrw">
         
            <div class="col-12 col-sm-6 col-md-7">
                <form method="post" action="?op=Pre">
                    <?php
                    //if (!isset($_REQUEST['buscaRegiao']) && (!isset($_REQUEST['leagueId']))) {
                    ?>

                    <select class="form-control" name="buscaRegiao">
                        <option value=''><?php echo $selectedCountry ?: "Selecione uma região"; ?>
                        </option>
                        <?php
                        $exibidos = [];
                        $data = json_decode($responseCompetitions, true);
                        if (isset($data['competitions']) && is_array($data['competitions'])) {
                            foreach ($data['competitions'] as $league) {
                                $countryName = isset($league['area']['name']) ? $league['area']['name'] : 'País não disponível';
                                if (!in_array($countryName, $exibidos)) {
                                    echo "<option value='$countryName'>$countryName</option>";
                                    $exibidos[] = $countryName;
                                }
                            }
                        }

                        //}
                        ?>
                    </select>

            </div>


            <div class="col-12 col-sm-6 col-md-4">
                <?php
                //if ($selectedCountry == '') {
                
                ?>
                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <?php
                // }
                ?>
                </form>

            </div>


        </div>






        <div class="row justify-content-center rrw">
            <?php
           
            if ($selectedCountry != '') {
 //   ===================== ===================== Exibir seleção de liga caso a região tiver sido selecionada  ===================== ===================== 
                ?>

                <div class="col-12 col-sm-6 col-md-7">
                    <form action="?op=a" method="post">
                        <select class="form-control" name="leagueId">

                            <option value=''><?php echo $leaguename ?: "Selecione a Liga"; ?>

                                <?php
                                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                if ($httpCode != 200) {
                                    echo "Erro na requisição. Código HTTP: $httpCode";
                                } else {
                                    if (isset($data['competitions']) && is_array($data['competitions'])) {
                                        foreach ($data['competitions'] as $league) {
                                            $leagueId = $league['id'];
                                            $leagueName = $league['name'];
                                            $countryName = $league['area']['name'];
                                            if (empty($selectedCountry) || strcasecmp($countryName, $selectedCountry) == 0) {
                                                echo "<option value='$leagueId'>$leagueName - $countryName</option>";
                                            }
                                        }
                                    }
                                }

                                ?>
                        </select>



                </div>





                <div class="col-12 col-sm-6 col-md-4">
                    <?php //if ($selectedLeagueId == '' && $selectedCountry != '' ) { ?>
                    <input type="hidden" name="selectedCountry" value="<?php echo $selectedCountry; ?>">

                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                    <?php //} ?>
                </div>

                </form>
            <?php } ?>
        </div>







        <div class="row justify-content-center rrw">
            <div class="col-12 col-sm-6 col-md-7">
                <?php

                if ((isset($_REQUEST['leagueId']))) {
                    $selectedLeagueName = '';
                    if (!empty($selectedLeagueId)) {
                        foreach ($data['competitions'] as $league) {
                            if ($league['id'] == $selectedLeagueId) {
                                $selectedLeagueName = $league['name'];
                                break;
                            }
                        }
                    }
                    echo "Nome da Liga: $selectedLeagueName <br>";
                }
 

                ?>
            </div>
        </div>


        <div class="row justify-content-center rrw">



       
            <div class="col-12 col-sm-6 col-md-7">
            <form action="?op=SelectT" method="post">
                <?php

                if (isset($matchesData['matches']) && is_array($matchesData['matches']) && count($matchesData['matches']) > 0) {
                    echo "<select class='form-control' name='selectedTeam'>";

                    echo "<option value=''> Selecione um time</option>";

                    $Texibidos = [];
                    foreach ($matchesData['matches'] as $match) {

                        //$nameAt = $matchesData['name']; 
                
                        $timeCId = isset($match['homeTeam']['id']) ? $match['homeTeam']['id'] : 'Id não disponível';
                        $timeFId = isset($match['awayTeam']['id']) ? $match['awayTeam']['id'] : 'Id não disponível';

                        $TimeC = isset($match['homeTeam']['name']) ? $match['homeTeam']['name'] : 'Equipe não disponível';
                        $TimeF = isset($match['awayTeam']['name']) ? $match['awayTeam']['name'] : 'Equipe não disponível';

                        if (!in_array($TimeC, $Texibidos)) {
                            echo "<option value='$timeCId'>" . $TimeC . "</option>";
                            $Texibidos[] = $TimeC;
                        }
                        if (!in_array($TimeF, $Texibidos)) {

                            echo "<option value='$timeFId'>" . $TimeF . "</option>";
                            $Texibidos[] = $TimeF;
                        }

                    }

                }

                echo "</select>";
                //================================================================================================================================
                

                ?>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <?php if ($selectedLeagueId != '') { ?>
                    


                   
                    <input type="hidden" name="leagueId" value="<?php echo $selectedLeagueId; ?>">
                    <input type="hidden" name="selectedCountry" value="<?php echo $selectedCountry; ?>">
                    <input type="hidden" name="leaguename" value="<?php echo $leaguename; ?>">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>

                <?php } 
        print  $selectedTeam; 
        ?>
                </form>
            </div>


            <div class="col-12 col-sm-6 col-md-4">

            </div>

        </div>






        <div class="row justify-content-center">
            <a href="?op=Clear" class="btnLFiltro">Limpar filtro</a>
        </div>


    </div>






    <div class="col-12 col-sm-6 col-md-8 d-flex" style="min-height: 100vh; max-height: 100vh;">
        <div class="p-3  flex-grow-1">

            <?php
            //================================================================================================================================
           
           
            if (isset($matchesData['matches']) && is_array($matchesData['matches']) && count($matchesData['matches']) > 0) {
                echo "<div class='scrollable-table-wrapper'>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>Data</th><th>Equipe 1</th><th>Equipe 2</th><th>Placar</th></tr></thead>";
                echo "<tbody>";
            
                // Se um time foi selecionado, filtrar os jogos para esse time
                if (isset($selectedTeamId) && !empty($selectedTeamId )&& $selectedTeamId !=='' ) {
                    foreach ($matchesData['matches'] as $match) {
                        // Inicializar as variáveis com valores padrão
                        $TimeCasa = 'Equipe não disponível';
                        $TimeFora = 'Equipe não disponível';
                        $PontosC = 'N/A';
                        $PontosF = 'N/A';
                        $date = 'Data não disponível';
            
                       
                        if ($match['homeTeam']['id'] == $selectedTeamId) {
                            $TimeCasa = isset($match['homeTeam']['name']) ? $match['homeTeam']['name'] : 'Equipe não disponível';
                            $PontosC = isset($match['score']['fullTime']['home']) ? $match['score']['fullTime']['home'] : 'N/A';
                        }
            
                      
                        if ($match['awayTeam']['id'] == $selectedTeamId) {
                            $TimeFora = isset($match['awayTeam']['name']) ? $match['awayTeam']['name'] : 'Equipe não disponível';
                            $PontosF = isset($match['score']['fullTime']['away']) ? $match['score']['fullTime']['away'] : 'N/A';
                        }
            
                        if ($match['homeTeam']['id'] == $selectedTeamId || $match['awayTeam']['id'] == $selectedTeamId) {
                            $date = isset($match['utcDate']) ? date('d/m/Y H:i', strtotime($match['utcDate'])) : 'Data não disponível';
            
                            
                            echo "<tr>";
                            echo "<td>$date</td>";
                            echo "<td>$TimeCasa</td>";
                            echo "<td>$TimeFora</td>";
                            echo "<td>$PontosC - $PontosF</td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    
                    foreach ($matchesData['matches'] as $match) {
                       
                        $TimeCasa = 'Equipe não disponível';
                        $TimeFora = 'Equipe não disponível';
                        $PontosC = 'N/A';
                        $PontosF = 'N/A';
                        $date = 'Data não disponível';
            
                       
                        $TimeCasa = isset($match['homeTeam']['name']) ? $match['homeTeam']['name'] : 'Equipe não disponível';
                        $PontosC = isset($match['score']['fullTime']['home']) ? $match['score']['fullTime']['home'] : 'N/A';
            
                       
                        $TimeFora = isset($match['awayTeam']['name']) ? $match['awayTeam']['name'] : 'Equipe não disponível';
                        $PontosF = isset($match['score']['fullTime']['away']) ? $match['score']['fullTime']['away'] : 'N/A';
            
                        
                        $date = isset($match['utcDate']) ? date('d/m/Y H:i', strtotime($match['utcDate'])) : 'Data não disponível';
            
                        
                        echo "<tr>";
                        echo "<td>$date</td>";
                        echo "<td>$TimeCasa</td>";
                        echo "<td>$TimeFora</td>";
                        echo "<td>$PontosC - $PontosF</td>";
                        echo "</tr>";
                    }
                }
            
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else if (isset($matchesData['matches'])) {
                echo "<div id='Ptimes'>";
                echo "<p>Não há partidas disponíveis para esta liga ou temporada.</p>";
                echo "</div>";
            }
            ?>
            










        </div>
    </div>



</div>








<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
<script src="../jquery-3.7.1.js"></script>
</body>

</html>