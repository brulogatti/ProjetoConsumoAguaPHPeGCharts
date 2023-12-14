<div class="main-content">
    <div class="login-box">
        <div class="login-window">
            <form action="./cadastro.php" method="post">
                <div class="login-title">Cadastro</div>
                <div class="mui-textfield mui-textfield--float-label">
                    <label>Nome: </label>
                    <input type="text" name="nome" id="nome" required>
                </div>
                <div class="mui-textfield mui-textfield--float-label">
                    <label>E-mail: </label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="mui-textfield mui-textfield--float-label">
                    <label>Senha: </label>
                    <input type="password" name="senha" id="senha" required>
                </div>
                <div class="mui-textfield mui-textfield--float-label">
                    <label>Confirmação de Senha:</label>
                    <input type="password" name="confirm" id="confirm" required>
                </div>
                <input type="submit" value="Cadastrar" class="mui-btn">
            </form>
            <div>
                <a class="mui-btn" href="login.php">Login</a>
            </div>
        </div>
    </div>
</div>