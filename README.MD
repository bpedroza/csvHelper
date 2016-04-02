#CSV HELPER

At work i find very frequently the need to use information sent to us in csv format. Wether i need to input it into a database, or create something else using it, typically i need it in an array. Even better if I can only pull the columns i need and get the names of the database columns i want to be the array keys. That makes database insertion easy. That's why i made this tool.

##Usage
###include the file
``` 
include '/path/CSVHelper.class.php'; 
```

###Create a new instance of the class
```
$MyCSVHelper = new CSVHelper();
```

###Turn a csv file into an array
```
$MyCSVHelper->csvToArray($csvFile, $useColumnNames = true, $arrayMap = array(), $useOnlyMappedColumns = false);
```

### Write a csv file from an array
```
$MyCSVHelper->arrayToCSV($array, $saveLocation, $delimiter = ",", $encloser = '"', $lineEnd = "\n", $useColumnNames = true)
```
				
### Create insert query from a csv file
```
$MyCSVHelper->createInsertQueryString($MyCSVHelper->csvToArray($csvLocation,true,$arrayMap, true),'table_name');
```

### Create Update query from a csv file
```
$MyCSVHelper->createQueryString($MyCSVHelper->csvToArray($csvLocation,true,$arrayMap, true),'aTable', 'modelNumber');
```


## A Note:
I've noticed that the servers i work on tend to time out after processing about 40,000 lines using the csvToArray function. If you have a very large csv file you might want to consider spliting it into multiple files before using this tool. There are some good csv splitting desktop applications availible for use. I might try to create a splitting function for this utility, until then use an open source desktop application for csv files containing more than 40,000 lines.
				
That's it, hope you find it usefull.
