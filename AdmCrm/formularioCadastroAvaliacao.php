<?php include '..\AdmCrm\connectionADM.php'; 

session_start();

if ( ! isset( $_SESSION['USUARIO'] ) && ! isset( $_SESSION['ACESSO'] ) ) {
    // Ação a ser executada: mata o script e manda uma mensagem
   echo  '<script type="text/javascript"> window.location.href = "http://d42150:8087/CESUDESK/NewCesudesk/index.php"  </script>'; 
}



$ID_MATRICULA_AVALIADOR = $_SESSION['MATRICULA'];

$ID_MATRICULA_CONSULTOR = $_POST['ID_MATRICULA_CONSULTOR'];  // será passado para proxima pág por hidden

$LIGACAO = $_POST['LIGACAO'];


  $sqlValida ="SELECT tc.ID_MATRICULA
                                ,tc.ID_COLABORADOR
                                ,tg.ID_GRUPO
                                ,tc.NOME
                                ,tg.DESCRICAO AS NOME_GRUPO
                                ,(SELECT NOME FROM tb_crm_colaborador WHERE tc.ID_COLABORADOR_GESTOR = ID_COLABORADOR ) NOME_GESTOR
                        FROM tb_crm_colaborador tc
                INNER JOIN tb_crm_grupo tg ON tg.ID_GRUPO = tc.ID_GRUPO 
                       WHERE ID_MATRICULA ='{$ID_MATRICULA_CONSULTOR}'
                         AND (tc.STATUS_COLABORADOR = 'ATIVO' OR tc.STATUS_COLABORADOR = 'FERIAS')";

          $stmtValida = sqlsrv_prepare($conn, $sqlValida);
          $resultValida = sqlsrv_execute($stmtValida);
          $resultadoSQL = sqlsrv_fetch_array($stmtValida);

          if ( $resultadoSQL == 0) {
              echo  '<script type="text/javascript">alert("Numero de Matricula não Existe");</script>';
              echo  '<script type="text/javascript"> window.location.href = "formularioAvaliacao.php" </script>';
              //header('location: PaginaIni.php');   não funciona
          }
$ID_CONSULTOR = $resultadoSQL['ID_COLABORADOR'];
$NOME_GESTOR =  $resultadoSQL['NOME_GESTOR'];
$NOME_CONSULTOR =  $resultadoSQL['NOME'];
$NOME_GRUPO =  $resultadoSQL['NOME_GRUPO'];
$ID_GRUPO = $resultadoSQL['ID_GRUPO'];



$squilaAvaliacao = "SELECT ta.NUMERO
                          ,ta.ID_AVALIACAO
                          ,ta.ID_CARGO
                          ,ta.BO_STATUS
                     FROM tb_qld_cronograma_avaliacao ta
               INNER JOIN tb_crm_colaborador tc ON tc.ID_CARGO = ta.ID_CARGO
                    WHERE tc.ID_COLABORADOR = '{$_SESSION['ID_COLABORADOR']}'
                      AND ta.BO_STATUS = 'S'
                      AND NOT EXISTS (SELECT 1 
                     FROM tb_qld_pesquisa tp
                     INNER JOIN tb_crm_processo tproc ON tproc.ID = tp.ID_PROCESSO AND tproc.ATIVO ='1'
                    WHERE tp.ID_AVALIACAO = ta.ID_AVALIACAO
                      AND tp.ID_COLABORADOR = '{$ID_CONSULTOR}') ";
                  
$result_squilaAvaliacao = sqlsrv_prepare($conn, $squilaAvaliacao);
sqlsrv_execute($result_squilaAvaliacao);




