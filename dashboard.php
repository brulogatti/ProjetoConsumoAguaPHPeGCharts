<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel: Monitoramento uso da água</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/painel.css">
</head>

<body>
    <?php
    if (isset($_SESSION["email"])) {
        echo '<p style="color: white;">Sessão aberta, seja bem-vindo(a) ' . $_SESSION["email"] .'</p>';
    ?>
        <div class="atividade">
            <p><a href="registroAtividade.php">Registrar nova atividade</a></p>
        </div>
        <div class="main">
            <div class="content">
                <div class="tabelas">
                    <?php
                    include "inc/tabela_consumo.inc";
                    ?>
                </div>
            </div>
            <div class="graficos">
                <?php
                $year = date("Y");
                $month = date("m");
                if (isset($_POST["dataAno"])) {
                    $year = $_POST["dataAno"];
                }
                if(isset($_POST["dataMes"])){
                    $month=$_POST["dataMes"];
                }
                require_once "funcoes.php";
                $nomeArquivoAnual = "consumoAnual.json";
                $nomeArquivoMensal = "consumoMensal.json";
                $nomeArquivoTotal = "consumoTotal.json";
                file_put_contents($nomeArquivoAnual, "");
                file_put_contents($nomeArquivoMensal, "");
                file_put_contents($nomeArquivoTotal, "");
                gerarGrafico(1, $year);
                gerarGrafico(2,$month);
                gerarGrafico(3,"total");
                desenharGrafico($nomeArquivoAnual, "Consumo Anual - $year");
                ?>
                <form action="dashboard.php" method="post">
                    <label>Ano:</label>
                    <input type="number" name="dataAno" id="dataAno" value="<?=$year?>" required>
                    <input type="submit" value="Gerar Ano">
                </form>
                <?php
                desenharGrafico($nomeArquivoMensal, "Consumo Mensal - $month");
                ?>
                <form action="dashboard.php" method="post">
                    <label>Mês:</label>
                    <input type="number" name="dataMes" id="dataMes" value="<?=$month?>" required>
                    <input type="submit" value="Gerar Mês">
                </form>
                <?php
                desenharGrafico($nomeArquivoTotal, "Consumo Total");
                ?>
            </div>
        </div>
        <p>
            <a href="finalizarSessao.php">Finalizar Sessão</a>
        </p>
    <?php
    } else {
        echo "<h2>Faça seu login ou cadastre-se!</h2>";
        echo "<p><a href='login.php'>Login</a></p>";
        echo "<p><a href='cadastro.php'>Cadastro</a></p>";
    }
    ?>
</body>

</html>