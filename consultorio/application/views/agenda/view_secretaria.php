<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<!-- INÍCIO Modal de Editar Situação da Consulta -->
<div id="frmSituacaoConsulta" class="modal hide fade" tabindex="-1" data-width="360">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Alterar situa&ccedil;&atilde;o</h3>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
        <?php              
            $data = array('class'=>'pure-form','id'=>'frmAlterarSituacaoConsulta');
            echo form_open('agenda/situacaoConsulta',$data);
            
                echo form_label('Data', 'data');
        		$data = array('id'=>'data', 'name'=>'data', 'class'=>'input-block-level');
        		echo form_input($data,'','readonly');
                
                echo form_label('Hor&aacute;rio', 'horario');
        		$data = array('id'=>'horario', 'class'=>'input-block-level');
        		echo form_input($data,'','readonly');
                
                echo form_label('Nome', 'nome');
        		$data = array('id'=>'nome', 'class'=>'input-block-level');
        		echo form_input($data,'','readonly');
            
                $options = array();		
        		foreach($situacao as $sit){
        			$options[$sit->sigla_situacao] = $sit->nome_situacao;
        		}	
                
                #echo '<div class="row-fluid">';
                #echo '<div class="span4">';
        		echo form_label('Situa&ccedil;&atilde;o da consulta<span class="obrigatorio">*</span>', 'situacao_consulta');
        		echo form_dropdown('situacao_consulta', $options, '', 'id="situacao_consulta" class="input-block-level"');
                #echo '</div>';
                #echo '</div>';
        ?>        
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" id="cd_agenda" name="cd_agenda" value="" />
    <button onclick="limpaModalAlterarSituacao()" name="cancelar" type="button" data-dismiss="modal" class="btn">Cancelar</button> 
    <input type="submit" id="btn_alterar" name="btn_alterar" class="btn btn-primary pull-right" value="Alterar" />      
  </div>
        <?php
        echo form_close();
        ?>
</div>
<!-- FIM Modal de Marcar e Editar Consulta -->
    

