<div class="main-content">
    <div class="activity-box">
        <div class="activity-window">
            <form action="registroAtividade.php" method="post">
                    <div class="activity-title">Atividade</div>
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="date" name="data" id="data" required>
                    </div>
                    <div class="mui-textfield mui-textfield--float-label">
                        <label>Quantidade de água gasta (em litros):</label>
                        <input type="number" name="qtd" id="qtd" min="0.01" step="0.01" required>
                    </div>
                    <div class="mui-textfield mui-textfield--float-label">
                        <label>Tipo de atividade:</label>
                        <input type="text" name="atividade" id="atividade" required>
                    </div>
                    <input type="submit" value="Enviar"  class="mui-btn">
            </form>
            <div>
                <a class="mui-btn" href="dashboard.php">Voltar</a>
            </div>
        </div>
    </div>
</div>