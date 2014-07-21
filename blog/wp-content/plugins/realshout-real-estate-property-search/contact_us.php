<? ob_start();
   error_reporting(0);
   include('../../../wp-config.php');
   
   
   if (isset($_REQUEST['id'])) {
   // new
	//$rs = mysql_query("SELECT details FROM temp_listing WHERE id = '{$_GET['id']}'");
	$rs = mysql_query("SELECT listing_id FROM temp_listing WHERE id = ".$_GET['id']);
	$row = mysql_fetch_array($rs);
	$str = $row['listing_id'];
	//
	
	$details = file_get_contents('http://www.google.com/base/feeds/snippets/'.$str);
	
	preg_match_all("/<g:location type='location'>(.*?)<\/g:location>/",$details,$locs); 
    $Vd5189de0 = $locs[1][0];	
	$location = $Vd5189de0;
	
	preg_match_all("/<g:bathrooms type='float'>(.*?)<\/g:bathrooms>/",$details,$ba); 
    $Vd01befa8 = $ba[1][0];
	$bath = $Vd01befa8;
	
	preg_match_all("/<g:bedrooms type='int'>(.*?)<\/g:bedrooms>/",$details,$br); 
    $Vcff8b1fe = $br[1][0];
	$bedroom = $Vcff8b1fe;
	
	preg_match_all("/<g:image_link type='url'>(.*?)<\/g:image_link>/",$details,$imgs); 
    $V78805a22 = $imgs[1][0];
	$image = $V78805a22;
	
	preg_match_all("/<g:property_type type='text'>(.*?)<\/g:property_type>/",$details,$pt); 
    $V23a5b8ab = $pt[1][0];	
	$prop = $V23a5b8ab;
	
	
	$inter = '';
	
	preg_match_all("/<g:expiration_date type='dateTime'>(.*?)<\/g:expiration_date>/",$details,$edt); 
    $Vb0ab0254 = $edt[1][0];
	$exp = $Vb0ab0254;
		
	preg_match_all("/<g:feature type='text'>(.*?)<\/g:feature>/",$details,$ftr); 
    $V1ba8aba1 = $ftr[1][0];
	$feat = $V1ba8aba1;
	
	preg_match_all("/<g:broker type='text'>(.*?)<\/g:broker>/",$details,$bkr); 
    $Vb33aed8f = $bkr[1][0]; 	
	$agent = $Vb33aed8f;
	
	preg_match_all("/<g:price type='floatUnit'>(.*?)<\/g:price>/",$details,$price); 
    $V78a5eb43 = $price[1][0];
	$price = $V78a5eb43;
	
	preg_match_all("/<content type='html'>(.*?)<\/content>/",$details,$dtl); 
    $V20d4441a = $dtl[1][0];
	$dtls = $V20d4441a;
	
	
	// for date
	$tmp_arr = explode("T",$exp);
    $exp_arr = explode("-",$tmp_arr[0]);
	
	$time = mktime(0,0,0,$exp_arr[1],$exp_arr[2],$exp_arr[0]);
	
	$exp = date('F d, Y',$time);
	//
	
   }
   
   if (isset($_GET['uid'])){
    //$rs = mysql_query("SELECT * FROM user_listing WHERE id = '{$_GET['uid']}'");
	$rs = mysql_query("SELECT listing_id FROM user_listing WHERE id = ".$_GET['uid']);
	$row = mysql_fetch_array($rs);
	$str = $row['listing_id'];
	
	$details = file_get_contents('http://www.google.com/base/feeds/snippets/'.$str);
	
	preg_match_all("/<g:location type='location'>(.*?)<\/g:location>/",$details,$locs); 
    $Vd5189de0 = $locs[1][0];	
	$location = $Vd5189de0;
	
	preg_match_all("/<g:bathrooms type='float'>(.*?)<\/g:bathrooms>/",$details,$ba); 
    $Vd01befa8 = $ba[1][0];
	$bath = $Vd01befa8;
	
	preg_match_all("/<g:bedrooms type='int'>(.*?)<\/g:bedrooms>/",$details,$br); 
    $Vcff8b1fe = $br[1][0];	
	$bedroom = $Vcff8b1fe;	
	
	preg_match_all("/<g:image_link type='url'>(.*?)<\/g:image_link>/",$details,$imgs); 
    $V78805a22 = $imgs[1][0];
	$image = $V78805a22;
	
	preg_match_all("/<g:expiration_date type='dateTime'>(.*?)<\/g:expiration_date>/",$details,$edt); 
    $Vb0ab0254 = $edt[1][0];
	$exp_ = $Vb0ab0254;
	
	preg_match_all("/<g:feature type='text'>(.*?)<\/g:feature>/",$details,$ftr); 
    $V1ba8aba1 = $ftr[1][0];
	$feat = $V1ba8aba1;
	
	preg_match_all("/<g:broker type='text'>(.*?)<\/g:broker>/",$details,$bkr); 
    $Vb33aed8f = $bkr[1][0];	
	$agent = $Vb33aed8f;
	
	preg_match_all("/<g:price type='floatUnit'>(.*?)<\/g:price>/",$details,$price); 
    $V78a5eb43 = $price[1][0];
	$price = $V78a5eb43;	
	
	preg_match_all("/<content type='html'>(.*?)<\/content>/",$details,$dtl); 
    $V20d4441a = $dtl[1][0];
	$dtls = $V20d4441a;
	
	
	// for date
	$tmp_arr = explode("T",$exp_);
    $exp_arr = explode("-",$tmp_arr[0]);
	
	$time = mktime(0,0,0,$exp_arr[1],$exp_arr[2],$exp_arr[0]);
	
	$exp = date('F d, Y',$time);
   }
   	
