<!DOCTYPE html>
<html>
    <head>
        <link rel="import" href="imports.html">
        <title>Alterar Cliente</title>
    </head>
    <body>
        <div class="container" style="display: flex; justify-content: center; margin-top: 15px">
        <form method="post" action="alterarcliente.php">
            <select id="selecionado">
                <option>Seleciona o tipo de busca</option>
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
            <button type="submit" class="btn btn-primary col-12" id="buscar">Pesquisar Clientes</button>
        </form>
        </div>
        <div class="container" id="formaltera" style="display: none">
            <form method="post" action="alterarcliente.php">
                <div class="form-group">
                    <label for="idalterado"> Identificador do Cliente</label>
                    <input type="number" class="form-control" readonly id="idalterado" placeholder="Insira ID do Cliente" name="idalterado" required>
                </div>
                <div class="form-group">
                    <label for="nomealterado">Nome Completo</label>
                    <input type="text" class="form-control" id="nomealterado" placeholder="Insira Nome Completo" name="nomealterado" required>
                </div>
                <div class="form-group">
                    <label for="enderecoalterado">Endereço</label>
                    <input type="text" class="form-control" id="enderecoalterado" placeholder="Insira Nome da Rua, Número" name="enderecoalterado" required>
                </div>
                <div class="form-group">
                    <label for="cidadealterado">Cidade do Cliente</label>
                    <input type="text" class="form-control" id="cidadealterado" placeholder="Insira Cidade do Cliente" name="cidadealterado" required>
                </div>
                <button type="submit" class="btn btn-primary">Alterar</button>
            </form>
        </div>
        <?php
            if (isset($_GET['idlinha']) && $_GET['idlinha'] != ''){

                $idlinha = $_GET['idlinha'];

                echo "<script>
                            $('#idcliente').val(".$idlinha.");
                            $('#buscar').click();
                      </script>";
            }

            if (isset($_POST["idcliente"]) && isset($_POST["nomecliente"])){

                $idcliente = $_POST["idcliente"];
                $nomecliente = $_POST["nomecliente"];

                if($idcliente != ''){

                    echo "<script>$('#formaltera').show();</script>";

                    $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                    $consulta = "SELECT * FROM bank.clientes WHERE id=$idcliente";
                    $exec=pg_query($open,$consulta) or die("Impossível executar query");

                    $result = pg_fetch_assoc($exec);
                        echo   "<script>
                                window.onload = function(){
                                    document.getElementById('idalterado').value='$idcliente';
                                    document.getElementById('nomealterado').value='".$result['nome']."';
                                    document.getElementById('enderecoalterado').value='".$result['endereco']."';
                                    document.getElementById('cidadealterado').value='".$result['cidade']."';
                                }
                            </script>";
            }
                elseif ($nomecliente != '') {
         
                $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                $consulta = "SELECT * FROM bank.clientes WHERE nome ILIKE '%$nomecliente%'";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");


                echo   "<div class='container'>
                        <form method='get' id='formseleciona'>
                        <table class='table table-striped table-selectable'><tbody><tr class=''>
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
                      <div class='btn btn-primary' id='selecionar'>Alterar</div>
                      </form>
                      </div>";
                }
                else echo "alert('Selecione o modo de busca e insira o valor correspondente.');";
            }

            if (isset($_POST["idalterado"]) && isset($_POST["nomealterado"]) && isset($_POST["enderecoalterado"]) && isset($_POST["cidadealterado"])){

                $idcliente = $_POST["idalterado"];
                $nomealterado = $_POST["nomealterado"];
                $enderecoalterado = $_POST["enderecoalterado"];
                $cidadealterado = $_POST["cidadealterado"];

                $open = pg_connect("host=localhost port=5432 dbname=bancocagepa user=lucas password=lucas") or die("Falha ao abrir conexão");

                $consulta = "UPDATE bank.clientes 
                             SET nome='$nomealterado', endereco='$enderecoalterado', cidade='$cidadealterado' 
                             WHERE id=$idcliente";
                $exec=pg_query($open,$consulta) or die("Impossível executar query");
                echo "<script>location.href = 'confirmacao.php';</script>";
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
                        $('#formseleciona').hide();
                    }
                    else if ($('#selecionado').val()=='Buscar por Nome'){
                        $('#divid').hide();
                        $('#divnome').show();
                        $('#idcliente').val('');
                        $('#formaltera').hide();
                    }
                    else {
                        $('#divid').hide(); 
                        $('#divnome').hide();
                    }
                });

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
                           $('#buscar').click();
                       }
                    });
                });
            });
        </script>
    </body>
</html>