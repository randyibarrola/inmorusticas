<script language="javascript">
window.moveTo(200,150);
</script>
<?
 ob_start();
 

  include('../../../wp-config.php'); 
  if (isset($_REQUEST['submit']))
   {
  
	$ph = $_REQUEST['ph'];
	$mail=$_REQUEST['email'];
	$pass=$_REQUEST['pass'];
	$name=$_REQUEST['name'];
		
	$check_mail="SELECT * FROM user WHERE email='$mail'";
	$result=mysql_query($check_mail);
	$mail_result=mysql_num_rows($result);
	if($mail_result>0)
	{
	echo "<p align='center'><strong>An account is already created with this email address</strong></p>";
	}
	else
	{
	$confirm= "<p align='center'><strong>Congratulation, your account has been created.</strong><br /><a href=\"javascript:void(0)\" onClick=\"window.close();\">CLOSE THIS WINDOW AND LOG IN USING THE LOGIN FORM</a></p>";
	$query="insert into user (name,pass,ph,email) values ('$name','$pass','$ph','$mail')";
	mysql_query($query);
	}
		
	?>
	
	<?php
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Registration</title>
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<link href="css/style.css" rel="stylesheet" />

<script>
 function form_Validate(){
  if (document.contact.name.value == ""){
   alert('Please enter name');
   document.contact.name.focus();
   return false;
  }
  
   if (document.contact.pass.value == ""){
   alert('Please enter password.');
   document.contact.pass.focus();
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
				 <br/>
				 <?
				 if ($confirm=="")
				 {
				 ?>
				 <form name="contact" action="" method="post" enctype="multipart/form-data">
				  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" style="font-size:12px;font-family:verdana">
				  	     <tr>
						 <td colspan="4" style="padding-left:10px">
						 <h2 style="font-size:16px;font-family:verdana">Member Sign Up</h2>
						 <p style="font-size:12px;font-family:verdana">Sign up for a quick account and save listings in your members area</p>
						 </td>
						 </tr>
				         <tr>
						  <td align="right" valign="top" class="normtxt" width="22%">User name</td>
						  <td align="center" valign="top" class="normtxt" width="1%"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input name="name" type="text" id="name" size="30" /></td>
						  <td align="left" valign="top" class="normtxt" width="28%">*</td>
						 </tr>
						 
						  <tr>
						  <td align="right" valign="top" class="normtxt">Password</td>
						  <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="password" name="pass" id="pass" value="" size="30" /></textarea></td>
						  <td align="left" valign="top" class="normtxt">*</td>
						 </tr>
						 
						 
						  <tr>
						  <td align="right" valign="top" class="normtxt">Email</td>
						  <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="text" name="email" id="email" value=""  size="30" /></td>
						  <td align="left" valign="top" class="normtxt">*</td>
						 </tr>
						 <tr>
						  <td align="right" valign="top" class="normtxt">Phone</td>
						  <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="text" name="ph" id="ph" size="30" /></td>
						  <td align="left" valign="top" class="normtxt"></td>
						 </tr>
									 
						
						 
						 <tr>
						  <td align="right" valign="top" class="">&nbsp;</td>
						  <td align="center" valign="top" class="">&nbsp;</td>
						  <td align="center" valign="top" class=""><input type="submit" name="submit" class="normtxt" value="Sign Up" onClick="return form_Validate();" /></td>
						  <td align="left" valign="top" class="">
						    </td>
						 </tr>
					   </table>
				      </form>
					  <?
					  }
					  else
					  {
					  	echo $confirm;
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