if ( ($LIGACAO == 'ATIVO') or ($LIGACAO == 'RECEPTIVO') ) {

    $squilaQuestao = "SELECT ID_QUESTAO
                               ,tc.ID_MATRICULA
                               ,tq.ID_QUESTAO
                               ,tq.DESCRICAO
                               ,tq.PESO
                               ,tq.DESC_OBSERVACAO
                               ,tq.BO_FALHA_CRITICA
                               ,tq.BO_PARCIAL
                               ,CASE WHEN tc.ID_GRUPO in (24,26,27,28,29,30) THEN 24 ELSE tc.ID_GRUPO END GRUPO_COLABORADOR
                          FROM tb_qld_questoes tq 
                    INNER JOIN tb_crm_colaborador tc ON CASE 
                                                        WHEN tc.ID_GRUPO IN (24,26,27,28,29,30) THEN 24 ELSE tc.ID_GRUPO END  = tq.ID_GRUPO
                         WHERE tq.BO_FALHA_CRITICA = 'N'
                           AND tq.BO_QUESTAO_ATIVA = 'S'
                           AND tc.ID_MATRICULA = '{$ID_MATRICULA_CONSULTOR}'
                           AND (tc.STATUS_COLABORADOR ='ATIVO' OR tc.STATUS_COLABORADOR = 'FERIAS') 
                           AND tq.TIPO_LIGACAO = '{$LIGACAO}' ";

    $result_squilaQuestao = sqlsrv_prepare($conn, $squilaQuestao);
    sqlsrv_execute($result_squilaQuestao);


    $squilaQuestaoCritico = "SELECT ID_QUESTAO
                           ,tc.ID_MATRICULA
                           ,tq.ID_QUESTAO
                           ,tq.DESCRICAO
                           ,tq.PESO
                           ,tq.DESC_OBSERVACAO
                           ,tq.BO_FALHA_CRITICA
                           ,tq.BO_PARCIAL
                           ,CASE WHEN tc.ID_GRUPO in (24,26,27,28,29,30) THEN 24 ELSE tc.ID_GRUPO END GRUPO_COLABORADOR
                      FROM tb_qld_questoes tq 
                INNER JOIN tb_crm_colaborador tc ON CASE 
                                                    WHEN tc.ID_GRUPO IN (24,26,27,28,29,30) THEN 24 ELSE tc.ID_GRUPO END  = tq.ID_GRUPO
                     WHERE BO_FALHA_CRITICA = 'S'
                       AND tc.ID_MATRICULA = '{$ID_MATRICULA_CONSULTOR}'
                       AND (tc.STATUS_COLABORADOR ='ATIVO' OR tc.STATUS_COLABORADOR = 'FERIAS') 
                       AND tq.TIPO_LIGACAO = '{$LIGACAO}'";

$result_squilaQuestaoCritico = sqlsrv_prepare($conn, $squilaQuestaoCritico);
sqlsrv_execute($result_squilaQuestaoCritico);


$squilaResultLigacao = "SELECT trs.ID_RESULT_LIG
                               ,tg.DESCRICAO
                               ,trs.DESCRICAO AS DESC_RESUL_LIGACAO
                          FROM tb_qld_resultado_ligacao trs
                    INNER JOIN tb_crm_grupo tg ON (CASE WHEN tg.ID_GRUPO IN (24,26,27,28,29,30) THEN 24 ELSE tg.ID_GRUPO END) = trs.ID_GRUPO
                    INNER JOIN tb_crm_colaborador tc ON tc.ID_GRUPO = tg.ID_GRUPO
                         WHERE tc.ID_MATRICULA = '{$ID_MATRICULA_CONSULTOR}'  ";

$result_squilaResultLigacao = sqlsrv_prepare($conn, $squilaResultLigacao);
sqlsrv_execute($result_squilaResultLigacao);





}

