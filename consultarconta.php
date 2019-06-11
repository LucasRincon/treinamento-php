<!DOCTYPE html>
<html>
    <head>
        <link rel="import" href="imports.html">
        <title>Alterar Cliente</title>
    </head>
    <body>
        <div class="container">
            <form method="post" action="consultarconta.php">
                <div class="form-group">
                    <label for="idconta">Identificador da Conta</label>
                    <input type="number" class="form-control" name = "idconta" id="idconta" placeholder="Insira ID da Conta" required>
                </div>
                <button type="submit" class="btn btn-primary" id="consultar">Consultar Conta</button>
            </form>
        </div>
        <?php
            if (isset($_GET['idcontas']) && $_GET['idcontas'] != ''){

                $idcontas = $_GET['idcontas'];

                echo "<script>
                            $('#idconta').val(".$idcontas.");
                            $('#consultar').click();
                      </script>";
            }

            if (isset($_POST['idconta'])){

                $idconta = $_POST['idconta'];

                $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                $consulta = "SELECT * FROM bank.contas WHERE idconta=$idconta";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");

                $result = pg_fetch_assoc($exec);

                echo    "<div class='container'>
                        <h3 style='text-align: center; margin-bottom: -20px;'>Dados da Conta</h3>
                        <table class='table table-striped'>
                        <tbody>
                        <tr>
                        <th>Numero da Conta</th>
                        <th>Agência</th>
                        <th>Saldo</th>
                        </tr><br>
                        <tr>
                        <td>".$result["idconta"]."</td>
                        <td>".$result["agencia"]."</td>
                        <td>".$result["saldo"]."</td>
                        </tr>
                        </tbody>
                        </table>
                        </div>";

                $consulta = "SELECT * FROM bank.clientes a
                             INNER JOIN bank.contasclientes b
                             ON a.id = b.idclientes
                             WHERE b.idcontas=$idconta";
                $exec=pg_query($open, $consulta) or die("Impossível executar query");

                echo   "<div class='container'>
                        <form method='get' id='formseleciona'>
                        <h3 style='text-align: center; margin-bottom: -20px;'>Lista de Clientes Vinculados à Conta</h3>
                        <table class='table table-striped table-selectable'>
                        <tbody><tr class=''>
                        <th></th>
                        <th>id</th>
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        </tr><br>";
                $i = 0;
                while($result = pg_fetch_assoc($exec)){
                    $i++;
                    echo "<tr class=''>
                          <td><input type='checkbox' name='linha$i' value='".$result['id']."'/></td>
                          <td>".$result["id"]."</td>
                          <td>".$result["nome"]."</td>
                          <td>".$result["endereco"]."</td>
                          <td>".$result["cidade"]."</td>
                          </tr>";
                }
                echo "</tbody>
                      </table>
                      <div class='btn btn-primary' id='selecionar'>Editar Cliente</div>
                      </form>
                      </div>";
            }
        ?>
        <script>
            $(document).ready(function(){
                $(".table-selectable tr").click(function(e) {
                        var selecionar = !$(this).hasClass("selected");
                        $(this).parent().find("tr.selected").removeClass("selected");
                        $(this).parent().find("input[type='checkbox']").prop('checked', false);
                        if (selecionar) {
                            $(this).addClass("selected");
                            $(this).find("input[type='checkbox']").prop('checked', true);
                        }
                });
                $('#selecionar').click(function(){
                    $("input[name*='linha']").each(function(){
                       var selecionado = $(this).prop('checked'); 
                       if(selecionado){
                           var idlinha = $(this).val();
                           $('#idcliente').val(idlinha);
                           location.href=('alterarcliente.php?idlinha='+idlinha);
                       }
                    });
                });
            });
        </script>
    </body>
</html>