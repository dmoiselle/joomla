<?php
//Array 1
/* ensure that this file is called up by another file */
defined( '_VALID_MOS' ) or die( 'Direct access of this file is prohibited.' );
// Loading of the HTML class
require_once( $mainframe->getPath( 'front_html' ) );
//Array 2
$mainframe->setPageTitle( "Services" );
echo "<link rel='stylesheet' href='components/com_destinations/css/destinations.css' type='text/css' />";


$act = mosGetParam($_REQUEST,"act",'');
$option = mosGetParam ($_REQUEST, 'option', '');
$task = mosGetParam ($_REQUEST, 'task', '');
$id = mosGetParam ($_REQUEST, 'id', '');
$cat = mosGetParam ($_REQUEST, 'cat', '');
$pkg = mosGetParam ($_REQUEST, 'pkg', '');
$section = mosGetParam ($_REQUEST, 'section', '');




switch( $act ) {
	case 1:
		viewPackages($cat,$section);
	break;	
	case 2:
		echo"<h3 class='Packagetitle'>$pkg</h3>";
		if($task=="save"):
		booking($_POST['name'],$_POST['age'],$_POST['address'],$_POST['city'],$_POST['country'],$_POST['telephone'],$_POST['email'],$_POST['toVisit'],$_POST['safari'],$_POST['pax'],$_POST['comments']);
		endif;
		viewItinerary($id);
		
	break;
	
	case 3:
		echo"<h3 class=\"componentheading\">Booking Form</h3>";
		bookingForm($pkg);
	break;
	case 6:
		echo"<h3 class=\"componentheading\">Booking Form</h3>";
		journeybookingForm($pkg);
	break;
	case 4:
		//echo"<h3>Booking Form</h3>";
		viewService($_GET['service']);
	break;
	
	case 5:
		echo"<h3 class='Packagetitle'>$pkg</h3>";
		if($task=="save"):
		booking($_POST['name'],$_POST['age'],$_POST['address'],$_POST['city'],$_POST['country'],$_POST['telephone'],$_POST['email'],$_POST['toVisit'],$_POST['safari'],$_POST['pax'],$_POST['comments']);
		endif;
		viewJourney($id);
	break;
	
	case 6:
	echo"Results!";
	break;
	
	case 7:
	echo "<h3 class='Packagetitle'>Make a Reservation</h3>";
	doDirectPayment($_POST['name'],$_POST['address'],$_POST['city'],$_POST['country'],$_POST['telephone'],$_POST['email'],$_POST['numberRooms'],$_POST['safari'],$_POST['criteria'],$_POST['numberAdults']);
	break;
	case 8: 
	echo "<h3 class='Packagetitle'>Payment Confirmation</h3";
	doDirectPaymentReceipt();
	break;
	default:
		echo"<span class='contentheading'>Itineraries</span>";
		componentHome();
	break;
	
	
}

function componentHome(){

	global $database, $mainframe, $mosConfig_list_limit,$option;
	$database->setQuery( "SELECT * FROM `#__mh_service` WHERE  `published`=1 ORDER BY `ordering` ASC ");
		
		$rows = $database->loadObjectList();
		
		if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
		}
		
		HTML_destinations::servicesList($option,$rows);


}

function viewService($service){
	global $database,$option;
	
		$database->setQuery( "SELECT * FROM `#__mh_service` WHERE `id`=$service ");
		
		$rows = $database->loadObjectList();
		
		if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
		}
		HTML_destinations::service($option,$rows);


}

