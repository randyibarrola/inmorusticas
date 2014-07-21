<?php
class zen_rss {
var $RSS_Content = array();
function zen_rss(){
 $this->RSS_Content = array();
}

function RSS_Tags($item, $type)
{
		$y = array();
		$tnl = $item->getElementsByTagName("title");
		$tnl = $tnl->item(0);
		$title = $tnl->firstChild->data;

		$tnl = $item->getElementsByTagName("link");
		$tnl = $tnl->item(0);
		$link = $tnl->firstChild->data;

		$tnl = $item->getElementsByTagName("description");
		$tnl = $tnl->item(0);
		$description = $tnl->firstChild->data;

		$y["title"] = $title;
		$y["link"] = $link;
		$y["description"] = $description;
		$y["type"] = $type;
		
		return $y;
}


function RSS_Channel($channel)
{
	$items = $channel->getElementsByTagName("item");
	
	// Processing channel
	
	$y = $this->RSS_Tags($channel, 0);		// get description of channel, type 0
	array_push($this->RSS_Content, $y);
	
	// Processing articles
	
	foreach($items as $item)
	{
		$y =$this->RSS_Tags($item, 1);	// get description of article, type 1
		array_push($this->RSS_Content, $y);
	}
}

function RSS_Retrieve($url)
{


	$doc  = new DOMDocument();
	$doc->load($url);

	$channels = $doc->getElementsByTagName("channel");
	
	$this->RSS_Content = array();
	
	foreach($channels as $channel)
	{
		 $this->RSS_Channel($channel);
	}
	
}


function RSS_RetrieveLinks($url)
{

	$doc  = new DOMDocument();
	$doc->load($url);

	$channels = $doc->getElementsByTagName("channel");
	
	$this->RSS_Content = array();
	
	foreach($channels as $channel)
	{
		$items = $channel->getElementsByTagName("item");
		foreach($items as $item)
		{
			$y = $this->RSS_Tags($item, 1);	// get description of article, type 1
			array_push($this->RSS_Content, $y);
		}
		 
	}

}


function RSS_Links($url, $size)
{


	$page = "<ul>";

	$this->RSS_RetrieveLinks($url);
	if($size > 0)
		$recents = array_slice($this->RSS_Content, 0, $size);

	foreach($recents as $article)
	{
		$type = $article["type"];
		if($type == 0) continue;
		$title = $article["title"];
		$link = $article["link"];
		$page .= "<li><a href=\"$link\">$title</a></li>\n";			
	}

	$page .="</ul>\n";

	return $page;
	
}



function RSS_Display($url, $size)
{
	

	$opened = false;
	$page = "";

	$this->RSS_Retrieve($url);
	if($size > 0)
		$recents = array_slice($this->RSS_Content, 0, $size);

	foreach($recents as $article)
	{
		$type = $article["type"];
		if($type == 0)
		{
			if($opened == true)
			{
				$page .="</ul>\n";
				$opened = false;
			}
			$page .="<b>";
		}
		else
		{
			if($opened == false) 
			{
				$page .= "<ul>\n";
				$opened = true;
			}
		}
		$title = $article["title"];
		$link = $article["link"];
		$description = $article["description"];
		$page .= "<li><a href=\"$link\">$title</a>";
		if($description != false)
		{
			$page .= "<br>$description";
		}
		$page .= "</li>\n";			
		
		if($type==0)
		{
			$page .="</b><br />";
		}

	}

	if($opened == true)
	{	
		$page .="</ul>\n";
	}
	return $page."\n";
	
}

}