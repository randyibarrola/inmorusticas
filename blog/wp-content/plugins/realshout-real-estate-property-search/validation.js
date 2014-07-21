	
	function validate_login()
	{
	 
	 if (document.login.user_email.value == "")
	 {
	   alert('Please enter email address.');
	   document.login.user_email.focus();
	   return false;
	  }
	  
	   if (document.login.user_pass.value == "")
	   {
	   alert('Please enter password.');
	   document.login.user_pass.focus();
	   return false;
	  }
	}
	
	function checkAvailability(value)
	{ 
		
	  if(value != "")
	  { 
		  url = '<?=get_settings('home')?>/wp-content/plugins/GoogleBasePlugin/fetch.php?id='+value; 
		  AjaxRequest.get(
		  { 
			  'url':url,
			  'parameters':{ 'id':value},
			  'onSuccess':function(req){ 
			  document.getElementById('dn').innerHTML =  req.responseText;
			   },'onLoading':function() { document.getElementById('dn').innerHTML = 'loading...'; }
		  }
		 );
		} 
	  }
	
	
	function validate()
	{
	 if (document.form1.state.value == "")
	  {
		alert('Please select state');
		document.form1.state.focus();
		return false;
	  }
	  
	  if (document.form1.city.value == "")
	  {
		alert('Please select city');
		document.form1.city.focus();
		return false;
	  }
	  
	  if (document.form1.listing.value == "")
	  {
		alert('Please select listing type');
		document.form1.listing.focus();
		return false;
	  }

	if (document.form1.start_price.value!="")
	{
	 n=document.form1.start_price.value.length;
	 for (i=0;i<n; i++)
	 {
	
			cchar=document.form1.start_price.value.charAt(i);
	  if (parseFloat(cchar)|| (cchar=='.')||(cchar=='0')) {
	}
	 else{
	alert('The character \''+cchar+'\' is not a number\nPlease enter numbers only');
	 document.form1.start_price.value='';
	 return false;
	 }
	}
	
	}
	
	
	
	 /*if (document.form1.end_price.value == ""){
		alert('Please enter the price range ');
	 document.form1.end_price.focus();
	 return false;
	   }*/ 
		 if (document.form1.end_price.value!=""){
	 n=document.form1.end_price.value.length;
	for (i=0;i<n; i++)
	 {
	
			cchar=document.form1.end_price.value.charAt(i);
	  if (parseFloat(cchar)|| (cchar=='.')||(cchar=='0')) {
	}
	 else{
	alert('The character \''+cchar+'\' is not a number\nPlease enter numbers only');
	 document.form1.end_price.value='';
	 return false;
	 }
	}
	
	}

	}