<!-- INÍCIO Modal de Marcar e Editar Consulta -->
<div id="frmMarcaConsulta" class="modal hide fade" tabindex="-1" data-width="760">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><span id="spanTituloConsulta">Marcar</span> consulta <span id="dataHora"></span></h3>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
        <?php              
            $data = array('class'=>'pure-form','id'=>'frmMarcarConsulta');
            echo form_open('agenda/salvar',$data);
            
                $options = array('' => '', '1' => 'Sim', '2' => 'N&atilde;o');		
                
                echo '<div class="row-fluid">';
                echo '<div class="span2">';
        		echo form_label('Primeira vez?<span class="obrigatorio">*</span>', 'primeira_vez');
        		echo form_dropdown('primeira_vez', $options, '', 'id="primeira_vez" class="input-block-level"');
                echo '</div>';
                
                echo '<div class="span2">';
                echo form_label('Data<span class="obrigatorio">*</span>', 'data_agenda');
        		$data = array('name'=>'data_agenda','id'=>'data_agenda', 'placeholder'=>'Digite uma data', 'class'=>'input-block-level', 'value'=>$dataCompleta);
        		echo form_input($data);
                echo '</div>';
                
                $options = array('' => '');		
        		foreach($especialidade as $tp){
        			$options[$tp->cd_especialidade] = $tp->nome_especialidade;
        		}	
                echo '<div class="span3">';
        		echo form_label('Especialidade<span class="obrigatorio">*</span>', 'cd_especialidade_agenda');
        		echo form_dropdown('cd_especialidade_agenda', $options, '', 'id="cd_especialidade_agenda" class="input-block-level"');
                echo '</div>';
                
                $options = array('' => '');		
        		/*foreach($medico as $med){
        			$options[$med->matricula_funcionario] = $med->nome_funcionario;
        		}*/
                	
                echo '<div class="span5">';
        		echo form_label('M&eacute;dico<span class="obrigatorio">*</span>', 'matricula_medico_agenda');
        		echo form_dropdown('matricula_medico_agenda', $options, '', 'id="matricula_medico_agenda" class="input-block-level"');
                echo '</div>';
                
                /*echo '<div class="span3">';
                echo form_label('Hor&aacute;rio<span class="obrigatorio">*</span>', 'horario_agenda');
        		$data = array('name'=>'horario_agenda','id'=>'horario_agenda', 'placeholder'=>'Digite o hor&aacute;rio', 'class'=>'input-block-level');
        		echo form_input($data);
                echo '</div>';*/
                echo '</div><!--Fecha <div class="row-fluid">-->';
                
                                
                echo '<div id="horarios" class="row-fluid">';
                echo '</div>';
                
                echo '<div id="resVerificao"></div>';
            /*
        		echo form_label('Pesquise o paciente', 'dados_paciente');
        		$data = array('name'=>'dados_paciente','id'=>'dados_paciente', 'placeholder'=>'Digite o nome ou CPF', 'class'=>'input-block-level');
        		echo form_input($data);
              */  
                #echo '<div id="resMarcar"></div>';
                
                echo '<div class="row-fluid">';
                
                echo '<div class="span4">';
                echo form_label('Pesquise o paciente', 'dados_paciente');
        		$data = array('name'=>'dados_paciente','id'=>'dados_paciente', 'placeholder'=>'Digite o nome ou CPF', 'class'=>'input-block-level');
        		echo form_input($data);
                echo '</div>';
                
                echo '<div id="teste" class="span2">';
                echo form_label('Matr&iacute;cula', 'matricula_paciente');
        		$data = array('name'=>'matricula_paciente','id'=>'matricula_paciente', 'class'=>'input-block-level');
        		echo form_input($data,'','readonly');
                echo '</div>';
                
                echo '<div class="span6">';
                echo form_label('Nome<span class="obrigatorio">*</span>', 'nome_paciente');
        		$data = array('name'=>'nome_paciente','id'=>'nome_paciente', 'placeholder'=>'Digite o nome', 'class'=>'input-block-level');
        		echo form_input($data);
                echo '</div>';
                
                echo '</div><!--Fecha <div class="row-fluid">-->';
                
                echo '<div id="resMarcar"></div>';
                
                echo '<div class="row-fluid">';
                echo '<div class="span4">';
                echo form_label('Telefone Fixo', 'tel_fix_paciente');
        		$data = array('name'=>'tel_fix_paciente','id'=>'tel_fix_paciente', 'placeholder'=>'Digite o telefone fixo', 'class'=>'input-block-level');
        		echo form_input($data);
                echo '</div>';
                
                echo '<div class="span4">';
                echo form_label('Telefone Celular', 'tel_cel_paciente');
        		$data = array('name'=>'tel_cel_paciente','id'=>'tel_cel_paciente', 'placeholder'=>'Digite o telefone celular', 'class'=>'input-block-level');
        		echo form_input($data);
                echo '</div>';
                #echo '</div><!--Fecha <div class="row-fluid">-->';
                
                $options = array('' => '');		
        		foreach($plano as $pla){
        			$options[$pla->sigla_plano] = $pla->nome_plano;
        		}		
                
                #echo '<div class="row-fluid">';
                echo '<div class="span4">';
        		echo form_label('Plano<span class="obrigatorio">*</span>', 'sigla_plano_agenda');
        		echo form_dropdown('sigla_plano_agenda', $options, '', 'id="sigla_plano_agenda" class="input-block-level"');
                echo '</div>';
                
                $options = array('' => '');		
        		/*foreach($medico as $med){
        			$options[$med->matricula_funcionario] = $med->nome_funcionario;
        		}*/
                /*	
                echo '<div class="span6">';
        		echo form_label('M&eacute;dico<span class="obrigatorio">*</span>', 'matricula_medico_agenda');
        		echo form_dropdown('matricula_medico_agenda', $options, '', 'id="matricula_medico_agenda" class="input-block-level"');
                echo '</div>';*/
                echo '</div><!--Fecha <div class="row-fluid">-->';
                
        	#echo form_fieldset_close();
        ?>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" id="cd_edita_consulta" name="cd_agenda" value="" />
    <button onclick="limpaModalMarcarConsulta()" name="cancelar" type="button" data-dismiss="modal" class="btn">Cancelar</button> 
    <input type="submit" id="btn_marcar" name="btn_cadastro" class="btn btn-primary pull-right" value="Agendar" />      
  </div>
        <?php
        echo form_close();
        ?>
</div>
<!-- FIM Modal de Marcar e Editar Consulta -->

