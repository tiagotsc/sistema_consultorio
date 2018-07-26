<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>

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

$data = array('class'=>'pure-form','id'=>'frm_funcionario');
echo form_open_multipart('funcionario/salvar',$data);
	echo form_fieldset("Dados do funcion&aacute;rio");
    echo '<div class="row-fluid">';
    
        echo '<div class="span6">';
        
		echo form_label('Matr&iacute;cula', 'matricula_funcionario');
		$data = array('name'=>'matricula_funcionario','id'=>'matricula_funcionario','value'=>$matricula_funcionario, 'class'=>'input-block-level');
		echo form_input($data,'','readonly');
		
		echo form_label('Nome<span class="obrigatorio">*</span>', 'nome_funcionario');
		$data = array('name'=>'nome_funcionario','id'=>'nome_funcionario','value'=>$nome_funcionario, 'placeholder'=>'Digite o nome', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
        
        echo form_label('Data de nascimento<span class="obrigatorio">*</span>', 'data_nascimento_funcionario');
		$data = array('name'=>'data_nascimento_funcionario','id'=>'data_nascimento_funcionario','value'=>$data_nascimento_funcionario, 'placeholder'=>'Digite a data de nascimento', 'class'=>'input-block-level', 'maxlength'=>'10');
		echo form_input($data);
        
        $options = array('' => '');		
		foreach($perfil as $per){
			$options[$per->cd_perfil] = $per->nome_perfil;
		}
		
		echo form_label('Perfil<span class="obrigatorio">*</span>', 'cd_perfil_funcionario');
		echo form_dropdown('cd_perfil_funcionario', $options, $cd_perfil_funcionario, 'id="cd_perfil_funcionario" class="input-block-level"');
		
        $options = array('' => '', 'SIM' => 'SIM', 'NAO' => 'N&Atilde;O');		
		
		echo form_label('&Eacute; m&eacute;dico?<span class="obrigatorio">*</span>', 'medico_funcionario');
		echo form_dropdown('medico_funcionario', $options, $medico_funcionario, 'id="medico_funcionario" class="input-block-level"');
        
        $options = array('' => '');		
		foreach($especialidade as $esp){
			$options[$esp->cd_especialidade] = $esp->nome_especialidade;
		}
		
        #echo '<div id="todasEspecialidades">';
        echo '<a id="addEspecialidade" href="#addEspecialidade">Adicionar especialidade</a>';
        
        if($especialidadesMedico){
            
            $cont = 0;
            foreach($especialidadesMedico as $espMed){
            
                if($cont == 0){
        		  echo form_label('Especialidade<span class="obrigatorio">*</span>', 'cd_especialidade['.$cont.']');
                }
        		echo form_dropdown('cd_especialidade['.$cont.']', $options, $espMed->cd_especialidade, 'id="cd_especialidade['.$cont.']" class="input-block-level"');
            
                $cont++;
            }
            
        }else{
            
                echo form_label('Especialidade<span class="obrigatorio">*</span>', 'cd_especialidade[0]');
        		echo form_dropdown('cd_especialidade[0]', $options, '', 'id="cd_especialidade[0]" class="input-block-level"');
            
        }
        
        
        echo '<div id="especialidades"></div>';
        #echo '</div>';
        
        $options = array();	# LIMPA ARRAY	
		foreach($status as $sta){
			$options[$sta->sigla_status] = $sta->nome_status;
		}
		
		echo form_label('Status', 'sigla_status_funcionario');
		echo form_dropdown('sigla_status_funcionario', $options, $sigla_status_funcionario, 'id="sigla_status_funcionario" class="input-block-level"');
		
        
		$options = array('' => '');		
		foreach($sexo as $sex){
			$options[$sex->sigla_sexo] = $sex->nome_sexo;
		}
		
		echo form_label('Sexo<span class="obrigatorio">*</span>', 'sigla_sexo_funcionario');
		echo form_dropdown('sigla_sexo_funcionario', $options, $sigla_sexo_funcionario, 'id="sigla_sexo_funcionario" class="input-block-level"');
		
        echo form_label('CPF<span class="obrigatorio">*</span>', 'cpf_funcionario');
		$data = array('name'=>'cpf_funcionario','id'=>'cpf_funcionario','value'=>$cpf_funcionario, 'placeholder'=>'Digite o CPF', 'class'=>'input-block-level', 'maxlength'=>'11');
		echo form_input($data);
        
        echo '</div> <!--FECHA DIV SPAN6-->';
        echo '<div class="span6">';
        
        echo form_label('Telefone Fixo', 'tel_fix_funcionario');
		$data = array('name'=>'tel_fix_funcionario','id'=>'tel_fix_funcionario','value'=>$tel_fix_funcionario, 'placeholder'=>'Digite o telefone fixo', 'class'=>'input-block-level', 'maxlength'=>'16');
		echo form_input($data);
        
        echo form_label('Telefone Celular', 'tel_cel_funcionario');
		$data = array('name'=>'tel_cel_funcionario','id'=>'tel_cel_funcionario','value'=>$tel_cel_funcionario, 'placeholder'=>'Digite o telefone celular', 'class'=>'input-block-level', 'maxlength'=>'16');
		echo form_input($data);
        
        echo form_label('CEP<span class="obrigatorio">*</span>', 'cep_funcionario');
		$data = array('name'=>'cep_funcionario','id'=>'cep_funcionario','value'=>$cep_funcionario, 'placeholder'=>'Digite o CEP', 'class'=>'input-block-level', 'maxlength'=>'10');
		echo form_input($data);
        
        echo form_label('Endere&ccedil;o<span class="obrigatorio">*</span>', 'endereco_funcionario');
		$data = array('name'=>'endereco_funcionario','id'=>'endereco_funcionario','value'=>$endereco_funcionario, 'placeholder'=>'Digite o endere&ccedil;o', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
        
        echo form_label('N&uacute;mero<span class="obrigatorio">*</span>', 'numero_funcionario');
		$data = array('name'=>'numero_funcionario','id'=>'numero_funcionario','value'=>$numero_funcionario, 'placeholder'=>'Digite o n&uacute;mero', 'class'=>'input-block-level', 'maxlength'=>'30');
		echo form_input($data);
        
        echo form_label('Bairro<span class="obrigatorio">*</span>', 'bairro_funcionario');
		$data = array('name'=>'bairro_funcionario','id'=>'bairro_funcionario','value'=>$bairro_funcionario, 'placeholder'=>'Digite o bairro', 'class'=>'input-block-level', 'maxlength'=>'80');
		echo form_input($data);
        
		$options = array('' => '');		
		foreach($estado as $est){
			$options[$est->sigla_estado] = $est->nome_estado;
		}
        
		echo form_label('estado', 'estado_funcionario');
		echo form_dropdown('estado_funcionario', $options, $estado_funcionario, 'id="estado_funcionario" class="input-block-level"');
        
        echo form_label('E-mail', 'email_funcionario');
		$data = array('name'=>'email_funcionario','id'=>'email_funcionario','value'=>$email_funcionario, 'placeholder'=>'Digite o e-mail', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
        
	    echo '</div><!--FECHA DIV SPAN6 -->';
        
    echo '</div><!--FECHA DIV FLUID-->';	
        #echo '<div class="actions">';
        echo '<a href="'.base_url('funcionario').'" class="btn btn-primary pull-left">Voltar</a>';
        #echo '</div>';
        
        echo '<div class="actions">';
		echo form_submit("btn_salvar",$botao, 'class="btn btn-primary pull-right"');
        echo '</div>';
        
        
	echo form_fieldset_close();
echo form_close();
?>

            </div>
			<!--End Main Content Area here-->

<script type="text/javascript">
$(document).ready(function(){
	//$('#cpf_funcionario').mask('000.000.000-00', {reverse: true});
    //$('#cpf_funcionario').mask('00.000.000-a');
    $('#data_nascimento_funcionario').mask('00/00/0000');
	$('#tel_fix_funcionario').mask('(00)0000-0000');
	$('#tel_cel_funcionario').mask('(00)00000-0000');
	$('#cep_funcionario').mask('00000-000');
    $('#cpf_funcionario').mask('000.000.000-00');
    
    $("#addEspecialidade").click(function(){
        
        var comboEspec = '<label>';
        comboEspec += '<select id="cd_especialidade[]" name="cd_especialidade[]">';
        <?php
        foreach($especialidade as $esp){
			#$options[$esp->cd_especialidade] = $esp->nome_especialidade;
        ?>
        comboEspec += '<option value="<?php echo $esp->cd_especialidade; ?>"><?php echo $esp->nome_especialidade; ?></option>';
        <?php
        }
        ?>
        comboEspec += '</select>';
        comboEspec += '<a class="removeEspec" onclick="$(this).parent().remove();" href="#addEspecialidade">&nbspRemover</a>';
        comboEspec += '</label>';

        $("#especialidades").append(comboEspec);
    });
    
    $("#addEspecialidade").hide();
    
    if($("#medico_funcionario").val() == 'SIM'){
        
        $("#addEspecialidade").show();
        $('label[for="cd_especialidade[0]"]').show();
        $('label[for="name^=cd_especialidade"]').show();
        $("[name^=cd_especialidade]").show();
        //$("#todasEspecialidades").show();
        
    }else{
        
        $("#addEspecialidade").hide();
        $('label[for="cd_especialidade[0]"]').hide();
        $('label[for="name^=cd_especialidade"]').hide();
        $("[name^=cd_especialidade]").hide();
        //$("#todasEspecialidades").hide();
        
    }
	
    $("#medico_funcionario").change(function(){
        
        if($(this).val() == 'SIM'){
            
            $("#addEspecialidade").show();
            $('label[for="cd_especialidade[0]"]').show();
            $('label[for="name^=cd_especialidade"]').show();
            //$("[name^=cd_especialidade]").parent().show();
            $("[name^=cd_especialidade]").show();
            $(".removeEspec").show();
            
        }else{
            
            $("#addEspecialidade").hide();
            $('label[for="cd_especialidade[0]"]').hide();
            $('label[for="name^=cd_especialidade"]').hide();
            
            //$("[name^=cd_especialidade]").parent().hide();
            $("[name^=cd_especialidade]").hide();
            //$('#cd_especialidade').hide();
            //$('#cd_especialidade').val('');
            $("[name^=cd_especialidade]").val('');
            $(".removeEspec").hide();
            
        }
        
    });
    
    // Valida o formulário do funcionario
	$("#frm_funcionario").validate({
		debug: false,
		rules: {
			nome_funcionario: {
                required: true,
                minlength: 10
            },
            data_nascimento_funcionario: {
                required: true,
                minlength: 10
            },
            sigla_sexo_funcionario: "required",
            
            "cd_especialidade[0]": {
                required: function(element) {
                    return $("#medico_funcionario").val() == 'SIM';
                }
            },
            
            nacionalidade_funcionario: "required",
            cpf_funcionario: "required",
            medico_funcionario: "required",
            cd_perfil_funcionario: "required",
            cep_funcionario: {
                required: true,
                minlength: 9
                            
            },
            endereco_funcionario: "required",
            numero_funcionario: "required",
            bairro_funcionario: "required",
			email_funcionario: {
				/*required: true,*/
				email: true
			}
		},
		messages: {
			nome_funcionario: {
                required: "Preencha o nome.",
                minlength: "Digite o nome completo."
            },
            data_nascimento_funcionario: {
                required: "Preencha a data de nascimento.",
                minlength: "Digite a data completa."
            },
            sigla_sexo_funcionario: "Selecione o sexo.",
            
            "cd_especialidade[0]": "Selecione a especialidade.",
            
            nacionalidade_funcionario: "Selecione a nacionalidade.",
            cpf_funcionario: "Preencha o CPF.",
            medico_funcionario: "O funcion&aacute;rio &eacute; m&eacute;dico?",
            cd_perfil_funcionario: "Selecione o perfil.",
            cep_funcionario: {
                required: "Preencha o CEP.",
                minlength: "Digite o CEP completo"
            },
            endereco_funcionario: "Preencha o endere&ccedil;o.",
            numero_funcionario: "Preencha o n&uacute;mero.",
            bairro_funcionario: "Preencha o bairro.",
			email_funcionario: {
				/*required: "Este campo é obrigatório e possuí mensagem diferente do campo nome.",*/
				email: "E-mail inv&aacute;lido.",
		   }
	   }
   });
   
   // Busca o endereço de acordo com o CEP
   $("#cep_funcionario").keyup(function(){
	   /*
       //Verificar se o campo o nome nao e vazio
		if($(this).val().length == 9){
		 
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>ajax/pegaEndereco',
              data: {
                cep: $(this).val()
              },
              dataType: "json",
              //error: function(res) {
 	            //$("#resMarcar").html('<span>Erro de execução</span>');
              //},
              success: function(res) {
                
                $("#endereco_funcionario").val(res[0].tipo_logradouro + ' ' + res[0].logradouro);
                $("#bairro_funcionario").val(res[0].bairro);
                $("#estado_funcionario").val(res[0].uf);
              }
            });  
         
		}*/
        $.ajax({
              type: "POST",
              //url: '<?php echo base_url(); ?>ajax/pegaEndereco',
              url: 'http://clareslab.com.br/ws/cep/json/'+$(this).val(),              
              data: {
                cep: $(this).val()
              },
              dataType: "json",
              /*error: function(res) {
 	            $("#resMarcar").html('<span>Erro de execução</span>');
              },*/
              success: function(res) {
                
                $("#endereco_funcionario").val(res.endereco);
                $("#bairro_funcionario").val(res.bairro);
                $("#estado_funcionario").val(res.uf);

                if($("#cep_funcionario").val().length > 8){
                    
                    $("#numero_funcionario").focus();
                    
                }
                
              }
            }); 
        
   }); 
   
});
</script>			
			