?>
<? if (isset($_REQUEST['submit']))
   { 
	
	$subject = 'Property Inquiry From Your Website';
	// body
	$message = 'Subject : '.$_REQUEST['subject'].'<br/>';
	//$message.= 'Country : '.$_REQUEST['select_country'].'<br/>';
	$message.='<br/>';
	$message.='Inquiry : '.$_REQUEST['enquiry'];
	$message.='<br/>';
	$message.='Details : '.$_REQUEST['details'].'<BR/><img src="'.$_REQUEST['image'].'" width="200" alt="property image"><BR/>';	
	$message.='Property Location : '.$_REQUEST['loc'].'<BR/>';
	$message.='Price : '.$_REQUEST['price'].'<BR/>';
	$message.='Beds: '.$_REQUEST['br'].'&nbsp;&nbsp;&nbsp;Baths: '.$_REQUEST['ba'].'<BR/>';
	//$message.='Expiration Date : '.$_REQUEST['exp'].'<BR/>';
	$message.='Listing Agent/Broker: '.$_REQUEST['agent'].'<BR/>';
	//$message.='Features : '.$_REQUEST['feat'].'<BR/>';

	// From
	$fromemail=$_POST['email'];
	$fromname=$_POST['name'];
	$to=$_REQUEST['mail'];
	$header = "From: $fromname <$fromemail>\n" . "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1\n" . "Return-Path: <$to>";
		
	//$header  = 'MIME-Version: 1.0' . "\r\n";
	//$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	//$header.= 'From: '.$_REQUEST['name'].' <'.$_REQUEST['email'].'>';

	// Enter your email address
		
	// Check, if message sent to your email
	// display message "We've recived your information"
	 $query_contact="INSERT INTO contact_us (subject, message, from_name, from_email, location) values ('".addslashes($subject)."','".addslashes($message)."','".$_REQUEST['name']."','".$_REQUEST['email']."','".addslashes($_REQUEST['loc'])."')";
		
	$rs = mysql_query($query_contact);	

	if ($rs){
	 mail($to,$subject,$message,$header);
	 header("Location: contact_us.php?confirm=1");
	} 
	?>
	
	<?php

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contact US</title>
<link href="css/style.css" rel="stylesheet" />

<script>
 function form_Validate(){
  if (document.contact.name.value == ""){
   alert('Please enter name');
   document.contact.name.focus();
   return false;
  }
  
  if (document.contact.email.value == ""){
   alert('Please enter email');
   document.contact.email.focus();
   return false;
  }
  if (document.contact.email.value != ""){
	  if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.contact.email.value))){
	   alert("Invalid E-mail Address! Please re-enter.")
	   document.contact.email.value = '';
	   document.contact.email.focus();
	   return false;
	 } 
  }
  if (document.contact.subject.value == ""){
   alert('Please enter subject');
   document.contact.subject.focus();
   return false;
  }
  if (document.contact.enquiry.value == ""){
   alert('Please enter enquiry');
   document.contact.enquiry.focus();
   return false;
  }   
 }
