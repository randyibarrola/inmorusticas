function F2c4f30a8()
	{
 
 if (document.login.user_email.value == "")
 {
 alert('Please enter email address.');
document.login.user_email.focus();
return false;
}

 if (document.login.V186bca78.value == "")
 {
 alert('Please enter password.');
document.login.user_pass.focus();
return false;
}
}

	function Faf06cf26(value)
	{ 
 
 if(value != "")
 { 
 V572d4e42 = '<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/fetch.php?id='+value; 
 AjaxRequest.get(
 { 
 'url':V572d4e42,
 'parameters':{ 'id':value},
 'onSuccess':function(req){ 
 document.getElementById('dn').innerHTML = req.responseText;
},'onLoading':function() { document.getElementById('dn').innerHTML = 'loading...'; }
}
);
} 
 }

	
	function Ff9ab0545()
	{
 if (document.form1.V9ed39e2e.value == "")
 {
 alert('Please select state');
document.form1.V9ed39e2e.focus();
return false;
}

 if (document.form1.V4ed5d2ea.value == "")
 {
 alert('Please select city');
document.form1.V4ed5d2ea.focus();
return false;
}

 if (document.form1.V4aea81fe.value == "")
 {
 alert('Please select listing type');
document.form1.V4aea81fe.focus();
return false;
}
if (document.form1.V53ce7d32.value!="")
	{
 n=document.form1.V53ce7d32.value.length;
for (i=0;i<n; i++)
 {
	
 cchar=document.form1.V53ce7d32.value.charAt(i);
if (parseFloat(cchar)|| (cchar=='.')||(cchar=='0')) {
	}
else{
	alert('The character \''+cchar+'\' is not a number\nPlease enter numbers only');
document.form1.V53ce7d32.value='';
return false;
}
}

	}

	
	
  
 if (document.form1.V67eb2711.value!=""){
 n=document.form1.V67eb2711.value.length;
for (i=0;i<n; i++)
 {
	
 cchar=document.form1.V67eb2711.value.charAt(i);
if (parseFloat(cchar)|| (cchar=='.')||(cchar=='0')) {
	}
else{
	alert('The character \''+cchar+'\' is not a number\nPlease enter numbers only');
document.form1.V67eb2711.value='';
return false;
}
}

	}
}