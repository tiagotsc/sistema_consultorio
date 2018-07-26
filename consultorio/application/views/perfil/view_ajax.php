<?php
if($pesquisa == 'normal'){
    if($qtdPacientes[0]->total == 0){
    	echo "<span class='semRegistro'>Nenhum paciente encontrado!</span>";
    }else{
    	$dadosPacientes = array();
    	foreach($pacientes as $paciente){
    		
    		$push = array(
    			$paciente->matricula_paciente,
    			$paciente->nome_paciente,
    			$paciente->tel_fix_paciente,
    			$paciente->tel_cel_paciente,
    			'<a href="'.base_url('paciente/ficha/'.$paciente->matricula_paciente).'">
                    <i title="Editar" class="general foundicon-edit icon tamIcone"></i>
                </a>
                <a data-toggle="modal" onclick="remover('.$paciente->matricula_paciente.',\''.$paciente->nome_paciente.'\')" href="#removerPaciente">
                    <i title="Remover" class="general foundicon-remove icon tamIcone"></i>
                </a>'
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

if($pesquisa == 'autoComplete'){
    
    $this->output->set_header('Content-Type: application/json; charset=utf-8');
    #$teste = array('aaaa','bbbb','cccc');
    echo str_ireplace('nome_paciente', 'value', json_encode($pacientes));
    
}