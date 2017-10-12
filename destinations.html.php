<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_destinations {
	
	function servicesList($option,$rows){
		global $database;
		?>
		<div id='serviceList'><ul>
			<?php
			
			$javascript="<script language='javascript' type='text/javascript'>
		function dispSite(service)
		{ ";
		
					$k = 0;
					for ($i=0, $n=count( $rows ); $i < $n; $i++) {// for 1
						$row = &$rows[$i];
						
						$javascript.="document.getElementById('div$row->id').style.display = 'none';";
						
						$database->setQuery( "SELECT * FROM `#__mh_section` WHERE `service`='$row->name'  AND `published`=1 ORDER BY `name` ASC ");
						$sections = $database->loadObjectList();
			
						$services.="<div id='div$row->id' >";
				
						//$services.="<h3 class='divtop'>$row->name</h3><p>".html_entity_decode($row->description,ENT_QUOTES)."</p>";
						
						for ($j=0, $m=count( $sections ); $j < $m; $j++) {// for 2
							$sect = $sections[$j];
							$services.="<div id='sect$j'>";
							$services.="<h4> + $sect->name</h4>";
				//$section_list.="<li><a  href='javascript:void(0);' onclick=\"ajaxFunction('$site[0]'); return false;\">$site[1]</a></li>";
							$services.="<ul>";
							$database->setQuery( "SELECT * FROM `#__mh_categories` WHERE `section`='$sect->name' AND `service`='$row->name'  AND `published`=1 ORDER BY `name` ASC ");
							$categories = $database->loadObjectList();
							
							for ($k=0, $o=count( $categories ); $k < $o; $k++) {// for 3
								$cat = $categories[$k];
								$database->setQuery( "SELECT COUNT(*) AS pkgs FROM `#__mh_packages` WHERE `category`='$cat->name' AND `section`='$sect->name' AND `published`=1");
								$pkage = $database->loadResult();
							
								$services.="<li><a  href='index.php?option=$option&act=1&cat=$cat->name&section=$cat->section'>$cat->name ($pkage)</a></li>";	
								}// close for 3
								
							$services.="</ul>";	
							$services.="</div>";
							}// close for 2
							
						
						$services.="</div>";
						//$serv.="<li><a href='javascript:void(0);' onClick='dispSite(\"div$row->id\")'> $row->name</a></li>";
				}// close for 1
			$javascript.="document.getElementById(service).style.display = 'block';
			 }
			 </script>";
			 //first display the javascript that displays the sections & categories when a service is clicked
			echo $javascript;
			//then display the services
			echo $serv;
			
			?>					
		</ul></div>
		<div id="dispList">
		<?php
			echo $services;
		
		
		?>
		</div>
		
		<?php
		}
		
		function service($option,$rows){
		global $database;
		?>
		<!--<div id='serviceList'><ul>-->
			<?php
			
			$javascript="<script language='javascript' type='text/javascript'>
		function dispSite(service)
		{ ";
		
					//$k = 0;
					//for ($i=0, $n=count( $rows ); $i < $n; $i++) {// for 1
						$row = &$rows[0];
						
						//$javascript.="document.getElementById('div$row->id').style.display = 'none';";
						
						$database->setQuery( "SELECT * FROM `#__mh_section` WHERE `service`='$row->name'  AND `published`=1 ORDER BY `name` ASC ");
						$sections = $database->loadObjectList();
			
						//$services.="<div id='div$row->id' style='display:none;'>";
				
						$services.="<h3 class='divtop'>$row->name</h3>
						<p>".html_entity_decode($row->description,ENT_QUOTES)."</p>";
						
						for ($j=0, $m=count( $sections ); $j < $m; $j++) {// for 2
							$sect = $sections[$j];
							$services.="<h4>$sect->name</h4>";
							$services.="<div id='sect$j'>";
			//$section_list.="<li><a  href='javascript:void(0);' onclick=\"ajaxFunction('$site[0]'); return false;\">$site[1]</a></li>";
							$services.="<ul>";
							$database->setQuery( "SELECT * FROM `#__mh_categories` WHERE `section`='$sect->name' AND `service`='$row->name'  AND `published`=1 ORDER BY `name` ASC ");
							$categories = $database->loadObjectList();
							
							for ($k=0, $o=count( $categories ); $k < $o; $k++) {// for 3
								$cat = $categories[$k];
								$database->setQuery( "SELECT COUNT(*) AS pkgs FROM `#__mh_packages` WHERE `category`='$cat->name' AND `section`='$sect->name' AND `published`=1 ");
								$pkage = $database->loadResult();
							
								$services.="<li><a  href='index.php?option=$option&act=1&cat=$cat->name&section=$cat->section'>$cat->name ($pkage)</a></li>";	
								}// close for 3
								
							$services.="</ul>";	
							$services.="</div>";
							}// close for 2
							
						
						//$services.="</div>";
						//$serv.="<li><a href='javascript:void(0);' onClick='dispSite(\"div$row->id\")'> $row->name</a></li>";
				//}// close for 1
			$javascript.="document.getElementById(service).style.display = 'block';
			 }
			 </script>";
			 //first display the javascript that displays the sections & categories when a service is clicked
			//echo $javascript;
			//then display the services
			//echo $serv;
			
			?>
			
			
					
										
 
		<!--</ul></div>-->
		<div id="dispList">
		<?php
			echo $services;
		
		
		?>
		</div>
		
		<?php
		}
		function PackagesList($option,&$rows,&$pageNav){
		$link="index.php?option=com_destinations&act=1";
		?>
		<form action="index.php" method="post" name="adminForm">
		<table width='100%' border='0' cellpadding='2' cellspacing='2' class='packagelist'>
		<th>Title</th><th >Duration (Days)</th></tr>
					<?php
					$k = 0;
					for ($i=0, $n=count( $rows ); $i < $n; $i++) {
					$row = &$rows[$i];
					
					?>
					<tr class="<?php echo "row$k"; ?>">
     <td class='bg_grey'><a href='index.php?option=<?php echo $option; ?>&act=2&id=<?php echo $row->id; ?>&pkg=<?php echo $row->title; ?>'><?php echo $row->title; ?></a></td><td ><?php echo $row->duration; ?></td></tr>
		<?php
		$k = 1 - $k;
			} ?>
    <tr>
      <td align="center" colspan="2"> <?php //echo $pageNav->writePagesLinks($link); ?><p><?php //echo $pageNav->writePagesCounter(); ?></p></td>
    </tr>
    
  </table>
  <input type="hidden" name="act" value="1" />
    <input type="hidden" name="limitstart"  />
  <input type="hidden" name="task" value="" />
  </form><p><a href="javascript:history.go(-1)">[Back]</a></p>
  
		<?php
		
		}
		
function displayRates() {
     global $database;
     // Query database and display packages and rates
     $query = "SELECT p.title, r.criteria, r.pax, r.price" .
	 " FROM #__mh_packages AS p, #__rates AS r" .
     " WHERE c.id=r.id" ;

     $database->setQuery( $query, 0);
     // Make sure rows were returned before outputting
     if($rows = $database->loadObjectList()) {
	 echo "<table border=\"1\"><th><td>Criteria</td><td>Pax</td><td>Price</td></th></tr>";
	 $id="";
	 $criteria="";
          foreach ($rows as $row)
          {
               $rowID = $row->ref;
			   $thisRef=$rowRef;
			   $rowPackage = $row->title;
			   $rowCrit = $row->criteria;
			   $thisCrit=$rowCrit;
			   $rowPax = $row->pax;
			   $rowPrice = $row->price;
			   
			   //$rowRef = htmlspecialchars($row->ref, ENT_QUOTES);
			   if($thisRef!=$ref) {
               		echo "<tr><td>$rowID</td><td>$rowPackage</td><td>$rowCrit</td><td>$rowPax</td><td>$rowPrice</td></tr>";
			   }
			   elseif($thisCrit!=$criteria) {
			   		echo "<tr><td></td><td></td><td>$rowCrit</td><td>$rowPax</td><td>$rowPrice</td></tr>";
			   }
			   else {
			   		echo "<tr><td style=\"border-top:0\"></td><td style=\"border-top:0\"></td><td style=\"border-top:0\">$rowCrit</td><td>$rowPax</td><td>$rowPrice</td></tr>";
			   }
			   
			   
          } //end foreach
     } // end if

}// end function
		
		
				
		function Itinerary($rows,$desc,$rates,$pkg){
		$n=count( $rows );
		$details=$desc[0];
		?>
			<div style="border:1px solid #ccc; padding:3px;">
			<table width='100%' border='0' cellpadding='4' cellspacing='2' class="packagedisp">
			<tr><th colspan="2"><?php echo $details->duration; ?> Days</th></tr>
		<?php
			
			$k = 0;
			for ($i=0, $n; $i < $n; $i++) {//open for
			$row = $rows[$i];
			
			?>
			
			<tr><th><span class="from"><?php echo $row->place; ?></span></th><th><span class="to"><?php echo $row->accomodation; ?></span></th></tr>
			<tr><td colspan="2"><span class="day">Day <?php echo $row->day; ?>:</span> <?php echo html_entity_decode($row->description,ENT_QUOTES); ?></td></tr>
			<?php
					
			}//close for
			if($i==0) $label="Enquire";
			if($i>0) $label="Book";
			?>
			<tr><td colspan="2">
            <table border=\"1\"><tr><th width="35%"></th><th width="10%">Pax</th><th width="10%">Price</th></tr><?php
	 $id="";
	 $criteria="";
	 $j=count($rates);
	 for ($i=0, $j; $i < $j; $i++) {//open for
			$newrow = $rates[$i];
          //foreach ($rates as $newrow)
          //{
               //$rowID = $row->ref;
			   //$thisRef=$rowRef;
			   //$rowPackage = $row->title;
			   $rowCrit = $newrow->criteria;
			   $thisCrit=$rowCrit;
			   $rowPax = $newrow->pax;
			   $rowPrice = $newrow->price;
			   
			   //$rowRef = htmlspecialchars($row->ref, ENT_QUOTES);
			   /*if($thisRef!=$ref) {
               		echo "<tr><td>$rowID</td><td>$rowPackage</td><td>$rowCrit</td><td>$rowPax</td><td>$rowPrice</td></tr>";
			   } */
			   if($thisCrit!=$criteria) {
			   		echo "<tr><td>$rowCrit</td><td>$rowPax</td><td>$rowPrice</td></tr>";
			   }
			   else {
			   		echo "<tr><td style=\"border-top:0\"></td><td>$rowPax</td><td>$rowPrice</td></tr>";
			   }
			   $criteria=$rowCrit;
			   
          } //end for
		  ?></table>
            </td></tr>
			</table>
			<p align="center"><a href="index.php?option=com_destinations&act=3&pkg=<?php echo $pkg; ?>" class="button">[<?php echo $label; ?>]</a> | <a href="javascript:history.go(-1)" class="button">[Back]</a></p></div>
		<?php	
		}
		
		function journeyItinerary($rows,$description,$pkg){
		$n=count( $rows );
		?>
			<div style="border:1px solid #ccc;">
			<table width='100%' border='0' cellpadding='4' cellspacing='2' class="packagedisp">
			<tr><td colspan="2" bgcolor="#FFFFFF"><?php echo html_entity_decode($description,ENT_QUOTES); ?></td>
			</tr>
			<tr><th colspan="2"><?php echo $n; ?> Days</th></tr>
		<?php
			$k = 0;
			for ($i=0, $n; $i < $n; $i++) {//open for
			$row = $rows[$i];
			
			?>
			
			<tr><th><span class="from"><?php echo $row->place; ?></span></th><th><span class="to"><?php echo $row->accomodation; ?></span></th></tr>
			<tr><td colspan="2"><span class="day">Day <?php echo $row->day; ?>: </span></strong><?php echo html_entity_decode($row->description,ENT_QUOTES); ?></td></tr>
			<?php
					
			}//close for
			if($i==0) $label="Enquire";
			if($i>0) $label="Book";
			?>
			</table>
			<p align="center"><a href="index.php?option=com_destinations&act=6&pkg=<?php echo $pkg; ?>" class="button">[<?php echo $label; ?>]</a> | <a href="javascript:history.go(-1)" class="button">[Back]</a></p></div>
			<?php
			
		}
		
		function bookingForm($crit,&$rows,$id,$table)
		{
			global $mosConfig_live_site, $mainframe;
			$validate = josSpoofValue();
			$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++) {//open for loop
			$row = &$rows[$i];
			if($table=="packages")$opt.="<option value='$row->title'>$row->title [$row->service]</option>";
			if($table=="journey")$opt.="<option value='$row->title'>$row->title </option>";
			}//close for
			
			for ($i=0, $n=count( $crit ); $i < $n; $i++) {//open for loop
			$crt = &$crit[$i];
			$criteria.="<option value='$crt->criteria'>$crt->criteria </option>";
			
			}//close for
		?>
		<script language='javascript' type='text/javascript'>
		function submitbutton(pressbutton)
		 {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				history.go(-1);
			}
			
			var ok=true;
			if (pressbutton == 'save') {
				if(form.name.value==""){
				alert("Please enter your name.");
				ok=false;
				}
				if(form.email.value==""){
				alert("Please enter your Email Address.");
				ok=false;
				}
				if(form.pax.value==""){
				alert("Please enter the number of adults.");
				ok=false;
				}
					
						
				if(ok==true) form.submit();
			}
		}
			

		
		</script>
		<div style="float: right;">
					
				</div>
				<br clear="all" />
		<table>
		<form action='index.php' name="adminForm"  method="post" >
		<tr><th colspan="2"> Kindly fill in the details below to help us get in touch with you<br /></th></tr>
		<tr><td><strong>Name</strong></td><td><input type='text' name='name' size='25' /></td></tr>
		<tr><td><strong>Address</strong></td><td><input type='text' name='address' size='25' /></td></tr>
		<tr><td><strong>City</strong></td><td><input type='text' name='city' size='25' /></td></tr>
		<tr><td><strong>Country</strong></td><td><input type='text' name='country' size='25' /></td></tr>
		<tr><td><strong>Telephone</strong></td><td><input type='text' name='telephone' size='25' /></td></tr>
		<tr><td><strong>Email</strong></td><td><input type='text' name='email' size='25' /></td></tr>
		
        <tr><td><strong>Number of rooms</strong></td><td><select name="numberRooms" id="numberRooms" >
                         <option value="1">1</option>
                         <option value="2">2</option>
                         <option value="3">3</option>
                         <option value="4">4</option>
                         <option value="5">5</option>
                         <option value="6">6</option>
                         <option value="7">7</option>
                         <option value="8">8</option>
                         <option value="9">9</option>

                         
                       </select></td></tr>
        <tr><td><strong>Type of safari</strong></td><td><select name="safari" class="smallgrey"><?php echo $opt; ?></select></td></tr>
        <tr><td><strong>Criteria</strong></td><td><select name="criteria" class="smallgrey"><?php echo $criteria; ?></select></td></tr>
		<tr><td><strong>Guests per room</strong></td><td><table><tr>
                   <td colspan="3" class="widgetTxt">
                       
                   </td>
               </tr>

             <tr>
                 <td style="vertical-align: bottom;" width="33%">
                     <label for="numberAdults">adults<br/>(18+)</label>
                 </td>
                <td width="33%">
                    <label for="numberBigChildren">children<br/>(13-17)</label>
                </td>

                <td width="33%">
                    <label for="numberChildren">children<br/>(0-12)</label>
                </td>
             </tr>
               <tr>
                   <td style="vertical-align: bottom;">
                       <select name="numberAdults" id="numberAdults" class="widget">
                         <option value="1">1</option>

                         <option value="2">2</option>
                         <option value="3">3</option>
                         
                       </select>
                   </td>
                 <td>
                     <select name="numberBigChildren" id="numberBigChildren" class="widget">

                       <option value="0">0</option>
                       <option value="1">1</option>
                       <option value="2">2</option>
                       <option value="3">3</option>
                       
                     </select>

                 </td>
                   <td>
                       <select name="numberChildren" id="numberChildren" class="widget">
                         <option value="0">0</option>
                         <option value="1">1</option>
                         <option value="2">2</option>
                         <option value="3">3</option>

                         
                       </select>
                   </td>
               </tr></table></td>
		<tr><td><strong>Other questions or comments</strong></td><td><textarea name="comments" cols="20" rows="5"></textarea></td></tr>
		<tr><td colspan="2" style="text-align:right; padding-right:5px; padding-top:7px">
        
        <input type="submit" name="submit" class="button"  value="Make a Reservation"/></td></tr>
        
        
		

		<?php     //editorArea( 'editor1', '' , 'comments', '600', '400', '70', '15'); ?>
		
		<input type="hidden" name="option" value="com_destinations" />
		<input type="hidden" name="act" value="7" />
		
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		
		</form>
		</table>
		<?php
		
		//}//close for loop
		}
		
		function doDirectPayment($name,$address,$city,$country,$telephone,$email,$numberRooms,$safari,$criteria,$numberAdults, $amount) { ?>
		<form action="index.php" method="POST" >
	<input type=hidden name=paymentType value="<?=$paymentType?>" />
        <center>
        <table class="api">
        
                <td class="thinfield"> Name:</td>
                <td align=left><input type="text" size="30" maxlength="32" name="firstName" value="<?php echo $name ?>" /></td>
            </tr>
            
            <tr>
                <td class="thinfield">
                    Card Type:</td>
                <td>
                    <select name="creditCardType">
                    <option></option>
                    <option value="Visa" selected="selected">Visa</option>
                    <option value="MasterCard">MasterCard</option>
                    <option value="Discover">Discover</option>
                    <option value="Amex">American Express</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Card Number:</td>
                <td>
                    <input type="text" size="19" maxlength="19" name="creditCardNumber" value="" /></td>
            </tr>
            <tr>
                <td class="thinfield">
                    Expiration Date:</td>
                <td>
                    <select name="expDateMonth">
                    <option value="1">01</option>
                    <option value="2">02</option>
                    <option value="3">03</option>
                    <option value="4">04</option>
                    <option value="5">05</option>
                    <option value="6">06</option>
                    <option value="7">07</option>
                    <option value="8">08</option>
                    <option value="9">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    </select>
                    <select name="expDateYear">
                    <option value="2004">2004</option>
                    <option value="2005">2005</option>
                    <option value="2006">2006</option>
                    <option value="2007">2007</option>
                    <option value="2008">2008</option>
                    <option value="2009">2009</option>
                    <option value="2010" selected>2010</option>
                    <option value="2011">2011</option>
                    <option value="2012">2012</option>
                    <option value="2013">2013</option>
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Card Verification Number:</td>
                <td>
                    <input type="text" size="3" maxlength="4" name="cvv2Number" value="" /></td>
            </tr>
            
            <tr>
                <td class="thinfield">
                    Billing Address:
                </td>
                <td>
                    <input type="text" size="25" maxlength="100" name="address1" value="<?php echo $address ?>" /></td>
            </tr>
            
            <tr>
                <td class="thinfield">
                    City:
                </td>
                <td>
                    <input type="text" size="25" maxlength="40" name="city" value="<?php echo $city ?>" /></td>
            </tr>
            <tr>
                <td class="thinfield">
                    State:
                </td>
                <td>
                    <select name="state">
                    <option></option>
                    <option value="AK">AK</option>
                    <option value="AL">AL</option>
                    <option value="AR">AR</option>
                    <option value="AZ">AZ</option>
                    <option value="CA" selected>CA</option>
                    <option value="CO">CO</option>
                    <option value="CT">CT</option>
                    <option value="DC">DC</option>
                    <option value="DE">DE</option>
                    <option value="FL">FL</option>
                    <option value="GA">GA</option>
                    <option value="HI">HI</option>
                    <option value="IA">IA</option>
                    <option value="ID">ID</option>
                    <option value="IL">IL</option>
                    <option value="IN">IN</option>
                    <option value="KS">KS</option>
                    <option value="KY">KY</option>
                    <option value="LA">LA</option>
                    <option value="MA">MA</option>
                    <option value="MD">MD</option>
                    <option value="ME">ME</option>
                    <option value="MI">MI</option>
                    <option value="MN">MN</option>
                    <option value="MO">MO</option>
                    <option value="MS">MS</option>
                    <option value="MT">MT</option>
                    <option value="NC">NC</option>
                    <option value="ND">ND</option>
                    <option value="NE">NE</option>
                    <option value="NH">NH</option>
                    <option value="NJ">NJ</option>
                    <option value="NM">NM</option>
                    <option value="NV">NV</option>
                    <option value="NY">NY</option>
                    <option value="OH">OH</option>
                    <option value="OK">OK</option>
                    <option value="OR">OR</option>
                    <option value="PA">PA</option>
                    <option value="RI">RI</option>
                    <option value="SC">SC</option>
                    <option value="SD">SD</option>
                    <option value="TN">TN</option>
                    <option value="TX">TX</option>
                    <option value="UT">UT</option>
                    <option value="VA">VA</option>
                    <option value="VT">VT</option>
                    <option value="WA">WA</option>
                    <option value="WI">WI</option>
                    <option value="WV">WV</option>
                    <option value="WY">WY</option>
                    <option value="AA">AA</option>
                    <option value="AE">AE</option>
                    <option value="AP">AP</option>
                    <option value="AS">AS</option>
                    <option value="FM">FM</option>
                    <option value="GU">GU</option>
                    <option value="MH">MH</option>
                    <option value="MP">MP</option>
                    <option value="PR">PR</option>
                    <option value="PW">PW</option>
                    <option value="VI">VI</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    ZIP Code:
                </td>
                <td>
                    <input type="text" size="10" maxlength="10" name="zip" value="" />(5 or 9 digits)
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Country:
                </td>
                <td>
                    United States
                </td>
            </tr>
            <tr>
                <td class="thinfield">
                    Amount:</td>
                <td>
                    <input readonly type="text" size="4" maxlength="7" name="amount" value="<?php echo $amount ?>" /> USD	
                                                            
                </td>
            </tr>
			<tr>
			<td/>
			<td align=left><b>(DoDirectPayment only supports USD at this time)</b></td>
			</tr>
            <tr>
                <td class="field">
                </td>
                <td>
                    <input type="Submit" value="Submit" /></td>
            </tr>
        </table>
        </center></center>
        <input type="hidden" name="act" value= "8" />
        
    </form> <?php
		}
	
function doDirectPaymentReceipt($resArray) { ?>
	<b>Thank you for your payment!</b><br><br>
	
    ?><table width = 400>
        <tr>
            <td>
                Transaction ID:</td>
            <td><?=$resArray['TRANSACTIONID'] ?></td>
        </tr>
        <tr>
            <td>
                Amount:</td>
            <td><?=$currencyCode?> <?=$resArray['AMT'] ?></td>
        </tr>
        <tr>
            <td>
                AVS:</td>
            <td><?=$resArray['AVSCODE'] ?></td>
        </tr>
        <tr>
            <td>
                CVV2:</td>
            <td><?=$resArray['CVV2MATCH'] ?></td>
        </tr>
    </table><?php
}//end function doDirectPaymentReceipt



	}
	
?>	