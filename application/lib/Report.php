<?php

/**
 * Created by PhpStorm.
 * User: ZNZG
 * Date: 2018/2/24
 * Time: 18:35
 */
namespace app\lib;

class Report
{
    /**
     * 导出报表
     * @param $title          //报表名称
     * @param $columninfo     //列信息
     * @param $infolist       //数据列表
     * @param $filename       //存储文件名称（不加后缀的时候）
     */
    public static function expReport($title,$columninfo,$infolist,$filename)
    {
        //vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);

        //设置标题
        $objPHPExcel->getActiveSheet()->setTitle($title);

        $column_titles = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

        //设置sheet列头信息
        self::setCellTitles($objPHPExcel,$column_titles,$columninfo);

        //设置列内容
        self::setCellBodies($objPHPExcel,$column_titles,$columninfo,$infolist);

        //整体设置字体和字体大小
        self::setCellFonts($objPHPExcel,$column_titles,$columninfo);

        //输出Excel表格到浏览器下载
        self::downloadFile($objWriter,$filename);
    }

    /**
     * 设置列表头信息
     * @param $objPHPExcel
     * @param $column_titles
     * @param $columninfo
     */
    private static function setCellTitles($objPHPExcel,$column_titles,$columninfo)
    {
        $i = 0;
        $obj = $objPHPExcel->setActiveSheetIndex();
        foreach($columninfo as $k => $v)
        {
            $obj = $obj->setCellValue($column_titles[$i].'1', $columninfo[$i]['name']);
            $i++;
        }
    }

    /**
     * 设置列表内容
     * @param $objPHPExcel
     * @param $column_titles
     * @param $columninfo
     * @param $infolist
     */
    private static function setCellBodies($objPHPExcel,$column_titles,$columninfo,$infolist)
    {
        $i=2;
        foreach($infolist as $v){
            //设置单元格的值
            $j = 0;
            foreach($columninfo as $kitem => $vitem)
            {
                $sheets=$objPHPExcel->getActiveSheet()->setCellValue($column_titles[$j].$i,$v[$vitem['field']]);
                $j++;
            }
            $i++;
        }
    }

    /**
     * 设置字体及单元格宽度
     * @param $objPHPExcel
     * @param $column_titles
     * @param $columninfo
     */
    private static function setCellFonts($objPHPExcel,$column_titles,$columninfo)
    {
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

        $i = 0;
        foreach($columninfo as $k => $v)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($column_titles[$i])->setWidth(24);
            $i++;
        }
    }

    /**
     * 命名并下载导出文件
     * @param $objWriter
     * @param $filename
     */
    private static function downloadFile($objWriter,$filename)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'_'.date('YmdHis').'.xls"'); //excel表格名称
        $objWriter->save('php://output');
    }
}