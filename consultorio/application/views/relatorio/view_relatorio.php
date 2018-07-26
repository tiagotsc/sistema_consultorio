<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>

<!-- INÍCIO Modal de Editar Situação da Consulta -->
<div id="frmParametros" class="modal hide fade" tabindex="-1" data-width="360">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>SMS Enviados</h3>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
        <?php              
            $data = array('class'=>'pure-form','id'=>'relatorios');
            echo form_open('relatorio/geraRelatorio',$data);
            
                echo form_label('Informe a data início:', 'data');
        		$data = array('id'=>'data_inicio', 'name'=>'data_inicio', 'class'=>'input-block-level data');
        		echo form_input($data,'');
                
                echo form_label('Informe a data fim:', 'data');
        		$data = array('id'=>'data_fim', 'name'=>'data_fim', 'class'=>'input-block-level data');
        		echo form_input($data,'');
        ?>      
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" id="tipo_relatorio" name="tipo_relatorio" value="" />
    <button name="cancelar" type="button" data-dismiss="modal" class="btn">Cancelar</button> 
    <input type="submit" id="btn_alterar" name="btn_alterar" class="btn btn-primary pull-right" value="Gerar" />      
  </div>
        <?php
        echo form_close();
        ?>
</div>
<!-- FIM Modal de Marcar e Editar Consulta -->

<div class="contentArea">

    <div class="divPanel notop page-content">

        <div class="breadcrumbs">
            <a href="<?php echo base_url(); ?>">In&iacute;cio</a> &nbsp;/&nbsp; <span>Funcion&aacute;rio</span>
        </div>

        <div class="row-fluid">
            <!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
                <ul class="nav nav-tabs nav-stacked">
                    <li class="nav-header">
                        <a data-toggle="modal" data-target="#frmParametros" onclick="$('#tipo_relatorio').val('smsEnviados')" href="#">Consolidado arquivo de retorno</a>
                        <p class="spanRelatorio">Descrição: Informa a soma total dos valores pagos por arquivo/banco.</p>
                    </li>
               </ul>
            </div>
			<!--End Main Content Area here-->
			
			

<script type="text/javascript">

$(".data").datepicker({
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
    } 
});

$(document).ready(function(){
    
    // Valida o formulário
	$("#relatorios").validate({
		debug: false,
		rules: {
			data_inicio: {
                required: true
            },
            data_fim: {
                required: true
            }
		},
		messages: {
			data_inicio: {
                required: "Informe a data início."
            },
            data_fim: {
                required: "Informe a data fim."
            }
	   }
   });   
   
});

</script>