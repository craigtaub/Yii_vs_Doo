<?php
//at top of index.php
if (extension_loaded('xhprof')) {
    include_once '/var/www/xhprof/xhprof-0.9.2/xhprof_lib/utils/xhprof_lib.php';
    include_once '/var/www/xhprof/xhprof-0.9.2/xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}


include './protected/config/common.conf.php';
include './protected/config/routes.conf.php';
include './protected/config/db.conf.php';

#Just include this for production mode
//include $config['BASE_PATH'].'deployment/deploy.php';
include $config['BASE_PATH'].'Doo.php';
include $config['BASE_PATH'].'app/DooConfig.php';

# Uncomment for auto loading the framework classes.
/*function __autoload($classname){
	Doo::autoload($classname);
}*/
Doo::conf()->set($config);
include $config['BASE_PATH'].'diagnostic/debug.php';

# database usage
//Doo::useDbReplicate();	#for db replication master-slave usage
Doo::db()->setMap($dbmap);
Doo::db()->setDb($dbconfig, $config['APP_MODE']);
Doo::db()->sql_tracking = true;	#for debugging/profiling purpose

Doo::app()->route = $route;

# Uncomment for DB profiling
//Doo::logger()->beginDbProfile('doowebsite');
Doo::app()->run();
//Doo::logger()->endDbProfile('doowebsite');
//Doo::logger()->rotateFile(20);
//Doo::logger()->writeDbProfiles();
?>

<?php
//at bottom of index.php
if (extension_loaded('xhprof')) {
//    include '/var/www/xhprof/xhprof-0.9.2/xhprof_lib/utils/xhprof_lib.php';
//    include '/var/www/xhprof/xhprof-0.9.2/xhprof_lib/utils/xhprof_runs.php';
    $profiler_namespace = 'doophpBlog';  // namespace for your application
    $xhprof_data = xhprof_disable();
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);
 
    // url to the XHProf UI libraries (change the host name and path)
    $profiler_url = sprintf('/xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
    echo '<a href="'. $profiler_url .'" target="_blank">Profiler output</a>';
}

?>
