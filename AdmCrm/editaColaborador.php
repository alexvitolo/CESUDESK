<?php include '..\AdmCrm\connectionADM.php'; 
session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
 // Ação a ser executada: mata o script e manda uma mensagem
echo  '<script type="text/javascript"> window.location.href = "http://d42150:8080/login"  </script>'; }

if ($_SESSION['ACESSO'] <> 1 )  {
 // Ação a ser executada: mata o script e manda uma mensagem
 echo  '<script type="text/javascript"> window.location.href = "index.php"  </script>';
}

$ID_COLABORADOR = $_POST["ID_COLABORADOR"]; // id colaborador

$squiladica = "SELECT TC.ID_COLABORADOR,
                      TC.NOME,
                      TC.LOGIN_REDE,
                      TC.LOGIN_TELEFONIA,
                      TC.TELEFONE,
                      TC.DT_NASCIMENTO,
                      TC.DT_ADMISSAO,
                      TC.EMAIL,
                      TC.STATUS_COLABORADOR,
                      TC.ID_MATRICULA,
                      TC.ID_COLABORADOR_GESTOR,
                      TC.CODIGO_PORTAL,
                      TC.NIVEL_CARGO,
                      TC.ID_CARGO,
                      TC.ID_GRUPO,
                      TC.ID_HORARIO,
                      TC.DT_DESLIGAMENTO,
                      TC.ID_MOTIVO as MOTIVO_DESLIGAMENTO,
                      TC.ID_SUB_MOTIVO


                 FROM tb_crm_colaborador TC
                WHERE ID_COLABORADOR = '{$ID_COLABORADOR}' ";

$result_squila = sqlsrv_prepare($conn, $squiladica);
sqlsrv_execute($result_squila);

$vetorSQL = sqlsrv_fetch_array($result_squila);

$DT_NASCIMENTO = date_format($vetorSQL['DT_NASCIMENTO'], "Y-m-d");
$DT_ADMISSAO = date_format($vetorSQL['DT_ADMISSAO'], "Y-m-d");


$sqlSupervisores = "SELECT tc.ID_COLABORADOR AS ID_SUP
                      ,tc.NOME AS NOME_SUP
                  FROM tb_crm_colaborador tc
            INNER JOIN tb_crm_cargo ta ON ta.ID_CARGO = tc.ID_CARGO AND ta.BO_GESTOR = 'S'
              ORDER BY tc.NOME";

$result_supervisores = sqlsrv_prepare($conn, $sqlSupervisores);
sqlsrv_execute($result_supervisores);


$sqlHorarios = "SELECT th.ID_HORARIO
                      ,CONVERT(varchar,th.ENTRADA,108) AS ENTRADA
                      ,CONVERT(varchar,th.SAIDA,108) AS SAIDA
                      ,CONVERT(varchar,th.CARGA_HORARIO,108) AS CARGA_HORARIO
                      FROM tb_crm_horario th
                    WHERE th.BO_ESCALA_FDS = 'N'
                   ORDER BY th.CARGA_HORARIO
                       ,th.ENTRADA";

$result_Horario = sqlsrv_prepare($conn, $sqlHorarios);
sqlsrv_execute($result_Horario);

$sqlCargos = "SELECT tc.ID_CARGO
                    ,tc.DESCRICAO AS DESC_CARGO
                  FROM tb_crm_cargo tc
              ORDER BY tc.DESCRICAO";

$result_Cargo = sqlsrv_prepare($conn, $sqlCargos);
sqlsrv_execute($result_Cargo);

$sqlGrupo = "SELECT tg.ID_GRUPO
                   ,tg.DESCRICAO AS DESC_GRUPO
                   ,CASE
                    WHEN tr.DESCRICAO = 'Sem região' THEN 'Sem região' ELSE tr.DESCRICAO
                     END AS DESC_REGIAO
              FROM tb_crm_grupo tg
        INNER JOIN tb_crm_regiao tr on tg.ID_REGIAO = tr.ID_REGIAO";

$result_Grupo = sqlsrv_prepare($conn, $sqlGrupo);
sqlsrv_execute($result_Grupo);