<div class="contentArea">

    <div class="divPanel notop page-content">

        <div class="breadcrumbs">
            <a href="<?php echo base_url(); ?>">In&iacute;cio</a> &nbsp;/&nbsp; <span>Agenda Secret&aacute;ria</span>
        </div>

        <div class="row-fluid">
		<!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
                <?php echo $this->session->flashdata('status'); ?>
                <div id="resChamados"></div>
                <legend>
                    Agenda
                    <?php
                    
                    $botaoMarcar = '<span data-toggle="modal" onclick="limpaModalMarcarConsulta()" href="#frmMarcaConsulta" id="spanCustomTitle">
                                        <a onclick="$(\'#horarios\').html(\'\'); $(\'#horarios\').css(\'display\',\'none\')">Marcar consulta</a>
                                        <i class="general foundicon-plus icon iconeClick"></i>
                                    </span>';
                                    
                    if(in_array(22, $this->session->userdata('permissoes'))){
                        echo $botaoMarcar;
                    }
                    
                    ?>
                </legend>
               
                <label>
                    Pesquisar consulta:
                    <input type="text" id="pesquisarConsulta" name="pesquisarConsulta" class="input-block-level" />
                </label>
                
                <div id="resPesqConsulta"></div>
               
	           <strong><?php echo $dataCompleta; ?></strong>
                
                <?php
            	$this->table->set_heading('Hor&aacute;rio', 'Nome', 'Telefone', 'Situa&ccedil;&atilde;o', 'A&ccedil;&atilde;o');
                
                foreach($todasConsultas as $tc){
                    
                    $botaoSituacao = ((in_array(23, $this->session->userdata('permissoes'))))? '<a id="linkSituacao'.$tc->cd_agenda.'" data-toggle="modal" href="#frmSituacaoConsulta" onclick="editarSituacaoConsulta('.$tc->cd_agenda.', \''.$dataCompleta.'\', \''.$tc->horario_agenda.'\', \''.$tc->nome_paciente.'\', \''.$tc->sigla_situacao_agenda.'\')">'.$tc->nome_situacao_agenda.'</a>': $tc->nome_situacao_agenda;
                    
                    $cell1 = array('data' => substr($tc->horario_agenda,0, 5), 'class' => 'consultaAgenda'.$tc->cd_agenda);
                    $cell2 = array('data' => $tc->nome_paciente.'<br>Doutor(a): '.$tc->nome_funcionario, 'class' => 'consultaAgenda'.$tc->cd_agenda);
                    $cell3 = array('data' => $tc->tel_fix_paciente/*.' / '.$tc->tel_cel_paciente*/, 'class' => 'consultaAgenda'.$tc->cd_agenda);
                    $cell4 = array('data' => $botaoSituacao);
                    
                    if($tc->novo_paciente == 'SIM'){
                        $linkEditarPaciente = (in_array(25, $this->session->userdata('permissoes')))? '<a href="'.base_url("paciente/ficha/".$tc->matricula_paciente).'"><i title="Ficha incompleta" class="general foundicon-address-book icon tamIcone"></i></a>': '';
                    }else{
                        $linkEditarPaciente = '';
                    }
                    
                    $linkEncaminharConsulta = (in_array(24, $this->session->userdata('permissoes')))? '<a class="encaminharCon'.$tc->cd_agenda.'" href="'.base_url("agenda/encaminharConsulta/".$tc->cd_agenda).'"><i title="Encaminhar para consulta" class="general foundicon-right-arrow icon tamIcone"></i></a>': '';
                    
                    if(in_array(22, $this->session->userdata('permissoes'))){
                        
                        $botaoEditar = '<a data-toggle="modal" href="#frmMarcaConsulta" onclick="editarConsulta('.$tc->cd_agenda.', \''.$this->util->formataData($tc->data_agenda,'BR').'\', \''.$tc->horario_agenda.'\', '.$tc->matricula_paciente.', \''.$tc->nome_paciente.'\', \''.$tc->tel_fix_paciente.'\', \''.$tc->tel_cel_paciente.'\', \''.$tc->sigla_plano_agenda.'\', \''.$tc->especialidade.'\', \''.$tc->matricula_medico_agenda.'\', \''.$tc->nome_funcionario.'\')">
                                                <i title="Editar consulta" class="general foundicon-edit icon tamIcone"></i>
                                        </a>';
                        
                    }else{
                        
                        $botaoEditar = '';
                        
                    }
                    
                    $cell5 = array('data' => $linkEditarPaciente.$botaoEditar.$linkEncaminharConsulta);
                        
                    $this->table->add_row($cell1, $cell2, $cell3, $cell4, $cell5);
                }
                
            	$template = array('table_open' => '<table class="table table-bordered">');
            	$this->table->set_template($template);
            	echo $this->table->generate();
            	?>
		 
            </div>
			<!--End Main Content Area here-->		

<script type="text/javascript">

function teste(){
    $("[type=radio][name=hora][value='07:45']").prop("checked", true);
}

$.fn.verificaConsulta = function() {

    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>ajax/verificaConsulta',
      data: {
        matricula_medico_agenda: $("#matricula_medico_agenda").val(),
        data_agenda: $("#data_agenda").val(),
        horario_agenda: $("#horario_agenda").val(),
        cd_especialidade_agenda: $("#cd_especialidade_agenda").val()
      },
      dataType: "json",
      error: function(res) {
        $("#resVerificao").html('<span>Erro de execução</span>');
      },
      success: function(res) {
      
        if(res['situacao'] == true){
            $("#resVerificao").html('<div class="alert">Existe uma consulta marcada nessa data/hor&aacute;rio/m&eacute;dico.</div>');
        }else{
            $("#resVerificao").html('');
        }
        
      }
    });

};

