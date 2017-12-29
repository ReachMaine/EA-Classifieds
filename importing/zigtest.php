<?php
/* CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `getads` AS select `o`.`number` AS `adnumber`,`o`.`category` AS `classification`,concat(`x`.`Account #`,' - ',`x`.`Advertiser Name`) AS `customer`,`x`.`Salsp` AS `salesperson`,`o`.`adtext` AS `copy` from (`onlineclassifieds` `o` join `admanifest` `x`) where (`o`.`number` = `x`.`Ad-Number`)

testing trying to do a better job of price....
ᐧ*/
$databasehost = "localhost";
$databasename = "webmaste_grocery";
$databasetable = "visionDataDump";
$databaseusername="webmaste_grocery";
$databasepassword = "gr0c3ry";
$fieldseparator = "~";
$lineseparator = "\n";
$csvfile = "datafiles/classifieds.csv";


if(!file_exists($csvfile)) {
    die("File not found. Make sure you specified the correct path.");
}

try {
    $pdo = new PDO("mysql:host=$databasehost;dbname=$databasename",
        $databaseusername, $databasepassword,
        array(
            PDO::MYSQL_ATTR_LOCAL_INFILE => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
} catch (PDOException $e) {
    die("database connection failed: ".$e->getMessage());
}

$affectedRows = $pdo->exec("truncate table visionDataDump");



$affectedRows = $pdo->exec("LOAD DATA LOCAL INFILE ".$pdo->quote($csvfile)." INTO TABLE `$databasetable`
      FIELDS
      TERMINATED BY ".$pdo->quote($fieldseparator)."
      LINES TERMINATED BY ".$pdo->quote($lineseparator)).
      " (adnumber, classification,customer,salesperson,copy)";


echo "<p>Loaded a total of $affectedRows <br> records from this csv file.</p>";



// THIS ENDS THE DATA UPLOAD PORTION OF THE FILE.




// Create connection
$conn = new mysqli($databasehost, $databaseusername, $databasepassword, $databasename);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete visionData ads that are not running again.
$affectedRows = $pdo->exec("delete from rentals where not adnumber in (Select adnumber from visionDataDump)");
$affectedRows = $pdo->exec("delete from jobs where not adnumber in (Select adnumber from visionDataDump)");
$affectedRows = $pdo->exec("delete from classifieds where not adnumber in (Select adnumber from visionDataDump)");
$affectedRows = $pdo->exec("delete from realestate where not adnumber in (Select adnumber from visionDataDump)");


$sql = "SELECT * from visionDataDump";
$result = $conn->query($sql);

$usql = "UPDATE visionDataDump set copy2 = fnStripTags(copy2)";
$uresult = $conn->query($usql);

if ($result->num_rows > 0) {
     $count_displayads = 0;
     $count_price = 0;
     $count_ads = 0;
     $count_emptyad;
    // output data of each row
    while($row = $result->fetch_assoc()) {

      $count_ads++;
      #set default values
      $thisphone = '';
      $thisurl = '';
      $thisemail = '';

      #Update Classified Category For Listing to map with WP
      $usql = "UPDATE visionDataDump v, classifieds_category c SET v.wpcategoryslug = c.wpcategoryslug WHERE v.classification =  c.visionDataCategoryID and classifiedid = ".$row["classifiedid"];
      $uresult = $conn->query($usql);

      #Update Towns
      $usql = "UPDATE visionDataDump v, towns t SET v.city = t.towns_wpcategory_slug WHERE locate(t.town,v.copy2) > 0 and classifiedid = ".$row["classifiedid"];  $uresult = $conn->query($usql);
      $uresult = $conn->query($usql);

      #Strip Tags
      //$supertext = strip_tags($row["copy"]);
      #$supertext = preg_replace('/\r\n?/', "\. ", $supertext); //remove period then carriage returns by adding space
      $supertext = $row["copy"]; // we start here.
      //echo "<pre> supertext before is: ".$supertext."</pre>";
      $supertext = str_replace('<B>','', $supertext); // remove <B>
      $supertext = str_replace('</B>','', $supertext); //Remove </B>
      $supertext = str_replace('<HR SIZE=1 NOSHADE>','', $supertext); //Remove <HR SIZE=1 NOSHADE>
      $supertext = str_replace('\r','', $supertext); //remove P.O. Box
      $supertext = str_replace('P\.O\.','PO', $supertext); //replace P.O. Box
      $supertext = trim( $supertext); //trim leading & trailing whitespace

      $supertext = preg_replace('/(\s\s+|\t|\n)/', ' ', $supertext); //remove tabs and spaces

      //echo "<pre> supertext after is: ".$supertext."</pre><hr>";
      $usql = "UPDATE visionDataDump set copy2 = '".$supertext."' where classifiedid = ".$row["classifiedid"];  $uresult = $conn->query($usql);
      $uresult = $conn->query($usql);

      #Update Contact Phone using PHP Regular Expressions
      $re = '/[\([]?([0-9]{3})?[\)\]]?[-. ]?[\([]?([0-9]{3})[\)\]]?[-. ]?[\([]?([0-9]{4})[\)\]]?|([0-9])[-. ]([0-9])[-. ]([0-9])[-. ]([0-9])[-. ]([0-9])[-. ]([0-9])[-. ]([0-9])[-. ]([0-9])[-. ]([0-9])[-. ]([0-9])/'; //regular expression for phone
      preg_match_all($re, $row["copy"], $re_phone, PREG_SET_ORDER, 0);
      if (strlen($re_phone[0][0]) > 5 && strlen($re_phone[0][0]) < 12 )
      		 {$thisphone = trim($re_phone[0][0]); $updatephone = 'Yes';}
      else {$updatephone = 'No';}

      #Update Contact URL PHP Regular Expressions
      $re2 = '/(^|\s)((https?:\/\/)?[\w-]+(\.[a-z-]+)+\.?(:\d+)?(\/\S*)?)/im';
      preg_match_all($re2, $row["copy"], $re_url, PREG_SET_ORDER, 0);
      if (strlen($re_url[0][0]) > 5 ) {
      	$thisurl = trim($re_url[0][0]);
      	$updateurl = 'Yes';
      	}
      else {$updateurl = 'No';}


      // zig - check for price here
      $updateprice = 'No';
      $price = "";
      $dollar_spot = strpos($supertext, "$");
      if ($dollar_spot) {
        //echo "<p>got a dollar sign for ".$row["classifiedid"]." at ".$dollar_spot." near ".substr($supertext, $dollar_spot,10)."</p>";
        //echo "<p>".substr($supertext, $dollar_spot)."</p>";
        $regex_price = '\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})?'; // orginal
        $regex_price = '([0-9]+(,[0-9]{3})*(\.[0-9]*)?|\.[0-9]+)';  // try2
        if ( preg_match($regex_price, substr($supertext, $dollar_spot), $matches) === 1 ) {
          $price = rtrim($matches[0],".");// trim off any trailing "."
          $price = str_replace(",", "", $price); // remove the commas
          //echo "<p>price is....:{".$price."}</p>";
          $updateprice = 'Yes';
          $count_price++;
        }
        //echo "<hr>";
      }


      #Update Contact Email PHP Regular Expressions
      $re3 = '([\w.-]+@([\w-]+)\.+\w{2,})';
      preg_match_all($re3, $row["copy"], $re_email, PREG_SET_ORDER, 0);
      if (strlen(re_email[0][0]) > 0 )
      	{$thisemail = trim($re_email[0][0]); $updateemail = 'Yes'; }
      else {$updateemail = 'No';}

      #Strip Tags
      $supertext = strip_tags($row["copy"]);
      #$supertext = preg_replace('/\r\n?/', "\. ", $supertext); //remove period then carriage returns by adding space
      $supertext = str_replace('P\.O\.',"PO", $supertext); //remove P.O. Box
      $supertext = preg_replace('/(\s\s+|\t|\n)/', ' ', $supertext); //remove tabs and spaces

      #Update SQL Statement For The Current Row
      if (trim)
      $usql = "update visionDataDump set copy2 = '".$supertext."'";
      if ($updatephone = 'Yes')  { $usql =  $usql.", contactphone = '".$thisphone."'";}
      if ($updateurl = 'Yes')  { $usql =   $usql.", contacturl = '".$thisurl."'";}
      if ($updateemail = 'Yes')  { $usql =   $usql.", contactemail = '".$thisemail."'";}
      if ($updateprice = 'Yes') { $usql =   $usql.", price = '".$price."'";} // zig - use new price column for price
      $usql = $usql . " where adnumber = " . trim($row["adnumber"]);
      $uresult = $conn->query($usql);


      //$usql = "UPDATE visionDataDump set copy2 = '".$supertext."' where classifiedid = ".$row["classifiedid"];  $uresult = //$conn->query($usql);
      //$uresult = $conn->query($usql);

      // Check if Display Ad Exists And update "featured_ad field if it does."
      $vd = trim($row["adnumber"]);
      //$testimage = "/home/webmaster/public_html/manage.downeastmaine.com/displayads/".$vd.".jpg";
      $testimage = "/home/webmaster/public_html/manage.downeastmaine.com/vddisplayads/".$vd.".jpg";
      $testurl = "http://manage.downeastmaine.com/manage.downeastmaine.com/vddisplayads/".$vd.".jpg";
      $got_image = false;
      if (file_exists($testimage)) {

        $usql = "UPDATE visionDataDump set displayad = '".$testurl."' where classifiedid = ".$row["classifiedid"];
        $uresult = $conn->query($usql);
        //$uresult = $conn->query($usql);
        $count_displayads++;
        $got_image = true;
      }

      if ( ($supertext == "") && (!$got_image)) {
        $count_emptyad++;
      }
      // Print the entire match result
      //echo "id: " . $row["adnumber"]. "| Length of phone: ".strlen($phone[0][0]). " | values ".$updatephone."|".$updateurl."|".$updateemail."|" . $supertext . "| sql:".$usql." | phone[0][0] is ".$phone[0][0]." </p>";
    }
  } else {
    echo "0 results";
}

// delete empty non-display ads.
$usql = 'delete from visionDataDump  where copy2 = "" AND displayad = "" ' ; $uresult = $conn->query($usql);
echo "Processed $count_ads ads with $count_displayads display ads and $count_price with prices and $count_emptyad ignored (empty) ads";

exit();  // zig - bail
#Fix Empty Strings to NULL Before Moving Data Around
$usql = "update visionDataDump set contacturl = NULL where LENGTH( LTRIM( RTRIM( contacturl ) ) )  = 0"; $uresult = $conn->query($usql);
$usql = "update visionDataDump set contactphone = NULL where LENGTH( LTRIM( RTRIM( contacphone ) ) )  = 0"; $uresult = $conn->query($usql);
$usql = "update visionDataDump set contactemail = NULL where LENGTH( LTRIM( RTRIM( contactemail ) ) )  = 0"; $uresult = $conn->query($usql);
$usql = "update visionDataDump set price = NULL where LENGTH( LTRIM( RTRIM( price ) ) )  = 0"; $uresult = $conn->query($usql);


#Move Rentals To rentals Table, then delete from visionDataDump
$usql ="INSERT INTO rentals (adnumber,classification,copy,image,contactphone,contacturl,contactemail,copy2,town,wpcategoryslug,customer,salesperson, price)  SELECT adnumber,classification,copy,image,contactphone,contacturl,contactemail,copy2,city,wpcategoryslug,customer,salesperson,price FROM visionDataDump   WHERE classification between 500 and 599 and not adnumber in (Select adnumber from rentals)";
$uresult = $conn->query($usql);

$usql ="Delete  FROM visionDataDump WHERE classification between 500 and 599";
$uresult = $conn->query($usql);

#Update Title
$usql = "Update rentals set rental_title = LEFT( copy2, 100 ) where DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);

#Update bedrooms
$usql = "UPDATE rentals SET beds =1 WHERE (copy LIKE '%1 bedroom%' OR copy LIKE '%1BR%' OR copy LIKE '%1 BR%' OR copy LIKE '%one bedroom%' OR copy LIKE '%effic%' OR copy LIKE '%studio%')  and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET beds =2 WHERE (copy LIKE '%2 bedroom%' OR copy LIKE '%2BR%' OR copy LIKE '%2 BR%' OR copy LIKE '%two bedroom%')   and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET beds =3 WHERE (copy LIKE '%3 bedroom%' OR copy LIKE '%3BR%' OR copy LIKE '%3 BR%' OR copy LIKE '%three bedroom%')   and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$affectedRows = $pdo->exec("UPDATE rentals SET beds =4 WHERE (copy LIKE '%4 bedroom%' OR copy LIKE '%4BR%' OR copy LIKE '%4 BR%' OR copy LIKE '%four bedroom%') and DATE(`ts`) = CURDATE()"); $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET beds =5 WHERE (copy LIKE '%5 bedroom%' OR copy LIKE '%5BR%' OR copy LIKE '%5 BR%' OR copy LIKE '%five bedroom%') and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);

#Update Bathrooms
$usql = "UPDATE rentals SET baths =1 WHERE (copy LIKE '%1 bath%' OR copy like '%1 bathroom%' or copy LIKE '%1BA%' OR copy LIKE '%1 BA%' OR copy LIKE '%one bath%'OR copy LIKE '%full bathroom%') and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET baths =2 WHERE  (copy LIKE '%2 bath%' OR copy like '%2 bathroom%' or copy LIKE '%2BA%' OR copy LIKE '%2 BA%' OR copy LIKE '%two bath%')    and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET baths =3 WHERE  ( copy LIKE '%3 bath%' OR copy like '%3 bathroom%' or copy LIKE '%3BA%' OR copy LIKE '%3 BA%' OR copy LIKE '%three bath%')    and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET baths =4 WHERE  (copy LIKE '%4 bath%' OR copy like '%4 bathroom%' or copy LIKE '%4BA%' OR copy LIKE '%4 BA%' OR copy LIKE '%four bath%')   and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET baths =5 WHERE  (copy LIKE '%5 bath%' OR copy like '%5 bathroom%' or copy LIKE '%5BA%' OR copy LIKE '%5 BA%' OR copy LIKE '%five bath%')    and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);

#Update Rental Pricing
/*$usql = "update rentals set price = mid(copy,LOCATE('$',copy)+1,locate('/',SUBSTRING(copy,LOCATE('$',copy)+2)))
Where (price = 0 or price is null) and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "update rentals set price = mid(copy,LOCATE('$',copy)+1,locate(' ',SUBSTRING(copy,LOCATE('$',copy)+2)))
Where (price = 0 or price is null) and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "update rentals set price = mid(copy,LOCATE('$',copy)+1,locate('.',SUBSTRING(copy,LOCATE('$',copy)+2)))
Where (price = 0 or price is null) and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "update rentals set price = NULL where price=0"; $uresult = $conn->query($usql);

$usql = "update visionDataDump set contacturl = NULL where LENGTH( LTRIM( RTRIM( contacturl ) ) )  = 0"; $uresult = $conn->query($usql);
$usql = "update visionDataDump set contactphone = NULL where LENGTH( LTRIM( RTRIM( contacphone ) ) )  = 0"; $uresult = $conn->query($usql);
$usql = "update visionDataDump set contactemail = NULL where LENGTH( LTRIM( RTRIM( contactemail ) ) )  = 0"; $uresult = $conn->query($usql);
$usql = "update visionDataDump set price = NULL where LENGTH( LTRIM( RTRIM( price ) ) )  = 0"; $uresult = $conn->query($usql);
*/
#<<<<<<<<<<<<<<<<<<<<<<< END OF RENTALS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

#<<<<<<<<<<<<<<<<<<<<<<< START OF JOBS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

#Move Jobs To Jobs Table then delete from visionDataDump
$usql ="INSERT INTO jobs (adnumber,classification,copy,image,contactphone,contacturl,contactemail,copy2,city,wpcategoryslug,displayad,customer,salesperson)  SELECT adnumber,classification,copy,image,contactphone,contacturl,contactemail,copy2,city,wpcategoryslug, displayad,customer,salesperson FROM visionDataDump   WHERE classification in (400,405) and not adnumber in (Select adnumber from jobs)";
$uresult = $conn->query($usql);
$usql = "Delete from visionDataDump Where classification in(400,405)";  $uresult = $conn->query($usql);


#<<<<<<<<<<<<<<<<<<<<<<< END OF JOBS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

#<<<<<<<<<<<<<<<<<<<<<<< START OF REAL ESTATE >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

#Move Real Estate Ads Into Real Estate Table then delete from visionDataDump
$usql ="INSERT INTO realestate (adnumber, classification,copy,image,contactphone,contacturl,contactemail,copy2,city,wpcategoryslug,customer,salesperson, price)  SELECT adnumber,classification,copy,image,contactphone,contacturl,contactemail,copy2,city,wpcategoryslug,customer,salesperson,price FROM visionDataDump where classification between 600 and 699  and not adnumber in (Select adnumber from realestate)";
$uresult = $conn->query($usql);
$usql = "update realestte set title = left(copy2,70)";  $uresult = $conn->query($usql);
$usql = "Delete from visionDataDump Where classification between 600 and 699";  $uresult = $conn->query($usql);

#Update bedrooms
$usql = "UPDATE rentals SET beds =1 WHERE (copy LIKE '%1 bedroom%' OR copy LIKE '%1BR%' OR copy LIKE '%1 BR%' OR copy LIKE '%one bedroom%' OR copy LIKE '%effic%' OR copy LIKE '%studio%')  and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET beds =2 WHERE (copy LIKE '%2 bedroom%' OR copy LIKE '%2BR%' OR copy LIKE '%2 BR%' OR copy LIKE '%two bedroom%')   and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET beds =3 WHERE (copy LIKE '%3 bedroom%' OR copy LIKE '%3BR%' OR copy LIKE '%3 BR%' OR copy LIKE '%three bedroom%')   and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$affectedRows = $pdo->exec("UPDATE rentals SET beds =4 WHERE (copy LIKE '%4 bedroom%' OR copy LIKE '%4BR%' OR copy LIKE '%4 BR%' OR copy LIKE '%four bedroom%') and DATE(`ts`) = CURDATE()"); $uresult = $conn->query($usql);
$usql = "UPDATE rentals SET beds =5 WHERE (copy LIKE '%5 bedroom%' OR copy LIKE '%5BR%' OR copy LIKE '%5 BR%' OR copy LIKE '%five bedroom%') and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);

#Update Bathrooms
$usql = "UPDATE realestate SET baths =1 WHERE (copy LIKE '%1 bath%' OR copy like '%1 bathroom%' or copy LIKE '%1BA%' OR copy LIKE '%1 BA%' OR copy LIKE '%one bath%'OR copy LIKE '%full bathroom%') and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE realestate SET baths =2 WHERE  (copy LIKE '%2 bath%' OR copy like '%2 bathroom%' or copy LIKE '%2BA%' OR copy LIKE '%2 BA%' OR copy LIKE '%two bath%')    and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE realestate SET baths =3 WHERE  ( copy LIKE '%3 bath%' OR copy like '%3 bathroom%' or copy LIKE '%3BA%' OR copy LIKE '%3 BA%' OR copy LIKE '%three bath%')    and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE realestate SET baths =4 WHERE  (copy LIKE '%4 bath%' OR copy like '%4 bathroom%' or copy LIKE '%4BA%' OR copy LIKE '%4 BA%' OR copy LIKE '%four bath%')   and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "UPDATE realestate SET baths =5 WHERE  (copy LIKE '%5 bath%' OR copy like '%5 bathroom%' or copy LIKE '%5BA%' OR copy LIKE '%5 BA%' OR copy LIKE '%five bath%')    and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);

#Update Real Estate Pricing
/*$usql = "update realestate set price = mid(copy,LOCATE('$',copy)+1,locate('/',SUBSTRING(copy,LOCATE('$',copy)+2)))
Where (price = 0 or price is null) and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "update realestate set price = mid(copy,LOCATE('$',copy)+1,locate(' ',SUBSTRING(copy,LOCATE('$',copy)+2)))
Where (price = 0 or price is null) and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "update realestate set price = mid(copy,LOCATE('$',copy)+1,locate('.',SUBSTRING(copy,LOCATE('$',copy)+2)))
Where (price = 0 or price is null) and DATE(`ts`) = CURDATE()"; $uresult = $conn->query($usql);
$usql = "update realestate set price = NULL where price=0"; $uresult = $conn->query($usql);  */

#<<<<<<<<<<<<<<<<<<<<<<< END OF REAL ESTATE  >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

#<<<<<<<<<<<<<<<<<<<<<<< START OF Classifieds >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

#Move Classifieds to Classifieds Table then delete from visionDataDump
$usql ="INSERT INTO classifieds (adnumber,classification,copy,image,contactphone,contacturl,contactemail,copy2,city,wpcategoryslug,customer,salesperson)  SELECT adnumber,classification,copy,image,contactphone,contacturl,contactemail,copy2,city,wpcategoryslug,customer,salesperson FROM visionDataDump where classification < 899 and not adnumber in (Select adnumber from classifieds)";
$uresult = $conn->query($usql);
$usql = "update classifieds set property_title = left(copy2,70)";  $uresult = $conn->query($usql);
$usql = "Delete from visionDataDump Where classification between 0 and 899";  $uresult = $conn->query($usql);

#<<<<<<<<<<<<<<<<<<<<<<< END OF CLASSIFIEDS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>



echo "<h1>Done!</h1>";
$conn->close();
?>
