<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
/*
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=exceldata.xls");
header("Pragma: no-cache");
header("Expires: 0");
*/

/** Error reporting */
#error_reporting(E_ALL);
#ini_set('memory_limit','100M');
#ini_set('display_errors', TRUE);
#ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Sao_Paulo');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');


#require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once('assets/PHPExcel/Classes/PHPExcel.php');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Sistema Consultorio Online")
							 ->setLastModifiedBy("Sistema Consultorio Online")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

# Controla a posição das colunas
$contCampo = 0;

# Estiliza a primeira coluna em negrito
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

if($campos <> ''){
    
    # Cria as colunas títulos do excel
    foreach($campos as $campo){ 
        
        # Estiliza a coluna em negrito
        $objPHPExcel->getActiveSheet()->getStyle($contCampo)->getFont()->setBold(true);
        
        # Cria a coluna
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($contCampo, 1,  $campo);
        
        # Próxima coluna
        $contCampo++;
    }
    
}
# Conteúdo a partir da segunda linha
$linha = 2;

if($valores <> ''){

    # Controla o campo da linha
    $contCampoLinha = 0;
    foreach($valores as $valor){ # Alimenta as colunas com o conteúdo
        
        # Remove o negrito
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(false);
        
        # Remove o negrito
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(false);
        foreach($campos as $campo){
        
            # Estiliza removendo o negrito
            $objPHPExcel->getActiveSheet()->getStyle($contCampoLinha)->getFont()->setBold(false);
            
            # Adiciona o conteúdo
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($contCampoLinha, $linha,  $valor[$campo]);
            
            # Segue pra próxima coluna
            $contCampoLinha++;
        }
    
        # Retorno a primeira coluna    
        $contCampoLinha = 0;
    
        # Avança pra próxima linha
        $linha++;    
    }

}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('relatorio');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="relatorio.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;

?>