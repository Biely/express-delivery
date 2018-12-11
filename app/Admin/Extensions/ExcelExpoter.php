<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use Encore\Admin\Facades\Admin;
use Log;

class ExcelExpoter extends AbstractExporter
{
    protected $head = [];
    protected $body = [];
    public function setAttr($head, $body){
        $this->head = $head;
        $this->body = $body;
    }

    public function export()
    {
        Excel::create(date('YmdHis',time()).Admin::user()->name.'导出数据', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {
                $head = $this->head;
                $body = $this->body;

                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) use ($body) {
                    foreach ($body as $keyName){
                        switch ($keyName) {
                            case 'qtype' :
                                $qtype = array_get($item, $keyName);
                                $arr[] = getQdata($qtype)['name'];
                                break;
                            case 'isok' :
                                $isok = array_get($item, $keyName);
                                $deadline = array_get($item, 'deadline');
                                if($isok==0){
                                    $d = "";
                                    if($deadline<time()){
                                      $d ="（已超时）";
                                    }
                                    $w = "待处理".$d;
                                  }
                                  if($isok==1){
                                    $d = "";
                                    if($deadline<time()){
                                      $d ="（已超时）";
                                    }
                                    $w = "正在处理".$d;
                                  }
                                  if($isok==2){
                                    $d ="（已超时）";
                                    $w = "已完结".$d;
                                  }
                                  if($isok==3){
                                    $w = "已完结";
                                  }
                                  $arr[] = $w;
                                break;
                            case 'deadline' :
                                $arr[] = date('Y-m-d H:i:s', array_get($item, $keyName));
                                break;
                            default:
                                if(array_get($item, $keyName)==null){
                                    $arr[] = "##";
                                }else{
                                    $arr[] = array_get($item, $keyName);
                                }
                                break;
                        }
                    }
                    return $arr;
                });

                $rows = collect([$head])->merge($rows);
                $sheet->rows($rows);

            });

        })->export('xls');
    }
}