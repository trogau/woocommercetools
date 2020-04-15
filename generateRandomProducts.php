<?php
/**
 * Output a CSV with a whole bunch of new randomly created products based off the WooCommerce sample data. 
 */
$currentIndex = 0;

// This is the included sample products CSV that ships with WooCommerce
if (($handle = fopen("sample_products.csv", "r")) !== FALSE) 
{
    // A bunch of arrays so we can make variations of some of the product types
    $clothesTypes = array();
    $clothesSkus = array();
	$clothesCategories = array();
	$clothesIndexes = array();

	$rowList = array();
	$row = 0;

    while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) 
    {
        $num = count($data);
        $row++;

        // Create a copy of this row to easily modify later
        $rowList[$data[0]] = $data;

        for ($c=0; $c < $num; $c++) 
        {
            echo "\"".$data[$c] . "\",";
        }

        // Just pick the simple type products to copy and make variations of
        if ($data[1] === 'simple')
        {
            $clothesTypes[] = $data[3];
            $clothesSkus[] = $data[2];
			$clothesCategories[] = $data[25];
			$clothesIndexes[] = $data[0];
        }
        $currentIndex = $data[0];

        print "\r\n";
    }
    fclose($handle);
}

$output = fopen("php://output",'w') or die("Can't open php://output");

for ($i = 0; $i < sizeof($clothesTypes); $i++)
{
	for ($k = 0; $k < 1000; $k++)
	{
		// copy the row for this index
		$newRow = $rowList[$clothesIndexes[$i]];

		// update the fields we want to modify
		$currentIndex += 2; // add two to the index ID just to spread out a range of different IDs
		$newRow[0] = $currentIndex; // set the new ID
		$newRow[2] = $newRow[2]."-".$currentIndex; // set the new product code
		$newRow[3] = ucfirst(getRandomColour()). " ".$newRow[3]. " Model ".$currentIndex; // set the new product name

		fputcsv($output, $newRow, ",", "\"");
	}
}

function getRandomColour()
{
    $colours = array("red", "green", "blue", "yellow", "purplue", "octarine", "orange", "white", "black", "grey", "turquoise", "ruby", "silver", "gold", "diamond", "cream", "bone", "off-white", "ivory", "beige", "chartreuse", "emerald", "pink");

    return ($colours[rand(0,sizeof($colours)-1)]);
}
