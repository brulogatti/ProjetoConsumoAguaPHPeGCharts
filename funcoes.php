<?php
function cadastrarUsuario()
{
    $codigo = obterCodigo();

    //Verificar se a senha é igual a confirmação de senha
    if (confirmarSenha()) {

        $usuario = array(
            "nome" => $_POST["nome"],
            "email" => $_POST["email"],
            "senha" => $_POST["senha"],
            "codigo" => $codigo
        );

        if (naoExisteUsuario($_POST["email"])) {

            if (file_exists("usuarios.json")) {
                $dados = file_get_contents("usuarios.json");
                $dados = json_decode($dados, true);
            }
            $dados[] = $usuario;
            $dados = json_encode($dados, JSON_PRETTY_PRINT);
            file_put_contents("usuarios.json", $dados);

            atualizarCodigo($codigo);
            echo "<h2>Usuário cadastrado com sucesso!</h2>";
            echo "<p><a href='login.php'>Ir para login</a></p>";
        } else {
            echo "<h2>Usuário já existente!</h2>";
            echo "<p><a href='cadastro.php'>Cadastrar outro usuário</a></p>";
        }
    }
}

function confirmarSenha()
{
    $result = false;
    if ($_POST["senha"] == $_POST["confirm"]) {
        $result = true;
    } else {
        echo "<h2>Confirmação de senha incorreta!</h2>";
        echo "<p><a href='cadastro.php'>Voltar</a></p>";
    }
    return $result;
}

function obterCodigo()
{
    if (file_exists("codigo_usuarios.json")) {
        $codigo = file_get_contents("codigo_usuarios.json");
        $codigo = json_decode($codigo);
    } else {
        $codigo = 1;
    }
    return $codigo;
}

function atualizarCodigo($codigo)
{
    $codigo = $codigo + 1;
    $codigo = json_encode($codigo);
    file_put_contents("codigo_usuarios.json", $codigo);
}

function autenticarUsuario()
{
    if (isset($_POST["email"]) && isset($_POST["senha"])) {
        $email = $_POST["email"];
        $senha = $_POST["senha"];

        $dados = file_get_contents("usuarios.json");
        $dados = json_decode($dados, true);

        foreach ($dados as $indice => $user) {
            if ($user["email"] == $email && $user["senha"] == $senha) {
                $_SESSION["email"] = $email;
                $_SESSION["senha"] = $senha;
                $_SESSION["codigo"] = $user["codigo"];
                header("Location: dashboard.php");
                //Caso não esteja funcionando o header, utilize o código abaixo:
                //echo "<p><a href='dashboard.php'>Painel de controle</a></p>";
            }
        }

        if (!isset($_SESSION["email"]) && !isset($_SESSION["senha"])) {
            echo "<h2>Login ou senha incorretos!</h2>";
            echo "<p><a href='login.php'>Tentar novamente</a></p>";
        }
    }
}

function registrarAtividade()
{
    $atividade = array(
        "data" => $_POST["data"],
        "qtd" => $_POST["qtd"],
        "atividade" => $_POST["atividade"]
    );

    $atividadeJson = "atividades/atividades_user" . $_SESSION["codigo"] . ".json";

    if (file_exists($atividadeJson)) {
        $dados = file_get_contents($atividadeJson);
        $dados = json_decode($dados, true);
    }
    $dados[] = $atividade;
    $dados = json_encode($dados, JSON_PRETTY_PRINT);
    file_put_contents($atividadeJson, $dados);

    echo "<h2>Atividade salva com sucesso!</h2>";
    echo "<p><a href='registroAtividade.php'>Registrar nova atividade</a></p>";
    echo "<p><a href='dashboard.php'>Painel</a></p>";
}

function listarAtividades()
{
    $atividadeJson = "atividades/atividades_user" . $_SESSION["codigo"] . ".json";

    if (file_exists($atividadeJson)) {
        $dados = file_get_contents($atividadeJson);
        $dados = json_decode($dados, true);

        foreach ($dados as $indice => $atividade) {
            echo "<tr>
                <td>" . $atividade["data"] . "</td>
                <td>" . $atividade["qtd"] . "</td>
                <td>" . $atividade["atividade"] . "</td>
            </tr>";
        }
    }
}

function graficoAnual($valor)
{
    $usuario = $_SESSION["codigo"];
    $arquivo = "atividades/atividades_user$usuario.json";
    if (file_exists($arquivo)) {
        $consumo = file_get_contents($arquivo);
        $consumo = json_decode($consumo, true);

        for ($i = 1; $i < 13; $i++) {
            $soma[$i] = 0;
        }

        foreach ($consumo as $indice => $gasto) {
            $data = explode("-", $gasto["data"]);
            $mes = intval($data[1]);
            //2023-09-12
            //$data[0] = 2023; -> ano
            //$data[1]=09; -> mes
            if ($data[0] == $valor) {
                $soma[$mes] = $soma[$mes] + $gasto["qtd"];
            }
        }

        foreach ($soma as $indice => $mes) {
            if (file_exists("consumoAnual.json")) {
                $dados = file_get_contents("consumoAnual.json");
                $dados = json_decode($dados, true);
            }
            $graficoJson = array(
                "label" => "Mes $indice",
                "valor" => $mes
            );
            $dados[] = $graficoJson;
            $dados = json_encode($dados, JSON_PRETTY_PRINT);
            file_put_contents("consumoAnual.json", $dados);
        }
    }
}

