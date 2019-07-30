<?php

        include 'dosya_kontrol_kutuphanesi.php';



		$dosyaadi = $_FILES["zip_file"]["name"];
		$yuklenendosyaadi = DosyaAdi($dosyaadi); 
		$yuklenendosyauzantisi = DosyaUzantisiTuru($dosyaadi); 
		
		$kaynakdosya = $_FILES["zip_file"]["tmp_name"];
		$dosyaturu = $_FILES["zip_file"]["type"];

	
	    //önbellek klasörü yoksa oluştur
		if(!is_dir($onbellek_klasoru))
		{
			mkdir($onbellek_klasoru,0700);			
		}
	
	
	

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
									$hedef_yol = $onbellek_klasoru."/".$dosyaadi; 
									if(move_uploaded_file($kaynakdosya, $hedef_yol)) 
										{
											$zip = new ZipArchive();
											$x = $zip->open($hedef_yol);
						
											if ($x === true) 
												{      
													$zipicindekidosya=$zip->getNameIndex(0);                 // zip dosyasından dosya adı al
													$hedefdosyaadi=DosyaAdi($zipicindekidosya);		 						
													$hedefdosyaturu=DosyaUzantisiTuru($zipicindekidosya); 
								
													$zip->extractTo($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
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
									$hedef_yol = $onbellek_klasoru."/".$dosyaadi; 
									if(move_uploaded_file($kaynakdosya, $hedef_yol)) 
										{
											$zip = new ZipArchive();
											$x = $zip->open($hedef_yol);
						
											if ($x === true) 
												{      
													$zipicindekizip=$zip->getNameIndex(0);                 // zip dosyasından dosya adı al
													$zipicindekidosyaadi=DosyaAdi($zipicindekizip);		 						
													$zipicindekidosyaturu=DosyaUzantisiTuru($zipicindekizip); 
													$zip->extractTo($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
													$zip->close();
													unlink($hedef_yol);
												}
	
	
	
//***************************************************************** İkinci Zip dosyası Başlangıç **********************************************************************************
	                                    $ikincizipdosyasi = $onbellek_klasoru."/".$yuklenendosyaadi."/".$zipicindekidosyaadi.".".$zipicindekidosyaturu;
												
											if( ZipIcindeki_DosyaSayisi($ikincizipdosyasi)=="1")       //Dosyanın içinde 1 dosya var mı?
												{
													if( ZipIcindeki_DosyaUzantisi($ikincizipdosyasi) =="xml")
														{
															if( ZipIcindeki_DosyaIcerigiTuru($ikincizipdosyasi) == "application/xml" or  ZipIcindeki_DosyaIcerigiTuru($ikincizipdosyasi) == "text/xml" or  ZipIcindeki_DosyaIcerigiTuru($ikincizipdosyasi) == "text/plain")            // Dosya içeriğini xml mi?
																{		
																	$ikinci_hedef_yol = $onbellek_klasoru."/".$yuklenendosyaadi."/".$zipicindekidosyaadi.".".$zipicindekidosyaturu; 
																	$ikinci_zip = new ZipArchive();
																	$y = $ikinci_zip->open($ikinci_hedef_yol);											
											
																	if ($y === true) 
																		{      
																			$zipicindekidosya=$ikinci_zip->getNameIndex(0);                 // zip dosyasından dosya adı al
																			$hedefdosyaadi=DosyaAdi($zipicindekidosya);		 						
																			$hedefdosyaturu=DosyaUzantisiTuru($zipicindekidosya); 
																			$ikinci_zip->extractTo($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
																			$ikinci_zip->close();
																			unlink($ikinci_hedef_yol);
																		}
																}	
															else
																{
																	echo "İkinci Zip içerisinde geçerli bir XML dosyası mevcut değil. Dosya İçeriği Türü:".ZipIcindeki_DosyaIcerigiTuru($ikincizipdosyasi);
																	unlink($ikincizipdosyasi); rmdir($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
																	exit();
																}
										
														}
													else
														{
															echo "İkinci Zip içerisindeki dosyanın uzantısı XML değil. Dosya Uzantısı:".ZipIcindeki_DosyaUzantisi($ikincizipdosyasi);
															unlink($ikincizipdosyasi); rmdir($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
															exit();
														}	
										
												}
											else
												{
													echo "İkinci Zip dosyası birden fazla dosya içeriyor. Dosya sayısı:".ZipIcindeki_DosyaSayisi($ikincizipdosyasi);
													unlink($ikincizipdosyasi); rmdir($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
													exit();
												}
//***************************************************************** İkinci Zip dosyası Bitiş **********************************************************************************
										}
									else
										{
											echo "Dosya yüklenemedi.";
											rmdir($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
										    exit();
										}	
										
								}
							else
								{
									echo "Zip içerisinde geçerli bir Zip dosyası mevcut değil. Dosya İçeriği Türü:".ZipIcindeki_DosyaIcerigiTuru($kaynakdosya);
									rmdir($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
									exit();
								}
								
						}
					else
						{
							echo "Zip içerisindeki dosyanın uzantısı Zip değil. Dosya Uzantısı:".ZipIcindeki_DosyaUzantisi($kaynakdosya);
							rmdir($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
						    exit();
						}		
								
								
				}
			else
				{
					echo "Zip dosyası birden fazla dosya içeriyor. Dosya sayısı:".ZipIcindeki_DosyaSayisi($kaynakdosya);
					rmdir($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
				    exit();
	
				}
		
		
		}
	else
		{
			echo "Geçerli bir Zip dosyası değil. Dosya İçeriği Türü:".DosyaIcerigiTuru($kaynakdosya);
			 rmdir($onbellek_klasoru."/".$yuklenendosyaadi."/"); 
			 exit();
	
		}
		
		
}







elseif ( DosyaUzantisiTuru($dosyaadi) == "xml" )   //Dosya adı uzantısı .xml mi?
		{
			if( DosyaIcerigiTuru($kaynakdosya) == "application/xml" or DosyaIcerigiTuru($kaynakdosya) == "text/xml" or DosyaIcerigiTuru($kaynakdosya) == "text/plain")  // Dosya içeriğini XML mi?
				{
					$hedefdosyaadi=$yuklenendosyaadi;
					
					if(!is_dir($onbellek_klasoru."/".$yuklenendosyaadi."/"))
						{
							mkdir($onbellek_klasoru."/".$yuklenendosyaadi, 0700);			
						}
						
					$xml_hedef_yol = $onbellek_klasoru."/".$yuklenendosyaadi."/".$dosyaadi;  
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


?>