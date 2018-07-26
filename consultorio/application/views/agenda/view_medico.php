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

<div class="contentArea">

    <div class="divPanel notop page-content">

        <div class="breadcrumbs">
            <a href="<?php echo base_url(); ?>">In&iacute;cio</a> &nbsp;/&nbsp; <span>Agenda <?php echo $this->session->userdata('tipo_usuario'); ?></span>
        </div>

        <div class="row-fluid">
		<!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
                <?php echo $this->session->flashdata('status'); ?>
                <div id="resAtender"></div>
               
	           <strong><?php echo $dataCompleta; ?></strong>
                
                <?php
            	$this->table->set_heading('Hor&aacute;rio', 'Nome', 'Telefone', 'Situa&ccedil;&atilde;o', 'A&ccedil;&atilde;o');
                
                foreach($todasConsultas as $tc){
                    
                    $botaoAltera = (in_array(26, $this->session->userdata('permissoes')))? '<a id="linkSituacao'.$tc->cd_agenda.'" data-toggle="modal" href="#frmSituacaoConsulta" onclick="editarSituacaoConsulta('.$tc->cd_agenda.', \''.$dataCompleta.'\', \''.$tc->horario_agenda.'\', \''.$tc->nome_paciente.'\', \''.$tc->sigla_situacao_agenda.'\')">'.$tc->nome_situacao_agenda.'</a>': '';
                    
                    $cell1 = array('data' => substr($tc->horario_agenda,0, 5), 'class' => 'consultaAgenda'.$tc->cd_agenda);
                    $cell2 = array('data' => $tc->nome_paciente, 'class' => 'consultaAgenda'.$tc->cd_agenda);
                    $cell3 = array('data' => $tc->tel_fix_paciente/*.' / '.$tc->tel_cel_paciente*/, 'class' => 'consultaAgenda'.$tc->cd_agenda);
                    $cell4 = array('data' => $botaoAltera);
                    
                    if($tc->sigla_situacao_agenda == 'FIN'){
                        $classAtende = 'OpcionalatenderPac';
                    }else{
                        $classAtende = 'atenderPac';
                    }
                    
                    if(in_array(27, $this->session->userdata('permissoes'))){
                        
                        $botaoChamar = '<a class="chamarPac'.$tc->cd_agenda.'" href="'.base_url("agenda/chamaPaciente/".$tc->cd_agenda).'">
                                                <i title="Chamar paciente" class="general foundicon-down-arrow icon tamIcone"></i>
                                        </a>';
                        
                    }else{
                        
                        $botaoChamar = '';
                        
                    }
                    
                    if(in_array(28, $this->session->userdata('permissoes'))){
                        
                        $botaoAtender = '<a class="'.$classAtende.$tc->cd_agenda.'" href="'.base_url("agenda/atenderPaciente/".$tc->cd_agenda).'">
                                                <i title="'.$tc->title_atendimento.'" class="general foundicon-search icon tamIcone"></i>
                                        </a>';
                        
                    }else{
                        
                        $botaoAtender = '';
                        
                    }
                    
                    $cell5 = array('data' => $botaoChamar.$botaoAtender);
                        
                    $this->table->add_row($cell1, $cell2, $cell3, $cell4, $cell5);
                }
                
            	$template = array('table_open' => '<table class="table table-bordered">');
            	$this->table->set_template($template);
            	echo $this->table->generate();
            	?>
		 
            </div>
			<!--End Main Content Area here-->		

<script type="text/javascript">

self.setInterval(function(){$(this).pacienteAtender()}, 1000); // Chama a função a cada 1 segundo

/*
Essa função monitório a situação das marcações agendadas e as alertam na agenda para o médico
*/
$.fn.pacienteAtender = function() { 

            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/monitoraPacientes',
              dataType: "json",
              error: function(res) {
 	            $("#resAtender").html('<span>Erro de execução</span>');
              },
              success: function(res) {
                
                // Dê início define a cor preta e fonte normal para todos os pacientes da agenda
                $("[class^=consultaAgenda]").css('color', 'black');
                $("[class^=consultaAgenda]").css('font-weight', 'normal');
                
                // Dê início oculta a funcionalidade de atender paciente
                $("[class^=atenderPac]").hide();
                
                var conteudoHtml = '';
                var mostra = false;
                
                $.each(res, function() {
                    
                  if(this.sigla_situacao_agenda == 'CON' || this.sigla_situacao_agenda == 'FIN'){
                    
                    mostra = true;
                    
                    if(this.sigla_situacao_agenda == 'CON'){
                        // Define a cor vermelha e fonte negrito para os pacientes chamados
                        $(".consultaAgenda"+this.cd_agenda).css('color', 'red');
                        $(".consultaAgenda"+this.cd_agenda).css('font-weight', 'bold');
                        
                        conteudoHtml += '<p class="alert alert-warning"><strong>Voc&ecirc; esta atendendo o paciente marcado hoje (<?php echo date('d/m/Y') ?>) &agrave;s '+this.horario_agenda.substr(0, 5)+'.</strong></p>';
                    }
                    
                    if(this.sigla_situacao_agenda == 'FIN' || this.sigla_situacao_agenda == 'DES'){
                        
                        $(".consultaAgenda"+this.cd_agenda).css('text-decoration', 'line-through');
                        
                    }
                    
                    // Mostra a funcionalidade de atender paciente
                    $(".atenderPac"+this.cd_agenda).show();
                    
                    // Oculta a funcionalidade de chamar paciente
                    $(".chamarPac"+this.cd_agenda).hide();
                    
                  }
                  
                  if(this.sigla_situacao_agenda == 'PRE'){
                    
                    $(".chamarPac"+this.cd_agenda).show();
                    
                  }else{
                    
                    $(".chamarPac"+this.cd_agenda).hide();
                    
                  }
                  
                  // Altera o nome da situação da consulta
                  $("#linkSituacao"+this.cd_agenda).html(this.nome_situacao_agenda);
                  
                });
                
                // Exibe ou oculta a div de alertas (Vai depender se existe algum paciente com situação de CHAMADO)
                if(mostra == true){
                    $('#resAtender').show(); 
                }else{
                    $('#resAtender').hide(); 
                }
                
                $("#resAtender").html(conteudoHtml);
                
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
    $("#cd_agenda").val('');
    
}

/*
NA LEITURA DO DOCUMENTO
*/
$(document).ready(function(){
    
    $("[class^=chamarPac]").hide(); // Início a funcionalidade de chamar o paciente no modo oculto
    $("[class^=atenderPac]").hide(); // Início a funcionalidade de atender paciente no modo oculto
    
    $('#resChamados').hide(); // Início a div de alerta no modo oculto
    
    $('#situacao_consulta option[value=DES]').remove(); // Remover o valor 'DESISTIU' da combo 'situacao_consulta'
    $('#situacao_consulta option[value=ENC]').remove(); // Remover o valor 'ENCAIXE' da combo 'situacao_consulta'
    $('#situacao_consulta option[value=PRE]').remove(); // Remover o valor 'PRESENTE' da combo 'situacao_consulta'
    
});
</script>