function graficoMensal($valor)
{
    $usuario = $_SESSION["codigo"];
    $arquivo = "atividades/atividades_user$usuario.json";
    if (file_exists($arquivo)) {
        $consumo = file_get_contents($arquivo);
        $consumo = json_decode($consumo, true);

        if ($valor == 4 || $valor == 6 || $valor == 9 || $valor == 11) {
            $dias = 30;
        } elseif ($valor == 2) {
            $dias = 28;
        } else {
            $dias = 31;
        }

        for ($i = 1; $i <= $dias; $i++) {
            $soma[$i] = 0;
        }

        foreach ($consumo as $indice => $gasto) {
            $data = explode("-", $gasto["data"]);
            $dia = intval($data[2]);
            $mes = intval($data[1]);
            //2023-09-12
            //$data[0] = 2023; -> ano
            //$data[1]=09; -> mes
            if ($data[1] == $valor) {
                $soma[$dia] = $soma[$dia] + $gasto["qtd"];
            }
        }

        foreach ($soma as $indice => $dia) {
            if (file_exists("consumoMensal.json")) {
                $dados = file_get_contents("consumoMensal.json");
                $dados = json_decode($dados, true);
            }
            $graficoJson = array(
                "label" => "$indice-$valor",
                "valor" => $dia
            );
            $dados[] = $graficoJson;
            $dados = json_encode($dados, JSON_PRETTY_PRINT);
            file_put_contents("consumoMensal.json", $dados);
        }
    }
}

function graficoTotal()
{
    $usuario = $_SESSION["codigo"];
    $arquivo = "atividades/atividades_user$usuario.json";
    if (file_exists($arquivo)) {
        $consumo = file_get_contents($arquivo);
        $consumo = json_decode($consumo, true);

        $maior = 0;
        $menor = 1000000;

        foreach ($consumo as $dia) {
            $data = explode("-", $dia["data"]);
            if ($data[0] > $maior) {
                $maior = $data[0];
            }
            if ($data[0] < $menor) {
                $menor = $data[0];
            }
        }

        for ($i = $menor; $i < $maior + 1; $i++) {
            $soma["$i"] = 0;
        }

        foreach ($consumo as $indice => $gasto) {
            $data = explode("-", $gasto["data"]);
            $ano = intval($data[0]);
            $soma["$ano"] = $soma["$ano"] + $gasto["qtd"];
        }

        for ($i = $menor; $i < $maior + 1; $i++) {
            if (file_exists("consumoAnual.json")) {
                $dados = file_get_contents("consumoTotal.json");
                $dados = json_decode($dados, true);
            }
            $graficoJson = array(
                "label" => "$i",
                "valor" => $soma["$i"]
            );
            $dados[] = $graficoJson;
            $dados = json_encode($dados, JSON_PRETTY_PRINT);
            file_put_contents("consumoTotal.json", $dados);
        }
    }
}

function gerarGrafico($tempo, $valor)
{
    switch ($tempo) {
        case '1':
            #Anual
            graficoAnual($valor);
            break;
        case '2':
            #Mensal
            graficoMensal($valor);
            break;
        case '3':
            #Total
            graficoTotal();
            break;
        default:
            echo "<h3>Opção inexistente!</h3>";
            break;
    }
}

function desenharGrafico($arquivo, $tipoGrafico)
{
    if (str_contains($tipoGrafico, "Anual")) {
        $div = "anual";
    } elseif (str_contains($tipoGrafico, "Mensal")) {
        $div = "mensal";
    } else {
        $div = "total";
    }
    echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
    echo '<div id="chart_div_' . $div . '"></div>';
    $json_data = file_get_contents($arquivo);
    $data = json_decode($json_data);

    // Crie um array de dados no formato adequado para o Google Charts
    if ($data != null) {
        $chart_data = [['Label', 'Valor']];
        foreach ($data as $item) {
            $chart_data[] = [$item->label, $item->valor];
        }

        // Crie o gráfico de pizza usando o Google Charts
        echo '<script type="text/javascript">';
        echo 'google.charts.load("current", {"packages":["corechart"]});';
        echo 'google.charts.setOnLoadCallback(drawChart);';
        echo 'function drawChart() {';
        echo 'var data = google.visualization.arrayToDataTable(' . json_encode($chart_data) . ');';
        echo 'var options = {title: "' . $tipoGrafico . '"};';
        echo 'var chart = new google.visualization.PieChart(document.getElementById("chart_div_' . $div . '"));';
        echo 'chart.draw(data, options);';
        echo '}';
        echo '</script>';
    }
}

function naoExisteUsuario($usuario)
{
    $existe = true;
    if (file_exists("usuarios.json")) {
        $dados = file_get_contents("usuarios.json");
        $dados = json_decode($dados, true);
        foreach ($dados as $user) {
            if ($user["email"] == $usuario) {
                $existe = false;
            }
        }
    }

    return $existe;
}