$sqlPausa = "SELECT tp.ID_TIPO_PAUSA
                     ,tp.HORARIO_PAUSA
                     ,tp.DT_VIGENCIA_INICIAL
                     ,tp.DT_VIGENCIA_FINAL
                 FROM tb_crm_escala_pausa tp
                WHERE tp.ID_COLABORADOR = '{$ID_COLABORADOR}' 
                  AND tp.DT_VIGENCIA_FINAL IS NULL
             ORDER BY ID_TIPO_PAUSA";

$resultPausa = sqlsrv_prepare($conn, $sqlPausa);
sqlsrv_execute($resultPausa);


           $x=0;
          while ($dados = sqlsrv_fetch_array($resultPausa)) {
            $vetorPausa[$x][0] = $dados['ID_TIPO_PAUSA'];
            $vetorPausa[$x][1] = $dados['HORARIO_PAUSA'];
            $vetorPausa[$x][2] = $dados['DT_VIGENCIA_INICIAL'];
            $vetorPausa[$x][3] = $dados['DT_VIGENCIA_FINAL'];
            $x++;
          }



$sqlSubMotivo = "SELECT  td.ID_SUB_MOTIVO
                        ,td.SUB_MOTIVO 
              FROM tb_crm_desligamento_sub td";

$result_SubMotivo = sqlsrv_prepare($conn, $sqlSubMotivo);
sqlsrv_execute($result_SubMotivo);




$sqlMotivoP = "SELECT td.ID_MOTIVO
                      ,td.MOTIVO
                      ,td.DT_SISTEMA
                 FROM tb_crm_desligamento_motivo td";

