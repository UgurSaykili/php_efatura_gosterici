<?php

//Ayarlar
	$klasor_yolu=dirname( __FILE__ );
	$onbellek_klasoru=dirname( __FILE__ )."/onbellek";
//Ayarlar




/***************************    Yüklenen Dosya Kontrolü    ****************************/
        include 'dosya_kontrol_islemleri.php';
		$FaturaXmlDosyasi = file_get_contents($onbellek_klasoru."/".$yuklenendosyaadi."/".$hedefdosyaadi.".xml");
		
		
/******************************    EFatura Göster     ***********************************/		
		include 'efaturagoster.php';
		$FaturaXslDosyasi= FaturaXslDosyasiOlustur($FaturaXmlDosyasi);
		$FaturaHtmlDosyasi=FaturaHtmlDosyasiOlustur($FaturaXmlDosyasi);
		
		echo $FaturaHtmlDosyasi;
		

/******************************    Sertifika Kaydet     ***********************************/	
        $SertifikaDosyaAdiPEM = $onbellek_klasoru."/".$yuklenendosyaadi."/".$hedefdosyaadi.".pem";
        SertifikaKaydetPEM($FaturaXmlDosyasi,$SertifikaDosyaAdiPEM);
		
	 $SertifikaDosyaAdiDER = $onbellek_klasoru."/".$yuklenendosyaadi."/".$hedefdosyaadi.".der";
        SertifikaKaydetDER($FaturaXmlDosyasi,$SertifikaDosyaAdiDER);


/******************************    Önbellek Temizle     ***********************************/		
        $onbellek_temizleme_suresi= "1";  //dakikada
		include 'onbellek_temizle.php';

?>