function viewPackages($category,$section)
{

	global $database, $mainframe, $mosConfig_list_limit,$option;
	
		
		$limit = $mainframe->getUserStateFromRequest('viewlistlimit', 'limit', 30);
		$limitstart = $mainframe->getUserStateFromRequest("view{$option}limitstart", 'limitstart', 0);		
		$limit=2;
		$limitstart= mosGetParam ($_REQUEST, 'limitstart', '');
		$database->setQuery( "SELECT count(*) FROM #__mh_packages WHERE `category`='$category' AND `section`='$section' AND `published`=1");
		$total = $database->loadResult();
		echo $database->getErrorMsg();
		if ($limit > $total) {
		$limitstart = 0;
		}
		
		$database->setQuery( "SELECT * FROM `#__mh_packages` WHERE `category`='$category'  AND `section`='$section' AND `published`=1 ORDER BY `title` ASC");
		$rows = $database->loadObjectList();
		$chk=$rows[0];
		
			$database->setQuery( "SELECT id FROM #__mh_service WHERE `name`='$chk->service'");
			$service = $database->loadResult();
			$database->setQuery( "SELECT id FROM #__mh_categories WHERE `name`='$chk->category'");
			$category = $database->loadResult();
		if($service==4 || $service==6 || $category==5):
			echo"<h3 class='Packagetitle'>$chk->category</h3>";
			
			$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			echo"<h4 class='Packagetitle'>$row->title</h4>";
			viewItinerary($row->id);
			}
		else:
		

		$database->setQuery( "SELECT name,description FROM `#__mh_categories` WHERE `name`='$chk->category'  AND `section`='$chk->section' AND `published`=1  ORDER BY `name` ASC ");
		$catName = $database->loadObjectList();
		$catData=$catName[0];
		if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
		}
		echo"<h3>$catData->name</h3>";
		echo"<p>".html_entity_decode($catData->description,ENT_QUOTES)."</p>";
		include_once( "includes/pageNavigation.php" );
		$pageNav = new mosPageNav( $total, $limitstart, $limit  );
		HTML_destinations::PackagesList($option,$rows,$pageNav);
		endif;

}


function viewItinerary($pkg)
{

	global $database;
	
		
		//echo"SELECT * FROM `#__mh_packages` WHERE `category`=$category  ORDER BY `name` ASC LIMIT $limitstart,$limit ";
		$database->setQuery( "UPDATE `#__mh_packages` SET `hits`=(hits+1) WHERE `id`=$pkg");
		if (!$database->query()) {
				echo "<script> alert('".$database->stderr()."');</script>\n";
				exit();
		}
		$database->setQuery( "SELECT `duration`,`description` FROM `#__mh_packages` WHERE `id`=$pkg ");
		$desc = $database->loadObjectList();
		$database->setQuery( "SELECT r.criteria, r.pax, r.price FROM #__mh_packages AS p, #__rates AS r" . " WHERE p.id=r.id AND r.id=$pkg");
		 $rates = $database->loadObjectList();
		
		$database->setQuery( "SELECT count(*) FROM `#__mh_itinerary` WHERE `package`=$pkg ");
		$total = $database->loadResult();
		//if($total>0):
		$database->setQuery( "SELECT * FROM `#__mh_itinerary` WHERE `package`=$pkg  ORDER BY `sequence` ASC");
		$rows = $database->loadObjectList();
		if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
		}
			
		/*else:
			echo $desc->1;
		endif;*/
	
		HTML_destinations::Itinerary($rows,$desc,$rates,$pkg);
}

function viewJourney($pkg) {

	global $database;
		
		//echo"SELECT * FROM `#__mh_packages` WHERE `category`=$category  ORDER BY `name` ASC LIMIT $limitstart,$limit ";
		$database->setQuery( "UPDATE `#__mh_journey` SET `hits`=(hits+1) WHERE `id`=$pkg");
		if (!$database->query()) {
				echo "<script> alert('".$database->stderr()."');</script>\n";
				exit();
			}
		$database->setQuery( "SELECT `description` FROM `#__mh_journey` WHERE `id`=$pkg ");
		$desc = $database->loadResult();	
	    $database->setQuery( "SELECT count(*) FROM `#__mh_journey_itinerary` WHERE `journey`=$pkg ");
		$total = $database->loadResult();	
		$database->setQuery( "SELECT * FROM `#__mh_journey_itinerary` WHERE `journey`=$pkg  ORDER BY `day` ASC");
		$rows = $database->loadObjectList();
		if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
		}
			
		
		HTML_destinations::journeyItinerary($rows,$desc,$pkg);

}
function bookingForm($pkg)
{
	global $database;
	
	$where=" WHERE `published`=1";
	
	if($pkg!=0):
		$where=" WHERE `id`=$pkg ";
	endif;
	
	$database->setQuery( "SELECT * FROM `#__mh_packages`  $where ORDER BY `title` ASC");
	$rows = $database->loadObjectList();
	
	$database->setQuery( "SELECT DISTINCT criteria FROM `#__rates`  $where ");
	$crit = $database->loadObjectList();
	
	if ($database->getErrorNum()) {
	echo $database->stderr();
	return false;
	}
	HTML_destinations::bookingForm($crit, $rows,$pkg,"packages");

}
function journeybookingForm($pkg)
{
	global $database;
	$where="";
	if($pkg!=0):
		$where=" WHERE `id`=$pkg ";
		
	endif;
	
	$database->setQuery( "SELECT * FROM `#__mh_journey` $where ORDER BY `title` ASC");
	$rows = $database->loadObjectList();
	
	if ($database->getErrorNum()) {
	echo $database->stderr();
	return false;
	}
	HTML_destinations::bookingForm($rows,$pkg,"journey");

}
	
