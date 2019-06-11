<!DOCTYPE html>
<html>
    <head>
        <link rel="import" href="imports.html">
        <title>Cadastro de Contas</title>
    </head>
    <body>
    <?php 
    if (!isset($_POST['vinculados']) && !isset($_POST['idconta2'])){
    ?>
        <div class="container" id="formconta">
            <form method="post" action="criarconta.php">
                <div class="form-group">
                    <label for="idconta">Identificador da Conta</label>
                    <input type="number" class="form-control" id="idconta" name="idconta" placeholder="Insira ID da Nova Conta" required>
                </div>
                <div class="form-group">
                    <label for="cidadeconta">Cidade da Conta</label>
                    <input type="text" class="form-control" id="cidadeconta" name="cidadeconta" placeholder="Insira Cidade da Conta" required>
                </div>
                <div class="form-group">
                    <label for="saldo">Saldo da Conta</label>
                    <input type="text" class="form-control" id="saldo" pattern="\d+[,]\d{1,2}" name="saldo" placeholder="Insira Saldo da Conta (Ex.: xxx,xx)" required>
                </div>
                <div class="form-group">
                    <p>Selecione a agência:</p>
                    <input type="radio" name="agencia" value="1010"> 1010<br>
                    <input type="radio" name="agencia" value="2020"> 2020<br>
                    <input type="radio" name="agencia" value="3030"> 3030<br>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar Conta</button>
            </form>
        </div>
        <div class="container" id="confirmacaoconta" style="display: none">
            <p class="alert alert-success" role="alert" style="text-align: center; margin-top: 20%;">Dados cadastrados com sucesso!</p>
            <div style="display: flex; justify-content: center;">
                <button type="submit" class="btn btn-success" onclick="location.href = 'paginainicial.php'">Página Inicial</button>
            </div>
            <div style="display: flex; justify-content: center; margin-top: 10px;">
                <button class="btn btn-success" id="mostralista">Vincular Clientes</button>
            </div>
        </div>
        <?php
    }
            error_reporting(E_ALL);
            if (isset($_POST["idconta"]) && isset($_POST["cidadeconta"]) && isset($_POST["saldo"]) && isset($_POST["agencia"])){

                $idconta = $_POST["idconta"];
                $cidadeconta = $_POST["cidadeconta"];
                $saldo = $_POST["saldo"];
                $agencia = $_POST["agencia"];

                $saldo = str_replace(',', '.', $saldo);

                $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                $consulta = "INSERT INTO bank.contas (idconta, cidadeconta, saldo, agencia) VALUES ($idconta, '$cidadeconta', $saldo, $agencia)";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");
                echo "<script>$('#formconta').hide();
                              $('#confirmacaoconta').show();
                      </script>";

                $consulta = "SELECT * FROM bank.clientes";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");

                echo   "<div class='container' id='listaclientes' style='display: none'>
                        <form method='post' id='formseleciona' action='criarconta.php'>
                        <table class='table table-striped table-selectable-multiple'><tbody><tr class=''>
                        <th></th>
                        <th>id</th>
                        <th>Nome</th>
                        </tr><br>";
                $i = 0;
                while($result = pg_fetch_assoc($exec)){
                    $i++;
                    echo "<tr class=''>
                          <td><input type='checkbox' name='linha$i' value='".$result['id']."'/></td>
                          <td>".$result["id"]."</td>
                          <td>".$result["nome"]."</td>
                          </tr>";
                }
                echo "</tbody>
                      </table>
                      <input type='hidden' name='vinculados' id='vinculados'>
                      <input type='hidden' name='idconta2' id='idconta2' value='$idconta'>
                      <div class='btn btn-primary' id='vincular'>Vincular</div>
                      </form>
                      </div>";
            }
            else if (isset($_POST['vinculados']) && isset($_POST['idconta2'])){

                $vinculados = $_POST['vinculados'];
                $conta = $_POST['idconta2'];

                $vinculados = explode('@', $vinculados);

                $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                foreach ($vinculados as $key) {
                    if($key != ''){
                    $consulta = "INSERT INTO bank.contasclientes (idclientes, idcontas) VALUES ($key, $conta)";
                    $exec=pg_query($open,$consulta) or die("Impossível executar query");
                    }
                }
                echo "<script>location.href = 'confirmacao.php';</script>";
            }
        ?>
        <script>
        $(document).ready(function(){
            $('#mostralista').click(function(){
                $('#confirmacaoconta').hide();
                $('#listaclientes').show();
            });

            $(".table-selectable-multiple tr").click(function(e) {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass("selected");
                    $(this).find("input[type='checkbox']").prop('checked', false);
                }
                else {
                    $(this).addClass("selected");
                    $(this).find("input[type='checkbox']").prop('checked', true);
                }
            });
            var idlinha = '';
            $('#vincular').click(function(){
                $("input[name*='linha']").each(function(){
                    var selecionado = $(this).prop('checked'); 
                    if(selecionado){
                        idlinha += $(this).val()+'@';
                    }
                });
                $('#vinculados').val(idlinha);
                $('#formseleciona').submit();
            });
        });
        </script>
    </body>
</html>