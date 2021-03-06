<?php



function encodeFunc($value) {
  return '"'.$value.'"';
}
/* export rentals to csv */
$databasehost = "localhost";
$databasename = "webmaste_grocery";
$databasetable = "jobs";
$databaseusername="webmaste_grocery";
$databasepassword = "gr0c3ry";
// output headers so that the file is downloaded rather than displayed

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=jobs.csv');

// create a file pointer connected to the output stream
$output = fopen("php://output", 'w');
//$output = fopen("F:\Internet\Users\Linda\github_websites", 'w');


// output the column headings
//fputcsv($output, array('Column 1', 'Column 2', 'Column 3'));

// fetch the data
$conn = new mysqli($databasehost, $databaseusername, $databasepassword, $databasename);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * from ".$databasetable;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
} else {
echo "no results ";
}

// loop over the rows, outputting them
$delimiter = "~";
$enclosure = ' ';
$row = array('jobid', 'adnumber', 'classification', 'copy', 'image', 'company', 'job_title', 'city', 'contacturl', 'fullorparttime', 'prepforpublish', 'contactemail', 'contactphone', 'displayad', 'featured_ad', 'ts', 'copy2', 'wpcategoryslug', 'salesperson', 'customer', 'customerNomber');
fputcsv($output, array_map(encodeFunc,$row), $delimiter, $enclosure); // column names
while ($row = $result->fetch_assoc()) {
  //fputcsv($output, array_map(encodeFunc,$row), $delimiter,$enclosure);
   fputs($output, implode("~", array_map("encodeFunc", $row))."\r\n");
}