</script>
</head>
<body>

				 <br/>
				 <? 
				 if ($_GET['confirm']==1)
				 {
				 	echo '<h2 align="center" style="font-size:16px;font-family:verdana">Thank you for inquiry.  I will be in touch soon.</h2>';
				 }
				 if ($_GET['confirm']=="")
				 {
				 ?>
				 <form name="contact" action="" method="post" enctype="multipart/form-data">
				  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" style="font-size:12px;font-family:verdana">
				         <tr>
						 <td colspan="4" style="padding-left:10px">
						 <h2 style="font-size:16px;font-family:verdana">Contact Us</h2>
						 <p style="font-size:12px;font-family:verdana">Submit your information in the following form and we will get back to you asap.</p>
						 </td>
						 </tr>
						 <tr>
						  <td align="right" valign="top" width="22%">Name</td>
						  <td align="center" valign="top" width="1%"><strong>:</strong></td>
						  <td align="left" valign="top" ><input type="text" name="name" id="name" /></td>
						  <td align="left" valign="top"  width="28%">*</td>
						 </tr>
						 <tr>
						  <td align="right" valign="top" >Email</td>
						  <td align="center" valign="top" ><strong>:</strong></td>
						  <td align="left" valign="top" ><input type="text" name="email" id="email" value=""   /></td>
						  <td align="left" valign="top" >*</td>
						 </tr>
						 <tr>
						  <td align="right" valign="top" >Subject</td>
						  <td align="center" valign="top" ><strong>:</strong></td>
						  <td align="left" valign="top" ><input type="text" name="subject" id="subject"  /></td>
						  <td align="left" valign="top" >*</td>
						 </tr>
									 
						 <tr>
						  <td align="right" valign="top" >Inquiry</td>
						  <td align="center" valign="top" ><strong>:</strong></td>
						  <td align="left" valign="top" ><textarea name="enquiry" id="enquiry" cols="30" rows="7" ></textarea></td>
						  <td align="left" valign="top" >*</td>
						 </tr>
						 
						 <tr>
						  <td align="right" valign="top" class="">&nbsp;</td>
						  <td align="center" valign="top" class="">&nbsp;</td>
						  <td align="center" valign="top" class=""><input type="submit" name="submit"  value="Submit" onClick="return form_Validate();" /></td>
						  <td align="left" valign="top" class="">
						  <input type="hidden" value="<?=get_option('email')?>" name="mail" />
						  <input type="hidden" value="<?=$location?>" name="loc" />
						  <input type="hidden" value="<?=$prop?>" name="inter" />
						  <input type="hidden" value="<?=$price?>" name="price" />
						  <input type="hidden" value="<?=$image?>" name="image" />
						  <input type="hidden" value="<?=$feat?>" name="feat" />
						  <input type="hidden" value="<?=$agent?>" name="agent" />
						  <input type="hidden" value="<?=$exp?>" name="exp" />
						  <input type="hidden" value="<?=$bath?>" name="ba" />
						  <input type="hidden" value="<?=$bedroom?>" name="br" />
						  <input type="hidden" value="<?=$dtls?>" name="details" />
						  </td>
						 </tr>
					   </table>
				      </form>
					<?
					}
					?>  
					</td>
                <td width="8" align="left" valign="top"></td>
                <td width="29%" align="left" valign="top">
				
				</td>
              </tr>
			  <tr><td></td>&nbsp;<td>
</td></tr>
            </table></td>
        </tr>
        <tr>
          <td height="12" align="left" valign="top"></td>
        </tr>
        <tr>
          <td height="75" align="left" valign="middle" bgcolor="#E9E9E9"></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>