function booking($name,$age,$address,$city,$country,$telephone,$email,$toVisit,$safari,$pax,$comments)
	{
	
		
		$message="	Name : $name \n 
					Address : $address \n
					City : $city \n
					Country : $country \n
					Telephone : $telephone \n
					Email : $email \n
					Country to visit : $toVisit \n
					Type of safari : $safari \n
					Number of adults: $pax \n
					Comments : $comments \n";
					//talk2us@mihondestinations.com
		if(mail("info@startraveltour.com","Booking",$message,"From: WebAdmin<www.startraveltour.com>"))echo "Mesage Sent.";			
	
	}
	
function doDirectPayment($name,$address,$city,$country,$telephone,$email,$numberRooms,$safari,$criteria,$numberAdults){
global $database;
$database->setQuery("SELECT * FROM #__rates WHERE rtrim(criteria)='".$criteria."' AND pax=".$numberAdults);
$rows=$database->loadObjectlist();
$row = $rows[0];
$myprice=$row->price;

$amount= $myprice * $numberAdults * $numberRooms;


	HTML_destinations::doDirectPayment($name,$address,$city,$country,$telephone,$email,$numberRooms,$safari,$criteria,$numberAdults, $amount);
}

function doDirectPaymentReceipt() {

/***********************************************************

Submits a credit card transaction to PayPal using a
DoDirectPayment request.

The code collects transaction parameters from the form
displayed by DoDirectPayment.php then constructs and sends
the DoDirectPayment request string to the PayPal server.
The paymentType variable becomes the PAYMENTACTION parameter
of the request string.

After the PayPal server returns the response, the code
displays the API request and response in the browser.
If the response from PayPal was a success, it displays the
response parameters. If the response was an error, it
displays the errors.

Called by DoDirectPayment.php.

Calls CallerService.php and APIError.php.

***********************************************************/

require_once 'CallerService.php';


/**
 * Get required parameters from the web form for the request
 */
$paymentType =urlencode( $_POST['paymentType']);
$firstName =urlencode( $_POST['firstName']);
//$lastName =urlencode( $_POST['lastName']);
$creditCardType =urlencode( $_POST['creditCardType']);
$creditCardNumber = urlencode($_POST['creditCardNumber']);
$expDateMonth =urlencode( $_POST['expDateMonth']);

// Month must be padded with leading zero
$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);

$expDateYear =urlencode( $_POST['expDateYear']);
$cvv2Number = urlencode($_POST['cvv2Number']);
$address1 = urlencode($_POST['address1']);
$address2 = urlencode($_POST['address2']);
$city = urlencode($_POST['city']);
$state =urlencode( $_POST['state']);
$zip = urlencode($_POST['zip']);
$amount = urlencode($_POST['amount']);
//$currencyCode=urlencode($_POST['currency']);
$currencyCode="USD";
$paymentType=urlencode($_POST['paymentType']);

/* Construct the request string that will be sent to PayPal.
   The variable $nvpstr contains all the variables and is a
   name value pair string with & as a delimiter */
$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".         $padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
"&ZIP=$zip&COUNTRYCODE=US&CURRENCYCODE=$currencyCode";

/* Make the API call to PayPal, using API signature.
   The API response is stored in an associative array called $resArray */
$resArray=hash_call("doDirectPayment",$nvpstr);

/* Display the API response back to the browser.
   If the response from PayPal was a success, display the response parameters'
   If the response was an error, display the errors received using APIError.php.
   */
$ack = strtoupper($resArray["ACK"]);

if($ack!="SUCCESS")  {
    $_SESSION['reshash']=$resArray;
	$location = "APIError.php";
		 header("Location: $location");
   }

HTML_destinations::doDirectPaymentReceipt($resArray);
}
?>