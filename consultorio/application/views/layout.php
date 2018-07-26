<?php
    $menu = (!empty($menu))? $menu: '';
    $agenda = (!empty($agenda))? $agenda: '';
    $rodape = (!empty($rodape))? $rodape: '';
?>
<?php echo $html_header; ?>
<?php echo $menu; ?>
<?php echo $corpo; ?>
<?php echo $agenda; ?>
<?php echo $rodape; ?>
<?php echo $html_footer; ?>