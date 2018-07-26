<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>

<!-- INÍCIO Modal de Editar Situação da Consulta -->
<div id="alterarSenha" class="modal hide fade" tabindex="-1" data-width="360">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Alterar senha</h3>
  </div>
  <div class="modal-body">
    <div class="row-fluid">
        <?php              
            $data = array('class'=>'pure-form','id'=>'frmAlterarSenha');
            echo form_open('home/alteraSenha',$data);
            
                echo form_label('CPF<span class="obrigatorio">*</span>', 'cpf_funcionario');
        		$data = array('name'=>'cpf_funcionario','id'=>'cpf_funcionario', 'placeholder'=>'Digite o cpf', 'class'=>'input-block-level', 'maxlength'=>'200');
        		echo form_input($data);
                
                echo form_label('Data de nascimento<span class="obrigatorio">*</span>', 'data_nascimento_funcionario');
        		$data = array('name'=>'data_nascimento_funcionario','id'=>'data_nascimento_funcionario', 'placeholder'=>'Digite a data de nascimento', 'class'=>'input-block-level', 'maxlength'=>'10');
        		echo form_input($data);
                
                echo form_label('Nova senha<span class="obrigatorio">*</span>', 'nova_senha');
        		$data = array('name'=>'nova_senha','id'=>'nova_senha', 'placeholder'=>'Digite a nova senha', 'class'=>'input-block-level', 'maxlength'=>'11');
        		echo form_password($data);
                
                echo form_label('Repita nova senha<span class="obrigatorio">*</span>', 'repete_nova_senha');
        		$data = array('name'=>'repete_nova_senha','id'=>'repete_nova_senha', 'placeholder'=>'Repita a nova senha', 'class'=>'input-block-level', 'maxlength'=>'11');
        		echo form_password($data);
        ?>        
    </div>
  </div>
  <div class="modal-footer">
    <button onclick="limpaModalAlterarSituacao()" name="cancelar" type="button" data-dismiss="modal" class="btn">Cancelar</button> 
    <input type="submit" id="btn_alterar" name="btn_alterar" class="btn btn-primary pull-right" value="Alterar" />      
  </div>
        <?php
        echo form_close();
        ?>
</div>
<!-- FIM Modal de Marcar e Editar Consulta -->

<div align="center" class="contentArea"> <!-- CRIAR ESTILO PARA ALINHAR AO CENTRO -->

<div class="divPanel notop page-content">

    <div class="row-fluid">
		
        <div class="<?php echo $this->session->flashdata('class'); ?>"><?php echo $this->session->flashdata('status'); ?></div>
        
		<!--Edit Sidebar Content here-->
        <div id="div_principal_login" class="span6 sidebar">

            <div class="sidebox">
				
				<div class="span12">
				
					<div id="logo_login"> 
						<a id="divSiteTitle">CONSULTÓRIO ONLINE</a><br />
						<a id="divTagLine">Facilitando a sua vida</a>
					</div>
					
				</div>
				<br><br><br>
			
				<div class="span8" id="div_form_login">
				<form id="form_login" method="post" action="home/autentica">
				
					<label>
						Login:
						<input type="text" id="login" name="login" class="input-block-level" />
					</label>
					
					<label>
						Senha:
						<input type="password" id="senha" name="senha" class="input-block-level" />
					</label>
					
					<a id="link_lembra_senha" data-toggle="modal" href="#alterarSenha">Alterar senha</a>
					<input id="btn_logar" class="btn btn-primary pull-right" type="submit" value="Logar" />
					
				</form>	
				</div>
			</div>
			
            </div>
            
        </div>
		<!--End Sidebar Content here-->
    </div>

    <div id="footerInnerSeparator"></div>
	<div class="lead" id="div_login_desenvolvedor"><a href="mailto:tiagotsc@oi.com.br">Desenvolvido por: Tiago Silva Costa</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#cpf_funcionario').mask('000.000.000-00', {reverse: true});
    $('#data_nascimento_funcionario').mask('00/00/0000');
    
    // Valida o formulário
	$("#frmAlterarSenha").validate({
		debug: false,
		rules: {
            cpf_funcionario: "required",
            data_nascimento_funcionario: "required",
            nova_senha :{
                required: true,
                minlength: 6
            },
			repete_nova_senha:{
				equalTo: "#nova_senha"
		    }
		},
		messages: {
            cpf_funcionario: "Preencha o CPF.",
            data_nascimento_funcionario: "Preencha a data de nascimento.",
            nova_senha :{
                required: "Informe uma nova senha",
                minlength: "Digite 6 caracteres ou mais"
            },
    		repete_nova_senha :"A senha esta diferente"
	   }
   });    

});
</script>