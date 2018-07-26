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
                $data = array('class'=>'pure-form','id'=>'psq_funcionario');
            	echo form_open('funcionario/iniPesquisa',$data);
                    $attributes = array('id' => 'address_info', 'class' => 'address_info');
                    
                    $botaoCadastrar = (in_array(18, $this->session->userdata('permissoes')))? '<span id="spanCustomTitle"><a href="'.base_url("funcionario/ficha").'">Cadastrar</a><i onclick="location.href='.base_url("funcionario/ficha").'" class="general foundicon-plus icon iconeClick"></i></span>': '';
                    
            		echo form_fieldset("Pesquisar".$botaoCadastrar, $attributes);
            		
            			echo form_label('Nome ou CPF', 'dados_funcionario');
            			$data = array('name'=>'dados_funcionario','id'=>'dados_funcionario', 'placeholder'=>'Digite o nome ou CPF', 'class'=>'input-block-level');
            			echo form_input($data);
            			
                        echo '<div class="actions">';
            			echo form_submit("btn_cadastro","Pesquisar", 'class="btn btn-primary pull-right"');
                        echo '</div>';
            		echo form_fieldset_close();
            	echo form_close();
                
            	echo "<div class='table-responsive' id='resPesquisa'>";
            			if($pesquisa == 'sim'){
            				if($qtdFuncionarios[0]->total == 0){
            					echo "<span class='semRegistro'>Nenhum funcion&aacute;rio encontrado!</span>";
            				}else{
            					$dadosFuncionarios = array();
            					foreach($funcionarios as $funcionario){
            						
                                    $botaoEditar = (in_array(18, $this->session->userdata('permissoes')))? '<a href="'.base_url('funcionario/ficha/'.$funcionario->matricula_funcionario).'"><i title="Editar" class="general foundicon-edit icon tamIcone"></i></a>': '';
                                    $botaoExcluir = (in_array(19, $this->session->userdata('permissoes')))? '<a data-toggle="modal" onclick="remover(\''.$funcionario->matricula_funcionario.'\',\''.$funcionario->nome_funcionario.'\',\''.$funcionario->sigla_perfil_funcionario.'\')" href="#removerFuncionario"><i title="Remover" class="general foundicon-remove icon tamIcone"></i></a>': '';
                                    
            						$push = array(
            							$funcionario->matricula_funcionario,
            							$funcionario->nome_funcionario,
            							$funcionario->nome_status,
            							$funcionario->nome_perfil,
            							/*anchor(
            								base_url('funcionario/ficha/' . $funcionario->matricula_funcionario),
            								'<i title="Editar" class="general foundicon-edit icon tamIcone"></i>',
            								#array('onclick' => "return confirm('Você deseja desmarcar a consulta?')")
                                            array('title' => "Editar ficha do funcionario")
            							)*/
                                        $botaoEditar.$botaoExcluir
            						);
            						array_push($dadosFuncionarios, $push);
            					} # Fecha foreach
            					
            					$this->table->set_heading('Matr&iacute;cula', 'Nome', 'Status', 'Perfil', 'A&ccedil;&atilde;o');
            					$template = array('table_open' => '<table class="table table-bordered">');
            					$this->table->set_template($template);
            					echo $this->table->generate($dadosFuncionarios);
            				}
            				echo "<div class='pagination'><ul>" . $paginacao . "</ul></div>";
            			}
            	echo "</div>";                
                
                ?>
            </div>
			<!--End Main Content Area here-->
			
			

<script type="text/javascript">

function remover(matricula, nome, tipo){

    $("#excluirFuncionario").val(matricula);
    $("#nomeRemocao").val(nome);
    if(tipo == 'ME'){
        $("#alertaApaga").show();
        $("#alertaApaga").html('<h4><strong>Aten&ccedil;&atilde;o!</strong></h4> As consultas associadas a esse m&eacute;dico ser&atilde;o apagadas tamb&eacute;m.');
    }else{
        $("#alertaApaga").hide();
        $("#alertaApaga").html('');
    }
}

$(document).ready(function(){

	$("#dados_funcionario").keyup(function(){
	  
		//Verificar se o campo o nome nao e vazio
		if($(this).val() != ""){

			//Fazer requisição assincrona entre pagina
			$.post(
				'<?php echo base_url(); ?>ajax/pesquisaFuncionario',
				{
					dados_funcionario : $(this).val()
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

$("#dados_funcionario").autocomplete({
	source: '<?php echo base_url(); ?>ajax/pesquisaFuncionarioAutoComplete',
    minLength: 2,
    select: function( event, ui ) {
        window.location.href = '<?php echo base_url(); ?>funcionario/iniPesquisa/'+ui.item.value;
    }
});

</script>