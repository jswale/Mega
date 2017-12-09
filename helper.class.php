<?php
class Helper{

    public static function start($link) {
        file_put_contents(TMP_FOLDER . time() . "." . WAITING_EXTENSION, $link);
    }

    public static function cancel($id) {
        file_put_contents(TMP_FOLDER . $id . "." . CANCEL_EXTENSION, "");
    }
    
    public static function pause($id) {
        file_put_contents(TMP_FOLDER . $id . "." . PAUSE_EXTENSION, "");
    }
    
    public static function resume($id) {
        @unlink(TMP_FOLDER . $id . "." . PAUSE_EXTENSION);
    }    
        
    public static function download($link, $id) {    
        $mega = new MEGA();
	$mega->setHelperId($id);
	
	// Get file information from link
        $file = $mega->parse_link($link);
	// Extract information
        $info = $mega->public_file_info($file['ph'], $file['key'], TRUE);
	// Retrieve filename
        $filename=$info['at']['n'];	
	// Save filename to log file
        file_put_contents(TMP_FOLDER . $id . ".file", $filename);
	// Start downloading 
        $filepath = $mega->public_file_save($file['ph'], $file['key'], TMP_FOLDER, $id . "." . DATA_EXTENSION);        
	// Moving complete file
        rename($filepath, COMPLETE_FOLDER . $filename );
	// clean tmp files
        Helper::clean($id);
    }
   
    public static function clean($id){
    	@unlink(TMP_FOLDER . $id . "." . DATA_EXTENSION);
        @unlink(TMP_FOLDER . $id . "." . PID_EXTENSION);
        @unlink(TMP_FOLDER . $id . "." . PROGRESS_EXTENSION);
        @unlink(TMP_FOLDER . $id . "." . FILE_EXTENSION);
        @unlink(TMP_FOLDER . $id . "." . PAUSE_EXTENSION);
    }
    
    public static function time_duration($seconds)
    {
	    // Define time periods
	    $periods = array (
		    'h' => 3600,
		    'm' => 60,
		    's' => 1
	    );
     
    // Break into periods
    $seconds = (float) $seconds;
    $segments = array();
    foreach ($periods as $period => $value) {
	$count = floor($seconds / $value);
	$segments[strtolower($period)] = $count;
	$seconds = $seconds % $value;
    }
     
    // Build the string
    $string = array();
    foreach ($segments as $key => $value) {
	$string[] = ($value < 10 ? 0 : '') . $value;
    }
    
    //return implode(', ', $string);    
    return implode(':', $string);
    }    
}
?>