<?php
/**
 * sdInteractiveChart class
 * 
 * This class handles some config stuff for the interactive charts. It allows
 * the required javascript to be automatically added once the head of the site
 * has finished.
 *
 * @package    plugins
 * @subpackage sdInteractiveChart
 * @author     Seb Dangerfield - Second2
 * @version    0.2
 */ 
class sdInteractiveChartConfig
{
    /**
    * After the context has been initiated, we can add the required assets
    */
    public static function listenToContextLoadFactoriesEvent()
    {
        require_once( dirname(__FILE__).'/helper/sdInteractiveChartHelper.php');
        addInteractiveChartJavascript();


    }
 
}