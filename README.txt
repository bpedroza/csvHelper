CSV HELPER

Why did i make this? AT work i find very frequently the need to use information sent to us in csv format. Wether i need to input it into a database, or create something else using it, typically i need it in an array. Even better if I can only pull the columns i need and get the names of the database columns i want to be the array keys. That makes database insertion easy. That's why i made this tool.

How does it work?

Obviously you'll need the file, from there you'll need to create a new instance of csv helper.
$MyCSVHelper = new CSVHelper();

simple right?
Are you going to turn a csv file into an array? Use the csvToArray function

$MyCSVHelper->csvToArray($csvFile, $useColumnNames = true, $arrayMap = array(), $useOnlyMappedColumns = false)

The only mandatory argument is the file's location($csvFile)
Paramaters are as follows:

	@param $csvFile - String location of csv file
	@param $useColumnNames - bool (default true) use first data row as array indexes.
		if false use numeric indexes for the array
	@param $useArrayMap - array - if an array is passed as an argument, it will remap the data array to the specification.
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
	@param $useOnlyMappedColumns - bool(default false):
			if set to true columns not in $arrayMap will not be in the output array 
			precondition: $arrayMap must not be an empty array else this will be ignored
			
Returns a multidimensional associative arrray
	@return $arrayData[][] - array
	
Are you going from an array to csv? Use the arrayToCSV function

$MyCSVHelper->arrayToCSV($array, $saveLocation, $delimiter = ",", $encloser = '"', $lineEnd = "\n", $useColumnNames = true)
Mandatory arguments are:
	$array - the array you want to be converted to csv
	$saveLocation - the location where you want the csv file saved, or false.
	
Paramaters are as follows:
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
				
				
Need an insert query from a csv file? Try something like this.
$MyCSVHelper->createQueryString($MyCSVHelper->csvToArray($csvLocation,true,$arrayMap, true),'aTable');
the function accepts 2 argumants and creates a mysql insert query string.

public function createQueryString($array, $table)
Both arguments are mandatory:
$array is an array in the format created by  the csvToArray function
$table is the table data is to be inserted into.

	this function takes an array of associative arrays and creates a mass insert query.
	the keys of each associative array must be database table column names and the values are values to be inserted.
	
	@param $array - array - array of associative arrays containing data to be inserted.
	@param $table - String - name of the database table to insert data into. 
	
	@return $queryString - String- a query string created from the array




A Note:
Using this at work i've noticed that the server i was working with times out after processing about 40,000 lines using the csvToArray function. If you have a very large csv file you might want to consider spliting it into multiple files before using this tool. There are some good csv splitting desktop applications availible for use. I might try to create a splitting function for this utility, until then use an open source desktop application for csv files containing more than 40,000 lines.
				
That's it, hope you find it usefull.
