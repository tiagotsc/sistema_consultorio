<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>

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

$data = array('class'=>'pure-form','id'=>'frm_paciente');
echo form_open_multipart('paciente/salvar'.$direct,$data);
	echo form_fieldset("Ficha do paciente");
    echo '<div class="row-fluid">';
    
        echo '<div class="span6">';
        
		echo form_label('Matr&iacute;cula', 'matricula_paciente');
		$data = array('name'=>'matricula_paciente','id'=>'matricula_paciente','value'=>$matricula_paciente, 'class'=>'input-block-level');
		echo form_input($data,'','readonly');
		
		echo form_label('Nome<span class="obrigatorio">*</span>', 'nome_paciente');
		$data = array('name'=>'nome_paciente','id'=>'nome_paciente','value'=>$nome_paciente, 'placeholder'=>'Digite o nome', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
		
		$options = array('' => '');		
		foreach($sexo as $sex){
			$options[$sex->sigla_sexo] = $sex->nome_sexo;
		}
		
		echo form_label('Sexo<span class="obrigatorio">*</span>', 'sigla_sexo_paciente');
		echo form_dropdown('sigla_sexo_paciente', $options, $sigla_sexo_paciente, 'id="sigla_sexo_paciente" class="input-block-level"');
		
		$options = array('' => '');		
		foreach($nacionalidade as $nac){
			$options[$nac->sigla_nacionalidade] = $nac->nome_nacionalidade;
		}
		
		echo form_label('Nacionalidade<span class="obrigatorio">*</span>', 'nacionalidade_paciente');
		echo form_dropdown('nacionalidade_paciente', $options, $nacionalidade_paciente, 'id="nacionalidade_paciente" class="input-block-level"');
        
        echo form_label('RG<span class="obrigatorio">*</span>', 'rg_paciente');
		$data = array('name'=>'rg_paciente','id'=>'rg_paciente','value'=>$rg_paciente, 'placeholder'=>'Digite o RG', 'class'=>'input-block-level', 'maxlength'=>'11');
		echo form_input($data);
		
		echo form_label('E-mail', 'email_paciente');
		$data = array('name'=>'email_paciente','id'=>'email_paciente','value'=>$email_paciente, 'placeholder'=>'Digite o e-mail', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
        
        echo '</div> <!--FECHA DIV SPAN6-->';
        echo '<div class="span6">';
        
        echo form_label('Telefone Fixo', 'tel_fix_paciente');
		$data = array('name'=>'tel_fix_paciente','id'=>'tel_fix_paciente','value'=>$tel_fix_paciente, 'placeholder'=>'Digite o telefone fixo', 'class'=>'input-block-level', 'maxlength'=>'16');
		echo form_input($data);
        
        echo form_label('Telefone Celular', 'tel_cel_paciente');
		$data = array('name'=>'tel_cel_paciente','id'=>'tel_cel_paciente','value'=>$tel_cel_paciente, 'placeholder'=>'Digite o telefone celular', 'class'=>'input-block-level', 'maxlength'=>'16');
		echo form_input($data);
        
        echo form_label('CEP<span class="obrigatorio">*</span>', 'cep_paciente');
		$data = array('name'=>'cep_paciente','id'=>'cep_paciente','value'=>$cep_paciente, 'placeholder'=>'Digite o CEP', 'class'=>'input-block-level', 'maxlength'=>'10');
		echo form_input($data);
        
        echo form_label('Endere&ccedil;o<span class="obrigatorio">*</span>', 'endereco_paciente');
		$data = array('name'=>'endereco_paciente','id'=>'endereco_paciente','value'=>$endereco_paciente, 'placeholder'=>'Digite o endere&ccedil;o', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
        
        echo form_label('N&uacute;mero<span class="obrigatorio">*</span>', 'numero_paciente');
		$data = array('name'=>'numero_paciente','id'=>'numero_paciente','value'=>$numero_paciente, 'placeholder'=>'Digite o n&uacute;mero', 'class'=>'input-block-level', 'maxlength'=>'30');
		echo form_input($data);
        
        echo form_label('Bairro<span class="obrigatorio">*</span>', 'bairro_paciente');
		$data = array('name'=>'bairro_paciente','id'=>'bairro_paciente','value'=>$bairro_paciente, 'placeholder'=>'Digite o bairro', 'class'=>'input-block-level', 'maxlength'=>'80');
		echo form_input($data);
        
		$options = array('' => '');		
		foreach($estado as $est){
			$options[$est->sigla_estado] = $est->nome_estado;
		}
        
		echo form_label('estado', 'estado_paciente');
		echo form_dropdown('estado_paciente', $options, $estado_paciente, 'id="estado_paciente" class="input-block-level"');
        
	    echo '</div><!--FECHA DIV SPAN6 -->';
        
        echo form_hidden('novo_paciente','NAO');
        
    echo '</div><!--FECHA DIV FLUID-->';	
        #echo '<div class="actions">';
        echo '<a href="'.base_url('paciente').'" class="btn btn-primary pull-left">Voltar</a>';
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
	//$('#rg_paciente').mask('000.000.000-00', {reverse: true});
    //$('#rg_paciente').mask('00.000.000-a');
	$('#tel_fix_paciente').mask('(00)0000-0000');
	$('#tel_cel_paciente').mask('(00)00000-0000');
	$('#cep_paciente').mask('00000-000');
    $('#rg_paciente').mask('00000###########');
	
    // Valida o formulário do paciente
	$("#frm_paciente").validate({
		debug: false,
		rules: {
			nome_paciente: {
                required: true,
                minlength: 10
            },
            sigla_sexo_paciente: "required",
            nacionalidade_paciente: "required",
            rg_paciente: "required",
            cep_paciente: {
                required: true,
                minlength: 9
                            
            },
            endereco_paciente: "required",
            numero_paciente: "required",
            bairro_paciente: "required",
			email_paciente: {
				/*required: true,*/
				email: true
			}
		},
		messages: {
			nome_paciente: {
                required: "Preencha o nome.",
                minlength: "Digite o nome completo"
            },
            sigla_sexo_paciente: "Selecione o sexo.",
            nacionalidade_paciente: "Selecione a nacionalidade.",
            rg_paciente: "Preencha o RG.",
            cep_paciente: {
                required: "Preencha o CEP.",
                minlength: "Digite o CEP completo"
            },
            endereco_paciente: "Preencha o endere&ccedil;o.",
            numero_paciente: "Preencha o n&uacute;mero.",
            bairro_paciente: "Preencha o bairro.",
			email_paciente: {
				/*required: "Este campo é obrigatório e possuí mensagem diferente do campo nome.",*/
				email: "E-mail inv&aacute;lido.",
		   }
	   }
   });
   
   // Busca o endereço de acordo com o CEP
   $("#cep_paciente").keyup(function(){
	   
       //Verificar se o campo o nome nao e vazio
		if($(this).val().length == 9){
		 /*
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
                
                $("#endereco_paciente").val(res[0].tipo_logradouro + ' ' + res[0].logradouro);
                $("#bairro_paciente").val(res[0].bairro);
                $("#estado_paciente").val(res[0].uf);
              }
            });  
         */
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
                
                $("#endereco_paciente").val(res.endereco);
                $("#bairro_paciente").val(res.bairro);
                $("#estado_paciente").val(res.uf);
                
                if($("#cep_paciente").val().length > 8){
                    
                    $("#numero_paciente").focus();
                    
                }
                
              }
            }); 
		}
        
   }); 
   
});
</script>			
			