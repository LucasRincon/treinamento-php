<!DOCTYPE html>
<html>
    <head>
        <link rel="import" href="imports.html">
        <title>Alterar Cliente</title>
    </head>
    <body>
        <div class="container" style="display: flex; justify-content: center; margin-top: 15px">
            <form method="post" action="consultarcliente.php">
                <select id="selecionado">
                    <option>Seleciona o tipo de Consulta</option>
                    <option>Buscar por Nome</option>
                    <option>Buscar por ID</option>
                </select>
                <div class="form-group" id="divid" style="margin: 20px 0px -10px 0px">
                    <label for="idcliente">Identificador do Cliente</label>
                    <input type="number" class="form-control" id="idcliente" name="idcliente" placeholder="Insira ID do Cliente">
                </div>
                <div class="form-group" id="divnome" style="margin: 20px 0px -10px 0px">
                    <label for="nomecliente">Nome do Cliente</label>
                    <input type="text" class="form-control" id="nomecliente" name="nomecliente" placeholder="Insira Nome do Cliente">
                </div><br>
                <button type="submit" class="btn btn-primary col-12">Consultar Cliente</button>
            </form>
        </div>
        <?php
        if (isset($_POST["idcliente"]) && isset($_POST["nomecliente"])){

            $idcliente = $_POST["idcliente"];
            $nomecliente = $_POST["nomecliente"];

            if($idcliente != ''){

                $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                $consulta = "SELECT * FROM bank.clientes WHERE id=$idcliente";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");

                $result = pg_fetch_assoc($exec);

                $consulta = "SELECT * FROM bank.contasclientes WHERE idclientes=$idcliente";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");

                echo   "<div class='container'>
                        <table class='table table-striped table-selectable'><tbody><tr class=''>
                        <th>id</th>
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        <th>Contas</th>
                        </tr><br>
                        <tr>
                        <td>".$result["id"]."</td>
                        <td>".$result["nome"]."</td>
                        <td>".$result["endereco"]."</td>
                        <td>".$result["cidade"]."</td>
                        <td>";
  
                  while($result2 = pg_fetch_assoc($exec)){
                       echo "<a href='consultarconta.php?idcontas=".$result2['idcontas']."' target='janela'> ".$result2['idcontas']." </a>";
                  }
  
                  echo "</td>
                        </tr>
                        </tbody>
                        </table>
                        </div>";

                
            }
            elseif ($nomecliente != '') {
         
                $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                $consulta = "SELECT * FROM bank.clientes WHERE nome ILIKE '%$nomecliente%'";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");


                echo   "<div class='container'>
                        <table class='table table-striped'><tbody><tr>
                        <th>id</th>
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        <td>Contas</th>
                        </tr><br>";
                $i = 0;
                while($result = pg_fetch_assoc($exec)){
                    $i++;
                    $idcliente = $result['id'];

                    $consulta = "SELECT * FROM bank.contasclientes WHERE idclientes=$idcliente";
                    $exec2=pg_query($open,$consulta) or die("Impossível executar query");

                    echo "<tr>
                          <td>".$result["id"]."</td>
                          <td>".$result["nome"]."</td>
                          <td>".$result["endereco"]."</td>
                          <td>".$result["cidade"]."</td>
                          <td>";
                    while($result2 = pg_fetch_assoc($exec2)){
                        echo "<a href='consultarconta.php?idcontas=".$result2['idcontas']."' target='janela'> ".$result2['idcontas']." </a>";
                    }

                    echo "</td>
                          </tr>";
                }
                echo "</tbody>
                      </table>
                      </div>";
                }
            else echo "<script>alert('Selecione o modo de busca e insira o valor correspondente.');</script>";
        }
        ?>
        <script>
        $(document).ready(function(){
                $('#divid').hide(); 
                $('#divnome').hide();
                $('#selecionado').change(function(){
                    if($('#selecionado').val()=='Buscar por ID'){
                        $('#divid').show();
                        $('#divnome').hide();
                        $('#nomecliente').val('');
                    }
                    else if ($('#selecionado').val()=='Buscar por Nome'){
                        $('#divid').hide();
                        $('#divnome').show();
                        $('#idcliente').val('');
                    }
                    else {
                        $('#divid').hide(); 
                        $('#divnome').hide();
                    }
                });

                $('#consultarconta').click(function(){

                });
            });
        </script>
    </body>
</html>