<?php 
namespace App\Libraries;

use Exception;

class CSV {
	
	protected $fields;        /* columns names retrieved after parsing */
	protected $max_row_size;  /* maximum row size to be used for decoding */
	protected $separator;     /* separator used to explode each line */
	protected $enclosure;     /* enclosure used to decorate each field */
	
	public function __construct($config = array())
	{
		$sep = ($config['seperator']) ?? ',';
		$config = array_merge(array('max_row_size' => 4096, 'separator' => $sep, 'enclosure' => '"'), $config);
		$this->max_row_size = $config['max_row_size'];
		$this->separator = $config['separator'];
		$this->enclosure = $config['enclosure'];
	}
	
	public function read($file_path)
	{
		$file = fopen($file_path, 'r');
		$line = fgets($file);
		$keys_values = str_getcsv($line, $this->separator, $this->enclosure);
		$content = [];
		$keys = $this->escape_string($keys_values);
		
		while(($row = fgets($file)) != false ){
			// log_message('debug',print_r($row, true));
			if($row != null){
				$values = str_getcsv($row, $this->separator, $this->enclosure);
                array_walk($values, function(&$value){
                    $value = preg_replace("/[^A-Za-z0-9_ \-\&\+]/", '', trim(trim($value, "'")));
                });
				if(count($keys) == count($values)){
					$arr = new \stdClass();
					$new_values = $this->escape_string($values);
					for($j=0; $j < count($keys); $j++){
						if($keys[$j] != "") {
							$key = $keys[$j];
							$arr->$key = $new_values[$j];
						}
					}
					$content[] = $arr;
				}
			}
		}
		fclose($file);
		return $content;
	}
	
	public function create($file_path, $headers, $data)
	{
		$file = fopen($file_path, "w");
		fputcsv($file, $headers);
		foreach($data as $row){
			$row = str_getcsv($row, $this->separator, $this->enclosure);
			$row = $this->escape_string($row);
			fputcsv($file, $row);
		}
		fclose($file);
	}

	public function update($file_path, $data)
	{
		$file = fopen($file_path, "a");
		foreach($data as $row){
			$row = str_getcsv($row, $this->separator, $this->enclosure);
			$row = $this->escape_string($row);
			fputcsv($file, $row);
		}
		fclose($file);
	}
	
	public function download_csv($filename, $header, $data)
	{
		// output headers so that the file is downloaded rather than displayed
		// header('Content-Encoding: UTF-8');
		header('Content-Type: text/csv; charset=utf-8');
		header("Content-Disposition: attachment; filename={$filename}");
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		// fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, $header);
        foreach($data as $row){
            fputcsv($output, $row);
        }
        exit();
	}
	
	public function download_excel($filename, $headers, $data)
	{
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        echo implode("\t", $headers) . "\n";
        foreach ($data as $row) {
            echo implode("\t", array_values($row)) . "\n";
        }
        exit();
	}

	public function download_xml($filename, $data)
	{
        header("Content-type: text/xml");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><Invoices></Invoices>");

        array_to_xml($data, $xml);

        echo $xml->asXML();
        exit();
	}
	
	private function escape_string($data)
	{
		$result = array();
		foreach($data as $row){
			$result[] = str_replace('"', '', $row);
		}
		return $result;
	}

	public function splitFile($inputFile, $outputFile, $splitSize = 10000, $requiredColumns = []) {
    $in = fopen($inputFile, 'r');
    $out =[];
		$head = [];
    $rowCount = 0;
    $fileCount = 1;
    while (!feof($in)) {
				if ($rowCount == 0){
					$head = fgetcsv($in);
					if (empty(array_intersect($requiredColumns, $head)))
						throw new Exception('Missing required columns.');
				}
				$data = fgetcsv($in);
        if (($rowCount % $splitSize) == 0) {
            if ($rowCount > 0) {
                fclose($out);
            }
            $out = fopen($outputFile . $fileCount++ . '.csv', 'w');
						$data =$head;
        }
        // $data = fgetcsv($in);
        if ($data)
            fputcsv($out, $data);
        $rowCount++;
    }

    fclose($out);
  }
}