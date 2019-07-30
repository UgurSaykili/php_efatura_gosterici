<?php

		$FaturaXmlDosyasi = file_get_contents($_FILES["zip_file"]["tmp_name"]);

			
		include 'efaturagoster.php';
		$FaturaXslDosyasi= FaturaXslDosyasiOlustur($FaturaXmlDosyasi);
		$FaturaHtmlDosyasi=FaturaHtmlDosyasiOlustur($FaturaXmlDosyasi);
		
		echo $FaturaHtmlDosyasi;

?>