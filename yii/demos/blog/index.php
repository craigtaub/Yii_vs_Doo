<?php
//at top of index.php
if (extension_loaded('xhprof')) {
    include_once '/var/www/xhprof/xhprof-0.9.2/xhprof_lib/utils/xhprof_lib.php';
    include_once '/var/www/xhprof/xhprof-0.9.2/xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}

// change the following paths if necessary
$yii=dirname(__FILE__).'/../../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);

Yii::createWebApplication($config)->run();

//at bottom of index.php
if (extension_loaded('xhprof')) {
//    include '/var/www/xhprof/xhprof-0.9.2/xhprof_lib/utils/xhprof_lib.php';
//    include '/var/www/xhprof/xhprof-0.9.2/xhprof_lib/utils/xhprof_runs.php';
    $profiler_namespace = 'yiiBlog';  // namespace for your application
    $xhprof_data = xhprof_disable();
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);
     // url to the XHProf UI libraries (change the host name and path)
    $profiler_url = sprintf('/xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
    echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';
}


