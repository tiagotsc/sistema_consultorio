<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/WYSIWYG/jquery-te-1.4.0.min.js") ?>"></script>
<?php echo link_tag(array('href' => 'assets/js/WYSIWYG/jquery-te-1.4.0.css','rel' => 'stylesheet','type' => 'text/css')); ?>

<div class="contentArea">

    <div class="divPanel notop page-content">

        <div class="breadcrumbs">
            <a href="<?php echo base_url(); ?>">In&iacute;cio</a> &nbsp;/&nbsp; <span>Atendimento</span>
        </div>

        <div class="row-fluid">
		<!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
                <?php echo $this->session->flashdata('status'); ?>
                <div id="resAtender"></div>
               
	           <strong><?php echo $this->util->formataData($atendimento[0]->data_agenda, 'BR'); ?> &agrave;s <?php echo substr($atendimento[0]->horario_agenda,0, 5); ?></strong>
                
                <div id="div_atendimento">
                    <?php
                    $this->table->set_heading('Paciente', 'M&eacute;dico', 'Especialidade');
                    
                    $cell1 = array('data' => $atendimento[0]->matricula_paciente.' - '.$atendimento[0]->nome_paciente);
                    $cell2 = array('data' => $atendimento[0]->nome_funcionario);
                    $cell3 = array('data' => $atendimento[0]->nome_especialidade);
                    
                    $this->table->add_row($cell1, $cell2, $cell3);
                    
                    $template = array('table_open' => '<table class="table table-bordered">');
                        
                	$this->table->set_template($template);
                	echo $this->table->generate();
                    
                    $data = array('class'=>'pure-form','id'=>'frmAtendimento');
                    echo form_open_multipart('agenda/salvaAtendimento',$data);
                    
                        echo form_label('Anota&ccedil;&otilde;es', 'anotacoes_medico_agenda');
                		$data = array('id'=>'anotacoes_medico_agenda', 'name'=>'anotacoes_medico_agenda', 'class'=>'input-block-level');
                		echo form_textarea($data,$atendimento[0]->anotacoes_medico_agenda);
                        
                        echo '<div id="inicioReceita" style="text-align:right"><a id="addReceita" href="#inicioReceita">Adicionar receita</a></div>';
                        
                        if($receitasMedica){
                            
                            echo form_label('Receita m&eacute;dica', 'receita_medica[]');
                            foreach($receitasMedica as $rM){
                                echo '<div>';
                                $data = array('name'=>'receita_medica[]', 'class'=>'input-block-level receita_medica');
          		                echo form_textarea($data, $rM->conteudo_receita_medica);
                                echo '</div>';
                                echo '<div id="divReceita">';
                                echo '<a target="_blank" class="btn btn-primary" href="'.base_url('agenda/receita/'.$rM->cd_receita_medica.'/'.$cdAtendimento).'">Imprimir</a>';
                                echo '</div>';
                            }
                            
                        }else{
                            
                        echo form_label('Receita m&eacute;dica', 'receita_medica[]');
                        $data = array(/*'id'=>'receita_medica', */'name'=>'receita_medica[]', 'class'=>'input-block-level receita_medica');
                		echo form_textarea($data,/*$atendimento[0]->receita_medica*/'');
                        
                        }
                        echo '<div id="receitas"></div>';
                        
                        echo form_hidden('sigla_situacao_agenda','FIN');
                        
                        echo form_hidden('cd_agenda',$cdAtendimento);
                    
                        echo '<div class="actions margin-top">';
                        
                		echo form_submit("btn_salvar",'Salvar', 'class="btn btn-primary pull-right"');
                        echo '</div>';
                    
                    echo form_close();
                    ?>        
                </div>
                
                <div id="historicoAtendimento">
                <?php foreach($atendimentoHistorico as $aH){ ?>
                  <h3><?php echo $this->util->formataData($aH->data_agenda, 'BR').' &agrave;s '.substr($aH->horario_agenda, 0, 5).'<br>Dr.: '.$aH->nome_funcionario.' - '.$aH->nome_especialidade;?></h3>
                  <div>
                    <h4><strong>Anota&ccedil;&otilde;es</strong></h4>
                    <p>
                    <?php echo $aH->anotacoes_medico_agenda; ?>
                    </p>
                    <h4><strong>Receita</strong></h4>
                    <div>
                    <?php 
                        $receitas = $receitasHistorico[$aH->cd_agenda];
                        
                        $totalReceitas = count($receitas);
                        
                        $contReceita = 1;
                        foreach($receitas as $receita){
                            
                            $cssLinha = ($contReceita < $totalReceitas)? 'class="separadorReceitasHistorico"': '';
                            
                            echo '<p '.$cssLinha.'>'.$receita->conteudo_receita_medica.'</p>';
                            $contReceita++;
                        }
                    ?>
                    </div>
                  </div>
                 <?php } ?>
                </div>                
		 
            </div>
			<!--End Main Content Area here-->		

<script type="text/javascript">

$(document).ready(function(){
    
    $("#addReceita").click(function(){
        
        $('[name^=receita_medica]').jqte({
        	link: false,
        	outdent: false,
        	sub: false,
        	unlink: false,
        	format: false,
        	source: false
        }); 
        
        var receitas = '<div><a href="#inicioReceita" onclick="$(this).parent().remove();">Remover</a>';
        receitas += '<textarea class="receita_medica[]" name="receita_medica[]"></textarea>';
        receitas += '</div>';

        $("#receitas").append(receitas);
        
        $('[name^=receita_medica]').jqte({
        	link: false,
        	outdent: false,
        	sub: false,
        	unlink: false,
        	format: false,
        	source: false
        }); 
        
        
    });
    
});

$(function() {
    $( "#historicoAtendimento" ).accordion({
        collapsible: true, // Habilita a opção de expandir e ocultar ao clicar
        heightStyle: "content"
    });
});
 
$('#anotacoes_medico_agenda').jqte({
	link: false,
	outdent: false,
	sub: false,
	unlink: false,
	format: false,
	source: false
});  

$('.receita_medica').jqte({
	link: false,
	outdent: false,
	sub: false,
	unlink: false,
	format: false,
	source: false
});

function layoutTextArea(){
    $('.receita_medica').jqte({
    	link: false,
    	outdent: false,
    	sub: false,
    	unlink: false,
    	format: false,
    	source: false
    }); 
}
</script>