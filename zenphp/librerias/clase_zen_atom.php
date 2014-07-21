<?php
class zen_atom {
 
var $Atom_Content;

function zen_atom(){
 $this->Atom_Content = array();
}

function Atom_Tags($item, $type)
{
		$y = array();
		$tnl = $item->getElementsByTagName("title");
		$tnl = $tnl->item(0);
		$title = $tnl->firstChild->data;

		$tnl = $item->getElementsByTagName("link");
		$tnl = $tnl->item(0);		
		$link = $tnl->getAttribute("href");

		$tnl = $item->getElementsByTagName("summary");
		$tnl = $tnl->item(0);
		$description = $tnl->firstChild->data;

		$y["title"] = $title;
		$y["link"] = $link;
		$y["description"] = $description;
		$y["type"] = $type;
		
		return $y;
}


function Atom_Feed($doc)
{

	$entries = $doc->getElementsByTagName("entry");
	
	// Processing feed
	
	$y = array();
	$tnl = $doc->getElementsByTagName("title");
	$tnl = $tnl->item(0);
	$title = $tnl->firstChild->data;

	$tnl = $doc->getElementsByTagName("link");
	$tnl = $tnl->item(0);	
	$link = $tnl->getAttribute("href");

	$tnl = $doc->getElementsByTagName("subtitle");
	$tnl = $tnl->item(0);
	$description = $tnl->firstChild->data;

	$y["title"] = $title;
	$y["link"] = $link;
	$y["description"] = $description;
	$y["type"] = 0;

	array_push($this->Atom_Content, $y);
	
	// Processing articles
	
	foreach($entries as $entry)
	{
		$y = $this->Atom_Tags($entry, 1);		// get description of article, type 1
		array_push($this->Atom_Content, $y);
	}
}


function Atom_Retrieve($url)
{

	$doc  = new DOMDocument();
	$doc->load($url);

	$this->Atom_Content = array();
	
	$this->Atom_Feed($doc);
	
}


function Atom_RetrieveLinks($url)
{

	$doc  = new DOMDocument();
	$doc->load($url);

	$entries = $doc->getElementsByTagName("entry");
	
	$this->Atom_Content = array();
	
	foreach($entries as $entry)
	{
		$y = $this->Atom_Tags($entry, 1);	// get description of article, type 1
		array_push($this->Atom_Content, $y);
	}

}


function Atom_Links($url, $size)
{

	$page = "";

	$this->Atom_RetrieveLinks($url);
	if($size > 0)
		$recents = array_slice($this->Atom_Content, 0, $size);

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



function Atom_Display($url, $size)
{

	$opened = false;
	$page = "";

	$this->Atom_Retrieve($url);
	if($size > 0)
		$recents = array_slice($this->Atom_Content, 0, $size);

	foreach($recents as $article)
	{
		$type = $article["type"];
		if($type == 0)
		{
			if($opened == true)
			{
				$page .="</p>\n";
				$opened = false;
			}
			$page .="<b>";
		}
		else
		{
			if($opened == false) 
			{
				$page .= "<p>\n";
				$opened = true;
			}
		}
		$title = '<h2>'.$article["title"].'</h2>';
		$link = $article["link"];
		$description = $article["description"];
		$page .= "<span><a href=\"$link\">$title</a>";
		if($description != false)
		{
			$page .= "<br>$description";
		}
		$page .= "</span>\n";			
		
		if($type==0)
		{
			$page .="</b><br />";
		}

	}

	if($opened == true)
	{	
		$page .="</p>\n";
	}

	return $page."\n";
	
}


}
?>