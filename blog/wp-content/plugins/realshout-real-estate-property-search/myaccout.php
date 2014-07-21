<script language="javascript">
window.moveTo(200,150);

function form_Validate(){
  if (document.contact.old.value == ""){
   alert('Please enter old password.');
   document.contact.old.focus();
   return false;
  }
  if (document.contact.newd.value == ""){
   alert('Please enter new password.');
   document.contact.newd.focus();
   return false;
  }
  if (document.contact.confirmg.value == ""){
   alert('Please confrim password.');
   document.contact.confirmg.focus();
   return false;
  }

 }
</script>
</script>
<?
 ob_start();
   include('../../../wp-config.php');
 $user_id=$_SESSION['user_id'];
  if(isset($_REQUEST['user']))
  {
  $query_user="select * from user where id='$user_id'";
  $result_user=mysql_query( $query_user);
  $result_user_row=mysql_fetch_row( $result_user);
  
  
  }
  
  
  if (isset($_REQUEST['submit']))
   {
  
  $query_user="select * from user where id='$user_id'";
  $result_user=mysql_query( $query_user);
  $result_user_row=mysql_fetch_row( $result_user);
	$pass=$result_user_row[1];
	
	
	
	if($pass==$_REQUEST['old'])
	{
	
		if($_REQUEST['newd']==$_REQUEST['confirmg'])
		{
			
		   $new_pass=$_REQUEST['newd'];
		   $update_password="update user set pass='$new_pass' where id='$user_id'";
			mysql_query($update_password);
			echo "<strong>Successfully updated.</strong>";
			
		}
	
	
	}  
	
	else
	echo "<strong>Password is not correct.</strong>";
 

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contact US</title>
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

</head>
<body>

				 <br/>
				 <br/>
				 <br/>
				 
				 <form name="contact" action="" method="post" enctype="multipart/form-data">
				  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
				         <tr>
						  <td align="right" valign="top" class="normtxt" width="22%">Name</td>
						  <td align="center" valign="top" class="normtxt" width="1%"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="text" name="name" id="name"  value="<?= $result_user_row[2]?>"/></td>
						  <td align="left" valign="top" class="normtxt" width="28%"></td>
						 </tr>
						 <tr>
						  <td align="right" valign="top" class="normtxt">Old password</td>
						  <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="password" name="old" id="email" value=""   /></td>
						  <td align="left" valign="top" class="normtxt">*</td>
						 </tr>
						
						 <tr>
						  <td align="right" valign="top" class="normtxt">New password</td>
						  <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="password" name="newd"  /></td>
						  <td align="left" valign="top" class="normtxt">*</td>
						 </tr>
						 
						  <tr>
						  <td align="right" valign="top" class="normtxt">Confirm password</td>
						  <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="password" name="confirmg"  /></td>
						  <td align="left" valign="top" class="normtxt">*</td>
						 </tr>
						 
						 <tr>
						  <td align="right" valign="top" class="normtxt">Phone</td>
						  <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="text" name="phone"  value="<?= $result_user_row[3]?>" /></td>
						  <td align="left" valign="top" class="normtxt"></td>
						 </tr>
						 
						  <tr>
						  <td align="right" valign="top" class="normtxt">Email</td>
						  <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
						  <td align="left" valign="top" class="normtxt"><input type="text" name="email" readonly=""  value="<?= $result_user_row[4]?>" /></td>
						  <td align="left" valign="top" class="normtxt"></td>
						 </tr>
									 
						 
						 
						 <tr>
						  <td align="right" valign="top" class="">&nbsp;</td>
						  <td align="center" valign="top" class="">&nbsp;</td>
						  <td align="center" valign="top" class=""><input type="submit" name="submit" class="normtxt" value="Save" onClick="return form_Validate();" /></td>
						  <td align="left" valign="top" class="">
						  <input type="hidden" value="" name="mail" />
						  </td>
						 </tr>
					   </table>
				      </form></td>
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
