<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-12-26
 * Time: 下午3:25
 * To change this template use File | Settings | File Templates.
 */
class Zend_View_Helper_PrintCalendar{

    function PrintCalendar($data,$year = null,$column = null,$link = null){
        $year = $year?$year:date('Y');
        $column = $column?$column:3;
        return $this->printTableOfYear($data,$year,$column,$link);
    }

    function printTableOfYear($data,$year,$column,$link = null)
    {
        $months = array( 1 => 'jan', 2 => 'feb', 3 => 'mar', 4 => 'apr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'aug', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dec');
        $htmlTable = '';
        $htmlTable.= '<table width="100%" border="0" cellspacing="0" cellpadding="10"  class="table_border separator_top separator_bot">';
        $htmlTable.= '<tr>';
        foreach($months as $key => $month){
            $tableOfMonth = $this->printTableOfMonth($data[$key],$year,$key,$link);
            $htmlTable .=  '<td  align="center"  valign="top"><b>'.$year.' - '.$key.'</b><br>'.$tableOfMonth.'</td>';
            if( (( $key % $column ) == 0) && ($key < 12)){
                $htmlTable .=   '</tr><tr>';
            }
        }
        $htmlTable.= '</tr>';
        $htmlTable.= '</table>';
        return $htmlTable;
    }

    /**
     * 打印月份表格
     * @param $countDayOfMonth
     * @param $firstDayOfWeek
     * @param $row
     * @return string
     */
    function printTableOfMonth($data,$year,$month,$link = null)
    {
        $countDayOfMonth =  date('t',strtotime(implode('-',array($year,$month,1))));//这个月有多少天
        $firstDayOfWeek = date('w',strtotime(implode('-',array($year,$month,1))));//月初是周几
        //$endDayOfWeek = date('w',strtotime(implode('-',array($year,$month,$countDayOfMonth))));//月末是周几
        $total = $countDayOfMonth + $firstDayOfWeek;//总共有多少格
        $row = ceil($total/7);//月历一共有几行

        $htmlTable = '';
        $week = array('sun','mon','tue','wed','htu','fri','sat');
        $htmlTable.= '<table width="100%" border="0" cellspacing="1" cellpadding="5"  class="table_border separator_top separator_bot">';
        $htmlTable.= '<tr>';
        foreach($week as $weekVal){
            $htmlTable.= '<th>'.ucfirst($weekVal).'</th>';
        }
        $htmlTable.= '</tr>';
        $htmlTable.= '<tr>';
        $tmpDay = 0;
        for($i = 0 ; $i < ($row*7) ;$i++){
            $tmpDay = $i - $firstDayOfWeek;
            if($tmpDay >= 0 && $tmpDay < $countDayOfMonth){
                $dataFiller = $data[$tmpDay+1];
                if(!empty($link)){
                    $dataFiller = '<a href="'.$link.'&date='.implode('-',array($year,$month,($tmpDay+1))).'">'.$dataFiller.'</a>';
                }
                $filler = '<span style="float:left;color:#999;">'.($tmpDay+1).'</span><br><span style="float:right;color:#666;font-szie:14px;">&nbsp;'.$dataFiller.'</span>';
            }else{
                $filler = '&nbsp;';
            }
            $htmlTable.= '<td width="14%">'.$filler.'</td>';
            if((($i%7 == 6))&&(($i < $row*7-1))){
                $htmlTable.= '</tr><tr>';
            }
        }
        $htmlTable.= '</tr>';
        $htmlTable.= '</table>';
        $tmpDataTotal = 0;
        $tmpDataMin = 0;
        $tmpDataMax = 0;
        $tmpDataAverage = 0;
        if(!empty($data)){
            $tmpDataTotal = number_format(array_sum($data));
            $tmpDataMin = number_format(min($data));
            $tmpDataMax = number_format(max($data));
            $tmpDataAverage = number_format(round(array_sum($data)/$countDayOfMonth,2),2);
        }
        $htmlTable.= '<table width="100%" border="0" cellspacing="0" cellpadding="5">'.
                      '<tr>'.
                       ' <td width="25%"><span style="color:#999;">Total</span><span style="padding-left:5px;color:#666;font-szie:14px;">'.$tmpDataTotal.'</span></td>'.
                        '<td width="25%"><span style="color:#999;">Min</span><span style="padding-left:5px;color:#666;font-szie:14px;">'.$tmpDataMin.'</span></td>'.
                        '<td width="25%"><span style="color:#999;">Max</span><span style="padding-left:5px;color:#666;font-szie:14px;">'.$tmpDataMax.'</span></td>'.
                        '<td width="25%"><span style="color:#999;">Average</span><span style="padding-left:5px;color:#666;font-szie:14px;">'.$tmpDataAverage.'</span></td>'.
                      '</tr>'.
                    '</table>';
        /*
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="25%">Total</td>
    <td width="25%">Min</td>
    <td width="25%">Max</td>
    <td width="25%">Average</td>
  </tr>
</table>*/

        return $htmlTable;
    }
}