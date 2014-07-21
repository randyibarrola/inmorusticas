function EnviarA(type)
{
	var title = encodeURIComponent(window.document.title);
	var href = encodeURIComponent(window.document.location.href);
	var components = window.document.location.href.split('/');
	var tags = '';
	if(components[6] != '')
	{
		tags += components[6]+' ';
	}
	if(components[7] != '')
	{
		tags += components[7]+' ';
	}
	if(components[8] != '')
	{
		tags += components[8]+' ';
	}		
	tags = encodeURIComponent(tags);
	switch(type) {
		case 'meneame' :
			url = 'http://meneame.net/submit.php?url='+href;
			break;
		case 'digg' :
			url = 'http://digg.com/submit?phase=2&url='+href+'&title='+title;
			break;
		case 'delicious' :
			url = 'http://del.icio.us/post?url='+href+'&title='+title;
			break;
		case 'technorati' :
			url = 'http://www.technorati.com/search/'+tags+'?sub=postcosm';
			break;
		case 'yahoo' :
			url = 'http://myweb2.search.yahoo.com/myresults/bookmarklet?u='+href+'&t='+title+'&ei=UTF-8';
			break;
		case 'fresqui' :
			url = 'http://tec.fresqui.com/post?url='+href+'&title='+title;
			break;			
		case 'facebook' :
			url = 'http://www.facebook.com/share.php?u='+href;
			break;
		case 'twitter':
			url = 'http://twitter.com/home?status='+title+': '+href;
			break;
	}
	window.open(url);
}