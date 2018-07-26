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

<?php

            echo $this->session->flashdata('statusOperacao');
            $data = array('class'=>'pure-form','id'=>'salvar_perfil');
            echo form_open('perfil/salvarPerfil',$data);
                $attributes = array('id' => 'address_info', 'class' => 'address_info');
            
            	echo form_fieldset("Dados do perfil", $attributes);
            	
                    echo '<div class="row">';
                    
                        echo '<div class="span8">';
                        echo form_label('Nome do perfil', 'nome_perfil');
            			$data = array('name'=>'nome_perfil', 'value'=>$nome_perfil,'id'=>'nome_perfil', 'placeholder'=>'Digite o nome', 'class'=>'input-block-level');
            			echo form_input($data);
                        echo '</div>';
                        
                        echo '<div class="span4">';
                        $options = array('A' => 'Ativo', 'I' => 'Inativo');		
                		echo form_label('Status<span class="obrigatorio">*</span>', 'status_perfil');
                		echo form_dropdown('status_perfil', $options, $status_perfil, 'id="status_perfil" class="input-block-level"');
                        echo '</div>';
                    
                    echo '</div>';
                    echo '<div class="row">';
                    echo '<a href="'.base_url('perfil').'"class="btn btn-primary pull-left">Voltar</a>';                                
                    
                    echo form_submit("btn_cadastro","Salvar", 'style="margin-top: 2px" class="btn btn-primary pull-right"');
                    echo '</div>';
                         
                    echo form_hidden('cd_perfil', $cd_perfil);
                    
                    echo '<div id="divPermissoes">';     
                    echo '<label>';
                    echo '<input onclick="marcaTodos()" type="checkbox" id="todos" />';
                    echo '&nbspMARCAR / DESMARCAR TODOS</label>';
                    echo $permissoes;
                    echo '</div>';     
                                                
            	echo form_fieldset_close();
            echo form_close(); 

?>

            </div>
			<!--End Main Content Area here-->

<script type="text/javascript">

$(document).ready(function(){
    
    // Valida o formulário
	$("#salvar_perfil").validate({
		debug: false,
		rules: {
			nome_perfil: {
                required: true
            }
		},
		messages: {
			nome_perfil: {
                required: "Digite um nome para o perfil."
            }
	   }
   });   
   
});

function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    alert(out);
}

function marcaTodos(){
    
    if($('#todos').prop('checked') == true){
        $('input:checkbox').prop('checked', true);
    }else{
        $('input:checkbox').prop('checked', false);
    }
    
}

function marcaGrupo(classe, campo){
    
    if(campo.checked == true){
        $(classe).prop('checked', true);
    }else{
        $(classe).prop('checked', false);
    }

}

$(document).ready(function(){
    
    $(".data").mask("00/00/0000");
    
    $(".actions").click(function() {
        $('#aguarde').css({display:"block"});
    });
});

</script>			
			