function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    alert(out);
}

$.fn.horariosDisponiveis = function(horaDefinida) {
    
    $("#horarios").css('display', 'block');
    
    $("#horarios").html('<p style="text-align:center; font-size: 18px;">AGUARDE...</p>');
    
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>ajax/horariosDisponiveis',
      data: {
        matricula_medico_agenda: $("#matricula_medico_agenda").val(),
        data_agenda: $("#data_agenda").val(),
        cd_especialidade_agenda: $("#cd_especialidade_agenda").val()
      },
      dataType: "json",
      error: function(res) {
        $("#horarios").html('<span>Erro de execução</span>');
      },
      success: function(res) {
        
        var horarios = '';
        var hora = '';
        
        if(horaDefinida != null){
            horarios += '<label class="radio"><input type="radio" name="horario_agenda" value="'+horaDefinida+'" checked>'+horaDefinida+'</label>';
        }
        
        for($i=0; $i < res.length; $i++){
            
            //hora = res[$i].substr(0, 5);
            horarios += '<label class="radio"><input type="radio" name="horario_agenda" value="'+res[$i]+'">'+res[$i]+'</label>';
        
        }
        
        $("#horarios").html(horarios);
        
      }
    });
        
};

self.setInterval(function(){$(this).pacientesChamados()}, 1000); // Chama a função a cada 1 segundo

/*
Essa função verifica quais os paciente foram chamados pelo médico e os alertam na agenda para a secretária
*/
$.fn.pacientesChamados = function() { 

            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/monitoraAtendimentos',
              dataType: "json",
              error: function(res) {
 	            $("#resChamados").html('<span>Erro de execução</span>');
              },
              success: function(res) {
                
                // Dê início define a cor preta e fonte normal para todos os pacientes da agenda
                $("[class^=consultaAgenda]").css('color', 'black');
                $("[class^=consultaAgenda]").css('font-weight', 'normal');
                
                // Dê início oculta a funcionalidade de encaminhar paciente para consulta
                $("[class^=encaminharCon]").hide();
                
                var conteudoHtml = '';
                var mostra = false;
                
                $.each(res, function() {
                    
                  if(this.sigla_situacao_agenda == 'CHA'){
                    
                    mostra = true;
                    
                    // Define a cor vermelha e fonte negrito para os pacientes chamados
                    $(".consultaAgenda"+this.cd_agenda).css('color', 'red');
                    $(".consultaAgenda"+this.cd_agenda).css('font-weight', 'bold');
                    
                    <?php if(in_array(24, $this->session->userdata('permissoes'))){ ?>
                    // Mostra a funcionalidade de encaminhar paciente para a consulta
                    $(".encaminharCon"+this.cd_agenda).show();
                    <?php } ?>
                    
                    conteudoHtml += '<p class="alert alert-warning"><strong>Chame o paciente marcado hoje (<?php echo date('d/m/Y') ?>) &agrave;s '+this.horario_agenda.substr(0, 5)+'.</strong></p>';
                    
                  }
                  
                  if(this.sigla_situacao_agenda == 'FIN'){
                        
                    $(".consultaAgenda"+this.cd_agenda).css('text-decoration', 'line-through');
                        
                  }
                  
                  if(this.sigla_situacao_agenda == 'DES'){
                        
                    $(".consultaAgenda"+this.cd_agenda).css('text-decoration', 'line-through');
                        
                  }
                      
                  // Altera o nome da situação da consulta
                  $("#linkSituacao"+this.cd_agenda).html(this.nome_situacao_agenda);
                  
                });
                
                // Exibe ou oculta a div de alertas (Vai depender se existe algum paciente com situação de CHAMADO)
                if(mostra == true){
                    $('#resChamados').show(); 
                }else{
                    $('#resChamados').hide(); 
                }
                
                $("#resChamados").html(conteudoHtml);
                
              }
            });   
    
};

/*
ESSA FUNÇÃO É ACIONADA QUANDO CLICA-SE NA SITUAÇÃO DA CONSULTA

- ELA ALIMENTA O MODAL DE ALTERAÇÃO DE SITUAÇÃO DA CONSULTA
*/
function editarSituacaoConsulta(cd, data, horario, nome, situacao){
    
    $("#data").val(data);
    $("#horario").val(horario.substr(0, 5));
    $("#nome").val(nome);
    $("#situacao_consulta").val(situacao);
    $("#cd_agenda").val(cd);
    
}

