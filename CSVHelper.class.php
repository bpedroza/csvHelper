<?php
/******************************************
@author:Bryan Pedroza
version 1.0
7/29/2013

this class is meant to help with creating csv files from arrays,
create arrays from csv files, and at some point create querys for
inputting said data into a mysql database.
*********************************************/
class CSVHelper
{
	/**************************
	this function takes a multidimensional array as an argument.
	each array should be an associative array where the keys are the
	coulmn names(this is required if $useColumnNames is true). 

	@param $array - array - data to be converted into csv formatt.
	@param $saveLocation - String/bool -  where the csv file should be saved. ie dataExports/my_filename.csv
		if this parameter is a string the file will be saved to the specified location
		This can be set to false if you would like the csv data returned as a String.
	@param $delimiter - String/char - column seperators are "," by default.
	@param $encloser - String/char - column enclosed by " by default.
	@param $lineEnd - String - lines terminated by newline character "\n" by default.
	@param $useColumnNames - bool - default to true, uses array keys as first line for column names. default is true. 
	
	@return - bool/String - there are two possible return types
			1) if $saveLocation is set to false the csv data is returned as a string.
			2) if a location is set, after attempting to create the file a check is done to see if the 
				specified savelocation is a file, return is true or false when savelocation is not a file.
	****************************/
	public function arrayToCSV($array, $saveLocation, $delimiter = ",", $encloser = '"', $lineEnd = "\n", $useColumnNames = true)
	{
		$csvOut = '';
		
		if($useColumnNames)
			$csvOut = $encloser.implode($encloser.$delimiter.$encloser,array_keys($array[0])).$encloser.$lineEnd;
			
		foreach($array as $csvData)
		{
			$csvOut .= $encloser.implode($encloser.$delimiter.$encloser,$csvData).$encloser.$lineEnd;
		}
		
		if($saveLocation === false)
			return $csvOut;
		
		file_put_contents($saveLocation,$csvOut);
		
		if(is_file($saveLocation))
			return true;
		else
			return false;
	}
	
	/**************************
	this function takes a csv file as an argument.
	and outputs a multidimensional array where each array is a row of data.
	@param $csvFile - String location of csv file
	@param $useColumnNames - bool (default true) use first data row as array indexes.
		if false use numeric indexes for the array
	@param $useArrayMap - array if an array is passed as an argument, it will remap the data array to the specification.
		//preconditions
			array should be associative
			keys should be column names or numerical indexes
			values are what the colum names or indexes will be changed to.
		//example
			array(3) {
			  ["Year"]=>
			  string(4) "year"
			  ["Manufacturer"]=>
			  string(4) "make"
			  ["part-no"]=>
			  string(2) "id"
			}
	@param $useOnlyMappedColumns - bool:
			if set to true column not in $arrayMap will not be in the output array 
			default-false
	@return $arrayData[][] - array
	****************************/

	public function csvToArray($csvFile, $useColumnNames = true, $arrayMap = array(), $useOnlyMappedColumns = false)
	{
		$arrayData = array();
		$colNames = array();
		
		//create data array
		$row = 0;
		if (($handle = fopen($csvFile, 'r')) !== false)
		{
			while (($data = fgetcsv($handle)) !== false)
			{
				//get the first line for column name data
				if($row==0)
				{
					if($useColumnNames == true)
						$colNames = $data;
				}
				else
					$arrayData[] = $data;
			
				++$row;
			}	
			fclose($handle);
		}

		//go through the data and change keys to use column names
		//and agree with array map if @param $useArrayMap contains data.
			for($k = 0; $k < count($arrayData); ++$k)
			{
				$size = count($arrayData[$k]);
				for($i = 0; $i < $size ; ++$i)
				{
					$inMap = false;
					if($useColumnNames == true)
						$newKey = $colNames[$i];
					else
						$newKey = $i;
					
					if(count($arrayMap) && in_array($newKey,array_keys($arrayMap)))
					{
						$inMap = true;
						$newKey = $arrayMap[$newKey];
					}
		
					$arrayData[$k][$newKey] = $arrayData[$k][$i];
					
					if($useColumnNames == true && $newKey != $i)
						unset($arrayData[$k][$i]);
						
					if($useOnlyMappedColumns && !$inMap)
						unset($arrayData[$k][$newKey]);
				}
			}
			
		return $arrayData;
	} 

}
?>