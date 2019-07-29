<?php
//hataları göster
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
//hataları göster




//Ayarlar
	$klasor_yolu=dirname( __FILE__ );
	$gecici_klasor=dirname( __FILE__ )."/gecici";
//Ayarlar





function DosyaAdi($dosya)  //Dosya Adindan Dosya Adi
{
	$dosyayolu = pathinfo($dosya);
	$dosyaadi=$dosyayolu['basename'];
	$dosyaturu=$dosyayolu['extension'];
	$sonuc=str_replace(".".$dosyaturu, '', $dosyaadi); //dosya adından dosya türünü çıkart
	
	return $sonuc;
}

function DosyaIcerigiTuru($dosya)                 
{
	if(function_exists('mime_content_type'))
		{ 
			$dosyaicerigituru = mime_content_type($dosya); 
		}
	elseif(function_exists('finfo_open'))
		{ 
			$finfo = finfo_open(FILEINFO_MIME); 
			$dosyaicerigituru = finfo_file($finfo, $dosya); 
			finfo_close($finfo);
		}
		
	return $dosyaicerigituru;
}

function DosyaUzantisiTuru($dosya)  //Dosya Adindan Dosya Turu
{
	$dosyayolu = pathinfo($dosya);
	$dosyaturu=strtolower($dosyayolu['extension']);
	return $dosyaturu;
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












if($_FILES["zip_file"]["name"]) 
{
	$dosyaadi = $_FILES["zip_file"]["name"];
	
	$yuklenendosyaadi = DosyaAdi($dosyaadi);    //
	$kaynakdosya = $_FILES["zip_file"]["tmp_name"];
	$dosyaturu = $_FILES["zip_file"]["type"];




	

if( DosyaUzantisiTuru($dosyaadi) == "zip" )  //Dosya adı uzantısı .zip mi?
{
	if( DosyaIcerigiTuru($kaynakdosya) == "application/zip" or                   
		DosyaIcerigiTuru($kaynakdosya) == "application/x-zip-compressed" or 
		DosyaIcerigiTuru($kaynakdosya) == "multipart/x-zip" or 
		DosyaIcerigiTuru($kaynakdosya) == "application/x-compressed" )            // Dosya içeriğini zip mi?
		
		{	
			if( ZipIcindeki_DosyaSayisi($kaynakdosya)=="1")       //Dosyanın içinde 1 dosya var mı?
				{
					
//************************************************************* İçinde XML varsa ***************************************************
					if( ZipIcindeki_DosyaUzantisi($kaynakdosya) =="xml")
						{
							if( ZipIcindeki_DosyaIcerigiTuru($kaynakdosya) == "application/xml" or  ZipIcindeki_DosyaIcerigiTuru($kaynakdosya) == "text/xml" or  ZipIcindeki_DosyaIcerigiTuru($kaynakdosya) == "text/plain" )            // Dosya içeriğini xml mi?
								{
									//dosya yükleme
									$hedef_yol = $gecici_klasor."/".$dosyaadi; 
									if(move_uploaded_file($kaynakdosya, $hedef_yol)) 
										{
											$zip = new ZipArchive();
											$x = $zip->open($hedef_yol);
						
											if ($x === true) 
												{      
													$zipicindekidosya=$zip->getNameIndex(0);                 // zip dosyasından dosya adı al
													$hedefdosyaadi=DosyaAdi($zipicindekidosya);		 						
													$hedefdosyaturu=DosyaUzantisiTuru($zipicindekidosya); 
								
													$zip->extractTo($gecici_klasor."/".$yuklenendosyaadi."/"); 
													$zip->close();
													unlink($hedef_yol);
												}
										}
									else
										{
											echo "Dosya yüklenemedi.";
										    exit();
										}
										
								}
							else
								{
									echo "Zip içerisinde geçerli bir XML dosyası mevcut değil. Dosya İçeriği Türü:".ZipIcindeki_DosyaIcerigiTuru($kaynakdosya);
									exit();
								}	
										
						}
						
//************************************************************* İçinde Zip varsa ***************************************************
					elseif( ZipIcindeki_DosyaUzantisi($kaynakdosya) =="zip")
						{
							if( ZipIcindeki_DosyaIcerigiTuru($kaynakdosya) == "application/zip" or  
							    ZipIcindeki_DosyaIcerigiTuru($kaynakdosya) == "application/x-zip-compressed" or
                                ZipIcindeki_DosyaIcerigiTuru($kaynakdosya) == "multipart/x-zip" or 
                                ZipIcindeki_DosyaIcerigiTuru($kaynakdosya) == "application/x-compressed") 
								{

									//Zip dosyasını Aç
									$hedef_yol = $gecici_klasor."/".$dosyaadi; 
									if(move_uploaded_file($kaynakdosya, $hedef_yol)) 
										{
											$zip = new ZipArchive();
											$x = $zip->open($hedef_yol);
						
											if ($x === true) 
												{      
													$zipicindekizip=$zip->getNameIndex(0);                 // zip dosyasından dosya adı al
													$zipicindekidosyaadi=DosyaAdi($zipicindekizip);		 						
													$zipicindekidosyaturu=DosyaUzantisiTuru($zipicindekizip); 
													$zip->extractTo($gecici_klasor."/".$yuklenendosyaadi."/"); 
													$zip->close();
													unlink($hedef_yol);
												}
	
	
	
//***************************************************************** İkinci Zip dosyası Başlangıç **********************************************************************************
	                                    $ikincizipdosyasi = $gecici_klasor."/".$yuklenendosyaadi."/".$zipicindekidosyaadi.".".$zipicindekidosyaturu;
												
											if( ZipIcindeki_DosyaSayisi($ikincizipdosyasi)=="1")       //Dosyanın içinde 1 dosya var mı?
												{
													if( ZipIcindeki_DosyaUzantisi($ikincizipdosyasi) =="xml")
														{
															if( ZipIcindeki_DosyaIcerigiTuru($ikincizipdosyasi) == "application/xml" or  ZipIcindeki_DosyaIcerigiTuru($ikincizipdosyasi) == "text/xml" or  ZipIcindeki_DosyaIcerigiTuru($ikincizipdosyasi) == "text/plain")            // Dosya içeriğini xml mi?
																{		
																	$ikinci_hedef_yol = $gecici_klasor."/".$yuklenendosyaadi."/".$zipicindekidosyaadi.".".$zipicindekidosyaturu; 
																	$ikinci_zip = new ZipArchive();
																	$y = $ikinci_zip->open($ikinci_hedef_yol);											
											
																	if ($y === true) 
																		{      
																			$zipicindekidosya=$ikinci_zip->getNameIndex(0);                 // zip dosyasından dosya adı al
																			$hedefdosyaadi=DosyaAdi($zipicindekidosya);		 						
																			$hedefdosyaturu=DosyaUzantisiTuru($zipicindekidosya); 
																			$ikinci_zip->extractTo($gecici_klasor."/".$yuklenendosyaadi."/"); 
																			$ikinci_zip->close();
																			unlink($ikinci_hedef_yol);
																		}
																}	
															else
																{
																	echo "İkinci Zip içerisinde geçerli bir XML dosyası mevcut değil. Dosya İçeriği Türü:".ZipIcindeki_DosyaIcerigiTuru($ikincizipdosyasi);
																	unlink($ikincizipdosyasi); rmdir($gecici_klasor."/".$yuklenendosyaadi."/"); 
																	exit();
																}
										
														}
													else
														{
															echo "İkinci Zip içerisindeki dosyanın uzantısı XML değil. Dosya Uzantısı:".ZipIcindeki_DosyaUzantisi($ikincizipdosyasi);
															unlink($ikincizipdosyasi); rmdir($gecici_klasor."/".$yuklenendosyaadi."/"); 
															exit();
														}	
										
												}
											else
												{
													echo "İkinci Zip dosyası birden fazla dosya içeriyor. Dosya sayısı:".ZipIcindeki_DosyaSayisi($ikincizipdosyasi);
													unlink($ikincizipdosyasi); rmdir($gecici_klasor."/".$yuklenendosyaadi."/"); 
													exit();
												}
//***************************************************************** İkinci Zip dosyası Bitiş **********************************************************************************
										}
									else
										{
											echo "Dosya yüklenemedi.";
											rmdir($gecici_klasor."/".$yuklenendosyaadi."/"); 
										    exit();
										}	
										
								}
							else
								{
									echo "Zip içerisinde geçerli bir Zip dosyası mevcut değil. Dosya İçeriği Türü:".ZipIcindeki_DosyaIcerigiTuru($kaynakdosya);
									rmdir($gecici_klasor."/".$yuklenendosyaadi."/"); 
									exit();
								}
								
						}
					else
						{
							echo "Zip içerisindeki dosyanın uzantısı Zip değil. Dosya Uzantısı:".ZipIcindeki_DosyaUzantisi($kaynakdosya);
							rmdir($gecici_klasor."/".$yuklenendosyaadi."/"); 
						    exit();
						}		
								
								
				}
			else
				{
					echo "Zip dosyası birden fazla dosya içeriyor. Dosya sayısı:".ZipIcindeki_DosyaSayisi($kaynakdosya);
					rmdir($gecici_klasor."/".$yuklenendosyaadi."/"); 
				    exit();
	
				}
		
		
		}
	else
		{
			echo "Geçerli bir Zip dosyası değil. Dosya İçeriği Türü:".DosyaIcerigiTuru($kaynakdosya);
			 rmdir($gecici_klasor."/".$yuklenendosyaadi."/"); 
			 exit();
	
		}
		
		
}







elseif ( DosyaUzantisiTuru($dosyaadi) == "xml" )   //Dosya adı uzantısı .xml mi?
		{
			if( DosyaIcerigiTuru($kaynakdosya) == "application/xml" or DosyaIcerigiTuru($kaynakdosya) == "text/xml" or DosyaIcerigiTuru($kaynakdosya) == "text/plain")  // Dosya içeriğini XML mi?
				{
					$hedefdosyaadi=$yuklenendosyaadi;
					
					if(!is_dir($gecici_klasor."/".$yuklenendosyaadi."/"))
						{
							mkdir($gecici_klasor."/".$yuklenendosyaadi, 0700);			
						}
						
					$xml_hedef_yol = $gecici_klasor."/".$yuklenendosyaadi."/".$dosyaadi;  
					move_uploaded_file($kaynakdosya, $xml_hedef_yol);
				}
			else
				{
					echo "Geçerli bir XML dosyası değil. Dosya İçeriği Türü:".DosyaIcerigiTuru($kaynakdosya); $hedefdosyaadi=Null;
				}				
					
		}
	else
		{
			echo "Zip ya da XML dosyası değil. Dosya:".DosyaUzantisiTuru($dosyaadi); $hedefdosyaadi=Null;
		}



	
	
	
	
	
	
	
	
	
if (file_exists($gecici_klasor."/".$yuklenendosyaadi."/".$hedefdosyaadi.".xml"))    //Eğer XML Dosyası varsa işlem yap
{
   
//****************************************XSL Dosyası Oluştur**********************************************************
		$fn = $gecici_klasor."/".$yuklenendosyaadi."/".$hedefdosyaadi.".xml"; 
		$data = file_get_contents($fn);
		preg_match_all("#<cbc:EmbeddedDocumentBinaryObject.*?>([^<]+)</cbc:EmbeddedDocumentBinaryObject>#", $data, $xsl_ara);

		$EmbeddedDocumentBinaryObject=$xsl_ara[1][0];

		
		if (!$EmbeddedDocumentBinaryObject) 
		{
			echo "<br> HATA: Yüklenen dosya içinde XSL dosyası bulunamadı. </br>"; 
		}
		
		$xsl_dosyasi=base64_decode($EmbeddedDocumentBinaryObject);
		$xsl_dosyasi=str_replace('<xsl:stylesheet version="2.0"', '<xsl:stylesheet version="1.0"', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:character-map name="a">', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#128;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#129;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#130;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#131;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#132;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#133;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#134;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#135;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#136;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#137;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#138;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#139;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#140;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#141;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#142;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#143;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#144;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#145;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#146;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#147;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#148;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#149;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#150;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#151;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#152;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#153;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#154;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#155;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#156;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#157;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#158;" string=""/>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('<xsl:output-character character="&#159;" string=""/>', '', $xsl_dosyasi);
	    $xsl_dosyasi=str_replace('</xsl:character-map>', '', $xsl_dosyasi);
		$xsl_dosyasi=str_replace('indent="no"', 'indent="yes"', $xsl_dosyasi);
		

		
	  	$xsl_dosyasi_adi=$gecici_klasor."/".$yuklenendosyaadi."/".$hedefdosyaadi.".xsl";
	    file_put_contents($xsl_dosyasi_adi, $xsl_dosyasi);
		
		
		
//****************************************HTML Dosyası Oluştur**********************************************************
$xml = new DOMDocument;
$xml->load($gecici_klasor."/".$yuklenendosyaadi."/".$hedefdosyaadi.".xml");

$xsl = new DOMDocument;
$xsl->load($gecici_klasor."/".$yuklenendosyaadi."/".$hedefdosyaadi.".xsl");

// Configure the transformer
$xml_xsl_birlestir = new XSLTProcessor;
$xml_xsl_birlestir->importStyleSheet($xsl); // attach the xsl rules

$html=$xml_xsl_birlestir->transformToXML($xml);

echo $html;
	
} 


		
}
?>
