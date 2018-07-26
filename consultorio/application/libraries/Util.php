<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
setlocale(LC_ALL, 'pt_BR.UTF-8');
/**
 * Util
 * 
 * Formata e valida diversas informações
 * 
 * @package   
 * @author Tiago Silva Costa
 * @copyright Boomer
 * @version 2014
 * @access public
 */
class Util{
	
    private $menuCompleto = '';
    private $paisMenu = array();
    
    
	/**
	 * Util::formaValorBanco()
	 * 
     * Formata os valores para que possam ser trabalhadas no banco de dados
     * 
	 * @param mixed $valor
	 * @return
	 */
	public function formaValorBanco($valor){
		
		#strtoupper();
		
		if(empty($valor)){
			$valor = 'null';
		}elseif(preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $valor)){ # DATA
			$valor = "'".$this->formataData($valor, 'USA')."'";
		 }elseif(preg_match('/^[0-9]+[.,]{1}[0-9]{2}$/',$valor)){ # NUMÉRICO (PONTO FLUTUANTE)
			$valor = "'".preg_replace('/,/', '.', $valor)."'";
		 }elseif(preg_match('/^[0-9]+$/', $valor)){ # INTEIRO
			$valor = $valor;
		 }else{ # STRING
			 $valor = "'".$valor."'";
		 }
		