else{


    $squilaQuestao = "SELECT ID_QUESTAO
                               ,tc.ID_MATRICULA
                               ,tq.ID_QUESTAO
                               ,tq.DESCRICAO
                               ,tq.PESO
                               ,tq.DESC_OBSERVACAO
                               ,tq.BO_FALHA_CRITICA
                               ,tq.BO_PARCIAL
                               ,CASE WHEN tc.ID_GRUPO in (1,2,3,4,5,17) THEN 1 ELSE tc.ID_GRUPO END GRUPO_COLABORADOR
                          FROM tb_qld_questoes tq 
                    INNER JOIN tb_crm_colaborador tc ON CASE 
                                                        WHEN tc.ID_GRUPO IN (1,2,3,4,5,17) THEN 1 ELSE tc.ID_GRUPO END  = tq.ID_GRUPO
                         WHERE tq.BO_FALHA_CRITICA = 'N'
                           AND tq.BO_QUESTAO_ATIVA = 'S'
                           AND tc.ID_MATRICULA = '{$ID_MATRICULA_CONSULTOR}'
                           AND (tc.STATUS_COLABORADOR ='ATIVO' OR tc.STATUS_COLABORADOR = 'FERIAS') ";

    $result_squilaQuestao = sqlsrv_prepare($conn, $squilaQuestao);
    sqlsrv_execute($result_squilaQuestao);

    $squilaQuestaoCritico = "SELECT ID_QUESTAO
                           ,tc.ID_MATRICULA
                           ,tq.ID_QUESTAO
                           ,tq.DESCRICAO
                           ,tq.PESO
                           ,tq.DESC_OBSERVACAO
                           ,tq.BO_FALHA_CRITICA
                           ,tq.BO_PARCIAL
                           ,CASE WHEN tc.ID_GRUPO in (1,2,3,4,5,17) THEN 1 ELSE tc.ID_GRUPO END GRUPO_COLABORADOR
                      FROM tb_qld_questoes tq 
                INNER JOIN tb_crm_colaborador tc ON CASE 
                                                    WHEN tc.ID_GRUPO IN (1,2,3,4,5,17) THEN 1 ELSE tc.ID_GRUPO END  = tq.ID_GRUPO
                     WHERE BO_FALHA_CRITICA = 'S'
                       AND tc.ID_MATRICULA = '{$ID_MATRICULA_CONSULTOR}'
                       AND (tc.STATUS_COLABORADOR ='ATIVO' OR tc.STATUS_COLABORADOR = 'FERIAS') ";

$result_squilaQuestaoCritico = sqlsrv_prepare($conn, $squilaQuestaoCritico);
sqlsrv_execute($result_squilaQuestaoCritico);


$squilaResultLigacao = "SELECT trs.ID_RESULT_LIG
                               ,tg.DESCRICAO
                               ,trs.DESCRICAO AS DESC_RESUL_LIGACAO
                          FROM tb_qld_resultado_ligacao trs
                    INNER JOIN tb_crm_grupo tg ON (CASE WHEN tg.ID_GRUPO IN (1,2,3,4,5,17) THEN 1 ELSE tg.ID_GRUPO END) = trs.ID_GRUPO
                    INNER JOIN tb_crm_colaborador tc ON tc.ID_GRUPO = tg.ID_GRUPO
                         WHERE tc.ID_MATRICULA = '{$ID_MATRICULA_CONSULTOR}'  ";

$result_squilaResultLigacao = sqlsrv_prepare($conn, $squilaResultLigacao);
sqlsrv_execute($result_squilaResultLigacao);



}




$squilaObjetoTalisma = "SELECT ID_OBJETO_TALISMA
                              ,DESCRICAO
                              ,TABELA_TALISMA
                          FROM tb_qld_objeto_talisma";

