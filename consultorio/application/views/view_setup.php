<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.validate.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.mask.min.js") ?>"></script>

<div class="contentArea">

    <div class="divPanel notop page-content">
        <div id="divLogo" class="pull-left">
                    <a href="<?php echo base_url(); ?>" id="divSiteTitle">Consult&oacute;rio Online</a><br />
                    <a href="<?php echo base_url(); ?>" id="divTagLine">Facilitando a sua vida</a>
                </div>

        <div class="row-fluid">
		<!--Edit Main Content Area here-->
            <div class="span7" id="divMain">
            
                <div class="<?php echo $this->session->flashdata('class'); ?>"><?php echo $this->session->flashdata('status'); ?></div>
<?php

$data = array('class'=>'pure-form','id'=>'frm_instalar');
echo form_open('setup/processaInstacao',$data);
	echo form_fieldset(utf8_encode("Instalação do sistema"));
    echo '<div class="row-fluid">';
    
        echo '<div class="span6">';
        
		echo form_label('Nome:', 'nome_cliente');
		$data = array('name'=>'nome_cliente','id'=>'nome_cliente','value'=>'', 'placeholder'=>'Digite o nome da empresa', 'class'=>'input-block-level');
		echo form_input($data,'');
		
		echo form_label('CEP:<span class="obrigatorio">*</span>', 'cep_cliente');
		$data = array('name'=>'cep_cliente','id'=>'cep_cliente','value'=>'', 'placeholder'=>'Digite o CEP', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
        
        echo form_label('Endere&ccedil;o:<span class="obrigatorio">*</span>', 'endereco_cliente');
		$data = array('name'=>'endereco_cliente','id'=>'endereco_cliente','value'=>'', 'placeholder'=>'Digite o endere&ccedil;o', 'class'=>'input-block-level');
		echo form_input($data);

        echo form_label('N&uacute;mero:<span class="obrigatorio">*</span>', 'numero_endereco_cliente');
		$data = array('name'=>'numero_endereco_cliente','id'=>'numero_endereco_cliente','value'=>'', 'placeholder'=>'Digite o n&uacute;mero', 'class'=>'input-block-level', 'maxlength'=>'11');
		echo form_input($data);
		
		echo form_label('Bairro:<span class="obrigatorio">*</span>', 'bairro_cliente');
		$data = array('name'=>'bairro_cliente','id'=>'bairro_cliente','value'=>'', 'placeholder'=>'Digite o bairro', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
        
        echo '</div> <!--FECHA DIV SPAN6-->';
        echo '<div class="span6">';
        
        echo form_label('Cidade:<span class="obrigatorio">*</span>', 'cidade_cliente');
		$data = array('name'=>'cidade_cliente','id'=>'cidade_cliente','value'=>'', 'placeholder'=>'Digite a cidade', 'class'=>'input-block-level', 'maxlength'=>'16');
		echo form_input($data);
        
        echo form_label('Complemento:', 'comp_endereco_cliente');
		$data = array('name'=>'comp_endereco_cliente','id'=>'comp_endereco_cliente','value'=>'', 'placeholder'=>'Digite o complemento', 'class'=>'input-block-level', 'maxlength'=>'200');
		echo form_input($data);
        
        echo '<a href="#" id="add_telefone">Adicionar telefone</a>';
        
        echo form_label('Telefone:<span class="obrigatorio">*</span>', 'telefone_cliente');
		$data = array('name'=>'telefone_cliente[]','id'=>'telefone_cliente[]','value'=>'', 'placeholder'=>'Digite o telefone', 'class'=>'input-block-level', 'maxlength'=>'16');
		echo form_input($data);
        
        echo '<div id="telefones"></div>';
        
        $options = array(
                    0 => '',
                    1 => 'CPF',
                    2 => 'CNPJ'
                    );
		
		echo form_label('Tipo documento<span class="obrigatorio">*</span>', 'tipo_documento_cliente');
		echo form_dropdown('tipo_documento_cliente', $options, '', 'id="tipo_documento_cliente" class="input-block-level"');
		
        
        echo form_label('<span id="cpf_cnpj"></span>', 'cpf_cnpj_cliente');
		$data = array('name'=>'cpf_cnpj_cliente','id'=>'cpf_cnpj_cliente','value'=>'', 'placeholder'=>'Digite o n&uacute;mero', 'class'=>'input-block-level');
		echo form_input($data);
        
	    echo '</div><!--FECHA DIV SPAN6 -->';
        
    echo '</div><!--FECHA DIV FLUID-->';	

        echo '<div class="actions">';
		echo form_submit("btn_instalar",'Instalar', 'class="btn btn-primary pull-right"');
        echo '</div>';
        
        
	echo form_fieldset_close();
echo form_close();
?>

            </div>
			<!--End Main Content Area here-->

<script type="text/javascript">

function maskTelefone(){
    $("input[name='telefone_cliente[]']").mask("(00)00000-0000");
}

$(document).ready(function(){
    
    $('#cpf_cnpj_cliente').hide();
    
	//$('#cpf_funcionario').mask('000.000.000-00', {reverse: true});
    //$('#cpf_funcionario').mask('00.000.000-a');
    $('#data_nascimento_funcionario').mask('00/00/0000');
	$('#tel_fix_funcionario').mask('(00)0000-0000');
	$('#tel_cel_funcionario').mask('(00)00000-0000');
	$('#cep_cliente').mask('00000-000');
    //$('#cpf_funcionario').mask('000.000.000-00');
    
    $("input[name='telefone_cliente[]']").mask("(00)00000-0000");
    
    $("#add_telefone").click(function(){
        
        var inputTel = '<label><input onkeypress="maskTelefone()" type="text" name="telefone_cliente[]" placeholder="Digite o telefone" maxlength="16" />';
        inputTel += '<a onclick="$(this).parent().remove();" href="#" class="removeTel">&nbspRemover</a></label>';
        
        $("#telefones").append(inputTel);
    });
    
    $("#tipo_documento_cliente").change(function(){
        
        if($(this).val() == 0){
            
            $("#cpf_cnpj").html('');
            $('#cpf_cnpj_cliente').val('');
            $('#cpf_cnpj_cliente').hide();
            
        }
        
        if($(this).val() == 1){
            
            $("#cpf_cnpj").html('CPF:<span class="obrigatorio">*</span>');
            $('#cpf_cnpj_cliente').val('');
            $('#cpf_cnpj_cliente').show();
            $('#cpf_cnpj_cliente').mask('000.000.000-00');
            
        }
        
        if($(this).val() == 2){
            
            $("#cpf_cnpj").html('CNPJ:<span class="obrigatorio">*</span>');
            $('#cpf_cnpj_cliente').val('');
            $('#cpf_cnpj_cliente').show();
            $('#cpf_cnpj_cliente').mask('99.999.999/9999-99');
            
        }
        
    });
    
    // Valida o formulário do funcionario
	$("#frm_instalar").validate({
		debug: false,
		rules: {
			nome_cliente: {
                required: true
            },
            cep_cliente: {
                required: true,
                minlength: 9
            },
            endereco_cliente: "required",
            numero_endereco_cliente: "required",
            bairro_cliente: "required",
            cidade_cliente: "required",
            "telefone_cliente[]": "required",
            cnpj_cliente: "required"
		},
		messages: {
			nome_cliente: {
                required: "Preencha o nome."
            },
            cep_cliente: {
                required: "Preencha o CEP.",
                minlength: "Digite o CEP completo."
            },
            endereco_cliente: "Preencha o endere&ccedil;o.",
            
            numero_endereco_cliente: "Digite o n&uacute;mero do endere&ccedil;o.",
            bairro_cliente: "Digite o bairro.",
            cidade_cliente: "Digite a cidade.",
            "telefone_cliente[]": "Digite o telefone.",
            cnpj_cliente: "Digite o CNPJ."
	   }
   });
   
   // Busca o endereço de acordo com o CEP
   $("#cep_cliente").keyup(function(){
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
                
                $("#endereco_cliente").val(res.endereco);
                $("#bairro_cliente").val(res.bairro);
                $("#cidade_cliente").val(res.cidade);
                $("#estado_cliente").val(res.uf);
              }
            }); 
        
   }); 
   
});
</script>			
			