$result_MotivoP = sqlsrv_prepare($conn, $sqlMotivoP);
sqlsrv_execute($result_MotivoP);


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Analytics EAD</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >
      <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
      <!--header start-->
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.php" class="logo"><b> GCO – Gestão de Controle Operacional </b></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- settings start -->
                    <!-- settings end -->
                    <!-- inbox dropdown start-->
                    <!-- inbox dropdown end -->
                </ul>
                <!--  notification end -->
            </div>
            <div class="top-menu">
              <ul class="nav pull-right top-menu">
                    <li><a class="logout" href="validaLogout.php">Logout</a></li>
              </ul>
            </div>
        </header>
      <!--header end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN SIDEBAR MENU
      *********************************************************************************************************************************************************** -->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
              
                  <p class="centered"><a href="profile.html"><img src="assets/img/ui-sam.gif" class="img-circle" width="60"></a></p>
                  <h5 class="centered">Analytics EAD</h5>
                    
                  <li class="sub-menu"">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-dashboard"></i>
                          <span>Head Count</span>
                      </a>
                      <ul class ="sub">
                          <li class=""><a  href="index.php">Resumo</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a class="" href="javascript:;">
                          <i class="fa fa-th"></i>
                          <span>Schedule</span>
                      </a>
                      <ul class="sub">
                          <li class=""><a  href="listaColaboradores.php">Lista Colaboradores</a></li>
                          <li class=""><a  href="escalaPausa.php"> Escala de pausa </a></li>
                          <li class=""><a  href="escalaFinalSemana.php"> Escala Final de Semana </a></li>
                           <li class=""><a  href="dadosGestores.php"> Dados Gestores </a></li>
                          <li class=""><a  href="cadastroColaborador.php"> Sugestão Novo Colaborador </a></li> 
                          <li class=""><a  href="formularioAvaliacao1.php"> Formulário Monitoria </a>
                          
                      </ul>
                  </li>

                  <?php if (($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2) ) { ?>
                      <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-signal"></i>
                          <span>Qualidade</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class=""><a  href="itensMonitoria.php">Itens Monitoria</a></li>
                      </ul>
                  </li>
                          
                      </ul>
                  </li>
   
                    <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>General</span>
                      </a>
                      <ul class="sub">
                           <li><a  href="listaHorarios.php">Lista Pausas</a></li>
                           <li class=""><a  href="dimensionamento.php">Dimensionamento</a></li>
                          <li class="active"><a  href="colaboradores.php">Colaboradores</a></li>
                          <li class=""><a  href="cargo.php">Cargo</a></li>
                          <li class=""><a  href="grupo.php">Grupo</a></li>
                          <li class=""><a  href="regiao.php">Região</a></li>
                          <li class=""><a  href="processo.php">Processo</a></li>
                          <li class=""><a  href="motivo.php">Motivo</a></li>
                          <li class=""><a  href="submotivo.php">Sub-Motivo</a></li>
                          
                      </ul>
                  </li>

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
            <h3><i class="fa fa-right"></i> Edição de colaboradores</h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                         <form name="Form" method="post" id="formulario" action="ValidaEditaColaborador.php">
<!-- DADOS PESSOAIS-->
                          <fieldset>
                          <legend> Dados do colaborador     <input type="checkbox" name="validaDadosColaborador" unchecked data-toggle="switch"  value="on"> </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                            <td style="width:110px";>
                             <label style="margin-left: 15px" for="nome">Matricula: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="MATRICULA" value="<?php echo $vetorSQL['ID_MATRICULA']; ?>">
                            </td>
                            <td>
                             <label style="margin-left: 15px" for="nome">Nome: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="NOME" size="35" value="<?php echo $vetorSQL['NOME']; ?>">
                            </td>
                             <td>
                             <label style="margin-left: 15px" for="sobrenome">Data Nascimento: </label>
                            </td>
                            <td align="left">
                             <input type="date" name="dtNascimento" value="<?php echo $DT_NASCIMENTO ?>">
                            </td> 
                           </tr>

                           <tr>
                            <td>
                             <label style="margin-left: 15px" >E-mail: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="email" size="40" value="<?php echo $vetorSQL['EMAIL']; ?>">
                            </td>
                            <td>
                             <label style="margin-left: 15px" >Código Portal: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="codPortal" size="10" value="<?php echo $vetorSQL['CODIGO_PORTAL']; ?>">
                            </td>
                           </tr>

                           <tr>
                           <td>
                           <br>
                             <label style="margin-left: 15px" >Telefone: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="telefone" size="25" value="<?php echo $vetorSQL['TELEFONE']; ?>">
                            </td>
                           </tr>

                           <tr>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" for="rg">Login Rede: </label>
                            </td>
                            <td align="left">
                             <input type="text" name="loginRede" size="25" value="<?php echo $vetorSQL['LOGIN_REDE']; ?>"> 
                            </td>
                             <td>
                             <label style="margin-left: 15px" >Login Telefonia:</label>
                            </td>
                            <td align="left">
                             <input type="text" name="loginTelefonia" size="30" value="<?php echo $vetorSQL['LOGIN_TELEFONIA']; ?>"> 
                            </td>
                            <td>
                             <label style="margin-left: 15px" >Data Admissão :</label>
                            </td>
                            <td align="left">
                             <input type="date" name="dtAdmissao" value="<?php echo $DT_ADMISSAO ; ?>"> 
                            </td>
                           </tr>

                           <tr>
                            <td style="width:120px";>
                             <label style="margin-left: 15px">Nome Supervisor:</label>
                            </td>
                            <td align="left">
                             <select name="supervisor">
                                            <option value="null">Escolha um supervisor</option>
                                           <?php while ($row = sqlsrv_fetch_array($result_supervisores)){ ?>
                                            <option <?php if ($row['ID_SUP'] == $vetorSQL['ID_COLABORADOR_GESTOR']) { echo 'selected'; } ?> value=<?php echo $row['ID_SUP']?> > <?php echo $row['NOME_SUP'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            </td>
                           </tr>

                            <tr>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" for="rg">Cargo: </label>
                            </td>
                            <td align="left">
                             <select name="cargo">
                                         <?php while ($row = sqlsrv_fetch_array($result_Cargo)){ ?>
                                            <option <?php if ($row['ID_CARGO']==$vetorSQL['ID_CARGO']) { echo 'selected'; } ?> value=<?php echo $row['ID_CARGO']?> > <?php echo $row['DESC_CARGO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            <td>
                             <label style="margin-left: 15px" for="status">Nível Cargo :</label>
                            </td>
                            <td align="left">
                             <select name="nivelCargo">
                                    <option value="<?php echo $vetorSQL['NIVEL_CARGO']; ?>"><?php if ($vetorSQL['NIVEL_CARGO'] == null) { echo "Sem Nível" ;} else {echo $vetorSQL['NIVEL_CARGO'] ;} ?></option> 
                                    <option value="I">I</option>
                                    <option value="II">II</option> 
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="null">Sem Nível</option>
                            </select>
                            </td>
                             <td>
                             <label style="margin-left: 15px" for="status">Status :</label>
                            </td>
                            <td align="left">
                             <select name="STATUS"> 
                                 <option value="<?php echo $vetorSQL['STATUS_COLABORADOR']; ?>" ><?php echo $vetorSQL['STATUS_COLABORADOR']; ?></option> 
                                 <option value="ATIVO">ATIVO</option>
                                 <option value="FERIAS">FERIAS</option> 
                                 <option value="DESLIGADO">DESLIGADO</option>
                                 <option value="INSS">INSS</option>
                                 <option value="PROMOVIDO">PROMOVIDO</option>    
                            </select>
                            </td>
                           </tr>

                            <tr>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" >Horário: </label>
                            </td>
                            <td align="left">
                             <select  name="horario">
                                         <?php while ($row = sqlsrv_fetch_array($result_Horario)){ ?>
                                            <option <?php if ($row['ID_HORARIO']==$vetorSQL['ID_HORARIO']) { echo 'selected'; } ?>  value=<?php echo $row['ID_HORARIO']?> > <?php echo 'ENTRADA: '.$row['ENTRADA'].'  |  Saída: '.$row['SAIDA'].'  |  Carga Horária: '.$row['CARGA_HORARIO']?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                            <td>
                            <br/>
                             <label style="margin-left: 15px" >Grupo: </label>
                            </td>
                            <td align="left">
                             <select  name="grupo">
                                         <?php while ($row = sqlsrv_fetch_array($result_Grupo)){ ?>
                                           <option <?php if ($row['ID_GRUPO']==$vetorSQL['ID_GRUPO']) { echo 'selected'; } ?>  value=<?php echo $row['ID_GRUPO']?> > <?php echo 'Grupo: '. $row['DESC_GRUPO'].'  |   '. $row['DESC_REGIAO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                           </tr>
                          </table>
                         </fieldset>
                        <br>
                        <table cellspacing="10" style="vertical-align: middle">
                          <span style="padding-left:45px"></span>
                          </tr>
                           <legend> Escala de pausas <input type="checkbox" name="validaEscalaPausa" unchecked data-toggle="switch" /> </legend>
                           
                           <tr>
                           <td>
                             <label style="margin-left: 15px" >Pausa 1: </label>
                            </td>
                            <td align="left">
                             <input type="time" name="pausa1" size="15" value="<?php echo date_format($vetorPausa[0][1],"H:i") ?>">
                            </td>

                            
                           <td>
                             <label style="margin-left: 15px" >Lanche: </label>
                            </td>
                            <td align="left">
                             <input type="time" name="lanche" size="15" value="<?php echo date_format($vetorPausa[2][1],"H:i") ?>">
                             <input type="hidden" name="ID_COLABORADOR" value="<?php echo $ID_COLABORADOR ?>">
                            </td>
                        
                           <td>
                             <label style="margin-left: 15px" >Pausa 2: </label>
                            </td>
                            <td align="left">
                             <input type="time" name="pausa2" size="15" value="<?php echo date_format($vetorPausa[1][1],"H:i") ?>">
                            </td>

                           <td>
                            <a href="listaHorariosTrocaOK.php?ID_GRUPO=<?php echo $vetorSQL['ID_GRUPO']; ?>&ID_HORARIO=<?php echo $vetorSQL['ID_HORARIO']; ?>" target="_blank"><input style="margin-left: 55px" type="button" value="Horários Disponíveis" ></input></a>
                            </td>
                           </tr>

                             <tr>
                           <td><br>                            
                           </tr>


                         </table>
                         <br/>


                          <table cellspacing="10" style="vertical-align: middle">
                          <span style="padding-left:45px"></span>
                          </tr>
                           <legend> Motivo Desligamento <input type="checkbox" name="validaMotivoDesliga" unchecked data-toggle="switch" /> </legend>
                           
                           <tr>

                            <td>
                             <label style="margin-left: 15px" >Data Desligamento :</label>
                            </td>
                            <td align="left">
                             <input type="date" name="dtDesligamento" value="<?php if (($vetorSQL['DT_DESLIGAMENTO']) == null ){ echo ' ';}else {echo date_format($vetorSQL['DT_DESLIGAMENTO'], "Y-m-d") ;} ?>"> 
                            </td>


                           <td>
                             <label style="margin-left: 20px" >Motivo Desligamento </label>
                            </td>
                            <td align="left">
                              <select name="motivoDesligamento"> 
                                            <?php while ($row = sqlsrv_fetch_array($result_MotivoP)){ ?>
                                            <option <?php if ($row['ID_MOTIVO']==$vetorSQL['MOTIVO_DESLIGAMENTO']) { echo 'selected'; } ?> value=<?php echo $row['ID_MOTIVO']?> > <?php echo $row['MOTIVO'] ?> </option>
                                         <?php }
                                         ?> 
                            </select>
                            </td>

                            <td>
                             <label style="margin-left: 15px" >Sub-Motivo Desligamento </label>
                            </td>
                            <td align="left">
                             <select style="margin-left: 15px"  name="subMotivoDesligamento">
                                         <?php while ($row = sqlsrv_fetch_array($result_SubMotivo)){ ?>
                                            <option <?php if ($row['ID_SUB_MOTIVO']==$vetorSQL['ID_SUB_MOTIVO']) { echo 'selected'; } ?> value=<?php echo $row['ID_SUB_MOTIVO']?> > <?php echo $row['SUB_MOTIVO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>


                             <tr>
                           <td><br>
                           </tr>


                         </table>


                         <br/>

                          <td><button class="button" onclick=" return getConfirmation();" type="submit" value="<?php echo $row['ID_MATRICULA']?>"  name="ID_MATRICULA">Confirmar</button> 
                         <a href="colaboradores.php"><input type="button" value="Cancelar"></a>
                      </form>
                      </div><!-- /content-panel -->
                  </div><!-- /col-md-12 -->
              </div><!-- /row -->

    </section>
      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              2017 - ANALYTICS EAD
              <a href="" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>


    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>

    <!--script for this page-->

  </body>
</html>




 
  <!--custom switch-->
  <script src="assets/js/bootstrap-switch.js"></script>
  
  <!--custom tagsinput-->
  <script src="assets/js/jquery.tagsinput.js"></script>
  

  <script src="assets/js/form-component.js"></script>   
    

<script type="text/javascript">
(function(document) {
  'use strict';

  var LightTableFilter = (function(Arr) {

    var _input;

    function _onInputEvent(e) {
      _input = e.target;
      var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
      Arr.forEach.call(tables, function(table) {
        Arr.forEach.call(table.tBodies, function(tbody) {
          Arr.forEach.call(tbody.rows, _filter);
        });
      });
    }

    function _filter(row) {
      var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
      row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
    }

    return {
      init: function() {
        var inputs = document.getElementsByClassName('light-table-filter');
        Arr.forEach.call(inputs, function(input) {
          input.oninput = _onInputEvent;
        });
      }
    };
  })(Array.prototype);

  document.addEventListener('readystatechange', function() {
    if (document.readyState === 'complete') {
      LightTableFilter.init();
    }
  });

   })(document);
        


    function getConfirmation(){
       // var retVal = confirm("Do you want to continue ?");
       if(  confirm(" Deseja confirmar a edição ? ") == true ){
          return true;
       }
       else{
          return false;
       }
    }
        



</script>