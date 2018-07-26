<!-- INÍCIO Modal Definitions (tabbed over for <pre>) -->
<div id="removerPaciente" class="modal hide fade" tabindex="-1" data-width="400">
<?php               
$data = array('class'=>'pure-form','id'=>'remover_paciente');
echo form_open('paciente/remover',$data);
?>
  <div class="modal-body">
    <div class="row-fluid">
        <strong>Desejar remover o paciente?</strong>
        <div class="alert alert-block"><h4><strong>Aten&ccedil;&atilde;o!</strong></h4> As consultas associadas a esse paciente ser&atilde;o apagadas tamb&eacute;m.</div>
        <input type="hidden" id="excluirPaciente" name="excluirPaciente" />
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
            <a href="<?php echo base_url(); ?>">In&iacute;cio</a> &nbsp;/&nbsp; <span>Paciente</span>
        </div>

        <div class="row-fluid">
		<!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
                <?php echo $this->session->flashdata('status'); ?>
                <?php
                $data = array('class'=>'pure-form','id'=>'psq_paciente');
            	echo form_open('paciente/iniPesquisa',$data);
                    $attributes = array('id' => 'address_info', 'class' => 'address_info');
                    
                    $botaoCadastrar = (in_array(20, $this->session->userdata('permissoes')))? '<span id="spanCustomTitle"><a href="'.base_url("paciente/ficha").'">Cadastrar</a><i onclick="location.href='.base_url("paciente/ficha").'" class="general foundicon-plus icon iconeClick"></i></span>': '';
                    
            		echo form_fieldset("Pesquisar".$botaoCadastrar, $attributes);
            		
            			echo form_label('Nome ou CPF', 'dados_paciente');
            			$data = array('name'=>'dados_paciente','id'=>'dados_paciente', 'placeholder'=>'Digite o nome ou CPF', 'class'=>'input-block-level');
            			echo form_input($data);
            			
                        echo '<div class="actions">';
            			echo form_submit("btn_cadastro","Pesquisar", 'class="btn btn-primary pull-right"');
                        echo '</div>';
            		echo form_fieldset_close();
            	echo form_close();
                
            	echo "<div class='table-responsive' id='resPesquisa'>";
            			if($pesquisa == 'sim'){
            				if($qtdPacientes[0]->total == 0){
            					echo "<span class='semRegistro'>Nenhum paciente encontrado!</span>";
            				}else{
            					$dadosPacientes = array();
            					foreach($pacientes as $paciente){
            						
                                    $botaoEditar = (in_array(20, $this->session->userdata('permissoes')))? '<a href="'.base_url('paciente/ficha/'.$paciente->matricula_paciente).'"><i title="Editar" class="general foundicon-edit icon tamIcone"></i></a>': '';
                                    $botaoExcluir = (in_array(21, $this->session->userdata('permissoes')))? '<a data-toggle="modal" onclick="remover('.$paciente->matricula_paciente.',\''.$paciente->nome_paciente.'\')" href="#removerPaciente"><i title="Remover" class="general foundicon-remove icon tamIcone"></i></a>': '';
                                    
                                    
            						$push = array(
            							$paciente->matricula_paciente,
            							$paciente->nome_paciente,
            							$paciente->tel_fix_paciente,
            							$paciente->tel_cel_paciente,
            							/*anchor(
            								base_url('paciente/ficha/' . $paciente->matricula_paciente),
            								'<i title="Editar" class="general foundicon-edit icon tamIcone"></i>',
            								#array('onclick' => "return confirm('Você deseja desmarcar a consulta?')")
                                            array('title' => "Editar ficha do paciente")
            							)*/
                                        $botaoEditar.$botaoExcluir
            						);
            						array_push($dadosPacientes, $push);
            					} # Fecha foreach
            					
            					$this->table->set_heading('Matr&iacute;cula', 'Nome', 'Tel. Fixo', 'Tel. Celular', 'A&ccedil;&atilde;o');
            					$template = array('table_open' => '<table class="table table-bordered">');
            					$this->table->set_template($template);
            					echo $this->table->generate($dadosPacientes);
            				}
            				echo "<div class='pagination'><ul>" . $paginacao . "</ul></div>";
            			}
            	echo "</div>";                
                
                ?>
            </div>
			<!--End Main Content Area here-->
			
			

<script type="text/javascript">

function remover(matricula, nome){
    $("#excluirPaciente").val(matricula);
    $("#nomeRemocao").val(nome);
    
}

$(document).ready(function(){

	$("#dados_paciente").keyup(function(){
	  
		//Verificar se o campo o nome nao e vazio
		if($(this).val() != ""){

			//Fazer requisição assincrona entre pagina
			$.post(
				'<?php echo base_url(); ?>ajax/pesquisaPaciente',
				{
					dados_paciente : $(this).val()
				},
				function(resultado){
					$("#resPesquisa").html(resultado);
				}
			);

		}else{
		  
            $("#resPesquisa").html('');
          
		}

	});
});

$("#dados_paciente").autocomplete({
	source: '<?php echo base_url(); ?>ajax/pesquisaPacienteAutoComplete',
    minLength: 2,
    select: function( event, ui ) {
        window.location.href = '<?php echo base_url(); ?>paciente/iniPesquisa/'+ui.item.value;
    }
});

</script>