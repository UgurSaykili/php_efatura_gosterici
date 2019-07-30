
# PHP ile GİB E-Fatura Gösterme

## Özellikleri:

1)Temel fatura, Ticari fatura , Uygulama yanıtı kabul, Uygulama yanıtı red, Temel fatura KDV sıfır, Temel fatura iade ve Zarf dosyalarını açar.

2)Zip ile sıkıştırılmış Efatura dosyasını açar

3)Dosya içeriği denetimi yapar.

4)Dosya uzantısı denetimi yapar.

5)Efatura dosyalarınızı HTML biçiminde şekilde gösterir.

Geliştirici: Murat KARAGÖZ (murat.karagoz@hotmail.com.tr)

## Kullanımı
```php
include 'efaturagoster.php';

$FaturaXslDosyasi= FaturaXslDosyasiOlustur($FaturaXmlDosyasi);
$FaturaHtmlDosyasi=FaturaHtmlDosyasiOlustur($FaturaXmlDosyasi);

echo $FaturaHtmlDosyasi;
 ``` 
 
## Gereksinimleri
PHP XSL eklentisi        (extension=php_xsl.dll)

PHP FileInfo eklentisi   (extension=php_fileinfo.dll)    //Önbellekli Demo için gerekli

PHP ZipArchive eklentisi (PHP 5 >= 5.2.0, PHP 7, PECL zip >= 1.1.0) //Önbellekli Demo için gerekli

### Lisans
Creative Commons Atıf-GayriTicari-Türetilemez 4.0 Uluslararası Kamu Lisansı ile lisanslanmıştır. Detaylar için LİSANS dosyasına bakın.

