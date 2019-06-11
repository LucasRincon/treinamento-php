<!DOCTYPE html>
<html>
    <head>
        <link rel="import" href="imports.html">
        <title>Aplicação PHP + Banco de Dados</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">

            <!-- Logo -->
            <a class="navbar-brand">
                <img src="cagepa1.png" alt="logo" style="width:40px;">
            </a>
  
            <!-- Navegação -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="paginainicial.php" target="janela">Página Inicial</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="inserircliente.php" target="janela">Inserir Cliente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="alterarcliente.php" target="janela">Alterar cliente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="consultarcliente.php" target="janela">Consultar cliente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="criarconta.php" target="janela">Criar nova conta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="consultarconta.php" target="janela">Consultar conta</a>
                </li>
            </ul>
        </nav>
        <iframe name="janela" style="width: 100%; height: 91vh; border: none" src="paginainicial.php"></iframe>
    </body>
</html>