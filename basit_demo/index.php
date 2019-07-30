<?php

	    $FaturaXmlDosyasi=file_get_contents( dirname( __FILE__ )."/fatura.xml" );
	
		include 'efaturagoster.php';
		$FaturaHtmlDosyasi=FaturaHtmlDosyasiOlustur($FaturaXmlDosyasi);
		
		echo $FaturaHtmlDosyasi;
?>