/*
ESSA FUNÇÃO É ACIONADA QUANDO CLICA-SE EM 'Cancelar' NA HORA EDITAR A SITUAÇÃO DA CONSULTA

- ELA LIMPA OS CAMPOS DE EDIÇÃO DE CONSULTA
*/
function limpaModalAlterarSituacao(){
    
    $("#data_agenda").val('');
    $("#horario").val('');
    $("#nome").val('');
    $("situacao_consulta").val('');
    $("#hora_presenca_paciente_agenda").val('');
    $("#cd_agenda").val('');
    
}

/*
ESSA FUNÇÃO É ACIONADA QUANDO CLICA-SE NO LINK 'Selecionar'
NO RESULTADO DA BUSCA DE PACIENTE PARA MARCAR CONSULTA NA MODAL

- ELA TRANSFERE OS DADOS DO PACIENTE PARA A MODAL 
*/
function selecionaPaciente(matricula, nome, fixo, celular){
    $("#matricula_paciente").val(matricula);
    $("#nome_paciente").val(nome);
    $("#tel_fix_paciente").val(fixo);
    $("#tel_cel_paciente").val(celular);
    
    $("#resMarcar").css('display', 'none');
    
}

/*
ESSA FUNÇÃO É ACIONADA QUANDO CLICA-SE NO LINK 'Selecionar'
NO RESULTADO DA BUSCA DAS CONSULTAS DO PACIENTE

- ELA TRANSFERE OS DADOS DO PACIENTE PARA A MODAL 
*/
function selecionaConsulta(cd, matricula, nome, fixo, celular, data, horario, plano, medico, nomeMedico, especialidade){

    $("#cd_agenda").val(cd);
    $("#primeira_vez").val(2);
    $("#data_agenda").val(data);
    //$("#horario_agenda").val(horario.substr(0, 5));
    //$("[type=radio][name=hora][value='"+horario.substr(0, 5)+"']").prop("checked", true);
    $("#matricula_paciente").val(matricula);
    $("#nome_paciente").val(nome);
    $("#tel_fix_paciente").val(fixo);
    $("#tel_cel_paciente").val(celular);
    $("#sigla_plano_agenda").val(plano);
    //$("#matricula_medico_agenda").val(medico);
    
    $('#matricula_medico_agenda').append('<option value="'+medico+'" selected="selected">'+nomeMedico+'</option>');
    $("#cd_especialidade_agenda").val(especialidade);
    
    $("#btn_marcar").val('Alterar');
    
    $("#resPesqConsulta").css('display', 'none');
    
    $(this).horariosDisponiveis(horario.substr(0, 5));
    
    $("#resVerificao").html('');
    
    $("#dataHora").html(data+' &agrave;s '+horario.substr(0, 5));
    
    $('#nome_paciente').prop('readonly', true);
    
}

/*
ESSA FUNÇÃO LIMPA TODOS OS DADOS DA MODAL DE MARCAR CONSULTA

- ELA LIMPA PARA QUE SEJA CRIADA UMA NOVA CONSULTA
*/
function limpaModalMarcarConsulta(){
    
    $("#data_agenda").val('<?php echo $dataCompleta;?>');
    $("#spanTituloConsulta").html('Marcar');
    
    $('label[for="matricula_paciente"]').hide();
    $('#matricula_paciente').hide();
    
    $("#cd_agenda").val('');
    $("#primeira_vez").val('');
    //$("#horario_agenda").val('');
    $("#matricula_paciente").val('');
    $("#nome_paciente").val('');
    $("#tel_fix_paciente").val('');
    $("#tel_cel_paciente").val('');
    $("#sigla_plano_agenda").val('');
    $("#matricula_medico_agenda").val('');
    $("#btn_marcar").val('Agendar');
    
    $("#cd_especialidade_agenda").val('');
    
    $("#matricula_medico_agenda").html('');
    
    $("#resVerificao").html('');
    $("#dataHora").html('');
    
    $('label[for="dados_paciente"]').hide();
    $('#dados_paciente').hide();
    
}

