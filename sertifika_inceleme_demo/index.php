<?php

	        $FaturaXmlDosyasi=file_get_contents( dirname( __FILE__ )."/fatura.xml" );
	
		include 'efaturagoster.php';
                $FaturaSertifikaBilgisi=FaturaSertifikaBilgisiOlustur($FaturaXmlDosyasi);

		echo $FaturaSertifikaBilgisi;



/******************************    Sertifika Kaydet     ***********************************/	
            $SertifikaDosyaAdiPEM = dirname( __FILE__ )."/sertifika.pem";
            SertifikaKaydetPEM($FaturaXmlDosyasi,$SertifikaDosyaAdiPEM);
		
	    $SertifikaDosyaAdiDER = dirname( __FILE__ )."/sertifika.der";
            SertifikaKaydetDER($FaturaXmlDosyasi,$SertifikaDosyaAdiDER);


?>
