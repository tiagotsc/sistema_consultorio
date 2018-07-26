<?php
if($pesquisa == 'normal'){
    if($qtdFuncionarios[0]->total == 0){
    	echo "<span class='semRegistro'>Nenhum funcion&aacute;rio encontrado!</span>";
    }else{
    	$dadosFuncionario = array();
    	foreach($funcionarios as $funcionario){
    		
    		$push = array(
    			$funcionario->matricula_funcionario,
    			$funcionario->nome_funcionario,
    			$funcionario->nome_status,
    			$funcionario->nome_perfil_funcionario,
    			'<a href="'.base_url('funcionario/ficha/'.$funcionario->matricula_funcionario).'">
                    <i title="Editar" class="general foundicon-edit icon tamIcone"></i>
                </a>
                <a data-toggle="modal" onclick="remover(\''.$funcionario->matricula_funcionario.'\',\''.$funcionario->nome_funcionario.'\')" href="#removerFuncionario">
                    <i title="Remover" class="general foundicon-remove icon tamIcone"></i>
                </a>'
    		);
    		array_push($dadosFuncionario, $push);
    	} # Fecha foreach
    	
    	$this->table->set_heading('Matr&iacute;cula', 'Nome', 'Status', 'Perfil', 'A&ccedil;&atilde;o');
    	$template = array('table_open' => '<table class="table table-bordered">');
    	$this->table->set_template($template);
    	echo $this->table->generate($dadosFuncionario);
    }
    echo "<div class='pagination'><ul>" . $paginacao . "</ul></div>";
}

if($pesquisa == 'autoComplete'){
    
    $this->output->set_header('Content-Type: application/json; charset=utf-8');
    #$teste = array('aaaa','bbbb','cccc');
    echo str_ireplace('nome_funcionario', 'value', json_encode($funcionarios));
    
}