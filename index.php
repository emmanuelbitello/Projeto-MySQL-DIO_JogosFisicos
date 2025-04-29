<html>

<head>
<title>Coleção de Jogos Físicos</title>
</head>
<body>

<?php
// Habilita a exibição de erros para depuração
ini_set("display_errors", 1);
// Define o tipo de conteúdo da resposta como HTML com charset UTF-8 (para caracteres PT-BR)
header('Content-Type: text/html; charset=utf-8');

// Exibe a versão atual do PHP
echo 'Versão Atual do PHP: ' . phpversion() . '<br>';

// ================================================================
// Configurações de Conexão com o Banco de Dados MySQL Local
// ================================================================
$servername = "localhost"; // Endereço do servidor MySQL local
$username = "emmanuel-bitello"; // Usuário MySQL local
$password = "mysql@22"; // Senha do usuário MySQL local
$database = "CadastroJogos"; // Nome do banco de dados


// Criar conexão com o banco de dados MySQL usando a extensão mysqli
$link = new mysqli($servername, $username, $password, $database);

/* Verifica se houve erro na conexão */
if (mysqli_connect_errno()) {
    // Se a conexão falhar, exibe a mensagem de erro e encerra o script
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// Define o charset da conexão com o banco de dados para suportar caracteres especiais (como UTF-8)
mysqli_set_charset($link, "utf8mb4");


// ================================================================
// Geração Aleatória de Dados para Inserção
// ================================================================

// Gera um Título aleatório
$valor_rand2 = strtoupper(substr(bin2hex(random_bytes(4)), 1));
$titulo_gerado = "Jogo " . $valor_rand2;

// Define a lista de plataformas disponíveis e escolhe uma aleatoriamente
$opcoes_plataforma = array(
    "PlayStation 4",
    "PlayStation 5",
    "Nintendo Switch",
    "Xbox One",
    "Xbox-Series S/X",
    "PC"
);
$plataforma_gerada = $opcoes_plataforma[array_rand($opcoes_plataforma)];

// Determina as opções de Mídia com base na Plataforma selecionada e escolhe uma aleatoriamente
$opcoes_midia = array();
switch ($plataforma_gerada) {
    case "Nintendo Switch":
        $opcoes_midia = array("Cartucho", "Digital");
        break;
    case "PlayStation 4":
    case "PlayStation 5":
    case "Xbox One":
    case "Xbox-Series S/X":
        $opcoes_midia = array("Blu-Ray", "Digital");
        break;
    case "PC":
        $opcoes_midia = array("DVD", "Digital");
        break;
    default: // Caso genérico (se a plataforma gerada não estiver explicitamente listada acima)
        $opcoes_midia = array("Blu-Ray", "Cartucho", "DVD", "Digital");
        break;
}
$midia_gerada = $opcoes_midia[array_rand($opcoes_midia)];

// Define a lista de opções de Região e escolhe uma aleatoriamente
$opcoes_regiao = array(
    "NTSC-U",
    "PAL",
    "NTSC-J"
);
$regiao_gerada = $opcoes_regiao[array_rand($opcoes_regiao)];


// Determina a Data de Início para a Geração Aleatória da Data de Aquisição,
// baseando-se na data de lançamento da Plataforma e Mídia (para PC)
$timestamp_inicio = 0; // Inicializa timestamp de início

// Mapa de Datas de Lançamento/Referência por Plataforma (Formato dd/mm/yyyy)
$datas_referencia = array(
    "PlayStation 4"     => "15/11/2013",
    "PlayStation 5"     => "12/11/2020",
    "Nintendo Switch"   => "03/03/2017",
    "Xbox One"          => "22/11/2013",
    "Xbox-Series S/X"   => "10/11/2020",
    "PC_DVD"            => "11/06/1998", // Data base para PC com Mídia DVD (física)
    "PC_Digital"        => "12/09/2003"  // Data base para PC com Mídia Digital
);

$data_referencia_base_str = ""; // String da data base para a geração

// Lógica para determinar a data de referência base
if ($plataforma_gerada === "PC") {
    if ($midia_gerada === "DVD") {
        $data_referencia_base_str = $datas_referencia["PC_DVD"];
    } else { // Mídia é Digital para PC
        $data_referencia_base_str = $datas_referencia["PC_Digital"];
    }
} else {
    // Para outras plataformas, usa a data de lançamento da plataforma correspondente
    // As chaves aqui devem corresponder exatamente aos nomes das plataformas
    $data_referencia_base_str = $datas_referencia[$plataforma_gerada];
}

// Converte a string da data de referência base para timestamp
$data_referencia_obj = DateTime::createFromFormat('d/m/Y', $data_referencia_base_str);
if ($data_referencia_obj === false) {
    // Fallback se houver erro ao parsear a data (usa uma data antiga padrão)
    $timestamp_inicio = strtotime("1990-01-01");
} else {
    $timestamp_inicio = $data_referencia_obj->getTimestamp();
}

// Obtém o timestamp da data atual
$timestamp_fim = time(); // Timestamp do momento atual

// Garante que o timestamp_inicio não seja maior que o timestamp_fim antes de gerar o número aleatório
if ($timestamp_inicio > $timestamp_fim) {
    $timestamp_inicio = strtotime("-1 year", $timestamp_fim); // Retrocede 1 ano se houver inconsistência
}

// Gera um timestamp aleatório entre o timestamp de início e o timestamp de fim
$timestamp_aquisicao = mt_rand($timestamp_inicio, $timestamp_fim);

// Formata o timestamp gerado para o formato YYYY-MM-DD (formato para MySQL DATE)
$data_aquisicao_gerada = date('Y-m-d', $timestamp_aquisicao);


// Determina o Estado de Conservação com base na Mídia selecionada (física ou digital)
$estado_conservacao_gerado = null; // Inicializa como nulo

if ($midia_gerada !== "Digital") {
    // Se a mídia NÃO for Digital (ou seja, for física), gera aleatoriamente entre Novo e Usado
    $opcoes_estado = array("Novo", "Usado");
    $estado_conservacao_gerado = $opcoes_estado[array_rand($opcoes_estado)];
}
// Se a mídia for Digital, EstadoConservacao permanece nulo


// ================================================================
// Preparar e Executar a Query SQL de Inserção
// ================================================================

// Define as colunas e valores para a query SQL
$colunas = array("Titulo", "Plataforma", "Midia", "Regiao", "EstadoConservacao", "DataAquisicao");
$valores = array("'$titulo_gerado'", "'$plataforma_gerada'", "'$midia_gerada'", "'$regiao_gerada'");

// Adiciona o valor de EstadoConservacao, tratando NULL para SQL
if ($estado_conservacao_gerado === null) {
    $valores[] = "NULL"; // Insere NULL diretamente no SQL (sem aspas)
} else {
    $valores[] = "'" . $link->real_escape_string($estado_conservacao_gerado) . "'"; // Insere o valor entre aspas, escapando caracteres especiais
}

// Adiciona o valor de DataAquisicao (sempre incluído)
$valores[] = "'" . $link->real_escape_string($data_aquisicao_gerada) . "'"; // Insere a data entre aspas, escapando

// Junta as colunas e valores em strings formatadas para a query SQL
$colunas_sql = implode(", ", $colunas);
$valores_sql = implode(", ", $valores);

// Monta a query INSERT final
$query = "INSERT INTO Jogos ($colunas_sql) VALUES ($valores_sql)";


// Executa a query de inserção no banco de dados
if ($link->query($query) === TRUE) {
  // Se a inserção for bem-sucedida, exibe os dados inseridos
  echo "Novo registro criado com sucesso na tabela Jogos:<br>";
  echo "Titulo: " . $titulo_gerado . "<br>";
  echo "Plataforma: " . $plataforma_gerada . "<br>";
  echo "Midia: " . $midia_gerada . "<br>";
  echo "Regiao: " . $regiao_gerada . "<br>";
  // Exibe o Estado de Conservação (mostrando N/A para Mídia Digital)
  if ($estado_conservacao_gerado !== null) {
      echo "Estado de Conservacao: " . $estado_conservacao_gerado . "<br>";
  } else {
      echo "Estado de Conservacao: N/A (Mídia Digital)<br>";
  }
  echo "Data de Aquisição: " . $data_aquisicao_gerada . "<br>";
} else {
  // Se ocorrer um erro na inserção, exibe a mensagem de erro do MySQL
  echo "Erro: " . $link->error;
}

// A conexão com o banco de dados é fechada automaticamente ao final do script em PHP

?>
</body>
</html>
