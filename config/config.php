<?php

if (in_array('sdInteractiveChart', sfConfig::get('sf_enabled_modules', array())))
{
    // If auto_load_js is enabled then once the context has been initiated
    // we can add in the javascipt files we require!
    // NOTE: Chart specific ones get auto loaded once the page has loaded!
    if (sfConfig::get('app_sdInteractiveChart_auto_load_js')) {
        $this->dispatcher->connect('context.load_factories', array('sdInteractiveChartConfig', 'listenToContextLoadFactoriesEvent'));
    }
}
?>
