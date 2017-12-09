<?
include "const.php";
include "helper.class.php";

$values = array();
if ($handle = opendir(TMP_FOLDER)) {
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			$path_parts = pathinfo($entry);
			if($path_parts['extension'] == WAITING_EXTENSION) {
				$file = escapeshellarg(TMP_FOLDER . $path_parts['filename'] . "." . WAITING_EXTENSION);
				$url = `tail -n 1 $file`;
				array_push($values, '{"filename":"' .trim($url) . '"}');
			}
			if($path_parts['extension'] == PROGRESS_EXTENSION) {
				$file = escapeshellarg(TMP_FOLDER . $path_parts['filename'] . "." . FILE_EXTENSION);
				$filename = `tail -n 1 $file`;
				if($filename == "") {
					continue;
				}
				
				$file = escapeshellarg(TMP_FOLDER . $entry);
				$progress = `tail -n 1 $file`;
				preg_match('/(\d+\.\d+)%/', $progress, $matches);
				if(count($matches) == 2) {
					$progress = $matches[1];
				} else {
					$progress = "0";
				}
				
				$filemtime = @filemtime(TMP_FOLDER . $path_parts['filename'] . "." . FILE_EXTENSION);
				if(false === $filemtime) {
					$filemtime = 0;
				}
				
				if($filemtime == 0) {
					$duration = 0;
				} else {
					$duration = time() - $filemtime;
				}
				
				if($progress == "0") {
					$eta = 0;
				} else {
					$eta =( ($duration / $progress) * 100 ) - $duration;
				}
				
				$cancelInProgress = file_exists(TMP_FOLDER . $path_parts['filename'] . "." . CANCEL_EXTENSION) ? "true" : "false";
				$paused = file_exists(TMP_FOLDER . $path_parts['filename'] . "." . PAUSE_EXTENSION) ? "true" : "false";
				
				array_push($values, '{"filename":"' . $filename . '", "cancelInProgress" : "' . $cancelInProgress . '", "paused" : "' . $paused . '", "id":"' . $path_parts['filename'] . '", "progress":"' . $progress . '", "duration" : "' . Helper::time_duration($duration) . '", "eta" : "' . Helper::time_duration($eta) . '"}');
			}
		}
	}
	closedir($handle);
}
echo "{progress : [" . implode($values, ",") . "]}";
?>