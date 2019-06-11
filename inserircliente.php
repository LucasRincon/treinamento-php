<!DOCTYPE html>
<html>
    <head>
        <link rel="import" href="imports.html">
        <title>Cadastro de Cliente</title>
    </head>
    <body>
        <div class="container">
            <form method="post" action="inserircliente.php">
                <div class="form-group">
                    <label for="idcliente"> Identificador do Cliente</label>
                    <input type="number" class="form-control" id="idcliente" placeholder="Insira ID do Cliente" name="idcliente" required>
                </div>
                <div class="form-group">
                    <label for="nomecliente">Nome Completo</label>
                    <input type="text" class="form-control" id="nomecliente" placeholder="Insira Nome Completo" name="nomecliente" required>
                </div>
                <div class="form-group">
                    <label for="enderecocliente">Endereço</label>
                    <input type="text" class="form-control" id="enderecocliente" placeholder="Insira Nome da Rua, Número" name="enderecocliente" required>
                </div>
                <div class="form-group">
                    <label for="cidadecliente">Cidade do Cliente</label>
                    <input type="text" class="form-control" id="cidadecliente" placeholder="Insira Cidade do Cliente" name="cidadecliente" required>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>
        </div>
        <?php
            error_reporting(E_ALL);
            if (isset($_POST["idcliente"]) && isset($_POST["nomecliente"]) && isset($_POST["enderecocliente"]) && isset($_POST["cidadecliente"])){

                $idcliente = $_POST["idcliente"];
                $nomecliente = $_POST["nomecliente"];
                $enderecocliente = $_POST["enderecocliente"];
                $cidadecliente = $_POST["cidadecliente"];

                $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                $consulta = "SELECT COUNT(*) FROM bank.clientes WHERE id=$idcliente";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");
                $result=pg_result($exec, 0, 0);

                if($result>0){
                    echo "<script>alert('O ID inserido já está em uso');
                                  window.onload = function(){
                                  document.getElementById('idcliente').value=".$idcliente.";
                                  document.getElementById('nomecliente').value='".$nomecliente."';
                                  document.getElementById('enderecocliente').value='".$enderecocliente."';
                                  document.getElementById('cidadecliente').value='".$cidadecliente."';
                                  }
                          </script>";
                    $result=0;
                }
                else {
                    $consulta="INSERT INTO bank.clientes (id, nome, endereco, cidade) VALUES ($idcliente, '{$nomecliente}', '{$enderecocliente}', '{$cidadecliente}')";
                    $exec=pg_query($open,$consulta) or die("Impossível executar query");
                    echo "<script>location.href = 'confirmacao.php';</script>";
                }
            }
        ?>
    </body>
</html>