$result_squilaObjetoTalisma = sqlsrv_prepare($conn, $squilaObjetoTalisma);
sqlsrv_execute($result_squilaObjetoTalisma);





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
    <link rel="shortcut icon" href="icone.ico" >
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
              
                  <p class="centered"><a href=""><img src="assets/img/ui-sam.gif" class="img-circle" width="60"></a></p>
                  <h5 class="centered">Analytics EAD</h5>
                    
                  <li class="sub-menu"">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-dashboard"></i>
                          <span>Home</span>
                      </a>
                      <ul class ="sub">
                          <li class=""><a  href="index.php">Resumo</a></li>
                          <li class=""><a  href="DashboardQualidade.php">Dasboard Qualidade</a></li>
                          <li class=""><a  href="DashboardDiscador.php">Dasboard Discador</a></li>
                          <li class=""><a  href="DashboardMicroGestao.php">Dasboard Micro Gestão</a></li>
                          <li class=""><a  href="DashboardTrocaHorario.php">Dasboard Troca Horário</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a class="active" href="javascript:;">
                          <i class="fa fa-th"></i>
                          <span>Schedule</span>
                      </a>
                      <ul class="sub">
                          <li class=""><a  href="listaColaboradores.php">Lista Colaboradores</a></li>
                          <li class=""><a  href="escalaPausa.php"> Escala de pausa </a></li>
                          <li class=""><a  href="escalaFinalSemana.php"> Escala Final de Semana </a></li>
                          <li class=""><a  href="dadosGestores.php"> Dados Gestores </a></li>
                          <li class=""><a  href="cadastroColaborador.php"> Sugestão Novo Colaborador </a></li> 
                          <li class="active"><a  href="formularioAvaliacao.php"> Formulário de Avaliação </a></li>
                          
                      </ul>
                  </li>

                   <?php if (($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2) ) { ?>
                      <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-signal"></i>
                          <span>Qualidade</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class=""><a  href="questoesMonitoria.php">Questões Monitoria</a></li>
                          <li class=""><a  href="monitoriaRealizada.php">Monitoria Realizadas</a></li>
                          <li class=""><a  href="cronogramaAvaliacao.php">Cronograma Avaliação Monitoria</a></li>
                           <li class=""><a  href="prazoAvaliacao.php">Prazo Avaliação Monitoria</a></li>
                      </ul>
                  </li>

               <?php if (($_SESSION['ACESSO'] == 1) or ($_SESSION['ACESSO'] == 2) ) { ?>
                  <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-file-text"></i>
                          <span>Avaliações</span>
                      </a> <?php } ?>
                      <ul class="sub">
                          <li class=""><a  href="tipoTesteConhecimento.php">Tipo Conhecimento</a></li>
                          <li class=""><a  href="questoesConhecimento.php">Questões Conhecimento</a></li>
                          <li class=""><a  href="testeconhecimento.php">Teste Conhecimento</a></li>
                          <li class=""><a  href="testeConhecimentoCadastrado.php">Conhecimento Realizados</a></li>
                      </ul>
                  </li>


                  
                   

   
                    <?php if ($_SESSION['ACESSO'] == 1){ ?>
                      <li class="sub-menu">
                      <a class="" href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>General</span> 
                      </a> <?php } ?>
                      <ul class="sub">
                          <li><a  href="usuarioLogin.php">Usuários GCO</a></li>
                          <li><a  href="listaHorarios.php">Lista Pausas</a></li>
                         <li class=""><a  href="dimensionamento.php">Dimensionamento</a></li>
                          <li class=""><a  href="colaboradores.php">Colaboradores</a></li>
                          <li class=""><a  href="cargo.php">Cargo</a></li>
                          <li class=""><a  href="grupo.php">Grupo</a></li>
                          <li class=""><a  href="regiao.php">Região</a></li>
                          <li class=""><a  href="processo.php">Processo</a></li>
                          <li class=""><a  href="motivo.php">Motivo</a></li>
                          <li class=""><a  href="submotivo.php">Sub-Motivo</a></li>
                      </ul>
                  </li>

                   <?php if ($_SESSION['ACESSO'] == 1){ ?>
                      <li class="sub-menu">
                      <a class="" href="../MOBIRISE/INDEX.html" >
                          <i class="fa fa-cog fa-spin"></i>
                          <span>BETA DEV</span> 
                      </a> <?php } ?>

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
            <h3><i class="fa fa-right"></i> Formulário de Avaliação </h3>

            <!-- criar formulario -->
              <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                         <form name="Form" method="post" id="formulario" action="formularioCadastroAvaliacaoValida.php" onSubmit="return enviardados();">


                         <fieldset>
                          <legend> Dados do Consultor  </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                           <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">Nome Consultor: </label>
                            </td>
                            <td align="left"><br>
                            <?php echo $NOME_CONSULTOR ?></a>            
                            </td>
                             </tr>

                            <tr>
                            <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome" > Supervisor: </label>
                            </td>
                            <td align="left"><br>
                            <?php echo $NOME_GESTOR ?></a>            
                            </td>
                            </tr>

                              <tr>
                           <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">Grupo: </label>
                            </td>
                            <td align="left"><br>
                            <?php echo $NOME_GRUPO ?></a>            
                            </td>
                             </tr>

                          </table>
                         </fieldset><br><br>
                        <br>

<!-- DADOS PESSOAIS-->
                          <fieldset>
                          <legend> AVALIAÇÃO DA MONITORIA </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                           <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">Avaliação: </label>
                            </td>
                            <td align="left"><br>
                             <select name="ID_AVALIACAO">
                                            <option value="null">Escolha uma Avaliacao</option>
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaAvaliacao)){ ?>
                                            <option value="<?php echo $row['ID_AVALIACAO']?> "> <?php echo $row['NUMERO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                             </tr>
                          </table>
                         </fieldset><br><br>
                        <br>

                         <fieldset>
                          <legend> Dados do Talisma </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                           <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">OBJETO: </label>
                            </td>
                            <td align="left"><br>
                             <select name="ID_OBJETO_TALISMA">
                                            <option value="null">Escolha uma Avaliacao</option>
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaObjetoTalisma)){ ?>
                                            <option value=<?php echo $row['ID_OBJETO_TALISMA']?> > <?php echo $row['DESCRICAO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                            </td>
                             <td style="width:110px";><br>
                             <label style="margin-left: 15px" for="nome">ID Objeto: </label>
                            </td>
                            <td align="left"><br>
                             <input style="width:155px" type="text" maxlength="10" value="" id="CRTLV" name="DESC_ID_TALISMA" placeholder="Digite apenas NUMEROS" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>
                            </td>
                             </tr>
                          </table>
                         </fieldset><br><br><br>

                         <fieldset>
                          <legend> Dados da Monitoria </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                             <td style="width:110px";> <br>
                             <label style="margin-left: 15px" for="nome">CPF do Candidato: </label>
                            </td>
                            <td align="left">
                             <input type="text" value="" name="CPF_MONITORIA"  maxlength="15">
                            </td>
                             <td style="width:110px";> 
                             <label style="margin-left: 15px" for="nome">Gravador: </label>
                            </td>
                            <td align="left">
                             <input type="text" value="" name="ID_GRAVADOR"  maxlength="15"  onkeypress='return event.charCode >= 48 && event.charCode <= 57'  >
                            </td>
                            <td style="width:110px";> 
                             <label style="margin-left: 15px" for="nome">Ramal PA: </label>
                            </td>
                            <td align="left">
                             <input type="text" value="" name="RAMAL_PA"  maxlength="15"   onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
                            </td>
                             <td style="width:110px";> 
                             <label style="margin-left: 15px" for="nome">Data Atendimento: </label>
                            </td>
                            <td align="left">
                             <input type="date" value=""  name="DT_ATENDIMENTO"  maxlength="15"  >
                            </td>
                             </tr>
                          </table>
                         </fieldset><br><br><br>

                         <!--  variaveis php auxiliares    -->

                         <?php

                          $numeroItem = 1;
                          $numeroItemCrit = 1;
                    

                         ?>


                        <fieldset>
                          <legend> Itens da Monitoria </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                     <?php while ($row1 = sqlsrv_fetch_array($result_squilaQuestao)) { ?>
                             <td style="width:420px";> <br><hr>
                             <label style="margin-left: 15px" for="nome">Item <?php echo $numeroItem ?> : <?php echo $row1['DESCRICAO'] ?> </label>
                            </td>
                            <td><br><hr>
                              <select name="vetorRespostas[<?php echo $row1['ID_QUESTAO']?>][<?php echo $row1['PESO']?>]"> 
                                 <option value="" > Selecione uma Opção </option> 
                                 <option value="S">SIM</option>
                                 <option value="N">NÃO</option> 
                          <?php if ( $row1['BO_PARCIAL'] == 'S') { ?>
                                 <option value="P">PARCIAL</option>
                          <?php } ?>
                            </select>
                            </td>
                             </tr>
                     <?php $numeroItem ++ ; } ?>
                          </table>
                         </fieldset><br><br><br>


                         <fieldset>
                          <legend> Possui Falhas Críticas ?</legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                     <?php while ($row2 = sqlsrv_fetch_array($result_squilaQuestaoCritico)){ ?>
                             <td style="width:420px";><br><hr>
                             <label style="margin-left: 15px" for="nome">Item <?php echo $numeroItemCrit ?> : <?php echo $row2['DESCRICAO'] ?> </label>
                            </td>
                            <td><br><hr>
                              <select name="vetorRespostasCrit[<?php echo $row2['ID_QUESTAO']?>][<?php echo $row2['PESO']?>]"> 
                                 <option value="N" >NÃO</option> 
                                 <option value="S">SIM</option>
                                  <?php if ( $row2['BO_PARCIAL'] == 'S') { ?>
                                 <option value="P">PARCIAL</option>
                          <?php } ?>
                            </select>
                            </td>
                             </tr>
                     <?php $numeroItemCrit ++ ; } ?>
                          </table>
                         </fieldset><br><br><br>


                         <fieldset>
                          <legend> Resultado da Ligação </legend> 
                          <table cellspacing="10" style="vertical-align: middle">
                           <tr>
                             <td style="width:110px";> 
                              <label style="margin-left: 15px">  Grupo  </label>
                             </td>
                             <td>
                             <select name="ID_RESULT_LIG">
                                            <option value="null">Escolha uma Opção</option>
                                           <?php while ($row = sqlsrv_fetch_array($result_squilaResultLigacao)){ ?>
                                            <option value=<?php echo $row['ID_RESULT_LIG']?> > <?php echo $row['DESC_RESUL_LIGACAO'] ?> </option>
                                         <?php }
                                         ?>
                             </select>
                             </td>
                              </tr>


                            <tr>
                             <td style="width:110px";><br>
                              <label style="margin-left: 15px" for="nome">Observação: </label>
                             </td>
                             <td align="left"><br>
                              <textarea name="OBSERVACAO_PESQUISA" value="" cols="120" rows="10" > TEXTO </textarea>
                             </td>
                            </tr>
                           </table>
                         </fieldset><br><br><br>
            
                        

                         <br/>
                          <input type="hidden" name="ID_MATRICULA_CONSULTOR" value="<?php echo $ID_MATRICULA_CONSULTOR ?>"> 
                          <input type="hidden" name="ID_CONSULTOR" value="<?php echo $ID_CONSULTOR ?>"> 
                          <input type="hidden" name="ID_GRUPO" value="<?php echo $ID_GRUPO ?>">
                          <td><button class="button" onclick=" return getConfirmation();" type="submit" value=""  name="">Confirmar</button> 
                         <a href="formularioAvaliacao.php"><input type="button" value="Cancelar"></a>
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


function enviardados(){
 

if (document.Form.ID_AVALIACAO.value == 'null')
{
alert( "Preencha o campo AVALIACAO!" );
document.Form.ID_AVALIACAO.focus();
return false;
}


if (document.Form.ID_OBJETO_TALISMA.value == 'null')
{
alert( "Preencha o campo OBJETO!" );
document.Form.ID_OBJETO_TALISMA.focus();
return false;
}

if (document.Form.DESC_ID_TALISMA.value == '')
{
alert( "Preencha o campo ID OBJETO!" );
document.Form.DESC_ID_TALISMA.focus();
return false;
}else{

    if (!(!isNaN(parseFloat(document.Form.DESC_ID_TALISMA.value)) && isFinite(document.Form.DESC_ID_TALISMA.value))){
      alert( "Preencha o campo ID OBJETO SOMENTE COM NÚMEROS!" );
      document.Form.DESC_ID_TALISMA.focus();
      return false;
    }
    
}

if (document.Form.CPF_MONITORIA.value == '')
{
alert( "Preencha o campo CPF!" );
document.Form.CPF_MONITORIA.focus();
return false;
}

if (document.Form.ID_GRAVADOR.value == '')
{
alert( "Preencha o campo Gravador" );
document.Form.ID_GRAVADOR.focus();
return false;
}

if (document.Form.DT_ATENDIMENTO.value == '')
{
alert( "Preencha o campo Data Atendimento" );
document.Form.DT_ATENDIMENTO.focus();
return false;
}

if (document.Form.ID_RESULT_LIG.value == 'null')
{
alert( "Preencha o campo Grupo" );
document.Form.ID_RESULT_LIG.focus();
return false;
}

 
return true;
}






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
        

function formatar(mascara, documento){
  var i = documento.value.length;
  var saida = mascara.substring(0,1);
  var texto = mascara.substring(i)
  
  if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
  }
  
}

$(document).ready(function() {

    $("#CRTLV").bind('paste', function(e) {
        e.preventDefault();
    });

});


</script>