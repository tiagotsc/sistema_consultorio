<?php 
foreach($telefoneCliente as $tC){
    $tel[] = $tC->numero_telefone_cliente;
} 
?>
    <div id="footerOuterSeparator"></div>

    <div id="divFooter" class="footerArea">

        <div class="divPanel">

            <div class="row-fluid">
                <div class="span12" id="footerArea1">
                    
                    <h3><?php echo $cliente[0]->nome_cliente; ?></h3>

                    <p>
                        <?php echo $cliente[0]->endereco_cliente; ?>, 
                        <?php echo $cliente[0]->numero_endereco_cliente; ?>
                        <?php echo $cliente[0]->comp_endereco_cliente; ?>
                        Bairro: <?php echo $cliente[0]->bairro_cliente; ?> - 
                        CEP: <?php echo $cliente[0]->cep_cliente; ?>
                        CNPJ: <?php echo $cliente[0]->cpf_cnpj_cliente; ?>
                        <br/>Telefones: <?php echo implode(' / ', $tel); ?>
                    </p>
                        
                    <!--<p>Bairro: Copacabana - CEP: 20931-785</p>-->
                    <!--
                    <p> 
                        <a href="#" title="Terms of Use">Terms of Use</a><br />
                        <a href="#" title="Privacy Policy">Privacy Policy</a><br />
                        <a href="#" title="FAQ">FAQ</a><br />
                        <a href="#" title="Sitemap">Sitemap</a>
                    </p>
                    -->
                </div>
                <!--<div class="span3" id="footerArea2">

                    <h3>Recent Blog Posts</h3> 
                    <p>
                        <a href="#" title="">Lorem Ipsum is simply dummy text</a><br />
                        <span style="text-transform:none;">2 hours ago</span>
                    </p>
                    <p>
                        <a href="#" title="">Duis mollis, est non commodo luctus</a><br />
                        <span style="text-transform:none;">5 hours ago</span>
                    </p>
                    <p>
                        <a href="#" title="">Maecenas sed diam eget risus varius</a><br />
                        <span style="text-transform:none;">19 hours ago</span>
                    </p>
                    <p>
                        <a href="#" title="">VIEW ALL POSTS</a>
                    </p>

                </div>
                <div class="span3" id="footerArea3">

                    <h3>Sample Content</h3> 
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s. 
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s.
                    </p>

                </div>
                <div class="span3" id="footerArea4">

                    <h3>Get in Touch</h3>  
                                                               
                    <ul id="contact-info">
                    <li>                                    
                        <i class="general foundicon-phone icon"></i>
                        <span class="field">Phone:</span>
                        <br />
                        (123) 456 7890 / 456 7891                                                                      
                    </li>
                    <li>
                        <i class="general foundicon-mail icon"></i>
                        <span class="field">Email:</span>
                        <br />
                        <a href="mailto:info@yourdomain.com" title="Email">info@yourdomain.com</a>
                    </li>
                    <li>
                        <i class="general foundicon-home icon" style="margin-bottom:50px"></i>
                        <span class="field">Address:</span>
                        <br />
                        123 Street<br />
                        12345 City, State<br />
                        Country
                    </li>
                    </ul>

                </div>-->
            </div> <!-- FECHA <div class="row-fluid"> -->
<!--
            <br /><br />
            <div class="row-fluid">
                <div class="span12">
                    <p class="copyright">
                        Copyright © 2013 Your Company. All Rights Reserved.
                    </p>

                    <p class="social_bookmarks">
                        <a href="#"><i class="social foundicon-facebook"></i> Facebook</a>
			<a href=""><i class="social foundicon-twitter"></i> Twitter</a>
			<a href="#"><i class="social foundicon-pinterest"></i> Pinterest</a>
			<a href="#"><i class="social foundicon-rss"></i> Rss</a>
                    </p>
                </div>
            </div>
-->
        </div> <!-- FECHA <div class="divPanel"> -->
    </div> <!-- FECHA <div id="divFooter" class="footerArea"> -->