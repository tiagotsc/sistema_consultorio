<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<!-- INÍCIO Modal Definitions (tabbed over for <pre>) -->
<div id="removerFuncionario" class="modal hide fade" tabindex="-1" data-width="400">
<?php               
$data = array('class'=>'pure-form','id'=>'remover_funcionario');
echo form_open('funcionario/remover',$data);
?>
  <div class="modal-body">
    <div class="row-fluid">
        <strong>Desejar remover o funcionario?</strong>
        <div id="alertaApaga" class="alert alert-block"></div>
        <input type="hidden" id="excluirFuncionario" name="excluirFuncionario" />
        <input type="text" readonly="true" id="nomeRemocao" name="nomeRemocao" class="input-block-level" />
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn">Fechar</button>
    <button type="submit" class="btn btn-primary">Remover</button>
  </div>
<?php
echo form_close();
?>
</div>
<!-- FIM Modal Definitions (tabbed over for <pre>) -->
<div class="contentArea">

    <div class="divPanel notop page-content">

        <div class="breadcrumbs">
            <a href="<?php echo base_url(); ?>">In&iacute;cio</a> &nbsp;/&nbsp; <span>Funcion&aacute;rio</span>
        </div>

        <div class="row-fluid">
		<!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
                <?php echo $this->session->flashdata('status'); ?>
                <?php
                $data = array('class'=>'pure-form','id'=>'frm_envio_confirmacao');
            	echo form_open('sms/execEnvioConfirmacao',$data);
                    $attributes = array('id' => 'address_info', 'class' => 'address_info');
                    
            		echo form_fieldset("Enviar SMS de confirmação de consulta", $attributes);
            		    
                        $classSaldo = ($saldoTotal[0]->qtd_sms < 50)? 'class="alert alert-danger"': 'class="alert alert-info"';
                        $totalSaldo = ($saldoTotal[0]->qtd_sms > 0)? $saldoTotal[0]->qtd_sms: 0;
                        
                        
                        echo '<div '.$classSaldo.'><strong>Qtd. de sms disponíveis: '.$totalSaldo.'</strong></div>';  
                        
            			echo form_label('Data das consultas', 'data_consulta');
            			$data = array('name'=>'data_consulta','id'=>'data_consulta', 'placeholder'=>'Informe a data', 'class'=>'input-block-level');
            			echo form_input($data);
            			/*
                        echo '<div class="actions">';
            			echo form_submit("btn_cadastro","Pesquisar", 'class="btn btn-primary pull-right"');
                        echo '</div>';*/              
                
                ?>
                <div id="resultado"></div>
                <?php
                    echo form_fieldset_close();
            	echo form_close();  
                ?>
            </div>
            
			<!--End Main Content Area here-->
			
			

<script type="text/javascript">

function marcaTodos(){

    if($('#todasConsultas').prop('checked') == true){
        $('.selecionado').prop('checked', true);
    }else{
        $('.selecionado').prop('checked', false);
    }
	
}

$.fn.pegaConsultas = function() {
    
    //Fazer requisição assincrona entre pagina
    $.ajax({
      type: "POST",
      url: '<?php echo base_url(); ?>ajax/consultaParaEnvioSMS',
      data: {
        data_consulta: $("#data_consulta").val()
      },
      dataType: "json",
      error: function(res) {
        $("#resultado").html('<span>Erro de execução</span>');
      },
      success: function(res) {
        var cont=0;
        var conteudoHtml = '';
        conteudoHtml += '<table class="table table-bordered">';
        conteudoHtml += '<thead>';
        conteudoHtml += '<tr>';
        conteudoHtml += '<th><input type="checkbox" onclick="marcaTodos()" id="todasConsultas" name="todasConsultas"/></th>';
        conteudoHtml += '<th>Data/hora</th>';
        conteudoHtml += '<th>Matr&iacute;cula - Nome</th>';
        conteudoHtml += '<th>Celular</th>';
        conteudoHtml += '<th>Doutor(a)</th>';
        conteudoHtml += '</tr>';
        conteudoHtml += '</thead>';
        conteudoHtml += '<tbody>';
        
        $.each(res, function() {
          
          conteudoHtml += '<tr>';
          conteudoHtml += '<td><input type="checkbox" class="selecionado" id="selecionado['+cont+']" value="'+cont+'" name="selecionado['+cont+']"/></td>';
          conteudoHtml += '<td>'+this.data_agenda+' às '+this.horario_agenda+'</td>';
          conteudoHtml += '<td>'+this.matricula_paciente+' - '+this.nome_paciente+'</td>';
          conteudoHtml += '<td>'+this.tel_cel_paciente+'</td>';
          conteudoHtml += '<td>'+this.nome_medico;
          conteudoHtml += '<input type="hidden" name="cd_agenda['+cont+']" value="'+this.cd_agenda+'" />';
          conteudoHtml += '<input type="hidden" name="celular['+cont+']" value="'+this.tel_cel_paciente+'" />';
          conteudoHtml += '<input type="hidden" name="msg['+cont+']" value="Voce tem uma consulta marcada em '+this.data_agenda+' as '+this.horario_agenda+' com o dr(a) '+this.nome_medico+'\nResponda SIM para confirmar ou NAO para desmarcar." />';
          conteudoHtml += '</td>'
          conteudoHtml += '</tr>';
          
          cont=cont+1; 
          
        });
        
        conteudoHtml += '</tbody>';
        conteudoHtml += '</table>';
        
        if(res.length > 0){
        
            conteudoHtml += '<div><input type="submit" class="btn btn-primary pull-right" value="Enviar SMS" /></div>';
        
        }
        
        $("#resultado").html(conteudoHtml);
        
      }
    }); 
    
};

$(document).ready(function(){

    $("#data_consulta").mask("00/00/0000");

	$("#data_consulta").keyup(function(){
	  
        if($("#data_consulta").val().length == 10){
            $(this).pegaConsultas();
        }else{
            $("#resultado").html('');
        }

	});
});

$("#data_consulta").datepicker({
	dateFormat: 'dd/mm/yy',
	dayNames: ['Domingo','Segunda','Ter&ccedil;a','Quarta','Quinta','Sexta','S&aacute;bado','Domingo'],
	dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'],
	monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	nextText: 'Pr&oacute;ximo',
	prevText: 'Anterior',
    onSelect: function (dateText, inst){
	     //alert($("#data_consulta").val());
         $(this).pegaConsultas();
	}
});

</script>