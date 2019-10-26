<?php



function DosyaAdi($dosya)  //Dosya Adindan Dosya Adi
{
	$dosyayolu = pathinfo($dosya);
	$dosyaadi=$dosyayolu['basename'];
	$dosyaturu=$dosyayolu['extension'];
	$sonuc=str_replace(".".$dosyaturu, '', $dosyaadi); //dosya adından dosya türünü çıkart
	
	return $sonuc;
}



function DosyaUzantisiTuru($dosya)  //Dosya Adindan Dosya Turu
{
	$dosyayolu = pathinfo($dosya);
	$dosyaturu=strtolower($dosyayolu['extension']);
	return $dosyaturu;
}



function DosyaIcerigiTuru($file) 
{
	$mtype = false;
	if (function_exists('finfo_open')) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mtype = finfo_file($finfo, $file);
		finfo_close($finfo);
	} elseif (function_exists('mime_content_type')) {
		$mtype = mime_content_type($file);
	} 
	return $mtype;
}




function DosyaIcerigiTuruKontrol($dosya)                 
{
	if (DosyaIcerigiTuru($dosya) == "application/xml" or  DosyaIcerigiTuru($dosya) == "text/xml" or  DosyaIcerigiTuru($dosya) == "text/plain")
		{
			return "True";
		}
	else
		{
			return "False";
		}
}















function ZipIcindeki_DosyaSayisi($dosya)
{
	$zip = new ZipArchive();
	$zip->open($dosya);
	$hedefdosyasayisi=$zip->numFiles;
	$zip->close($dosya);
	
	return $hedefdosyasayisi;
}



function ZipIcindeki_DosyaUzantisi($dosya)
{
	$zip = new ZipArchive();
	$zip->open($dosya);
		
	$hedefdosyaadi=$zip->getNameIndex(0);
	
	$dosyayolu = pathinfo($hedefdosyaadi);
	$hedefdosyaturu=strtolower($dosyayolu['extension']);
	$zip->close($dosya);
	
	return $hedefdosyaturu;
}


function ZipIcindeki_DosyaIcerigiTuru($dosya)
{
	$zip = new ZipArchive();
	$zip->open($dosya);
	$binary = $zip->getFromIndex(0);
	$filename = $zip->getNameIndex(0);
	$zip->close();

	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$MIMETypeandCharset = $finfo->buffer($binary);

	if( strpos( $MIMETypeandCharset, ";" ) !== false) 
	{
		$MIMETypeAndCharsetArray = explode(';', $MIMETypeAndCharset);
		$sonuc = $MIMETypeAndCharsetArray[0];
	}
	else
	{
		$sonuc = $MIMETypeandCharset;
	}

	return $sonuc;
}		

?>