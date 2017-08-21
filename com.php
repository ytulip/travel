<?php
function OpenOfficeMakePropertyValue( $name, $value, $osm)
{
    $oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
    $oStruct->Name = $name;
    $oStruct->Value = $value;
    return $oStruct;
}

function OpenOfficeWordToHtml( $doc_url, $output_url )
{
    //调用OpenOffice.org服务器
    $osm = new ("com.sun.star.ServiceManager") or die ("Please be sure that OpenOffice.org is installed.\n");
    //设置应用程序继续隐藏,避免闪烁的屏幕上的文档
    $args = array(OpenOfficeMakePropertyValue("Hidden",true,$osm));
    //启动桌面
    $oDesktop = $osm->createInstance("com.sun.star.frame.Desktop");

    //加载。doc文件,从上面传递“隐藏”属性
    $oWriterDoc = $oDesktop->loadComponentFromURL($doc_url,"_blank", 0, $args);
    //设置参数为PDF输出
    $export_args = array(
        OpenOfficeMakePropertyValue("FilterName","HTML (StarWriter)",$osm) ,
        OpenOfficeMakePropertyValue("Overwrite","true",$osm)
    );
    //写出的HTML
    $oWriterDoc->storeToURL($output_url,$export_args);
    $oWriterDoc->close(true);
}

//$output_dir = "文件导出的目录，如F:/";
$doc_file = "F:/1.doc";
$output_file = "F:/1.html";

$doc_file = "file:///" . $doc_file;
$output_file = "file:///" . $output_file;
OpenOfficeWordToHtml($doc_file,$output_file);