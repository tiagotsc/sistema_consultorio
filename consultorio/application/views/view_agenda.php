			<!--Edit Sidebar Content here-->
            <div class="span5 sidebar">

                <div class="sidebox">
                    <h3 class="sidebox-title">Agenda <?php echo $this->session->userdata('tipo_usuario'); ?></h3> 
                      
                        <!-- Responsive calendar - START -->
                        <div class="responsive-calendar">
                            <div class="controls">
                                <a class="pull-left" data-go="prev"><div class="btn btn-primary">Anterior</div></a>
                                <h4><span data-head-year></span> <span data-head-month></span></h4>
                                <a class="pull-right" data-go="next"><div class="btn btn-primary">Pr&oacute;ximo</div></a>
                            </div><hr/>
                            <div class="day-headers">
                                  <div class="day header">Dom</div>
                                  <div class="day header">Seg</div>
                                  <div class="day header">Ter</div>
                                  <div class="day header">Qua</div>
                                  <div class="day header">Qui</div>
                                  <div class="day header">Sex</div>
                                  <div class="day header">S&aacute;b</div>
                            </div>
                            <div class="days" data-group="days">
                                  
                            </div>
                        </div>
                        <!-- Responsive calendar - END -->                      				

               
			       	  <h4 class="sidebox-title">D&ecirc; <span id="total"></span> pacientes em <?php echo $dataFormatada; ?>:</h4>				       
			        	
			            	<h5>Atendidos ( <span id="infoAtendidos"></span>% )</h5>
			            	<div class="progress progress-info">
                            <div id="barAtendidos" class="bar"></div>
                            </div>				          	
			          	<!--
			            	<h5>Desmarcados ( 80% )</h5>
			            	<div class="progress progress-success">
                            <div class="bar" style="width: 40%"></div>
                            </div>				          	
			          	-->
			            	<h5>Ausentes ( <span id="infoAusentes"></span>% )</h5>
			            	<div class="progress progress-warning">
                            <div id="barAusentes" class="bar"></div>
                            </div>				          	
			          	
			            	<h5>Desmarcados ( <span id="infoDesmarcados"></span>% )</h5>
			            	<div class="progress progress-danger">
                            <div id="barDesmarcados" class="bar"></div>
                            </div>         	
			      	
			   			
                </div>
                
            </div>
			<!--End Sidebar Content here-->
        </div><!-- FECHA <div class="row-fluid"> -->

        <div id="footerInnerSeparator"></div>
    </div><!-- FECHA <div class="divPanel notop page-content"> -->
</div><!-- FECHA <div class="contentArea"> -->

<script type="text/javascript">

self.setInterval(function(){$(this).estatisticaConsultas()}, 1000); // Chama a função a cada 1 segundo

/*
Essa função verifica quais os paciente foram chamados pelo médico e os alertam na agenda para a secretária
*/
$.fn.estatisticaConsultas = function() { 

            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/estatisticaDia',
              data: {
                dataCalendario: '<?php echo $dataCalendario; ?>'
              },
              dataType: "json",
              error: function(res) {
 	            $("#total").html('');
              },
              success: function(res) {
                
                $.each(res, function() {
                    
                    // Define a cor vermelha e fonte negrito para os pacientes chamados
                    $("#total").html(this.total);
                    
                    $("#infoAtendidos").html(this.porc_atendidos);
                    $("#barAtendidos").css('width', this.porc_atendidos+'%');
                    
                    $("#infoAusentes").html(this.porc_ausentes);
                    $("#barAusentes").css('width', this.porc_ausentes+'%');
                    
                    $("#infoDesmarcados").html(this.porc_desistiu);
                    $("#barDesmarcados").css('width', this.porc_desistiu+'%');                    
                  
                });
                
              }
            });   
    
};

$(document).ready(function(){
   $(".responsive-calendar").responsiveCalendar({
        onDayClick: function(events) { 
            
            var formataDia = $(this).data('day');
            var formataMes = $(this).data('month');
            
            if($(this).data('day') < 10){
                formataDia = '0'+$(this).data('day');
            }
            
            if($(this).data('month') < 10){
                formataMes = '0'+$(this).data('month');
            }
            
            var data = formataDia+'/'+formataMes+'/'+$(this).data('year');
            $(window.document.location).attr('href','<?php echo base_url('agenda/'.$this->session->userdata('direct_usuario')); ?>/'+data);
            //alert($(this).data('day')+'/'+$(this).data('month')+'/'+$(this).data('year'));
        },
        time: '<?php echo date('Y-m'); ?>',
        <?php if($qtdConsultas){ ?>
        events: {
            <?php 
            $qtdDataSelecionada = '';
            $qtdDataHoje = 'number:0,';
            foreach($qtdConsultas as $qtdC){ 
              
              # Se existir consultas marcadas na data selecionada, alementa a variável que informa essa quantidade
              if($qtdC->data_agenda == $dataCalendario){
                $qtdDataSelecionada = 'number:'. $qtdC->qtd_consulta.',';
              }/*else{
                $qtdDataSelecionada = 'number:0,';
              }*/
              
              if($qtdC->data_agenda == date('Y-m-d')){
                $qtdDataHoje = 'number:'. $qtdC->qtd_consulta.',';
              }
              #$qtdDataSelecionada = ($qtdC->data_agenda == $dataCalendario)? 'number:'. $qtdC->qtd_consulta.',' : '';
            ?>
            
            "<?php echo $qtdC->data_agenda; ?>":{"number": <?php echo $qtdC->qtd_consulta; ?>}, 
            
            <?php 
            } 
            ?>
            /*"2013-04-30": {"number": 5, "url": "http://w3widgets.com/responsive-slider"},
            "2013-04-26": {"number": 1, "url": "http://w3widgets.com"},*/ 
            
            "<?php echo $dataCalendario; ?>": {<?php echo $qtdDataSelecionada; ?> "class":"calendarSelecionada"},
            "<?php echo date('Y-m-d'); ?>": {<?php echo $qtdDataHoje; ?>"class":"calendarHoje"}
        }
        <?php } ?>
    });
});
</script>