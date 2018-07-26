<?php
echo doctype('html5');
echo "<html>";
echo "<head>";
echo "<title>" . $titulo . "</title>";
	#echo meta('Content-type', 'text/html; charset=utf-8', 'equiv');
	$meta = array(
	    array('name' => 'robots', 'content' => 'no-cache'),
	    array('name' => 'description', 'content' => $descricao),
	    array('name' => 'keywords', 'content' => $palavras_chave),
	    array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')
	);
	echo meta($meta); 	
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">'; # TORNA O LAYOUT FLUIDO	
        
	echo link_tag(array('href' => 'assets/bootstrap/css/bootstrap.min.css','rel' => 'stylesheet','type' => 'text/css'));
    echo link_tag(array('href' => 'assets/bootstrap/css/bootstrap-responsive.min.css','rel' => 'stylesheet','type' => 'text/css'));
    
    echo link_tag(array('href' => 'assets/fontawesome/css/font-awesome.min.css','rel' => 'stylesheet'));
    
    echo link_tag(array('href' => 'assets/icons/general/stylesheets/general_foundicons.css', 'media'=>'screen', 'rel' => 'stylesheet','type' => 'text/css'));
    echo link_tag(array('href' => 'assets/icons/social/stylesheets/social_foundicons.css', 'media'=>'screen', 'rel' => 'stylesheet','type' => 'text/css'));
	
    echo link_tag(array('href' => 'http://fonts.googleapis.com/css?family=Syncopate','rel' => 'stylesheet','type' => 'text/css'));
    echo link_tag(array('href' => 'http://fonts.googleapis.com/css?family=Abel','rel' => 'stylesheet','type' => 'text/css'));
    echo link_tag(array('href' => 'http://fonts.googleapis.com/css?family=Source+Sans+Pro','rel' => 'stylesheet','type' => 'text/css'));
    echo link_tag(array('href' => 'http://fonts.googleapis.com/css?family=Open+Sans','rel' => 'stylesheet','type' => 'text/css'));
    echo link_tag(array('href' => 'http://fonts.googleapis.com/css?family=Pontano+Sans','rel' => 'stylesheet','type' => 'text/css'));
    echo link_tag(array('href' => 'http://fonts.googleapis.com/css?family=Oxygen','rel' => 'stylesheet','type' => 'text/css'));
    
    echo link_tag(array('href' => 'http://fonts.googleapis.com/css?family=Economica','rel' => 'stylesheet','type' => 'text/css')); #CALENDÁRIO
    echo link_tag(array('href' => 'assets/css_calendario/responsive-calendar.css','rel' => 'stylesheet','type' => 'text/css')); #CALENDÁRIO
    
    echo link_tag(array('href' => 'assets/custom/custom.css','rel' => 'stylesheet','type' => 'text/css'));
    
    echo "<script type='text/javascript' src='".base_url('assets/js/jquery.js')."'></script>";
    
    echo "<script type='text/javascript' src='".base_url('assets/bootstrap/js/bootstrap.min.js')."'></script>";
    echo "<script type='text/javascript' src='".base_url('assets/default.js')."'></script>";
    echo "<script type='text/javascript' src='".base_url('assets/js/responsive-calendar.js')."'></script>";
    
    echo "<script type='text/javascript' src='".base_url('assets/popupmodal/js/bootstrap-modalmanager.js')."'></script>"; #POPUP
    echo "<script type='text/javascript' src='".base_url('assets/popupmodal/js/bootstrap-modal.js')."'></script>"; #POPUP
    echo link_tag(array('href' => 'assets/popupmodal/css/bootstrap-modal.css','rel' => 'stylesheet','type' => 'text/css')); #POPUP
    
    echo link_tag(array('href' => 'assets/js/jquery-ui/jquery-ui.css','rel' => 'stylesheet','type' => 'text/css'));
    echo "<script type='text/javascript' src='".base_url("assets/js/jquery-ui/jquery-ui.js")."'></script>";
    
    echo link_tag(array('href' => 'assets/custom/meu_estilo.css','rel' => 'stylesheet','type' => 'text/css')); # CRIADO POR TIAGO
    
echo "</head>";
echo '<body id="pageBody">';
    echo '<div id="divBoxed" class="container">';
    
            echo '<div class="transparent-bg" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;z-index: -1;zoom: 1;"></div>';