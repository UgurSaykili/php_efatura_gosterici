<?php




/************************ Eski Dosyaları Sil************************************/	
$tmp_dosya_turleri      = '*.xml';
$tmp_dosya_omru    = $onbellek_temizleme_suresi;

foreach (glob($onbellek_klasoru. '/*/'.$tmp_dosya_turleri ) as $tmp_dosya_adi)  
	{
		$dosya_olusturma_zamani = filemtime($tmp_dosya_adi);
		$dosya_yasi = time() - $dosya_olusturma_zamani; 
		
		if ($dosya_yasi > ($tmp_dosya_omru * 60)) //dosya eskiyse sil
			{
				unlink($tmp_dosya_adi);
			}
	}



/************************ Eski Klösürleri Sil************************************/
$tmp_klasor_omru    = $onbellek_temizleme_suresi;

foreach (glob($onbellek_klasoru. '/*' ) as $tmp_klasor_adi)  
	{
		$klasor_olusturma_zamani = filemtime($tmp_klasor_adi);
		$klasor_yasi = time() - $klasor_olusturma_zamani; 
	
	
		if(count(glob($tmp_klasor_adi.'/*')) === 0)  //klasör boşsa sil
			{
				rmdir($tmp_klasor_adi);
			}
		elseif ($klasor_yasi > ($tmp_klasor_omru * 60)) //klasör eskiyse sil
			{		
				foreach (glob($onbellek_klasoru. '/*/'.$tmp_dosya_turleri ) as $tmp_dosya_adi)  //klasör boş değilse boşlat
						{
							unlink($tmp_dosya_adi);
						}
				rmdir($tmp_klasor_adi); //klasörü sil
			}
	}


?>