		return $valor;
	}
	
	/**
	 * Util::removeAcentos()
	 * 
     * Remove os acentos da string
     * 
	 * @param mixed $string
	 * @return
	 */
	public function removeAcentos($string) {
	   
        $string = htmlentities($string, ENT_COMPAT, 'UTF-8');
        $string = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/', '$1',$string);

		/*$string = preg_replace("/[ÁÀÂÃÄáàâãä]/", "a", $string);
		$string = preg_replace("/[ÉÈÊéèê]/", "e", $string);
		$string = preg_replace("/[ÍÌíì]/", "i", $string);
		$string = preg_replace("/[ÓÒÔÕÖóòôõö]/", "o", $string);
		$string = preg_replace("/[ÚÙÜúùü]/", "u", $string);
		$string = preg_replace("/[Çç]/", "c", $string);
		$string = preg_replace("/[][><}{;,!?*%~^`&#]/", "", $string);*/
		#$string = preg_replace("/[][><}{)(:;,!?*%~^`&#@]/", "", $string);
		#$string = preg_replace("/ /", "_", $string);
		#$string = strtolower($string);
		
		return $string;
		
	}
	
	/**
	 * Util::formataData()
	 * 
     * Formata a data no formato do banco ou brasileiro
     * 
	 * @param mixed $data
	 * @param mixed $tipo
	 * @return
	 */
	public function formataData($data, $tipo){
	
		if($tipo == 'USA'){
			
			$data = implode('-',array_reverse(explode('/', $data)));
			
		}else{
		
			$data = implode('/',array_reverse(explode('-', $data)));
		
		}
	
		return $data;
	
	}
    
    /**
     * Util::montaMenu()
     * 
     * Monta o menu
     * 
     * @param mixed $menus
     * @param mixed $paisMenu
     * @return
     */
    public function montaMenu($menus, $paisMenu){
 
        foreach($paisMenu as $pM){
            $pais[] = $pM['pai_menu'];
        }

        $this->paisMenu = $pais;

        foreach($menus as $me){
            
            $menuItens[$me->pai_menu][$me->cd_menu] = array('link' => $me->link_menu,'nome' => $me->nome_menu);
            
        }
        
        return $this->loopMenu($menuItens);
        
    }
    
    /**
     * Util::loopMenu()
     * 
     * Auxilia na montagem do menu
     * 
     * @param mixed $menuTotal
     * @param integer $idPai
     * @param string $filho
     * @return
     */
    public function loopMenu(array $menuTotal , $idPai = 0, $filho = 'nao'){
        
        if($filho == 'nao'){ # Se não é filho define class pai
            $classUl = 'class="nav nav-pills ddmenu"';
        }else{ # Se é filho define classe filho
            $classUl = 'class="dropdown-menu"';
        }
        
        $this->menuCompleto .= '<ul '.$classUl.'>';
   
        foreach( $menuTotal[$idPai] as $idMenu => $menuItem){
            
            if(in_array($idMenu, $this->paisMenu)){ # É filho então configura filho
                $classLi = 'class="dropdown"';
                $link = '#';
                $classLinkPai = 'class="dropdown-toggle"';
                $auxLinkPai = '<b class="caret"></b>';
            }else{ # Configura pai
                $classLi = '';
                $link = base_url($menuItem['link']);
                $classLinkPai = '';
                $auxLinkPai = '';
            }
            
            # Verifica se o link esta presente na url atual
            #if(stripos($_SERVER['REDIRECT_QUERY_STRING'], $menuItem['link']) !== false){
            if($_SERVER['REDIRECT_QUERY_STRING'] == $menuItem['link']){
                $ativo = 'class="active"';
            }else{
                $ativo = '';
            }
          
            $this->menuCompleto .= '<li '.$ativo.' '.$classLi.'>';
            
            $this->menuCompleto .= '<a href="'.$link.'" '.$classLinkPai.'>'.htmlentities($menuItem['nome']).$auxLinkPai.'</a>';

            if( isset( $menuTotal[$idMenu] ) ) $this->loopMenu($menuTotal,$idMenu, 'sim');
            
            $this->menuCompleto .= '</li>';
            
        }
        
        $this->menuCompleto .= '</ul>';
        
        return $this->menuCompleto;
        
    }
    
    /**
     * Util::mesExtenso()
     * 
     * Retorna o mês por extenso
     * 
     * @param mixed $mes
     * @return
     */
    public function mesExtenso($mes){
        
        $meses['01'] = 'janeiro';
        $meses['02'] = 'fevereiro';
        $meses['03'] = utf8_decode('março');
        $meses['04'] = 'abril';
        $meses['05'] = 'maio';
        $meses['06'] = 'junho';
        $meses['07'] = 'julho';
        $meses['08'] = 'agosto';
        $meses['09'] = 'setembro';
        $meses['10'] = 'outubro';
        $meses['11'] = 'novembro';
        $meses['12'] = 'dezembro';
        
        return $meses[$mes];
        
    }
    
    /**
     * Util::intervaloHoras()
     * 
     * Retorna todos os horários encontrados encima dos minutos informados
     * 
     * @param mixed $horaInicio Hora inicial para iniciar intervalos
     * @param mixed $horaFim Hora fim para iniciar intervalos
     * @param mixed $minutos Minutos que será utilizados para gerar intervalos
     * @return Horários encontrados dentro do intervalos
     */
    public function intervaloHoras($horaInicio, $horaFim, $minutos){
    
        //Hora início
        $inicio 		= new DateTime($horaInicio);
     
        //Hora fim
        $fim 		= new DateTime($horaFim);
     
        //Pega todos os horários/intervalos de acordo com os minutos informados
        $horarios = array();
        while($inicio <= $fim){
            $horarios[] = $inicio->format('H:i');
            $inicio = $inicio->modify('+ '.$minutos.' minutes');
        }
        
        return $horarios;
    
    }
    
    /**
     * Util::diferencaHora()
     * 
     * Retorna a diferença das horas informadas
     * 
     * @param mixed $horaInicio Hora inicial para subtração
     * @param mixed $horaFim Hora final para subatração
     * @return A direfença das horas
     */
    public function diferencaHora($horaInicio, $horaFim){
         
        // Converte as duas datas para um objeto DateTime do PHP
        // PARA O PHP 5.3 OU SUPERIOR
        $inicio = DateTime::createFromFormat('H:i:s', $horaInicio);
        // PARA O PHP 5.2
        // $inicio = date_create_from_format('H:i:s', $inicio);
         
        $fim = DateTime::createFromFormat('H:i:s', $horaFim);
        // $fim = date_create_from_format('H:i:s', $fim);
     
        $intervalo = $inicio->diff($fim);
     
        // Formata a diferença de horas para
        // aparecer no formato 00:00:00 na página
        return $intervalo->format('%H:%I:%S');
    
    }
    
    /**
     * Util::montaPermissao()
     * 
     * Monta a árvore de permissões
     * 
     * @param mixed $permissoes Todas permissões
     * @param mixed $paiPermissoes Pai das permissões
     * @param bool $permissoesUsuario Permissões que o usuário pussui
     * @return
     */
    public function montaPermissao($permissoes, $paiPermissoes, $permissoesUsuario = false){
        
        foreach($paiPermissoes as $paiP){
            
            $perm[] = $paiP['pai_permissao'];
            
        }
        
        $this->paiPermissao = $perm;
        
        foreach($permissoes as $permi){
            
            $permItem[$permi->pai_permissao][$permi->cd_permissao] = array('nome'=>$permi->nome_permissao);
            
        }
        
        return $this->loopPermissoes($permItem, 0, 'nao', $permissoesUsuario);
    
    }
    
    /**
     * Util::loopPermissoes()
     * 
     * Auxilida a montagem das permissões
     * 
     * @param mixed $permissoesTotal
     * @param integer $idPai
     * @param string $filho
     * @param bool $permissoesUsuario
     * @return
     */
    public function loopPermissoes(array $permissoesTotal , $idPai = 0, $filho = 'nao', $permissoesUsuario = false){
        
        $this->permissoesCompleto .= '<ul id="idPermissoes">';
   
        foreach( $permissoesTotal[$idPai] as $idPermissao => $permissaoItem){
            
            //if(in_array($idPermissao, $this->paiPermissao)){ # Se não é filho define class pai
            if(preg_match('/^MENU/', $permissaoItem['nome'])){
                
                $this->controlaClass++;
            
                $sequencia = $this->controlaClass;
            
                $classUl = 'class="classItem'.$this->controlaClass.'"';
                $marcaTodos = 'onclick="marcaGrupo(\'.classItem'.$this->controlaClass.'\', this)"';
                
            }else{ # Se é filho define classe filho
            
                $classUl = 'class="classItem'.$this->controlaClass.'"';
                $marcaTodos = '';
            }
            
            if(in_array($idPermissao, $permissoesUsuario)){
                $marcado = 'checked';
            }else{
                $marcado = ''; 
            }
          
            $this->permissoesCompleto .= '<li>';
            
            $this->permissoesCompleto .= '<label>';
            $this->permissoesCompleto .= '<input '.$marcado.' type="checkbox" '.$marcaTodos.' '.$classUl.' name="permissao[]" value="'.$idPermissao.'" />&nbsp';
            $this->permissoesCompleto .= $permissaoItem['nome'];
            $this->permissoesCompleto .= '</label>';

            if( isset( $permissoesTotal[$idPermissao] ) ) $this->loopPermissoes($permissoesTotal,$idPermissao, 'sim', $permissoesUsuario);
            
            $this->permissoesCompleto .= '</li>';
            
        }
        
        $this->permissoesCompleto .= '</ul>';
        
        #$this->controlaClass++;
        
        return $this->permissoesCompleto;
        
    }

}