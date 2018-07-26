<div class="divPanel notop nobottom">
        <div class="row-fluid">
            <div class="span12">

                <div id="divLogo" class="pull-left">
                    <a href="#" id="divSiteTitle">Consult&oacute;rio Online</a><br />
                    <a href="#" id="divTagLine">Facilitando a sua vida</a>
                </div>

                <div id="divMenuRight" class="pull-right">
					<div class="navbar">
                    <button type="button" class="btn btn-navbar-highlight btn-large btn-primary" data-toggle="collapse" data-target=".nav-collapse">
                    NAVEGA&Ccedil;&Atilde;O <span class="icon-chevron-down icon-white"></span>
                    </button>
                        <div class="nav-collapse collapse">
                            <!--<ul class="nav nav-pills ddmenu">
                                <li><a href="<?php echo base_url(); ?>">In&iacute;cio</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle">Agenda<b class="caret"></b></a>
                                    <ul class="dropdown-menu">                            
                                        <li><a href="<?php echo base_url('agenda/medico'); ?>">M&eacute;dico</a></li>
                                        <li><a href="<?php echo base_url('agenda/secretaria'); ?>">Secret&aacute;ria</a></li>
                                    </ul>
                                </li>    
        					    <li class="active"><a href="<?php echo base_url("paciente"); ?>">Paciente</a></li>
        					    <li><a href="<?php echo base_url("funcionario"); ?>">Funcion&aacute;rio</a></li>						    
                                <li><a href="<?php echo base_url("home/logout"); ?>">Sair</a></li>
                            </ul>-->
                            <?php echo $menu; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div id="contentInnerSeparator"></div>
            </div>
        </div>
</div>

<div id="usuario">Ol&aacute;! <?php echo $this->session->userdata('nome'); ?></div>