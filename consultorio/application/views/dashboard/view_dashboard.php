<?php
echo link_tag(array('href' => 'assets/c3js/c3.css','rel' => 'stylesheet','type' => 'text/css'));
echo "<script type='text/javascript' src='".base_url('assets/c3js/d3.v3.min.js')."'></script>";
echo "<script type='text/javascript' src='".base_url('assets/c3js/c3.js')."'></script>";
?>
<div class="contentArea">

    <div class="divPanel notop page-content">

        <div class="breadcrumbs">
            <a href="<?php echo base_url(); ?>">In&iacute;cio</a> &nbsp;/&nbsp; <span>Gr&aacute;fico</span>
        </div>

        <div class="row-fluid">
		<!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
                <?php
                $options = array('' => '');		
        		foreach($anos as $ano){
        			$options[$ano->anos] = $ano->anos;
        		}
        		
        		echo form_label('Selecione o ano:', 'ano_grafico_linha');
        		echo form_dropdown('ano_grafico_linha', $options, '', 'id="ano_grafico_linha" class="input-block-level"');
        		
                ?>
                <h5 class="ocultar"><strong>Quantidade de atendidos e ausentes</strong></h5>             
                <div class="ocultar" id="chart1"></div>
                <h5 class="ocultar"><strong>Tempo médio (Horas):<br>Atrasos dos pacientes, atrasos dos atendimentos e tempo de atendimentos</strong></h5>
                <div class="ocultar" id="chart2"></div>
                <h5 class="ocultar"><strong>Pacientes com convênio e sem convênio</strong></h5>
                <div class="ocultar" id="chart3"></div>
            </div>
			<!--End Main Content Area here-->
			
			
<script type="text/javascript">

function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    alert(out);
}

$(document).ready(function(){
    
    $(".ocultar").css('display', 'none');
    
    $("#ano_grafico_linha").change(function(){
        
        if($(this).val() != ""){
            
            $(".ocultar").css('display', 'block');
            
            // GRÁFICO DE LINHA                        
            c3.generate({
                bindto: '#chart1',
                data: {
                    //url: '<?php echo base_url('assets/c3_test.json')?>',
                    url: '<?php echo base_url(); ?>ajax/graficoLinha/'+$(this).val(),
                    mimeType: 'json',
                    keys: {
                        x: 'meses', // it's possible to specify 'x' when category axis
                        value: ['Atendidos', 'Ausentes']
                    }
                },
            	axis: {
            		x: {
            			type: 'category'
            		}
            	}
            });
            
            // GRAFICO DE BARRA
            c3.generate({
                bindto: '#chart2',
                data: {
                  url: '<?php echo base_url(); ?>ajax/graficoBarra/'+$(this).val(),
                    mimeType: 'json'
                  ,
                  type: 'bar',
                  keys: {
                        x: 'meses', // it's possible to specify 'x' when category axis
                        value: ['Atrasos Pacientes', 'Atrasos Atendimentos', 'Tempo Atendimentos']
                  },
                  onclick: function (d, element) { console.log("onclick", d, element); },
                  onmouseover: function (d) { console.log("onmouseover", d); },
                  onmouseout: function (d) { console.log("onmouseout", d); }
                },
                axis: {
                  x: {
            			type: 'category'
            		},
                  y : {
                        tick: {
                            format: function (d) { return d; }
                            //format: d3.format("$,")
                        }
                  }
                },
                bar: {
                  width: {
                    ratio: 0.3
                  }
                },
                tooltip: {
                    format: {
                        value: function (value, ratio, id) {
                            var format = id === 'Atrasos Pacientes' ? d3.format(',') : d3.format(',');
                            return format(value).replace('.', ':');
                        }
            //            value: d3.format(',') // apply this format to both y and y2
                    }
                }
              }); 
              
              chart3 = c3.generate({
                    bindto: '#chart3',
                    data: {
                        //url: '<?php echo base_url('assets/c3_test.json')?>',
                        url: '<?php echo base_url(); ?>ajax/graficoBarraColada/'+$(this).val(),
                        mimeType: 'json',
                        type: 'bar',
                        x : 'meses',
                        columns: ['meses','Convênio', 'Particular'],
                        groups: [
                            ['Convênio', 'Particular']
                        ]
                    },
                    axis: {
                        x: {
                            type: 'category'
                        }
                    }
                });     
                                              
        }else{
            $(".ocultar").css('display', 'none');
        }
        
    });
});

</script>