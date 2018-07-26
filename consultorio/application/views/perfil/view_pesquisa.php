<!-- INÍCIO Modal Definitions (tabbed over for <pre>) -->
<div id="removerPerfil" class="modal hide fade" tabindex="-1" data-width="400">
<?php               
$data = array('class'=>'pure-form','id'=>'remover_perfil');
echo form_open('perfil/apagaPerfil',$data);
?>
  <div class="modal-body">
    <div class="row-fluid">
        <strong>Desejar remover o perfil?</strong>
        <input type="hidden" id="apg_cd_perfil" name="apg_cd_perfil" />
        <input type="text" readonly="true" id="apg_nome_perfil" name="apg_nome_perfil" class="input-block-level" />
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
            <a href="<?php echo base_url(); ?>">In&iacute;cio</a> &nbsp;/&nbsp; <span>Perfil</span>
        </div>

        <div class="row-fluid">
		<!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
                <?php 
                echo $this->session->flashdata('statusOperacao');
                $data = array('class'=>'pure-form','id'=>'pesquisa_perfil');
            	echo form_open('perfil/pesquisarPerfil',$data);
                    $attributes = array('id' => 'address_info', 'class' => 'address_info');
                    
                    $botaoCadastrar = (in_array(16, $this->session->userdata('permissoes')))? '<span id="spanCustomTitle"><a href="'.base_url("perfil/fichaPerfil").'">Cadastrar</a><i onclick="location.href='.base_url("perfil/fichaPerfil").'" class="general foundicon-plus icon iconeClick"></i></span>': '';
                    
            		echo form_fieldset("Pesquisar".$botaoCadastrar, $attributes);
            		
                        echo '<div class="row-fluid">';
                            echo '<div class="span8">';
                    			echo form_label('Nome', 'nome_perfil');
                    			$data = array('name'=>'nome_perfil','id'=>'nome_perfil', 'placeholder'=>'Digite o nome', 'class'=>'input-block-level');
                    			echo form_input($data);
                            echo '</div>';
                            echo '<div class="span4">';
                                $options = array(''=>'', 'A' => 'Ativo', 'I' => 'Inativo');		
                        		echo form_label('Status', 'status_perfil');
                        		echo form_dropdown('status_perfil', $options, $postStatus, 'id="status_perfil" class="input-block-level"');
                            echo '</div>';
                        echo '</div>';
            			
                        echo '<div class="actions">';
            			echo form_submit("btn_cadastro","Pesquisar", 'class="btn btn-primary pull-right"');
                        echo '</div>';
            		echo form_fieldset_close();
            	echo form_close();
                
            	echo "<div class='table-responsive' id='resPesquisa'>";
            			if($pesquisa == 'sim'){
            				if((bool)$perfis == false){
            					echo "<span class='semRegistro'>Nenhum perfil encontrado!</span>";
            				}else{
            					
                                $this->table->set_heading('Cd', 'Nome', 'Status', 'Ação');
                                            
                                foreach($perfis as $per){
                                    
                                    $cell1 = array('data' => $per->cd_perfil);
                                    $cell2 = array('data' => utf8_decode($per->nome_perfil));
                                    $cell3 = array('data' => $per->status_perfil);
                                    
                                    $botaoEditar = (in_array(16, $this->session->userdata('permissoes')))? '<a title="Editar" href="'.base_url('perfil/fichaPerfil/'.$per->cd_perfil).'"><i title="Editar" class="general foundicon-edit icon tamIcone"></i></a>': '';
                                    $botaoExcluir = (in_array(17, $this->session->userdata('permissoes')))? '<a title="Apagar" href="#removerPerfil" onclick="apagarRegistro('.$per->cd_perfil.',\''.$per->nome_perfil.'\')" data-toggle="modal"><i title="Remover" class="general foundicon-remove icon tamIcone"></i></a>': '';
                                    
                                    $cell4 = array('data' => $botaoEditar.$botaoExcluir);
                                        
                                    $this->table->add_row($cell1, $cell2, $cell3, $cell4);
                                    
                                }
                                
                            	$template = array('table_open' => '<table class="table table-bordered">');
                            	$this->table->set_template($template);
                            	echo $this->table->generate();
                                #echo "<ul class='pagination pagination-lg'>" . $paginacao . "</ul>"; 
                                                                
            				}
            				echo "<div class='pagination'><ul>" . $paginacao . "</ul></div>";
            			}
            	echo "</div>";                
                
                ?>
            </div>
			<!--End Main Content Area here-->
			
			

<script type="text/javascript">

function apagarRegistro(cd, nome){
    $("#apg_cd_perfil").val(cd);
    $("#apg_nome_perfil").val(nome);
}

$(document).ready(function(){

});

</script>