/*
ESSA FUNÇÃO TRANFERE OS DADOS DE UMA DAS CONSULTAS PARA A MODAL

- ELA REALIZA ESSA OPERAÇÃO PARA QUE SEJA POSSÍVEL EDITAR TAL CONSULTA
*/
function editarConsulta(cd, data, horario, matricula, nome, fixo, celular, plano, especialidade, matricula_medico, nome_medico){
    
    $("#data_agenda").val(data);
    $("#spanTituloConsulta").html('Alterar');
    
    $('label[for="dados_paciente"]').hide();
    $('#dados_paciente').hide();
    
    $('label[for="matricula_paciente"]').show();
    $('#matricula_paciente').show();
    
    $("#cd_edita_consulta").val(cd);
    $("#primeira_vez").val(2);
    //$("#horario_agenda").val(horario.substr(0, 5));
    
    $("#matricula_paciente").val(matricula);
    $("#nome_paciente").val(nome);
    $("#tel_fix_paciente").val(fixo);
    $("#tel_cel_paciente").val(celular);
    $("#sigla_plano_agenda").val(plano);
    $("#matricula_medico_agenda").val(matricula_medico);
    $("#btn_marcar").val('Alterar');
    
    $("#cd_especialidade_agenda").val(especialidade);
    
    $("#matricula_medico_agenda").html('');
    $("#matricula_medico_agenda").append('<option value="'+ matricula_medico +'">'+ nome_medico +'</option>');
    
    
    $(this).horariosDisponiveis(horario.substr(0, 5));
    
    $("#resVerificao").html('');
    
    $("#dataHora").html(data+' &agrave;s '+horario.substr(0, 5));
    
    $('#nome_paciente').prop('readonly', true);
    
}

