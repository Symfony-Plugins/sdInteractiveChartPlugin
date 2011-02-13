<?php
/**
 * rhAnnotatedTimeLineGraph - Creates AnnotatedTimeLine chart!
 * Google API URL: http://code.google.com/apis/visualization/documentation/gallery/annotatedtimeline.html
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Robert Heim
 * @version    0.1
 */


class AnnotatedTimeLineGraph extends BaseChart {
    protected $chartType = 'AnnotatedTimeLine';

    public function  __construct() {
        $this->chartPackage = 'annotatedtimeline';
    }

    protected function formatData($data, $labels = null) {
        $addedColumns = false;
        $this->html .= 'var data = new google.visualization.DataTable();' . "\n";
        $this->html .= "data.addColumn('date','Second');" . "\n";

        $rowCountAdded = false;
        $row = 1;
        $latestRow = 0;
        foreach($data as $dataText=>$dataSet) {
            if ($latestRow < $row) {
                if (is_string($dataText)) {
                    $this->html .= "data.addColumn('number', '".$dataText."');" . "\n";
                } else
                    $this->html .= "data.addColumn('number','total');" . "\n";
                $latestRow = $row;
            }


            if (!$rowCountAdded) {
                if ($dataSet instanceof sfOutputEscaperArrayDecorator) {
                    $total = $dataSet->count();
                } else {
                    $total = (is_array($dataSet)) ? count($dataSet) : count($data);
                }
                $this->html .= 'data.addRows('.$total.');' . "\n";
                // Draw labels
                foreach($labels as $key=>$label) {
                	// javascript uses milliseconds as base
                	$xLable = 'new Date('.($label*1000).')';
                    $this->html .= "data.setValue($key, 0, {$xLable});" . "\n";
                }

                $rowCountAdded = true;
            }

            if ((!is_array($dataSet)) && !($dataSet instanceof sfOutputEscaperArrayDecorator)) {
                 $this->html .= "data.setValue($dataText, 1, $dataSet);" . "\n";
            } else {
                foreach($dataSet as $key=>$nextRow) {
                   $this->html .= "data.setValue($key, $row, $nextRow);" . "\n";
                }
                $row++;
            }
        }
    }

}

?>