/*
NA LEITURA DO DOCUMENTO
*/
$(document).ready(function(){
    
    $("#dataHora").html('');
    $("#horarios").css('display', 'none');
    
    $("#matricula_medico_agenda").change(function(){
        
        if($(this).val() != ""){
            
            $(this).horariosDisponiveis();
            //$(this).verificaConsulta();           
            
        }else{
            
            $("#horarios").html('');
            $("#horarios").css('display', 'none');
            
        }
        
    });
    /*
    $("#horario_agenda").keyup(function(){
        
        if($(this).val() != "" && $("#matricula_medico_agenda").val() !=""){
            
            if($(this).val().length == 5){
                
                $(this).verificaConsulta();
                
            }
            
        }
        
    });*/
    /*
    $("#data_agenda").focusout(function(){
        
        if($(this).val().length == 10 && $("#matricula_medico_agenda").val() !="" && $("#horario_agenda").val().length == 5){
            
            $(this).verificaConsulta();
            
        }
    });
    */
    $("#data_agenda").keyup(function(){
        
        if($(this).val().length == 10 && $("#matricula_medico_agenda").val() !=""){
            
            $(this).horariosDisponiveis();
            //$(this).verificaConsulta();
            
        }else{
            
            $("#horarios").html('');
            $("#horarios").css('display', 'none');
            
        }
        
    });
    
    $("[class^=encaminharCon]").hide(); // Início a funcionalidade para mandar o paciente para consulta no modo oculto
    
    $('#resChamados').hide(); // Início a div de alerta no modo oculto
    
    $('#situacao_consulta option[value=CHA]').remove(); // Remover o valor 'CHAMADO' da combo 'situacao_consulta'
    $('#situacao_consulta option[value=FIN]').remove(); // Remover o valor 'FINALIZADO' da combo 'situacao_consulta'
    $('#situacao_consulta option[value=CON]').remove(); // Remover o valor 'FINALIZADO' da combo 'situacao_consulta'
    
    /*
    CONFIGURA AS MÁSCARAS NOS CAMPOS NECESSÁRIOS
    */
    $("#data_agenda").mask("00/00/0000");
    $("#horario_agenda").mask("00:00");
	$('#tel_fix_paciente').mask('(00)0000-0000');
	$('#tel_cel_paciente').mask('(00)00000-0000');
	$('#cep_paciente').mask('00000-000');
	
    /*
    INICIA A VALIDAÇÃO QUANDO O FORMULÁRIO FOR SUBMETIDO
    */
	$("#frmMarcarConsulta").validate({
		debug: false,
		rules: {
            primeira_vez: {
                required: true
            },
            cd_especialidade_agenda: {
                required: true
            },
            data_agenda: {
                required: true,
                minlength: 10
            },
			nome_paciente: {
                required: true,
                minlength: 10
            },
            horario_agenda: {
                required: true
            },
            sigla_plano_agenda: {
                required: true
            },
            matricula_medico_agenda: {
                required: true
            }
			
		},
		messages: {
            primeira_vez: {
                required: "Selecione a primeira vez."
            },
            cd_especialidade_agenda: {
                required: "Selecione a especialidade."
            },
            data_agenda: {
                required: "Informe uma data.",
                minlength: "Digite a data completo."
            },
			nome_paciente: {
                required: "Preencha o nome.",
                minlength: "Digite o nome completo."
            },
            horario_agenda: {
                required: "Selecione o hor&aacute;rio."
            },
            sigla_plano_agenda: {
                required: "Selecione o plano."
            },
            matricula_medico_agenda: {
                required: "Selecione o m&eacute;dico."
            }
	   }
   });
    
    /*
    CONSULTA O PACIENTE EM TEMPO REAL AO DIGITAR ALGUNS DADOS RELACIONADO A ELE
    */
	$("#dados_paciente").keyup(function(){
	  
		//Verificar se o campo o nome nao e vazio
		if($(this).val() != ""){
		      
            $("#resMarcar").css("display", "block");
          
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/pesquisaPacienteMarcarConsulta',
              data: {
                dados_paciente: $(this).val()
              },
              dataType: "json",
              error: function(res) {
 	            $("#resMarcar").html('<span>Erro de execução</span>');
              },
              success: function(res) {
              
                var conteudoHtml = '';
                conteudoHtml += '<table class="table table-bordered">';
                conteudoHtml += '<thead>';
                conteudoHtml += '<tr>';
                conteudoHtml += '<th>Matr&iacute;cula</th>';
                conteudoHtml += '<th>Nome</th>';
                conteudoHtml += '<th>Tel. Fixo</th>';
                conteudoHtml += '<th>Tel. Celular</th>';
                conteudoHtml += '<th>A&ccedil;&atilde;o</th>';
                conteudoHtml += '</tr>';
                conteudoHtml += '</thead>';
                conteudoHtml += '<tbody>';
                
                $.each(res, function() {
                  
                  var telFix = this.tel_fix_paciente;
                  var telCel = this.tel_cel_paciente;
                  
                  if(this.tel_fix_paciente == null){
                    telFix = '';
                  }
                  
                  if(this.tel_cel_paciente == null){
                    telCel = '';
                  }
                  
                  conteudoHtml += '<tr>';
                  conteudoHtml += '<td>'+this.matricula_paciente+'</td>';
                  conteudoHtml += '<td>'+this.nome_paciente+'</td>';
                  conteudoHtml += '<td>'+telFix+'</td>';
                  conteudoHtml += '<td>'+telCel+'</td>';
                  conteudoHtml += '<td><a href="#" onclick="selecionaPaciente('+this.matricula_paciente+',\''+this.nome_paciente+'\',\''+telFix+'\',\''+telCel+'\')">Selecionar</a></td>';
                  conteudoHtml += '</tr>';
                  
                });
                
                conteudoHtml += '</tbody>';
                
                $("#resMarcar").html(conteudoHtml);
                
              }
            });            

		}else{
            
            $("#resMarcar").css("display", "none");
            $("#resMarcar").html('');
          
		}

	});
    
    /*
    CONSULTA AS CONSULTAS MARCADAS DO PACIENTE
    */
	$("#pesquisarConsulta").keyup(function(){
	   
       //Verificar se o campo o nome nao e vazio
		if($(this).val() != ""){
		      
            $("#resPesqConsulta").css("display", "block");
          
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/pesquisaPacienteConsulta',
              data: {
                dados_paciente: $(this).val()
              },
              dataType: "json",
              error: function(res) {
 	            $("#resPesqConsulta").html('<span>Erro de execução</span>');
              },
              success: function(res) {
              
                var conteudoHtml = '';
                conteudoHtml += '<table class="table table-bordered">';
                conteudoHtml += '<thead>';
                conteudoHtml += '<tr>';
                conteudoHtml += '<th>Data</th>';
                conteudoHtml += '<th>Hor&aacute;rio</th>';
                conteudoHtml += '<th>Situa&ccedil;&atilde;o</th>';
                conteudoHtml += '<th>Nome</th>';
                conteudoHtml += '<th>A&ccedil;&atilde;o</th>';
                conteudoHtml += '</tr>';
                conteudoHtml += '</thead>';
                conteudoHtml += '<tbody>';
                
                $.each(res, function() {
                  
                  var telFix = this.tel_fix_paciente;
                  var telCel = this.tel_cel_paciente;
                  var dataFormatada = this.data_agenda;
                  
                  if(this.tel_fix_paciente == null){
                    telFix = '';
                  }
                  
                  if(this.tel_cel_paciente == null){
                    telCel = '';
                  }
                  
                  dataFormatada = dataFormatada.split('-'); // IGUAL A FUNÇÃO 'explode()' DO PHP
                  dataFormatada = dataFormatada.reverse(); // IGUAL A FUNÇÃO 'array_reverse()' DO PHP
                  dataFormatada = dataFormatada.join('/'); // IGUAL A FUNÇÃO 'implode()' DO PHP
                  
                  conteudoHtml += '<tr>';
                  conteudoHtml += '<td>'+dataFormatada+'</td>';
                  conteudoHtml += '<td>'+this.horario_agenda.substr(0, 5)+'</td>';
                  conteudoHtml += '<td>'+this.nome_situacao_agenda+'</td>';
                  conteudoHtml += '<td>'+this.nome_paciente+'</td>';
                  conteudoHtml += '<td><a data-toggle="modal" href="#frmMarcaConsulta" onclick="selecionaConsulta('+this.cd_agenda+', '+this.matricula_paciente+',\''+this.nome_paciente+'\',\''+telFix+'\',\''+telCel+'\', \''+dataFormatada+'\', \''+this.horario_agenda+'\', \''+this.sigla_plano_agenda+'\', \''+this.matricula_medico_agenda+'\',\''+this.nome_funcionario+'\', '+this.cd_especialidade_agenda+')">Selecionar</a></td>';
                  conteudoHtml += '</tr>';
                  
                });
                
                conteudoHtml += '</tbody>';
                
                $("#resPesqConsulta").html(conteudoHtml);
                
              }
            });            

		}else{
            
            $("#resPesqConsulta").css("display", "none");
            $("#resPesqConsulta").html('');
          
		}
       
    });   
    
    /*
    OCULTA E LIMPA OS DADOS DOS CAMPOS INFORMADOS
    */
    $('label[for="dados_paciente"]').hide();
    $('#dados_paciente').hide();
    $('#dados_paciente').val('');
    
    $('label[for="matricula_paciente"]').hide();
    $('#matricula_paciente').hide();
    $('#matricula_paciente').val('');
    
    
    /*
    AO SELECIONAR DETERMINADO VALOR DO CAMPO 'primeira_vez' A CONDIÇÃO MOSTRA 
    OU OCULTA DETERMINADOS CAMPOS DE ACORDO COM O VALOR SELECIONADO
    */
    $("#primeira_vez").change(function(){
        
        if($(this).val() != ""){
            
            if($(this).val() == '2'){ // PRIMEIRA VEZ - NÃO
                
                //$("#div_nome_paciente").removeClass("span6");
                //$("#div_nome_paciente").addClass("span6");
                
                $('label[for="dados_paciente"]').show();
                $('#dados_paciente').show();
                $('#dados_paciente').val('');
                
                
                $('label[for="matricula_paciente"]').show();
                $('#matricula_paciente').show();
                
                $('#nome_paciente').prop('readonly', true);
                $('#nome_paciente').prop("placeholder", "");
                
            }else{ // PRIMEIRA VEZ - SIM
                
                //$("#div_nome_paciente").removeClass("span6");
                //$("#div_nome_paciente").addClass("span6");
                
                $('label[for="dados_paciente"]').hide();
                $('#dados_paciente').hide();
                $('#dados_paciente').val('');
                
                $('label[for="matricula_paciente"]').hide();
                $('#matricula_paciente').hide();
                $('#matricula_paciente').val('');
                
                $('#nome_paciente').prop('readonly', false);
                
                $("#resMarcar").css("display", "none");
                $("#resMarcar").html('');
                
            }
            
        }else{
            
            $('label[for="dados_paciente"]').hide();
            $('#dados_paciente').hide();
            $('#dados_paciente').val('');
            
        }
        
    });
    
    $("#cd_especialidade_agenda").change(function(){
        
        if($(this).val() != ''){
            
            $("#matricula_medico_agenda").html('<option value="">AGUARDE...</option>');
            
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/medicosEspecialidades',
              data: {
                especialidade: $(this).val()
              },
              dataType: "json",
              error: function(res) {
 	            $("#resMarcar").html('<span>Erro de execução</span>');
              },
              success: function(res) {
                
                content = '<option value=""></option>';
                
                $.each(res, function() {
                  
                  content += '<option value="'+ this.matricula_funcionario +'">'+ this.nome_funcionario +'</option>';
                  
                });
                
                $("#matricula_medico_agenda").html('');
                $("#matricula_medico_agenda").append(content);
                
              }
            });
            
        }else{
            
            $("#matricula_medico_agenda").html('');
            
        }
        
    });

});

/*
CONFIGURA O CALENDÁRIO DATEPICKER NO INPUT INFORMADO
*/
$("#data_agenda").datepicker({
	dateFormat: 'dd/mm/yy',
	dayNames: ['Domingo','Segunda','Ter&ccedil;a','Quarta','Quinta','Sexta','S&aacute;bado','Domingo'],
	dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
	monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	nextText: 'Pr&oacute;ximo',
	prevText: 'Anterior',
    
    // Traz o calendário input datepicker para frente da modal
    beforeShow :  function ()  { 
        setTimeout ( function (){ 
            $ ( '.ui-datepicker' ). css ( 'z-index' ,  99999999999999 ); 
        },  0 ); 
    },
    onSelect: function (dateText, inst){
        
        if($("#data_agenda").val().length == 10 && $("#matricula_medico_agenda").val() !=""){
            
            $(this).horariosDisponiveis();
         
        }else{
            
            $("#horarios").html('');
            $("#horarios").css('display', 'none');
            
        }
	     //$(this).verificaConsulta();
	} 
});
</script>