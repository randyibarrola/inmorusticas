<?php ob_start();
error_reporting(0);
session_start();
/*Plugin Name: RealShoutSearch
Plugin URL: http://www.realshout.com/wordpress-real-estate-plugin.php
Description: Searchable real estate that is seo friendly.  Search and save real estate listings.
Author: Go Smart Solutions, LLC
Version: 2.0
Author URL: http://www.realshout.com
*/ 

/* ***** BEGIN LICENSE BLOCK *****
 * Version: GPL 3.0
 *
 * The contents of this plugin and all files within it may be used under the terms of the GNU
 * General Public License (the "License") Version 3 or later; you may NOT use
 * this plugin or files associated with it except in compliance with the License. You may obtain a copy of
 * the License at:
 * http://www.gnu.org/licenses/gpl.html
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is Go Smart Solutions, LLC code.
 *
 * The Initial Developer of the Original Code is
 * Go Smart Solutions, LLC.
 * Portions created by the Initial Developer are Copyright (C) 2009 to 2010
 * Go Smart Solutions, LLC the Initial Developer. All Rights Reserved.
 *
 * Contributor(s):
 *   Go Smart Solutions, LLC (service@gosmart4u.com)
 *
 * ***** END LICENSE BLOCK ***** */
 

function realshout_activate(){
 $pages = get_pages();
 echo '<pre>';
 print_r($pages);
 $post_id = 0;
 $id = 0;
 $flag = 0;
 foreach ($pages as $page){
  $post_name = $page->post_name;
  
  if ($post_name == 'search-real-estate'){
   $flag++;
   if (preg_match('%(\\{listing\\})%',$page->post_content,$arr)){
    $id = $page->ID;
	break;
   }
  }   
 }
 
 if (($flag > 0 && $id == 0) || $flag == 0){
  global $wpdb;
  $tbl_name = $wpdb->prefix.'posts';
  
  $now = @date('Y-m-d H:i:s',@time());
  $gm_now = @gmdate('Y-m-d H:i:s',@time());
  $art_str = '{listing}';  
  $title = 'Search Real Estate';
  $sts = 'publish';
  
  if ($flag == 0){
   $post_name = 'search-real-estate';
  }
  
  if ($flag > 0){
   $post_name = 'search-real-estate2';
  }
  
  $post_type = 'page';  
  
  
  $sql = "INSERT INTO ".$tbl_name." (post_author, post_date, post_date_gmt, post_content, post_title,post_status, post_name, post_type, post_modified, post_modified_gmt) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s)";   
  $rs = $wpdb->query($wpdb->prepare($sql,1,$now,$gm_now,$art_str,$title,$sts,$post_name,$post_type,$now,$gm_now));
  $post_id = mysql_insert_id(); 
  
  $rs_update = $wpdb->query("UPDATE ".$tbl_name." SET guid = '".get_option('home')."/?page_id=".$post_id."' WHERE ID = '".$post_id."'");
    
 }
  
 if ($flag > 0 && $id !=0){
  $post_id = $id;
 } 
 
 update_option('amazon_cs_page',$post_id);
 
 function createRewriteRules() {
  global $wp_rewrite;
  
  // add rewrite tokens
  $keytag = '%tag%';
  $wp_rewrite->add_rewrite_tag($keytag, '(.+?)', 'tag=');
	 
  $keywords_structure = $wp_rewrite->root . "tag/$keytag/";
  $keywords_rewrite = $wp_rewrite->generate_rewrite_rules($keywords_structure);
	 
  $wp_rewrite->rules = $keywords_rewrite + $wp_rewrite->rules;
  return $wp_rewrite->rules;
 }
 add_action('generate_rewrite_rules', 'createRewriteRules');

 global $wp_rewrite; // Global WP_Rewrite class object
 $arr = $wp_rewrite->rewrite_rules();  
 $wp_rewrite->flush_rules(); 
}
 
 
function F30d38403($V4584e82f, $V11e0eed8 = false) {
 if(!$V11e0eed8) {
 $V9b207167 = mysql_query("SELECT DATABASE()");
$V11e0eed8 = mysql_result($V9b207167, 0);
}
$V9b207167 = mysql_query("
 SELECT COUNT(*) AS count 
 FROM information_schema.tables 
 WHERE table_schema = '$V11e0eed8' 
 AND table_name = '$V4584e82f'
 ");
return mysql_result($V9b207167, 0) == 1;
}
function Fc7a53d3b($V1cb251ec)
 { 
 ?>
 
 <script language="javascript" src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/AjaxRequest.js"></script>
 <script language="javascript" src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/simple_overlay.js"></script>
 <link rel="stylesheet" type="text/css" href="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/simple_overlay.css">
 <script language="javascript" src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/js_functions.php"></script>
  <?
 
 
 $Vd3657c94 = ''; 
 if (preg_match('%(\\{listing\\})%', $V1cb251ec, $V8d777f38)) { 
 
 if(F30d38403('city', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE city (
 state_id int(11) NOT NULL,
 id int(11) NOT NULL auto_increment,
 city varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
  
if(F30d38403('state', '')) 
{
 $rs_field = mysql_query('DESC state');
 $flag = 0;
 while ($row_field = mysql_fetch_array($rs_field)){
   if($row_field['Field'] == 'country_id'){
    $flag = 1;
   }  
 }
 
 if ($flag == 0){
  mysql_query("ALTER TABLE `state` ADD `country_id` INT( 11 ) NOT NULL AFTER `state` ;"); 
 } 
}
else
 {
 
 
 mysql_query("CREATE TABLE state (
 id int(11) NOT NULL auto_increment,
 state varchar(50) NOT NULL,
 country_id int(11) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}

if(F30d38403('country', '')) 
{
 
}
else
 { 
 mysql_query("CREATE TABLE country (
 id int(11) NOT NULL auto_increment,
 country varchar(255) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
  
if(F30d38403('listing', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE listing (
 id int(11) NOT NULL auto_increment,
 listing varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(F30d38403('property', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE property (
 id int(11) NOT NULL auto_increment,
 property_type varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(F30d38403('distance', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE distance (
 id int(11) NOT NULL auto_increment,
 distance varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
  
if(F30d38403('contact_us', '')) 
{
 
}
else
 {
 mysql_query("CREATE TABLE contact_us (
 id int(11) NOT NULL auto_increment, 
 `subject` varchar(255) NOT NULL,
 message text NOT NULL, 
 from_name text NOT NULL,
 from_email varchar(255) NOT NULL,
 location varchar(255) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(F30d38403('user', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE `user` (
 id int(11) NOT NULL auto_increment,
 pass varchar(30) NOT NULL,
 `name` varchar(50) NOT NULL,
 ph varchar(50) NOT NULL,
 email varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
  
if(F30d38403('user_listing', '')) 
{
 $rs_field = mysql_query('DESC user_listing');
 $flag_ = 0;
 while ($row_field = mysql_fetch_array($rs_field)){
   if($row_field['Field'] == 'listing_id'){
    $flag_ = 1;
   }  
 }
 
 if ($flag_ == 0){
  mysql_query("ALTER TABLE `user_listing` ADD `listing_id` varchar( 255 ) NOT NULL AFTER `user_id` ;"); 
 } 
}
else
 {
 
 
 mysql_query("CREATE TABLE user_listing (
 id int(11) NOT NULL auto_increment,
 user_id int(11) NOT NULL, 
 listing_id varchar(255) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(F30d38403('temp_listing', '')) 
{
 $rs_field = mysql_query('DESC temp_listing');
 $flag__ = 0;
 while ($row_field = mysql_fetch_array($rs_field)){
   if($row_field['Field'] == 'listing_id'){
    $flag__ = 1;
   }  
 }
 
 if ($flag__ == 0){
  mysql_query("ALTER TABLE `temp_listing` ADD `listing_id` varchar( 255 ) NOT NULL AFTER `id` ;"); 
 }
}
else
 {
 
 
 mysql_query("CREATE TABLE temp_listing (
 id int(11) NOT NULL auto_increment,
 listing_id varchar(255) NOT NULL,
 date_in int(11) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(get_option('check')!="")
{
 /*$_SESSION['Ve8701ad4']=get_option('user_id');
$_SESSION['username']=get_option('username'); */
 $V16f709d3='check';
$Vfb456c69="";
update_option( $V16f709d3,$Vfb456c69); 
 } 
 ?>
<style>
.hover { background-color:<?=get_option('hover')?>;}
</style>
<?
 
 
 $V556792bd = get_option('license__');

 if (trim($V556792bd) == "") {
 echo "\074\160\040\163\164\171\154\145\075\134\047\143\157\154\157\162\072\043\106\106\060\060\060\060\073\040\146\157\156\164\055\167\145\151\147\150\164\072\142\157\154\144\134\047\076\120\154\145\141\163\145\040\163\145\164\040\164\150\145\040\114\151\143\145\156\163\145\040\113\145\171\040\151\156\040\171\157\165\162\040\141\144\155\151\156\040\143\157\156\164\162\157\154\040\160\141\156\145\154\056\074\057\160\076 "; 
 return;
}

 if ($V556792bd != ""){ 
 if (get_option('scl_xda__') == '' || get_option('knl_xdb__') == ''){ 
 $V3a6d0284 = file_get_contents('http://www.realshout.com/wplicence1/check_paid_licence.php?id='.$V556792bd);

 if ($V3a6d0284 == 1){
 update_option('scl_xda__',md5($V556792bd));
update_option('knl_xdb__',md5(str_replace("www.","",$_SERVER['SERVER_NAME']))); 
 $V46c48bec = file_get_contents('http://www.realshout.com/wplicence1/update_licence.php?id='.$V556792bd.'&link='.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
} else { 
 echo "\074\160\040\163\164\171\154\145\075\134\047\143\157\154\157\162\072\043\106\106\060\060\060\060\073\040\146\157\156\164\055\167\145\151\147\150\164\072\142\157\154\144\134\047\076\111\156\166\141\154\151\144\040\114\151\143\145\156\163\145\040\113\145\171\056\074\057\160\076 "; 
 return; 
 }
} else { 
 if (get_option('knl_xdb__') != md5(str_replace("www.","",$_SERVER['SERVER_NAME'])) || get_option('scl_xda__') != md5($V556792bd)){ 
 echo "\074\160\040\163\164\171\154\145\075\134\047\143\157\154\157\162\072\043\106\106\060\060\060\060\073\040\146\157\156\164\055\167\145\151\147\150\164\072\142\157\154\144\134\047\076\111\156\166\141\154\151\144\040\114\151\143\145\156\163\145\040\113\145\171\056\074\057\160\076 ";
 return;
} 
 } 
 } 
 
 
$V16f709d3='user';
$Vfb456c69="";
update_option( $V16f709d3,$Vfb456c69);
$V327a6c43=1;

 
 if(isset($_REQUEST['lgin'])) 
	{ 
 $Vc56f5648=$_REQUEST['user_email'];
$V186bca78=$_REQUEST['V186bca78'];

 $V1b1cc7f0="SELECT * from user WHERE email='$Vc56f5648' and pass='$V186bca78'";
$Vd7ac3e55=mysql_query($V1b1cc7f0);
$V51440634=mysql_num_rows($Vd7ac3e55);
$V7957a22e=mysql_fetch_row($Vd7ac3e55);
$Vb068931c=$V7957a22e[2];

 if($V51440634>0)
 { 
 $_SESSION['username']=$V7957a22e[2]; 
 $_SESSION['Ve8701ad4']=$V7957a22e[0]; 
 }

 else
 { 
 echo "<strong>invalid username or password.</strong>";

 }

	}

	if(isset($_REQUEST['signout']))
	{
 unset($_SESSION);
session_destroy(); 
 $V327a6c43=1;

	}

	
	if(isset($_REQUEST['remove']))
	{
 $Vb80bb774=$_REQUEST['Vb80bb774'];
$V687987ab="delete from user_listing where id='$Vb80bb774'";
mysql_query($V687987ab);
$Vd6fe1d0b=get_settings('home');
if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&d_=$V4c68e0c8&";
}
header("location:$Vd6fe1d0b/".$Ve2742836."show=1");	
 
	}	
	
	
	if(isset($_REQUEST['save']))
	{
 
 
 if(isset($_SESSION['Ve8701ad4']) && (trim($_SESSION['Ve8701ad4']) != ""))
 {
 
  
 $V3a2d7564 = mysql_query("SELECT listing_id FROM temp_listing WHERE id = ".$_GET['Vb80bb774']);
$Vf1965a85 = mysql_fetch_array($V3a2d7564);
  
 
 $Ve8701ad4 = $_SESSION['Ve8701ad4'];

 
 $V1b1cc7f0="INSERT into user_listing (user_id,listing_id) values ('$Ve8701ad4','{$Vf1965a85['listing_id']}')";
 mysql_query($V1b1cc7f0);
?>
 <h2 style="color:#333333">Listing Successfully Saved</h2>
 <h3 style="color:#333333"><a href="#advanced" style="cursor:pointer;" onClick="document.getElementById('ad_search').style.display = 'block';document.getElementById('si_search').style.display = 'none';">Continue Searching >></a></h3>
 <?
 
 }

 else
 {
 ?>
 
<h2 style="color:#333333">Please <a href="#login">log in</a> to save listing or <a OnClick="window.open('<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/sign_in.php','window1','menubar=no,width=500,height=400,toolbar=no,scrollbars=yes')">sign up here</a></h2>
 <?
 }
}

	if(isset($_REQUEST['show']))
	{	
	$Ve8701ad4=$_SESSION['Ve8701ad4'];	
	$Vbc646575="SELECT id,listing_id FROM user_listing where user_id=".$Ve8701ad4;
$V76fd4700=mysql_query($Vbc646575);
$V753e9cd2=mysql_num_rows($V76fd4700);
if($V753e9cd2<1)
	{
	?>
 <h3>You have no saved listings</h3>
	<?
	}
$V865c0c0b=0;
while($V7935de3b=mysql_fetch_row($V76fd4700))
	{
 
	$V327a6c43=0;
if($V865c0c0b%2==0)
	$Vb2145aac=get_option('first_color');
else
	$Vb2145aac=get_option('second_color');

	echo "<table bgcolor='$Vb2145aac' onMouseOver=\"this.className='hover'\" onMouseOut=\"this.className=''\">";
	
	$details = file_get_contents('http://www.google.com/base/feeds/snippets/'.$V7935de3b[1]);	
	
	preg_match_all("/<g:image_link type='url'>(.*?)<\/g:image_link>/",$details,$imgs); 
    $V78805a22 = $imgs[1][0];
	
	preg_match_all("/<g:location type='location'>(.*?)<\/g:location>/",$details,$locs); 
    $Vd5189de0 = $locs[1][0];
	
?>
 <tr>
 <td><a href="<?=get_settings('home')?>/?detail_save_listing=1&Vb80bb774=<?=$V7935de3b[0]?>"><img src="<?=$V78805a22?>" width='150' /></a></td>
	<td>
	<table>
<tr><td colspan="2"><?=$Vd5189de0?></td></tr>
<tr>
<td>
<?

preg_match_all("/<g:price type='floatUnit'>(.*?)<\/g:price>/",$details,$price); 
$V78a5eb43 = $price[1][0];

$asdcvb= str_replace('usd','',$V78a5eb43); 
$asdcvb=number_format($asdcvb,0);

echo '<strong>Price: </strong>$'.$asdcvb;

preg_match_all("/<g:bedrooms type='int'>(.*?)<\/g:bedrooms>/",$details,$br); 
$Vcff8b1fe = $br[1][0];	

preg_match_all("/<g:bathrooms type='float'>(.*?)<\/g:bathrooms>/",$details,$ba); 
$Vd01befa8 = $ba[1][0];

preg_match_all("/<g:property_type type='text'>(.*?)<\/g:property_type>/",$details,$pt); 
$V23a5b8ab = $pt[1][0];	

?>
</td>
</tr>
 <tr><td>Beds: <?=$Vcff8b1fe?></td><td>Baths:&nbsp;<?=$Vd01befa8?></td></tr>
<!--<tr><td>interior:not avaliable</td></tr>-->
<tr><td colspan="2">Property Type: <?=$V23a5b8ab?></td></tr>
<?
$Vdc634e20="bedroom>2.4";
if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&d_=$V4c68e0c8&";
}
$Vff208472 = get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php?uid=".$V7935de3b[0];

$contact = "<div class='simple_overlay' id='mies$V7935de3b[0]'><div class='close'></div>
	<iframe src='".get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php?uid=".$V7935de3b[0]."' width='525' frameborder='0' height='490'></iframe>
 </div><span id='triggers'><a><img alt='Contact Us' rel='#mies$V7935de3b[0]'></a></span>";
?>
<tr><td colspan="2"><a href="<?=get_settings('home')?>/<?=$Ve2742836?>detail_save_listing=1&Vb80bb774=<?=$V7935de3b[0]?>">Detail</a><a href="<?=get_settings('home')?>/<?=$Ve2742836?>remove=1&Vb80bb774=<?=$V7935de3b[0]?>">|Remove</a>|<? echo $contact;?></td></tr>
</table>
</td>
</tr>
</table>
	<?
	$V865c0c0b++;
}

	}	
	
	
if($V327a6c43==1)
{
 
 if(isset($_REQUEST['detail'])||isset($_REQUEST['detail_save_listing']))
 {
 
	$V410d9893=0;

 if(isset($_REQUEST['sub_name']))
 {
 $V410d9893=1;
}

if($V410d9893==0)
{ 
/* $_SESSION['Ve8701ad4']=get_option('user_id');
$_SESSION['username']=get_option('username');*/
$V16f709d3='check';
$Vfb456c69="check";
update_option( $V16f709d3,$Vfb456c69); 
	
 
 
if($_REQUEST['Vb33aed8f']==""){
$_REQUEST['Vb33aed8f']="Not avaliable";}

if($_REQUEST['Vb0ab0254']==""){
$_REQUEST['Vb0ab0254']="Not avaliable";}
?>
<link rel="stylesheet" type="text/css" href="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/pop_up.css">
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=get_option('google_api')?>" type="text/javascript"></script>
<script language="javascript" src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/body_load.js"></script>
<?
if(isset($_REQUEST['detail_save_listing']))
{
	
   $Vb80bb774=$_REQUEST['Vb80bb774'];
   $V436cd89d="SELECT id, listing_id FROM user_listing where id=".$Vb80bb774;
   $Vd89b9872=mysql_query($V436cd89d);
   $V244a79e6=mysql_fetch_row($Vd89b9872);
   
   $details = file_get_contents('http://www.google.com/base/feeds/snippets/'.$V244a79e6[1]);
   
   preg_match_all("/<link rel='alternate' type='text\/html' href='(.*?)'>/",$details,$lnk); 
    $lnkasd = $lnk[1][0];
	$lnk_ar = explode("'",$lnkasd);
	$lnklnk = $lnk_ar[0];
   
   preg_match_all("/<g:image_link type='url'>(.*?)<\/g:image_link>/",$details,$imgs); 
   $V78805a22 = $imgs[1][0];
   
   $m_images = '';
	if (count($imgs[1]) > 1){
	 $m_images = implode(',',$imgs[1]);
	} 
 
   $Vfd976cd0 = explode(',',$m_images);

?>
	
<table>
<tr>
<td>
<img src="<?=$V78805a22?>" align="center" alt="<? echo $V244a79e6[7]; ?>" />
</td>
</tr>
<? if (count($Vfd976cd0) > 1) { ?>
<tr>
 <td>
 <div id="sl" style="display:block"><a onClick="document.getElementById('sl').style.display = 'none';document.getElementById('sm').style.display = 'block';">Show More Photos</a></div>
 <div id="sm" style="display:none">
 <a onClick="document.getElementById('sl').style.display = 'block';document.getElementById('sm').style.display = 'none';">Show One Photo</a>
 <table>
 <? for($V865c0c0b = 1; $V865c0c0b < count($Vfd976cd0); $V865c0c0b++ ) { ?>
 <tr><td>
 <img src="<?=$Vfd976cd0[$V865c0c0b]?>" align="left" />
 </td></tr>
 <tr>
 <td>&nbsp;</td>
 </tr>
 <? } ?>
 </table>
 </div> 
 </td>
</tr>
<? }

preg_match_all("/<content type='html'>(.*?)<\/content>/",$details,$dtl); 
$V20d4441a = $dtl[1][0];

preg_match_all("/<g:location type='location'>(.*?)<\/g:location>/",$details,$locs); 
$Vd5189de0 = $locs[1][0];

preg_match_all("/<g:price type='floatUnit'>(.*?)<\/g:price>/",$details,$price); 
$V78a5eb43 = $price[1][0];

preg_match_all("/<g:bathrooms type='float'>(.*?)<\/g:bathrooms>/",$details,$ba); 
$Vd01befa8 = $ba[1][0];	

preg_match_all("/<g:bedrooms type='int'>(.*?)<\/g:bedrooms>/",$details,$br); 
$Vcff8b1fe = $br[1][0];

preg_match_all("/<g:broker type='text'>(.*?)<\/g:broker>/",$details,$bkr); 
$Vb33aed8f = $bkr[1][0];

 ?>
<tr>
<td>
<a style="cursor:pointer" onClick="history.back(-1)">Go Back</a><br />
<?=$V20d4441a?>
</td>
</tr>
<tr>
<td>
<table>
<tr><td><strong>Location: </strong><? echo $Vd5189de0;?></td></tr>
<tr>
<td>
<? 
$asdcvb= str_replace('usd','',$V78a5eb43); 
$asdcvb=number_format($asdcvb,0);

echo '<strong>Price: </strong>$'.$asdcvb;
?>
</td>
</tr>
<tr><td>
<b>Beds:</b>&nbsp;<?=$Vcff8b1fe?>&nbsp;&nbsp;<b>Baths:</b>&nbsp;<?=$Vd01befa8?></td></tr>
<!--<tr><td>interior:<?=$V244a79e6[6]?></td></tr>
<tr><td><strong>Expiration date: </strong><?=@date('F d, Y',$V244a79e6[12])?></td></tr>-->
<tr><td><strong>Listing Agent/Broker: </strong><?=$Vb33aed8f?></td></tr>
</table>
</td>
</tr>
</table>
<!--<p><strong>Features</strong><br/><?=$V244a79e6[11]?></p>-->
	
<? 
$Vd5189de0=$Vd5189de0;
$Vc106d13c=$Vb33aed8f;
$V78a5eb43=$V78a5eb43;
$V07159c47=$Vd01befa8;
$Vcff8b1fe = $Vcff8b1fe;
$Vd01befa8 = $Vd01befa8;
$V78805a22=$V244a79e6[2];
$Vb83a886a=get_option('email');
$Vff208472= get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php?uid=".$_GET['Vb80bb774'];
 
$V14136e54 = "[0-9]{5}";
ereg($V14136e54,$Vd5189de0,$V66373a9c);
$V0c0ae404 = $V66373a9c[0];
if ($V0c0ae404 == ''){
 $V0c0ae404 = $Vd5189de0; 
 } 
 
 echo "<div class='simple_overlay' id='mies".$_REQUEST['Vb80bb774']."'><div class='close'></div>
	<iframe src='".$Vff208472."' width='525' frameborder='0' height='490'></iframe>
 </div><span id='triggers'><img src=\"". get_settings('home'). "/wp-content/plugins/realshout-real-estate-property-search/contact-us.jpg\" rel='#mies".$_REQUEST['Vb80bb774']."' alt=\"Contact Us\" border=\"0\"></span>";
}
if(isset($_REQUEST['detail']))
{ 
   
    $V3a2d7564 = mysql_query("SELECT listing_id FROM temp_listing WHERE id = ".$_GET['Vb80bb774']);
    $Vf1965a85 = mysql_fetch_array($V3a2d7564);
    
	$details = file_get_contents('http://www.google.com/base/feeds/snippets/'.$Vf1965a85['listing_id']);
	
	
	preg_match_all("/<link rel='alternate' type='text\/html' href='(.*?)'>/",$details,$lnk); 
    $lnkasd = $lnk[1][0];
	$lnk_ar = explode("'",$lnkasd);
	$lnklnk = $lnk_ar[0];
	
	
	preg_match_all("/<g:location type='location'>(.*?)<\/g:location>/",$details,$locs); 
    $Vd5189de0 = $locs[1][0];
	
	preg_match_all("/<g:bathrooms type='float'>(.*?)<\/g:bathrooms>/",$details,$ba); 
    $Vd01befa8 = $ba[1][0];	

    preg_match_all("/<g:bedrooms type='int'>(.*?)<\/g:bedrooms>/",$details,$br); 
    $Vcff8b1fe = $br[1][0];	
	
	preg_match_all("/<g:image_link type='url'>(.*?)<\/g:image_link>/",$details,$imgs); 
    $V78805a22 = $imgs[1][0];
	
	preg_match_all("/<g:property_type type='text'>(.*?)<\/g:property_type>/",$details,$pt); 
    $V23a5b8ab = $pt[1][0];		
	 

	$V7ec83367 = '';
	
	preg_match_all("/<g:expiration_date type='dateTime'>(.*?)<\/g:expiration_date>/",$details,$edt); 
    $Vb0ab0254 = $edt[1][0];
	
	preg_match_all("/<g:feature type='text'>(.*?)<\/g:feature>/",$details,$ftr); 
    $V1ba8aba1 = $ftr[1][0];
	
	preg_match_all("/<g:broker type='text'>(.*?)<\/g:broker>/",$details,$bkr); 
    $Vb33aed8f = $bkr[1][0];  	 	 

    preg_match_all("/<g:price type='floatUnit'>(.*?)<\/g:price>/",$details,$price); 
    $V78a5eb43 = $price[1][0];	
	
	preg_match_all("/<content type='html'>(.*?)<\/content>/",$details,$dtl); 
    $V20d4441a = $dtl[1][0];
    
	$m_images = '';
	if (count($imgs[1]) > 1){
	 $m_images = implode(',',$imgs[1]);
	}
	 
 
	$Vfd976cd0 = explode(',',$m_images);	
	
	
 
	$Vcf8ca243 = explode("T",$Vb0ab0254);
    $Vd992c752 = explode("-",$Vcf8ca243[0]);

	$V07cc694b = mktime(0,0,0,$Vd992c752[1],$Vd992c752[2],$Vd992c752[0]);

	$Vb0ab0254 = date('F d, Y',$V07cc694b);

?>
<table>
<tr>
<td width="100%" style='background-image:url(<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/no_image.gif);background-repeat:no-repeat;position:relative;background-position:left;padding-left:5px;'>
<?
  list($width, $height, $type, $attr) = getimagesize($V78805a22);
  
  if ($width > 400){
   $width = 400;
  }
  
?>
<img src="<? echo $V78805a22;?>" width="<?=$width?>" align="left" alt="<? echo $Vd5189de0; ?>" />
</td>
</tr>
<? if (count($Vfd976cd0) > 1) { ?>
<tr>
 <td>
 <div id="sl" style="display:block"><a onClick="document.getElementById('sl').style.display = 'none';document.getElementById('sm').style.display = 'block';">Show More Photos</a></div>
 <div id="sm" style="display:none">
 <a onClick="document.getElementById('sl').style.display = 'block';document.getElementById('sm').style.display = 'none';">Show One Photo</a>
 <table>
 <? for($V865c0c0b = 1; $V865c0c0b < count($Vfd976cd0); $V865c0c0b++ ) { ?>
 <tr><td width="100%" style="background-image:url(<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/no_image.gif);background-repeat:no-repeat;position:relative;background-position:left;padding-left:5px;">
 <?
  list($width, $height, $type, $attr) = getimagesize($Vfd976cd0[$V865c0c0b]);
  
  if ($width > 400){
   $width = 400;
  }
  
?>
 <img src="<?=$Vfd976cd0[$V865c0c0b]?>" width="<?=$width?>" align="left" />
 </td></tr>
 <tr>
 <td>&nbsp;</td>
 </tr>
 <? } ?>
 </table>
 </div> 
 </td>
</tr>
<? } ?>
<tr>
<td>
<table>
 <tr>
 <td><a style="cursor:pointer" onClick="history.back(-1)">Go Back</a><br /><? echo $V20d4441a;?></td>
 </tr>
 <tr>
 <td><strong>Location: </strong><? echo $Vd5189de0;?></td>
 </tr>
 <tr> 
 <td> 
 <? 
 $V9c35be32= str_replace('usd','',$V78a5eb43); 
 $V9c35be32=number_format($V9c35be32,0);
echo '<b>Price: </b>$'.$V9c35be32;
echo '&nbsp;&nbsp;&nbsp;<b>Property Type: </b>'.$V23a5b8ab;
?>
 </td>
 </tr>
 <tr> 
 <td> <b>Beds:</b>&nbsp;<? echo $Vcff8b1fe;?>&nbsp;&nbsp;&nbsp;<b>Baths:</b>&nbsp;<? echo $Vd01befa8;?> 
 </td>
 </tr>
 <!--<tr><td>interior:<? echo $V7ec83367;?></td></tr>
 <tr>
 <td><strong>Expiration date: </strong><? echo $Vb0ab0254;?></td>
 </tr>
 -->
 <tr>
 <td><strong>Listing Agent/Broker: </strong><? echo $Vb33aed8f;?></td>
 </tr>
 </table>
</td>
</tr>
</table>
<!--<p><strong>Features</strong><br/><? echo $V1ba8aba1;?></p>-->
<?
if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&d_=$V4c68e0c8&";
}
$V16f709d3='contact';
$Vfb456c69="**".$Vd5189de0."**".$V07159c47."**".$V78805a22."**".$Vd4a6e95c."**not avaliable**".$Vb0ab0254."**".$V1ba8aba1."**".$Vc106d13c;
update_option($V16f709d3,$Vfb456c69);
$Vb83a886a=get_option('email');
if (isset($_REQUEST['detail'])){
$Vff208472 = get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php?id=".$_REQUEST['Vb80bb774'];
}
if (isset($_REQUEST['detail_save_listing'])){
$Vff208472 = get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php?uid=".$_REQUEST['Vb80bb774'];
}
$V88c52728 = get_settings('home')."/".$Ve2742836."save=1&Vb80bb774=".$_GET['Vb80bb774'];
$V8b89a856=get_settings('home');
if(isset($_REQUEST['detail'])) { ?>
<a style="font-size:18px" href="<?=$V88c52728?>"><img src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/save-listing.jpg" alt="Save Listing" border="0"></a><? } ?>&nbsp;&nbsp;<? echo "<div class='simple_overlay' id='mies".$_REQUEST['Vb80bb774']."'><div class='close'></div>
	<iframe src='".$Vff208472."' width='525' frameborder='0' height='490'></iframe>
 </div><span id='triggers'><img src=\"". get_settings('home'). "/wp-content/plugins/realshout-real-estate-property-search/contact-us.jpg\" rel='#mies".$_REQUEST['Vb80bb774']."' alt=\"Contact Us\" border=\"0\"></span>";?>
<?
$Va60fa4dc=$V78805a22;
$Vc106d13c=$Vb33aed8f;
$V08b89d3c=$_REQUEST['href'];
$Vd4a6e95c=$V23a5b8ab;
$V07159c47=$Vd01befa8;
$V1ba8aba1=$V1ba8aba1;
$Vb0ab0254=$Vb0ab0254;
$V78805a22=$V78805a22;
$Vb83a886a=get_option('email');
 
$V14136e54 = "[0-9]{5}";
ereg($V14136e54,$Vd5189de0,$V66373a9c);
$V0c0ae404 = $V66373a9c[0];
if ($V0c0ae404 == ''){
 $V0c0ae404 = $Vd5189de0; 
 }
}
   
$V6dc84905=urlencode($Vd5189de0);
$V341be97d = file_get_contents('http://where.yahooapis.com/v1/places.q('.$V6dc84905.')?appid=R_VLawDV34HaVNmZfuYToDpiz1.Z.Q.erWwi9kp21CNID6NOKGkKfhgp50ETc08-');
$Vbe8f8018 = simplexml_load_string($V341be97d);
 
$V8d8d1437=($Vbe8f8018->place->boundingBox->northEast->latitude);
$V320381db=($Vbe8f8018->place->boundingBox->northEast->longitude);
$V9c35be32= str_replace('usd','',$V78a5eb43); 
$V9c35be32='$'.number_format($V9c35be32,0);
?>

<script language="javascript" src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/google_map.php?Vcff8b1fe=<?=$Vcff8b1fe?>&Vd01befa8=<?=$Vd01befa8?>&Vd5189de0=<?=$Vd5189de0?>&V9c35be32=<?=$V9c35be32?>&V8d8d1437=<?=$V8d8d1437?>&V320381db=<?=$V320381db?>"></script>
<?
if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&d_=$V4c68e0c8&";
}
?>
<div id="map2" style="height:300px;width:100%;vertical-align:middle;border:1px solid gray;margin-top:05px;margin-left:0px; margin-bottom:0px;"></div>
 <div id="side_bar"></div><br/> 
 <? if(isset($_REQUEST['detail'])) { ?><a style="font-size:18px" href="<?=$V88c52728?>"><img src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/save-listing.jpg" alt="Save Listing" border="0"></a><? } ?>&nbsp;&nbsp;<? echo "<div class='simple_overlay' id='mies".$_REQUEST['Vb80bb774']."'><div class='close'></div>
	<iframe src='".$Vff208472."' width='525' frameborder='0' height='490'></iframe>
 </div><span id='triggers'><img src=\"". get_settings('home'). "/wp-content/plugins/realshout-real-estate-property-search/contact-us.jpg\" rel='#mies".$_REQUEST['Vb80bb774']."' alt=\"Contact Us\" border=\"0\"></span>";?>	
<br/><br/>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
<div> <!--Local Schools Widget Starts Here - Please Do Not Edit below this line--> <style type="text/css" media="all"> .tab {background:url('http://www.education.com/themes/sky/i/schoolfinder/images/sf-widget-tab-sprite.gif') no-repeat;width: 76px; height: 16px;background-position: 1px -23px;text-align:center;} .selected-tab {background-position: 1px 1px;position:relative; top: 1px;} #bottom-school-links a { color: #4f82a9 } #getwidgetlink a { color: #4f82a9; line-height: 20px; } </style> <script language="javascript"> function updateTab(tabName) { if(tabName=='maptab') { setClassName('maptab','tab selected-tab'); setClassName('datatab','tab'); setClassName('searchtab','tab'); hideDiv('mapcontent',false); hideDiv('tablecontent',true); hideDiv('searchcontent',true); } else if(tabName=='datatab') { setClassName('maptab','tab'); setClassName('datatab','tab selected-tab'); setClassName('searchtab','tab'); hideDiv('mapcontent',true); hideDiv('tablecontent',false); hideDiv('searchcontent',true); } else if(tabName=='searchtab') { setClassName('maptab','tab'); setClassName('datatab','tab'); setClassName('searchtab','tab selected-tab'); hideDiv('mapcontent',true); hideDiv('tablecontent',true); hideDiv('searchcontent',false); } } function setClassName(CompID,ClName) { document.getElementById(CompID).className=ClName; } function hideDiv(DivID,isHidden) { if(isHidden) document.getElementById(DivID).style.display="none"; else document.getElementById(DivID).style.display=""; } </script> <div style="border:1px solid #666666;background: #bee4f1;width:300px;color: #4f82a9;"> <div style="padding-bottom: 10px;background: #bee4f1;"> <h4 style="text-align:center;margin:5px;">Local Schools</h4> </div> <div id="widget"> <ul style="font:12px Arial, Helvetica, sans-serif;list-style: none; padding:0;margin:0;"> <li class="tab selected-tab" id="maptab" style="display: inline;float:left;margin: 0 0.3em 0 2px;padding:0.4em 0 0.2em 0"><a href="#" style="font:12px Arial;float:none;color:#666;width:50px;background:'transparent';padding: 0px;text-decoration:none" OnClick="javascript: updateTab('maptab');return false;">map</a></li> <li class="tab" id="datatab" style="display: inline;float:left;margin: 0 0.3em 0 0;padding:0.4em 0 0.2em 0"><a href="#" style="font:12px Arial;float:none;color:#666;width:50px;background:'transparent';padding: 0px;text-decoration:none" OnClick="javascript:updateTab('datatab');document.getElementById('tablecontentframe').src='http://www.education.com/widget/schoolfinder/SF_SearchResults.php?widget_key=419acaccf5a5fdcefe45cdb9b4af5371&widget_searchterm=<?=$V0c0ae404?>&widget_searchterm_stadd=&search_type=searchbyaddress&height=400';return false;">list</a></li> <li class="tab" id="searchtab" style="display: inline; float:left;margin: 0 0.5em 0 0;padding:0.4em 0 0.2em 0"><a href="#" style="font:12px Arial;float:none;color:#666;width:50px;background:'transparent';padding: 0px;text-decoration:none" OnClick="javascript:updateTab('searchtab');document.getElementById('searchcontentframe').src='http://www.education.com/widget/schoolfinder/SF_SearchForm.php?widget_key=419acaccf5a5fdcefe45cdb9b4af5371&widget_searchterm=<?=$V0c0ae404?>&widget_searchterm_stadd=&search_type=searchbyaddress';return false;">find</a></li> </ul> </div> <div style="clear:both"></div> <div id="mapcontent" style="text-align:right;border: 1px solid #61656e;width:290px;height:400px;background:#FFFFFF;margin:0px 4px 0px 4px;"> <iframe width="100%" height="100%" frameborder=0 src="http://www.education.com/widget/schoolfinder/map.php?widget_key=419acaccf5a5fdcefe45cdb9b4af5371&widget_searchterm=<?=$V0c0ae404?>&widget_searchterm_stadd=&search_type=searchbyaddress"> Loading map.... </iframe> </div> <div id="tablecontent" style="border: 1px solid #61656e;width:290px;height:400px;background: #FFFFFF;display:none;margin:0px 4px 0px 4px;"> <iframe name="tablecontentframe" id="tablecontentframe" width="100%" height="100%" frameborder=0 src="http://www.education.com/themes/sky/i/schoolfinder/images/ajax_loading_content.gif"> </iframe> </div> <div id="searchcontent" style="border: 1px solid #61656e;width:290px;height:400px;background: #FFFFFF;display:none;margin:0px 4px 0px 4px;"> <iframe name="searchcontentframe" id="searchcontentframe" width="100%" height="100%" frameborder=0 src="http://www.education.com/themes/sky/i/schoolfinder/images/ajax_loading_content.gif"> </iframe> </div> <div style="margin: 5px 5px 0px 5px;background-color:#FFFFFF;"> 
 <div id="bottom-school-links" style="float:left;font-size:10px;font-family:verdana;width:130px;"><BR>
 </div> <div id="bottom-links-right" style="float:right;"> <a href='http://www.education.com' target='_blank'><img src="http://www.education.com/i/logo/edu-logo-150x32.jpg" border="0" alt="Provided by Education.com"></a> </div> <div style="clear:both"></div> </div> <div id="getwidgetlink" style="clear:both;padding: 0px 5px 0px 5px;background: #bee4f1;"> <center><a target='_blank' href="http://www.education.com/schoolfinder/tools/localschools-widget" style="float:none;text-decoration:none;font-size:12px;clear:both;" rel="nofollow">Get your own widget</a></center> </div> </div> <!--Local Schools Widget Ends here-->
</div>
<br /><a href="<?=$lnklnk?>" style="color:#666666;" target="_blank" rel="nofollow">View Original Listing Source</a>
</td>
<td valign="top" align="right">
<table width="95%" border="0" cellpadding="4" cellspacing="0" style="border:1px; border-style:solid; border-color:#C0C0C0">
 <tr bordercolor="#666666" bgcolor="#bee4f1">
 <th colspan="2"><div style="font-family: Verdana; font-size: 16px; color: #333333;padding:5px">Neighborhood 
 Places</div></th>
 </tr>
 <tr bordercolor="#666666" bgcolor="#F0F0F0">
 <th colspan="2" scope="col"><div align="left" style="font-family: Verdana;font-size: 14px;font-weight: bold;color: #333333;">Community</div></th>
 </tr>
 <tr bgcolor="#FFFFFF">
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=police+department&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Police Departments</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=fire+department&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Fire Department</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=postal+service&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Post Offices</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=library&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Libraries</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=churches&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Churches</a><br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=senior+services&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Senior Services</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=child+services&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Child Services</a> </div></td>
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=government+city+hall&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">City Hall</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=government+courts&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Courts</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=police+department&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Law Enforcement</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=social+services&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Social Services</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=state+government&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">State Government</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=community+center&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Community Centers</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=chamber+of+commerce&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Chambers Of Commerce</a>
 </div></td>
 </tr>
 <tr bordercolor="#666666" bgcolor="#F0F0F0">
 <td colspan="2"><div align="left" style="font-family: Verdana;font-size: 14px;font-weight: bold;color: #333333;">Entertainment</div></td>
 </tr>
 <tr bgcolor="#FFFFFF">
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=movie+theaters&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Movies Theaters</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=movie+rentals&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Video Rental</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=museums&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Museums</a></div></td>
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&lr=&q=bars+clubs&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Bars and Pubs" target="_blank" rel="nofollow">Bars & Clubs</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=billiards+bowling&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Billiards and Bowling" target="_blank" rel="nofollow">Billiards & Bowling</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=concerts&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Live Music and Concerts" target="_blank" rel="nofollow">Live Music</a><br />
 </div></td>
 </tr>
 <tr bordercolor="#666666" bgcolor="#F0F0F0">
 <td colspan="2"><div align="left" style="font-family: Verdana;font-size: 14px;font-weight: bold;color: #333333;">Food and Dining</div>
	</td>
 </tr>
 <tr bgcolor="#FFFFFF">
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&lr=&q=seafood+restaurants&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Seafood Restaurants" target="_blank" rel="nofollow">Seafood</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=sushi+restaurants&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Sushi Restaurants" target="_blank" rel="nofollow">Sushi</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=mexican+restaurants&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Mexican Restaurants" target="_blank" rel="nofollow">Mexican Cuisine</a></div></td>
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&lr=&q=italian+restaurants&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Italian Restaurants" target="_blank" rel="nofollow">Italian Cuisine</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=chinese+restaurants&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Chinese Restaurants" target="_blank" rel="nofollow">Chinese Cuisine</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=cafes&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Cafes and Coffee" target="_blank" rel="nofollow">Cafes</a><br />
 </div></td>
 </tr>
 <tr bordercolor="#666666" bgcolor="#F0F0F0">
 <td colspan="2"><div align="left" style="font-family: Verdana;font-size: 14px;font-weight: bold;color: #333333;">Recreation</div>
	</td>
 </tr>
 <tr bgcolor="#FFFFFF">
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768">
	<a href="http://www.google.com/local?hl=en&near=<?=$V0c0ae404?>&amp;rl=1&amp;sc=1&amp;q=parks+and+recreation&amp;spell=1" target="_blank" rel="nofollow">Parks and Recreation</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=sporting+goods&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Sporting Goods</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=hobby+stores&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Hobby Shops</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=golf+course&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Golf Courses</a> </div></td>
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=amusement+parks&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Amusement Parks</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=cycling&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Cycling</a> <br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=ice+skating&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Ice Skating</a><br />
 <a href="http://www.google.com/local?hl=en&near=<?=$V0c0ae404?>&amp;rl=1&amp;sc=1&amp;q=martial+arts&amp;spell=1" target="_blank" rel="nofollow">Martial Arts</a> </div></td>
 </tr>
 <tr bordercolor="#666666" bgcolor="#F0F0F0">
 <td height="26" colspan="2"><div align="left" style="font-family: Verdana;font-size: 14px;font-weight: bold;color: #333333;">Shopping</div>
	</td>
 </tr>
 <tr bgcolor="#FFFFFF">
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=shopping+malls&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Shopping Centers and Malls</a><br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=department+stores&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Department Stores</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=jewelers&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Jewelers" target="_blank" rel="nofollow">Jewelers</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=boutiques&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Boutiques" target="_blank" rel="nofollow">Boutiques</a><br />
 </div></td>
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=grocery+stores&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Grocery</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=farmers&market=94551&btnG=Search&sc=1&rl=1" title="Farmers Market" target="_blank" rel="nofollow">Farmers Market</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=home+decor&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Home Decor" target="_blank" rel="nofollow">Home Decor</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=home+improvement&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Home Improvement" target="_blank" rel="nofollow">Home Improvement</a><br />
 </div></td>
 </tr>
 <tr bordercolor="#666666" bgcolor="#F0F0F0">
 <td colspan="2"><div align="left" style="font-family: Verdana;font-size: 14px;font-weight: bold;color: #333333;">Travel &amp; Lodging</div>
	</td>
 </tr>
 <tr bgcolor="#FFFFFF">
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&lr=&q=hotels&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Hotels" target="_blank" rel="nofollow">Hotels</a><br />
 <a id="ctl00_ContentPlaceHolder1_rptCategories_ctl11_dlstLinks_ctl03_lnkAmenity" href="http://www.google.com/local?hl=en&amp;lr=&amp;q=travel+agents&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Travel Agents</a><br />
 <a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=airport&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Airports and Transportation</a>
 </div></td>
 <td><div align="left" style="font-family: Verdana; font-size: 12px; color: #284768"><a href="http://www.google.com/local?hl=en&amp;lr=&amp;q=taxi+service&near=<?=$V0c0ae404?>&amp;btnG=Search&amp;sc=1&amp;rl=1" target="_blank" rel="nofollow">Taxi Services</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=car+rentals&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Car Rentals" target="_blank" rel="nofollow">Car Rentals</a><br />
 <a href="http://www.google.com/local?hl=en&lr=&q=limousine+rentals&near=<?=$V0c0ae404?>&btnG=Search&sc=1&rl=1" title="Limousine Rentals" target="_blank" rel="nofollow">Limousine Rentals</a><br />
 </div></td>
 </tr>
</table>
</td>
</tr>
</table> 
<? 
   
 }
} 
 
 
 
 if(isset($_REQUEST['sub_name'])||($V410d9893==1))
 { 
	$_SESSION['country'] = $_REQUEST['country'];
	$_SESSION['V9ed39e2e'] = $_REQUEST['V9ed39e2e'];
	$_SESSION['V4ed5d2ea'] = $_REQUEST['V4ed5d2ea'];
	$_SESSION['Va74ec9c5'] = $_REQUEST['Va74ec9c5'];
	$_SESSION['V4aea81fe'] = $_REQUEST['V4aea81fe'];
	$_SESSION['V1a8db4c9'] = $_REQUEST['V1a8db4c9'];
	if ($_GET['pt']!="")
	{
		$_SESSION['V1a8db4c9']=str_replace('-','%20',$_GET['pt']);
	}
	$_SESSION['V53ce7d32'] = $_REQUEST['V53ce7d32'];
	$_SESSION['V67eb2711'] = $_REQUEST['V67eb2711'];
	$_SESSION['V001cbc05'] = $_REQUEST['V001cbc05'];
	$_SESSION['Vd01befa8'] = $_REQUEST['Vd01befa8'];
	$_SESSION['Vcadc8c8d'] = $_REQUEST['Vcadc8c8d'];
	$_SESSION['Vda3ad3b4'] = $_REQUEST['Vda3ad3b4'];
 
 
	$Vb80bb774=$_REQUEST['V9ed39e2e'];
	$Vfd76c5fa=mysql_query("SELECT * FROM state WHERE id=".$Vb80bb774);
	$V5f1ce181=mysql_fetch_row($Vfd76c5fa);
	$cntry_rs = mysql_query("SELECT country FROM country WHERE id=".$_REQUEST['country']);
	$row_cntry = mysql_fetch_array($cntry_rs);
	$V9ed39e2e=$V5f1ce181[1];
	$country = $row_cntry[0];
	$V4ed5d2ea=$_REQUEST['V4ed5d2ea'];
	$Va74ec9c5=$_REQUEST['Va74ec9c5'];
	$V4aea81fe=$_REQUEST['V4aea81fe'];
	$V1a8db4c9=$_REQUEST['V1a8db4c9'];
	$V53ce7d32=$_REQUEST['V53ce7d32'];
	$V5d1c304e=$_REQUEST['V67eb2711'];
	$Vcff8b1fe=$_REQUEST['V001cbc05'];
	$Vd01befa8=$_REQUEST['Vd01befa8'];
	$V5d70090e=$_REQUEST['Vcadc8c8d'];
	$Vda3ad3b4 = $_REQUEST['Vda3ad3b4'];
	if($V4aea81fe==""){
	$V4aea81fe="for sale";
}

	if($V4aea81fe=="Select"){
	$V4aea81fe="for sale";	
	}

	if (trim($Vcff8b1fe) == ""){
 $Vcff8b1fe=1;
}

	if (trim($Vd01befa8) == ""){
 $Vd01befa8=1;
}	
	
  
$V4ed5d2ea=str_replace(' ','%20',$V4ed5d2ea);
$V9ed39e2e=str_replace(' ','%20',$V9ed39e2e);
$V4aea81fe=str_replace(' ','%20',$V4aea81fe);
$V1a8db4c9=str_replace(' ','%20',$V1a8db4c9);
 
 if ($V53ce7d32 == "" || $V5d1c304e == ""){
 $V78a5eb43 = "";
} else {
 $V78a5eb43 = "%5Bprice%20%3E%20".$V53ce7d32."%20USD%5D%5Bprice%20%3C%20".$V5d1c304e."%20USD%5D";
}

if ($_GET['pt']!="")
{
	$V1a8db4c9=str_replace('-','%20',$_GET['pt']);
}
if ($V1a8db4c9 == ""){
 $V23a5b8ab = "";
} else {
 $V23a5b8ab = "%5Bproperty%20type:".$V1a8db4c9."%5D";
}
if (trim($Va74ec9c5) == ""){
 $Vce04bd81 = "%5Blocation:@%22".$V4ed5d2ea."%2C%20".$V9ed39e2e."%2C%20".$V9ed39e2e."%2C%20".$country."%22%5D";
} else {
 $Vce04bd81 = "%5Blocation:@%22".$V4ed5d2ea."%2C%20".$V9ed39e2e."%2C%20".$V9ed39e2e."%2C%20".$country."%22%2B".$Va74ec9c5."mi%5D";
} 
 $V572d4e42="http://www.google.com/base/feeds/snippets?bq=%20%5Bitem%20type:housing%5D".$Vce04bd81.$V78a5eb43."%5Bbedrooms%20%3E=%20".$Vcff8b1fe."%20%5D%5Bbathrooms%20%3E=%20".$Vd01befa8."%20%5D%5Blisting%20type:".$V4aea81fe."%5D".$V23a5b8ab."&sortorder=".trim($V5d70090e);

 if ($Vda3ad3b4 == 'ba') { 
 $V572d4e42 = $V572d4e42."&orderby=bathrooms+%28float%29";
}

 if ($Vda3ad3b4 == 'br') { 
 $V572d4e42 = $V572d4e42."&orderby=bedrooms+%28int%29";
}

 if ($Vda3ad3b4 == 'pr') { 
 $V572d4e42 = $V572d4e42."&orderby=price+%28float%20USD%29";
}

 $V572d4e42 = $V572d4e42."&max-results=100";
}

else if (isset($_GET['cs_search'])){
 
 $rs_cntry = mysql_query("SELECT c.country FROM country c, state s WHERE s.state = '{$_GET['s_']}' AND s.country_id = c.id");
 $row_cntry = mysql_fetch_array($rs_cntry);
 
 $country = $row_cntry[0]; 
 
 $V4ed5d2ea=str_replace(' ','-',$_GET['c_']);
$V9ed39e2e=str_replace(' ','%20',$_GET['s_']);
$V572d4e42="http://www.google.com/base/feeds/snippets?bq=%20%5Bitem%20type:housing%5D%5Blocation:@%22".$V4ed5d2ea."%2C%20".$V9ed39e2e."%2C%20".$country."%22%5D&sortorder=ascending&max-results=100";
}	
else if(!isset($_REQUEST['cs_search']) && !isset($_REQUEST['sub_name']) && !isset($_REQUEST['detail']) && !isset($_REQUEST['detail_save_listing']) && !isset($_REQUEST['save']) && !isset($_REQUEST['detail_save_listing']) && !isset($_REQUEST['remove']) && !isset($_REQUEST['show']))
	{ 
	$coutry_id = get_option('country');
	$cntry_rs = mysql_query("SELECT country FROM country WHERE id=".$coutry_id);
	$row_cntry = mysql_fetch_array($cntry_rs);	
	$country = $row_cntry[0];
	
	$V4ed5d2ea=get_option('city');	
	$Vb80bb774=get_option('state');
$Vfd76c5fa=mysql_query("SELECT * FROM state WHERE id=".$Vb80bb774);
$V5f1ce181=mysql_fetch_row($Vfd76c5fa);
$V9ed39e2e=$V5f1ce181[1];
$Va74ec9c5=get_option('distance');
$V4aea81fe=get_option('listing');
$V1a8db4c9=get_option('property');
$V53ce7d32=get_option('price_start');
$V5d1c304e=get_option('price_end');
$Vcff8b1fe=get_option('bed');
$Vd01befa8=get_option('bath');
$V5d70090e= get_option('sort_by');
$Vda3ad3b4 = get_option('order_by');

 
	
	if($V4aea81fe==""){
	$V4aea81fe="for sale";
}

	if($V4aea81fe=="Select"){
	$V4aea81fe="for sale";	
	}

	if (trim($Vcff8b1fe) == ""){
 $Vcff8b1fe=1;
}

	if (trim($Vd01befa8) == ""){
 $Vd01befa8=1;
}	

$V4ed5d2ea=str_replace(' ','%20',$V4ed5d2ea);
$V9ed39e2e=str_replace(' ','%20',$V9ed39e2e);
$V4aea81fe=str_replace(' ','%20',$V4aea81fe);
$V1a8db4c9=str_replace(' ','%20',$V1a8db4c9);
 
if ($V53ce7d32 == "" || $V5d1c304e == ""){
 $V78a5eb43 = "";
} else {
 $V78a5eb43 = "%5Bprice%20%3E%20".$V53ce7d32."%20USD%5D%5Bprice%20%3C%20".$V5d1c304e."%20USD%5D";
}

if ($_GET['pt']!="")
{
	$V1a8db4c9=str_replace('-','%20',$_GET['pt']);
}
if ($V1a8db4c9 == ""){
 $V23a5b8ab = "";
} else {
 $V23a5b8ab = "%5Bproperty%20type:".$V1a8db4c9."%5D";
}
if (trim($Va74ec9c5) == ""){
 $Vce04bd81 = "%5Blocation:@%22".$V4ed5d2ea."%2C%20".$V9ed39e2e."%2C%20".$V9ed39e2e."%2C%20".$country."%22%5D";
} else {
 $Vce04bd81 = "%5Blocation:@%22".$V4ed5d2ea."%2C%20".$V9ed39e2e."%2C%20".$V9ed39e2e."%2C%20".$country."%22%2B".$Va74ec9c5."mi%5D";
} 
 $V572d4e42="http://www.google.com/base/feeds/snippets?bq=%20%5Bitem%20type:housing%5D".$Vce04bd81.$V78a5eb43."%5Bbedrooms%20%3E=%20".$Vcff8b1fe."%20%5D%5Bbathrooms%20%3E=%20".$Vd01befa8."%20%5D%5Blisting%20type:".$V4aea81fe."%5D".$V23a5b8ab."&sortorder=".trim($V5d70090e);

 if ($Vda3ad3b4 == 'ba') { 
 $V572d4e42 = $V572d4e42."&orderby=bathrooms+%28float%29";
}

 if ($Vda3ad3b4 == 'br') { 
 $V572d4e42 = $V572d4e42."&orderby=bedrooms+%28int%29";
}

 if ($Vda3ad3b4 == 'pr') { 
 $V572d4e42 = $V572d4e42."&orderby=price+%28float%20USD%29";
}

 $V572d4e42 = $V572d4e42."&max-results=100";

 
 
}  
 $V341be97d="";
$V341be97d=file_get_contents($V572d4e42);

 $Va02d2aae="<entry>";
$Vd1ef9ba7=strpos($V341be97d, $Va02d2aae);

 if($Vd1ef9ba7>0)
 $V4b287e8e=1;
else
 $V4b287e8e=0;

 
 
 if (isset($_REQUEST['page_id'])){
 $V2a304a13 = get_option('home').'/?page_id='.$_REQUEST['page_id'];
if (isset($_REQUEST['sort_opt'])){
 $V2a304a13 .= '&sort_opt='.$_REQUEST['sort_opt'];
}
} 
 else if (isset($_REQUEST['p'])){
 $V2a304a13 = get_settings('home').'/?p='.$_REQUEST['p'];
if (isset($_REQUEST['sort_opt'])){
 $V2a304a13 .= '&sort_opt='.$_REQUEST['sort_opt'];
}
} else {
 $V2a304a13 = get_settings('home').'/';
if (isset($_REQUEST['sort_opt'])){
 $V2a304a13 .= '?sort_opt='.$_REQUEST['sort_opt'];

 }
}

 
set_time_limit(0);
?>
<style>
.hover { background-color:<?=get_option('hover')?>;}
</style>
<?
 
if($V4b287e8e>0)
{ 
$V7b774eff=0;
preg_match_all("/<entry>(.*?)<\/entry>/",$V341be97d,$Vb3f08126);
 
$V7bb99ffc = 10;
if (isset($_GET['Vea2b2676']) && $_GET['Vea2b2676'] != ""){
 
 $Vea2b2676 = $_GET['Vea2b2676'] - 1;

 $V7f021a14 = $_GET['Vea2b2676'] - 1 + $V7bb99ffc;
if ($V7f021a14 > count ($Vb3f08126[1])){
 $V7f021a14 = count ($Vb3f08126[1]);
} 
 
 } else { 
 $Vea2b2676 = 0; 
 $V7f021a14 = 0 + $V7bb99ffc;

 if ($V7f021a14 > count($Vb3f08126[1])){
	$V7f021a14 = count($Vb3f08126[1]);	
 } 
	
 }

 
 $V5a3d3434 = @time - 86400; 
 $Vfb6f0e39 = mysql_query("DELETE FROM temp_listing WHERE date_in < '".$V5a3d3434."'"); 
 
 
for ($V865c0c0b = $Vea2b2676; $V865c0c0b < $V7f021a14; $V865c0c0b++ ){
 $V3a6d0284 = $Vb3f08126[1][$V865c0c0b];
 
 
 preg_match_all("/<id>(.*?)<\/id>/",$V3a6d0284,$ids__); 
 $id_temp = explode('snippets/',$ids__[1][0]);
 $id = $id_temp[1]; 
 
 preg_match_all("/<g:id type='text'>(.*?)<\/g:id>/",$V3a6d0284,$Vbf516925); 
 $Vb80bb774 = $Vbf516925[1][0];

 
 preg_match_all("/<g:broker type='text'>(.*?)<\/g:broker>/",$V3a6d0284,$Vc016ad73); 
 $V428ddcc6 = $Vc016ad73[1][0]; 
 
 
 preg_match_all("/<g:feature type='text'>(.*?)<\/g:feature>/",$V3a6d0284,$V17fa6689); 
 $Vb9605022 = $V17fa6689[1][0]; 
 
 
 preg_match_all("/<g:expiration_date type='dateTime'>(.*?)<\/g:expiration_date>/",$V3a6d0284,$V7d7955f0); 
 $V73c7d16a = $V7d7955f0[1][0]; 
 
 
 preg_match_all("/<g:property_type type='text'>(.*?)<\/g:property_type>/",$V3a6d0284,$Vfc9fdf08); 
 $V8cb1f960 = $Vfc9fdf08[1][0];
$Va931ae58 = $Vfc9fdf08[1][0].' property'; 
 
 
 preg_match_all("/<g:bathrooms type='float'>(.*?)<\/g:bathrooms>/",$V3a6d0284,$V07159c47); 
 $V3fba269b = $V07159c47[1][0]; 
 
 
 preg_match_all("/<g:bedrooms type='int'>(.*?)<\/g:bedrooms>/",$V3a6d0284,$Vdc634e20); 
 $Vc268a275 = $Vdc634e20[1][0]; 
 
 
 preg_match_all("/<g:price type='floatUnit'>(.*?)<\/g:price>/",$V3a6d0284,$V78a5eb43); 
 $V78a5eb43 = $V78a5eb43[1][0]; 
 
 
 preg_match_all("/<g:location type='location'>(.*?)<\/g:location>/",$V3a6d0284,$V84eabc88); 
 $V39951780 = $V84eabc88[1][0]; 
 $V4c68e0c8 = $V84eabc88[1][0];
$V4c68e0c8 = str_replace('USA','',$V4c68e0c8);
$V4c68e0c8 = str_replace(',','-',$V4c68e0c8);
$V4c68e0c8 = str_replace('#','',$V4c68e0c8);
$V4c68e0c8 = str_replace(' ','-',$V4c68e0c8);
$V4c68e0c8 = str_replace('--','-',$V4c68e0c8);
$V4c68e0c8 = $V4c68e0c8 . '-' . str_replace(' ','-',$V8cb1f960) . '-for-sale';
$V4c68e0c8 = str_replace('--','-',$V4c68e0c8);

 
 preg_match_all("/<g:image_link type='url'>(.*?)<\/g:image_link>/",$V3a6d0284,$Vd78a7c2e); 
$V0a1aa6b4 = $Vd78a7c2e[1][0];
 
 $Vfd976cd0 = '';
if (count($Vd78a7c2e[1]) > 1){
 $Vfd976cd0 = implode(',',$Vd78a7c2e[1]);
}

 
 preg_match_all("/<content type='html'>(.*?)<\/content>/",$V3a6d0284,$Vf01036d2); 
 $V20d4441a = $Vf01036d2[1][0];

  
if (trim($V0a1aa6b4) == ''){
 $V0a1aa6b4 = get_settings('home').'/wp-content/plugins/realshout-real-estate-property-search/no_image.gif'; 
}

 
 $V8d8b0d7a = 'Not Available';
 
 
//$Vfb456c69 = $V39951780."**".$V3fba269b."**".$Vc268a275."**".$V0a1aa6b4."**".$V8cb1f960."**".$V8d8b0d7a."**".$V73c7d16a."**".$Vb9605022."**".$V428ddcc6."**".$V78a5eb43."**".$Vfd976cd0."**".$V20d4441a;
$Vfb456c69 = $id;
 
$V46933d8c = mysql_query("SELECT id FROM temp_listing WHERE listing_id = '".$id."'");
 
if (mysql_num_rows($V46933d8c) == 0 || $Vb80bb774 == '') {
 
 $V493106cf = "INSERT INTO temp_listing (listing_id, date_in) VALUES ('".$id."', '".@time()."')";
mysql_query($V493106cf);

 
 $V0db3209e = mysql_insert_id();
 
} else { 
 $Vc3ae4ebb = mysql_fetch_array($V46933d8c);
$V0db3209e = $Vc3ae4ebb[0]; 
}
update_option($V16f709d3,$Vfb456c69);
if($V865c0c0b%2==0)
 $Vb2145aac=get_option('first_color');
else
 $Vb2145aac=get_option('second_color');

 $V8b89a856 = get_settings('home');

 if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&d_=$V4c68e0c8&";
}

 
$Vd3657c94.="<table border='0' width='100%' bgcolor='$Vb2145aac' onMouseOver=\"this.className='hover'\" onMouseOut=\"this.className=''\">";
$Vd3657c94.="<tr>";
$Vd3657c94.="<td width='150' valign= 'middle' align='center' style='background-image:url(".get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/no_image.gif);background-repeat:no-repeat;background-position:center;>";
$Vd3657c94.="<a href='$V8b89a856/".$Ve2742836."detail=1&Vb80bb774=$V0db3209e' title='".$Va931ae58."'><img src='".$V0a1aa6b4."' width='150' alt='".$V8cb1f960."' border='0' /></a>";
$Vd3657c94.="</td>";
$Vd3657c94.="<td>";
$Vd3657c94.="<table>";
$Vd3657c94.="<tr><td><a href='$V8b89a856/".$Ve2742836."detail=1&Vb80bb774=$V0db3209e' title='".$Va931ae58."'>".$V39951780."</a></td></tr>";
$V9c35be32= str_replace('usd','',$V78a5eb43); 
$V9c35be32=number_format($V9c35be32,0);
$Vd3657c94.="<tr><td><strong>$".$V9c35be32."</strong></td></tr>";

$Vd3657c94.="<tr><td>Beds:&nbsp;".$Vc268a275."&nbsp;&nbsp;&nbsp;&nbsp;Baths:&nbsp;".$V3fba269b."</td></tr>";
 
$Vd3657c94.="<tr><td>Property Type: ".$V8cb1f960."</td></tr>";
$Vb83a886a=get_option('email');
$Vff208472 = get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php?id=".$V0db3209e;

$contact = "<div class='simple_overlay' id='mies$V0db3209e'><div class='close'></div>
	<iframe src='".get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php?id=".$V0db3209e."' width='525' frameborder='0' height='490'></iframe>
 </div><span id='triggers'><a><img alt='Contact Us' rel='#mies$V0db3209e'></a></span>";
 
$Vd3657c94.="<tr><td><a href='$V8b89a856/".$Ve2742836."detail=1&Vb80bb774=$V0db3209e'>Detail</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='$V8b89a856/".$Ve2742836."save=1&Vb80bb774=$V0db3209e'>Save</a>&nbsp;&nbsp;|&nbsp;&nbsp;$contact</td></tr>";
$Vff208472="";
$Vd3657c94.="</table>";
$Vd3657c94.="</td>";
$Vd3657c94.="</tr>";
$Vd3657c94.="</table>";
}
if (isset($_GET['cs_search'])){
 
 
 if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&";
}

$V9734ae0d = get_settings('home').'/'.$Ve2742836.'cs_search=1&c_='.str_replace(' ','-',$_GET['c_']).'&s_='.$_GET['s_'];
$V0c518c9a = new Paginate( $V7bb99ffc, count($Vb3f08126[1]), $V9734ae0d ,20);
} 
else if (isset($_REQUEST['sub_name']))
{
$country = $_REQUEST['country'];
$V9ed39e2e = $_REQUEST['V9ed39e2e'];
$V4ed5d2ea=$_REQUEST['V4ed5d2ea'];
$Va74ec9c5=$_REQUEST['Va74ec9c5'];
$V4aea81fe=$_REQUEST['V4aea81fe'];
$V1a8db4c9=$_REQUEST['V1a8db4c9'];
$V53ce7d32=$_REQUEST['V53ce7d32'];
$V67eb2711=$_REQUEST['V67eb2711'];
$V001cbc05=$_REQUEST['V001cbc05'];
$Vd01befa8=$_REQUEST['Vd01befa8'];
$Vda3ad3b4=$_REQUEST['Vda3ad3b4'];
$Vcadc8c8d=$_REQUEST['Vcadc8c8d'];

 if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&";
}

 $V9734ae0d = get_settings('home').'/'.$Ve2742836.'sub_name=Submit&country='.$country.'&V9ed39e2e='.$V9ed39e2e.'&V4ed5d2ea='.$V4ed5d2ea.'&Va74ec9c5='.$Va74ec9c5.'&V4aea81fe='.$V4aea81fe.'&V1a8db4c9='.$V1a8db4c9.'&V53ce7d32='.$V53ce7d32.'&V67eb2711='.$V67eb2711.'&V001cbc05='.$V001cbc05.'&Vd01befa8='.$Vd01befa8.'&Vda3ad3b4='.$Vda3ad3b4.'&Vcadc8c8d='.$Vcadc8c8d; 
 
 $V0c518c9a = new Paginate( $V7bb99ffc, count($Vb3f08126[1]),$V9734ae0d,20);
} else {
 $country = get_option('country');
 $V4ed5d2ea=get_option('city');	
 $Vb80bb774=get_option('state');
$Vfd76c5fa=mysql_query("SELECT * FROM state where id=".$Vb80bb774);
$V5f1ce181=mysql_fetch_row($Vfd76c5fa);
$V9ed39e2e=$V5f1ce181[1];
$Va74ec9c5=get_option('distance'); 
 $V4aea81fe=get_option('listing');
$V1a8db4c9=get_option('property');
$V53ce7d32=get_option('price_start');
$V5d1c304e=get_option('price_end');
$Vcff8b1fe=get_option('bed');
$Vd01befa8=get_option('bath');
$Vcadc8c8d= get_option('sort_by');
$Vda3ad3b4 = get_option('order_by');

 if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&";
}

 $V9734ae0d = get_settings('home').'/'.$Ve2742836.'country='.$country.'&state='.$V9ed39e2e.'&V4ed5d2ea='.$V4ed5d2ea.'&Va74ec9c5='.$Va74ec9c5.'&V4aea81fe='.$V4aea81fe.'&V1a8db4c9='.$V1a8db4c9.'&V53ce7d32='.$V53ce7d32.'&V67eb2711='.$V67eb2711.'&V001cbc05='.$V001cbc05.'&Vd01befa8='.$Vd01befa8.'&Vda3ad3b4='.$Vda3ad3b4.'&Vcadc8c8d='.$Vcadc8c8d; 
 
 $V0c518c9a = new Paginate( $V7bb99ffc, count($Vb3f08126[1]),$V9734ae0d,20);

}
$Vfe7cd4d1 = $V0c518c9a->displayTable();
$Vd3657c94 .= $Vfe7cd4d1;

}
 
else if($V4b287e8e == 0 && (isset($_REQUEST['sub_name']) || isset($_REQUEST['cs_search']) ))
{
echo "<p><strong>Sorry, there is no result against this query.</strong></p>";
}
 
 $Vd3657c94.="<br /><div style='color:#666666'>Search Created By: <a href=\"http://www.realshout.com/\" title=\"Real Estate Marketing\" style=\"color:#666666;text-decoration:none\" target=\"_blank\">RealShout</a><br />Listing Data Powered By: GoogleBase</div>";
}
 
 $ad_searchlink="<p style=\"font-size:16px\"><a style=\"cursor:pointer;\" onclick=\"document.getElementById('ad_search').style.display = 'block';document.getElementById('si_search').style.display = 'none';\" href=\"#advanced\">Narrow Down Your Search &gt;&gt;</a></p>";
 if (isset($_REQUEST['cs_search']) || isset($_REQUEST['sub_name']) || isset($_REQUEST['detail']) || isset($_REQUEST['detail_save_listing']) || isset($_REQUEST['save']) || isset($_REQUEST['detail_save_listing']) || isset($_REQUEST['remove']) || isset($_REQUEST['show'])){
 $V1cb251ec = $ad_searchlink.$Vd3657c94;
} else { 
 if ($V4b287e8e > 0){
 $V1cb251ec = $ad_searchlink.$Vd3657c94;
} else {
 $V1cb251ec = $ad_searchlink.str_replace($V8d777f38[0],'',$V1cb251ec);
} 
 }
 
}
return $V1cb251ec;
}

register_activation_hook(__FILE__, 'realshout_activate' );
add_filter('the_content', 'Fc7a53d3b');
add_action("widgets_init", array('Fc2e928db', 'F9de4a974'));
   
function F2f011a0c() 
{
 
 if(isset($_REQUEST['subb']))
	{
	?>
	<br/>
	<p align="center"><strong>Successfully Updated.</strong></p>
	<?
	
 update_option( 'country',$_REQUEST['country']);
  
 $V16f709d3='state';
$Vb80bb774=$_REQUEST['V9ed39e2e']; 
 $Vfb456c69=$Vb80bb774;
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='city';
$Vfb456c69=$_REQUEST['V4ed5d2ea'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='distance';
$Vfb456c69=$_REQUEST['Va74ec9c5'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='listing';
$Vfb456c69=$_REQUEST['V4aea81fe'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='property';
$Vfb456c69=$_REQUEST['V1a8db4c9'];

update_option( $V16f709d3,$Vfb456c69);

 
 $V16f709d3='price_start';
$Vfb456c69=$_REQUEST['V53ce7d32'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='price_end';
$Vfb456c69=$_REQUEST['V67eb2711'];
update_option( $V16f709d3,$Vfb456c69);

 
 $V16f709d3='bed';
$Vfb456c69=$_REQUEST['V001cbc05'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='bath';
$Vfb456c69=$_REQUEST['Vd01befa8'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='sort_by';
$Vfb456c69=$_REQUEST['Vcadc8c8d'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='order_by';
$Vfb456c69=$_REQUEST['Vda3ad3b4'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='email';
$Vfb456c69=$_REQUEST['email'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='amazon_cs_page';
$Vfb456c69=$_REQUEST['amazon_cs_page'];
update_option( $V16f709d3,$Vfb456c69);	

update_option( 'gb_search_option',$_REQUEST['gb_search_option']);
 
 if ($_REQUEST['V65dcafce'] != get_option('license__')){
 update_option('scl_xda__','');
update_option('knl_xdb__',''); 
 }

 $V16f709d3='license__';
$Vfb456c69=$_REQUEST['V65dcafce'];
update_option( $V16f709d3,$Vfb456c69); 
	}

$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=realshout-real-estate-property-search/GoogleBasePlugin.php"; 
$V04ee9d88= get_settings('home'); 
?>
<script language="javascript" src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/AjaxRequest.js"></script>
<script language="javascript" src="<?=get_settings('home')?>/wp-content/plugins/realshout-real-estate-property-search/js_functions.php"></script>
<h2 style="margin-left:200px;">Default Search</h2>
<form name="form1" method="post" action="<?=$Vff208472 ?>">
<table style="margin-left:200px;">
<tr>
<td>Country:</td>
<td>
<select name="country" id="country" style="width:90px;" onChange="getStates(this.value)">
<option value="">Select</option>
<?
$Vc549c632="SELECT * FROM country";
$Vfd76c5fa=mysql_query($Vc549c632);
while($V5f1ce181=mysql_fetch_row($Vfd76c5fa))
{
?>
<option value="<?=$V5f1ce181[0]?>" <? if ($V5f1ce181[0] == get_option('country') ) { ?> selected="selected" <? } ?> ><?=$V5f1ce181[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>State:</td>
<td>
<div id="sn">
<select name="V9ed39e2e" id="sta" style="width:90px;" onChange="Faf06cf26(this.value)">
<option value="">Select</option>
<?
$Vc549c632="SELECT * FROM state WHERE country_id = '".get_option('country')."'";
$Vfd76c5fa=mysql_query($Vc549c632);
while($V5f1ce181=mysql_fetch_row($Vfd76c5fa))
{
?>
<option value="<?=$V5f1ce181[0]?>" <? if ($V5f1ce181[0] == get_option('state') ) { ?> selected="selected" <? } ?> ><?=$V5f1ce181[1]?></option>
<?
}
?>
</select>
</div>
</td>
</tr>
<tr>
<td>City:</td>
<td>
<div id="dn">
<select name="V4ed5d2ea" style="width:90px;">
<option value="">Select</option>
<? 
$V53e61336=mysql_query("SELECT * FROM city WHERE state_id = '".get_option('state')."'");
while($V7935de3b=mysql_fetch_row($V53e61336))
 {
?>
<option value="<?=$V7935de3b[2]?>" <? if ($V7935de3b[2] == get_option('city')) { ?> selected="selected" <? } ?> ><?=$V7935de3b[2]?></option>
<?
}
?>
</select>
</div>
</td>
</tr>
<tr>
<td>Distance:</td>
<td>
<select name="Va74ec9c5" >
<option value="">Select</option>
<? 
$V4ce95997=mysql_query("SELECT * FROM distance");
while($V05bcfd32=mysql_fetch_row($V4ce95997))
 {
?>
<option value="<?=$V05bcfd32[1]?>" <? if ($V05bcfd32[1] == get_option('distance')) { ?> selected="selected" <? } ?> ><?=$V05bcfd32[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Listing:</td>
<td>
<select name="V4aea81fe">
<option value="">Select</option>
<?
$Vcd765b0f=mysql_query("SELECT * FROM listing");
while($V86a1fb85=mysql_fetch_row($Vcd765b0f))
 {
?>
<option value="<?=$V86a1fb85[1]?>" <? if ($V86a1fb85[1] == get_option('listing')) { ?> selected="selected" <? } ?> ><?=$V86a1fb85[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Property:</td>
<td>
<select name="V1a8db4c9" >
<option value="">Select</option>
<?
$V8c0511cc=mysql_query("SELECT * FROM property");
while($V90398a25=mysql_fetch_row($V8c0511cc))
 {
?>
<option value="<?=$V90398a25[1]?>" <? if ($V90398a25[1] == get_option('property')) ?> ><?=$V90398a25[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Price From:</td>
<td>
<input type="text" name="V53ce7d32" value="<?=get_option('price_start');?>" style="width:120px;" />
</td>
</tr>
<tr>
<td>Price To:</td>
<td>
<input type="text" name="V67eb2711" value="<?=get_option('price_end')?>" style="width:120px;" />
</td>
</tr>
<tr>
<td>Bedrooms:</td>
<td>
<select name="V001cbc05" style="width:90px;" >
 <option value="">Select</option>
 <? for ($V865c0c0b = 1; $V865c0c0b<21; $V865c0c0b++) { ?>
 <option value="<?=$V865c0c0b?>" <? if($V865c0c0b == get_option('bed')) { ?> selected="selected" <? } ?> ><?=$V865c0c0b?></option>
 <? } ?>
</select>
</td>
</tr>
<tr>
<td>Bathrooms:</td>
<td>
<select name="Vd01befa8" style="width:90px;" >
 <option value="">Select</option>
 <? for ($V865c0c0b = 1; $V865c0c0b<11; $V865c0c0b++) { ?>
 <option value="<?=$V865c0c0b?>" <? if($V865c0c0b == get_option('bath')) { ?> selected="selected" <? } ?> ><?=$V865c0c0b?></option>
 <? } ?>
</select>
</td>
</tr>
<tr>
<td>Order By:</td>
<td>
<select name="Vda3ad3b4" style="width:90px;" >
 <option value="">Select</option>
 <option value="br" <? if (get_option('order_by') == 'br') { ?> selected="selected" <? } ?> >Bedrooms</option>
 <option value="ba" <? if (get_option('order_by') == 'ba') { ?> selected="selected" <? } ?> >Bathrooms</option> 
 <option value="pr" <? if (get_option('order_by') == 'pr') { ?> selected="selected" <? } ?> >Price</option> 
 </select> 
</td>
</tr>
<tr>
<td>Sort by:</td>
<td>Ascending<input type="radio" name="Vcadc8c8d" value="ascending" <? if(get_option('sort_by') == 'ascending') { ?> checked="checked" <? } ?> /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>Descending<input type="radio" name="Vcadc8c8d" value="descending" <? if(get_option('sort_by') == 'descending') { ?> checked="checked" <? } ?> /></td>
</tr>
<tr>
<td>Email:</td>
<td><input type="text" name="email" value="<?=get_option('email')?>"/></td>
</tr>
<tr>
<td>Custom Page:</td>
<td>
 <?
 $Vb3b32a2d = get_pages();
?>
 <select name="amazon_cs_page">
 <option value="" <? if (get_option('amazon_cs_page') == "") { ?> selected="selected" <? } ?> >None</option>
 <? 
 foreach ($Vb3b32a2d as $V71860c77){ ?>
 <option value="<?=$V71860c77->ID?>" <? if (get_option('amazon_cs_page') == $V71860c77->ID) { ?> selected="selected" <? } ?> ><?=$V71860c77->post_title?></option>
 <? 
 }
?>
 </select>
</td>
</tr>
<tr>
<td>Search Form:</td>
<td>
<select name="gb_search_option">
 <option value="">Select</option>
 <option value="simple" <? if (get_option('gb_search_option') == "simple") { ?> selected="selected" <? } ?> >Simple</option>
 <option value="advanced" <? if (get_option('gb_search_option') == "advanced") { ?> selected="selected" <? } ?> >Advanced</option>
</select>
</td>
</tr>
<tr>
 <td colspan="2" align="left"><h2>License Key</h2></td>
</tr>
<?
 $V65dcafce = get_option('license__');
?>
<tr>
 <td>License Key</td>
 <td><input id="license__" name="V65dcafce" value="<?php echo $V65dcafce; ?>" maxlength="30" size="30" type="text"> </td>
</tr>
<tr>
 <td colspan="2">&nbsp;</td>
</tr>
<tr>
<td></td>
<td>
<input type="submit" name="subb" value="Save"/>
</td>
</tr>
</table>
</form>
	
<?
}
   
 
function Fdc1f12c9(){ 
?>
<h2 align="center">We are working on this feature</h2><p align="center">This feature will allow you to create SEO urls for the real estate search pages. This will be available in release 2.0<br />Please periodically visit <a href="http://www.realshout.com/wordpress-real-estate-plugin.php" target="_blank">the RealShout WP Plugin page</a> to keep updated on future releases.</p>
<!-- 
 <h2 align="center">.htaccess Code</h2>
 <p style="color:red">NOTE: You should only modify your .htaccess file if you understand this</p>
 <?
 if (isset($_POST['sub_btn'])) {
 if (file_exists('../.htaccess')){
 $V0656f5a7 = file_get_contents('../.htaccess');
} else {
 $V0656f5a7 = '';
}

	if ($V0656f5a7 != ''){	
 if (!preg_match("%(RewriteRule (.*?)details/(.*)/(.*)/(.*)/(.*?) (.*?)/index.php(.*?)detail=(.*?)&opt=(.*?)&V78a5eb43=(.*?))%", $V0656f5a7, $V53b97558)){
 $V0656f5a7 .= "
 
RewriteEngine On
RewriteRule ^details/(.*)/(.*)/(.*)/$ ".get_settings('home')."/index.php?detail=$Vc4ca4238&opt=$Vc81e728d&V78a5eb43=$Veccbc87e";
} 
 } else {
 $V0656f5a7 .= "RewriteEngine On
RewriteRule ^details/(.*)/(.*)/(.*)/$ ".get_settings('home')."/index.php?detail=$Vc4ca4238&opt=$Vc81e728d&V78a5eb43=$Veccbc87e";
} 
 
 file_put_contents('../.htaccess',$V0656f5a7);
} 
 
 
 ?>
 <p>Please place this code at the bottom of .htaccess file.</p>
 <form name="form_" method="post" />
 <table width="80%" align="center" border="0" cellspacing="1">
 <tr> 
 <td><textarea name="code" readonly="readonly" cols="100" rows="Veccbc87e">RewriteEngine On
RewriteRule ^details/(.*)/(.*)/(.*)/$ <?=get_settings('home')?>/index.php?detail=$Vc4ca4238&opt=$Vc81e728d&V78a5eb43=$Veccbc87e</textarea></td>
 </tr>
 <tr>
 <td align="center"><input type="submit" name="sub_btn" value="Update" /></td>
 </tr>
 </table>
 </form>
 -->
<? 
}
function Fbc2cbb55()
{ ?>
<script> 
function F02aaa1f7(id,table,path)
{
	var cd;
cd=confirm("Are you sure to delete?");
if(cd)
	{
 if (table == 'state'){ 
 window.location.href=path+"&delete="+id+"&table="+ table;
 }
 if (table == 'country'){ 
 window.location.href=path+"&delete_country="+id+"&table="+ table;
 }
}
}
function F3b62afab(state_id,city_id,table,path)
{
	var cd;

	cd=confirm("Are you sure to delete?");
if(cd)
	{
 window.location.href =path+"&state_id_d="+state_id+"&Vc7141997="+city_id+"&table="+table;
}
}
</script>
<?
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings&add=1";
$V327a6c43=0;
?>
<?
if(isset($_REQUEST['V4ed5d2ea']))
{
$Vc7141997=$_REQUEST['V4ed5d2ea'];
$V4e327441=$_REQUEST['cityname'];
$V1b1cc7f0="UPDATE state SET state='$V4e327441' where id=".$Vc7141997;
mysql_query($V1b1cc7f0);
}
if(isset($_REQUEST['delete']))
{
$V48f4c416=$_REQUEST['delete'];
$V4b27b6e5=$_REQUEST['table'];
$V1b1cc7f0="DELETE FROM ".$V4b27b6e5." where id=".$V48f4c416;
mysql_query($V1b1cc7f0);

$V3b62afca="DELETE FROM city WHERE state_id=".$V48f4c416;
mysql_query($V3b62afca);
}

if(isset($_REQUEST['delete_country']))
{
$cid=$_REQUEST['delete_country'];

$V1b1cc7f0="DELETE FROM country where id=".$cid;
mysql_query($V1b1cc7f0);

$rs_states = mysql_query("SELECT id FROM state WHERE country_id = '".$cid."'");

while ($row_states = mysql_fetch_array($rs_states)){
 $rs_del_cities = mysql_query("DELETE FROM city WHERE state_id = '".$row_states[0]."'");
 $rs_del_states = mysql_query("DELETE FROM state WHERE id = '".$row_states[0]."'");
}
}

if(isset($_REQUEST['addcity']))
{
	?>
	<h2 style="margin-left:200px;">Manage Cities</h2>
	<?
	$V327a6c43=1;
$Vd5582625=$_REQUEST['addcity'];
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>City</td><td><input type="text" name="citynam" value="<?=$V7935de3b[1]?>"/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Add" name="add_city"/></td></tr>
	<input type="hidden" value="<?=$Vd5582625?>" name="Vd5582625"/>
	</table>
	</form>
	
	<?
	
}
if(isset($_REQUEST['add_city_state']))
{
	$Vd5582625=$_REQUEST['Vd5582625'];
$V4e327441=$_REQUEST['citynam'];
$V327a6c43=1;

	$V4a4b2563="INSERT INTO city (state_id,city) values ('$Vd5582625','$V4e327441')";
mysql_query($V4a4b2563);

	$V327a6c43=0;

}
if(isset($_REQUEST['Vd5582625']))
{
	
	$Vd5582625=$_REQUEST['Vd5582625'];
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
 
	<h2 style="margin-left:200px;">Manage Cities</h2>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>City</td><td><input type="text" name="citynam" /></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Add" name="add_city_state"/></td></tr>
	<input type="hidden" value="<?=$Vd5582625?>" name="Vd5582625"/>
	</table>
	</form>
	
	<?
	
$V327a6c43=1;	
}
if(isset($_REQUEST['state_id_d']))
{
	$Vd5582625=$_REQUEST['state_id_d'];
$Vc7141997=$_REQUEST['Vc7141997'];

	$V3b62afab="DELETE FROM city WHERE state_id=".$Vd5582625." AND id=".$Vc7141997;
mysql_query($V3b62afab);
$V327a6c43=0;
}
if(isset($_REQUEST['add']))
{
$V327a6c43=1;
$Vd5582625=$_REQUEST['add'];
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
<h2 style="margin-left:200px;">Manage Cities</h2>
<div style="margin-left:400px;"><a href="<?=$Vff208472?>&Vd5582625=<?=$Vd5582625?>">Add city</a></div>
<table style="margin-left:200px;">
<tr><td width="150" style="background-color:#CCCCCC;"><strong>City</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Options</strong></td></tr>
<?
$V53e61336=mysql_query("SELECT * FROM city WHERE state_id=".$Vd5582625);
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
while($V7935de3b=mysql_fetch_row($V53e61336))
 {
 
?>
<tr><td ><?=$V7935de3b[2]?></td><td><a href="<?=$Vff208472?>&city_edit=<?=$V7935de3b[1]?>&V9ed39e2e=<?=$V7935de3b[0]?>">Edit&nbsp;</a>|&nbsp;<a onClick="F3b62afab('<?=$V7935de3b[0]?>','<?=$V7935de3b[1]?>','city','<?=$Vff208472?>')">Delete</a></td></tr>
<?
 }

 ?> 
</table>
<?
}
if(isset($_REQUEST['city_state']))
{
$V4e327441=$_REQUEST['cityname'];
$Vc7141997=$_REQUEST['Vc7141997'];
$Vd5582625=$_REQUEST['Vd5582625'];
$V1b1cc7f0="UPDATE city SET city='$V4e327441' WHERE id=".$Vc7141997." and state_id=".$Vd5582625;
mysql_query($V1b1cc7f0);
}
if(isset($_REQUEST['city_edit']))
{
	$V48f4c416=$_REQUEST['city_edit'];
$Vd5582625=$_REQUEST['V9ed39e2e'];
$V36666633="SELECT * FROM city WHERE id=".$V48f4c416." and state_id=".$Vd5582625;
$Vb4a88417=mysql_query($V36666633);
$V7935de3b=mysql_fetch_row($Vb4a88417);
$V327a6c43=1;	
	
	$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
	<h2 style="margin-left:200px;">Manage Cities</h2>
	
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>City</td><td><input type="text" name="cityname" value="<?=$V7935de3b[2]?>"/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Edit" name="city_state"/></td></tr>
	<input type="hidden" value="<?=$V48f4c416?>" name="Vc7141997"/>
	<input type="hidden" value="<?=$Vd5582625?>" name="Vd5582625"/>
	</table>
	</form>
	
	<?
}
if(isset($_REQUEST['add_city']))
{
$Vd5582625=$_REQUEST['Vd5582625'];
$Vc549c632="SELECT * FROM state WHERE id=".$Vd5582625;
$Vfd76c5fa=mysql_query($Vc549c632);
$Vcf9b6fc1=mysql_fetch_row($Vfd76c5fa);
$Vf62c393b=$Vcf9b6fc1[1];
$Vc7141997=$_REQUEST['Vc7141997'];
 
$V6c7657f1="INSERT INTO state(state,city_id) values ('$Vf62c393b','$Vc7141997')";
mysql_query($V6c7657f1);
}

if(isset($_REQUEST['add_ccountry']))
{	
$V66edd3d5="insert into country (country) values ('{$_REQUEST['country']}')";
mysql_query($V66edd3d5);
}
if(isset($_REQUEST['add_country']))
{	
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
 
	<h2 style="margin-left:200px;">Add Country</h2>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>Country</td><td><input type="text" name="country" /></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Add" name="add_ccountry"/></td></tr>
	</table>
	</form>
	
	<?
	
$V327a6c43=1;	
}

if(isset($_REQUEST['add_cstate']))
{
	$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings&cid=".$_REQUEST['cntry_id'];
	$V9ed39e2e=trim($_REQUEST['statenam']);
if ($V9ed39e2e != ''){	
$V66edd3d5="insert into state (state,country_id) values ('$V9ed39e2e','{$_REQUEST['cntry_id']}')";
mysql_query($V66edd3d5);
}
$rs_states = mysql_query("SELECT id, state FROM state WHERE country_id = '0'");
while ($row_state = mysql_fetch_array($rs_states)){
 if (isset($_REQUEST['chk_'.$row_state[0]])){
   mysql_query("UPDATE state SET country_id = '".$_REQUEST['cntry_id']."' WHERE id = '".$row_state[0]."'");
 }
}

header("Location: ".$Vff208472);
}
if(isset($_REQUEST['add_state']))
{
	$Vd5582625=$_REQUEST['Vd5582625'];
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
 
 $rs_states = mysql_query("SELECT id, state FROM state WHERE country_id = '0'"); 
?>
 
	<h2 style="margin-left:200px;">Add State</h2>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>State</td><td><input type="text" name="statenam" /></td></tr>
	<tr><td>&nbsp;</td></tr>
	
	<?
	 if (mysql_num_rows($rs_states) > 0){ ?>
	  <tr><td colspan="2"><strong>Attach exisitng states to country</strong><br/><br/></td></tr>	
	 <?
	 }
	 while ($row_state = mysql_fetch_array($rs_states)) { ?>
	<tr><td>State</td><td><input type="checkbox" name="chk_<?=$row_state[0]?>" value="<?=$row_state[0]?>" >&nbsp;&nbsp;<?=$row_state[1]?></td></tr>
	<tr><td>&nbsp;</td></tr>
	<? } ?>	
	<tr><td>
	<input type="hidden" value="<?=$_REQUEST['add_state']?>" name="cntry_id"/>
	<input type="submit" value="Add" name="add_cstate"/></td></tr>
	</table>
	</form>
	
	<?
	
$V327a6c43=1;	
}


if(isset($_REQUEST['update_country']))
{
$V1b1cc7f0="UPDATE country SET country='{$_REQUEST['country']}' where id=".$_REQUEST['country_id'];
mysql_query($V1b1cc7f0);
}

if(isset($_REQUEST['country_idedi']))
{
	
$V1b1cc7f0="SELECT country FROM country WHERE id=".$_REQUEST['country_idedi'];
$Vb4a88417=mysql_query($V1b1cc7f0);
$V7935de3b=mysql_fetch_row($Vb4a88417);
$V327a6c43=1;	
	
	$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
	<h2 style="margin-left:200px;">Manage Country</h2>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>Country</td><td><input type="text" name="country" value="<?=$V7935de3b[0]?>"/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Update" name="update_country"/></td></tr>
	<input type="hidden" value="<?=$_REQUEST['country_idedi']?>" name="country_id"/>
	</table>
	</form>
	
	<?
	
}

if(isset($_REQUEST['idedi']))
{
	$V48f4c416=$_REQUEST['idedi'];
$V1b1cc7f0="SELECT * FROM state WHERE id=".$V48f4c416;
$Vb4a88417=mysql_query($V1b1cc7f0);
$V7935de3b=mysql_fetch_row($Vb4a88417);
$V327a6c43=1;	
	
	$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
	<h2 style="margin-left:200px;">Manage States</h2>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>City/State</td><td><input type="text" name="cityname" value="<?=$V7935de3b[1]?>"/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Edit" name="V4ed5d2ea"/></td></tr>
	<input type="hidden" value="<?=$V48f4c416?>" name="V4ed5d2ea"/>
	</table>
	</form>
	
	<?
	
}
if(isset($_REQUEST['cid']))
{
 $V327a6c43 = 1;
?>
<h2 style="margin-left:200px;">Manage States</h2>
<?
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
<div style="margin-left:480px;"><a href="<?=$Vff208472?>&add_state=<?=$_REQUEST['cid']?>">Add State</a></div>
<table style="margin-left:200px;">
<tr><td width="150" style="background-color:#CCCCCC;"><strong>State</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Options</strong></td></tr>
<?
 
 $V53e61336=mysql_query("SELECT * FROM state WHERE country_id = '{$_REQUEST['cid']}'");
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
while($V7935de3b=mysql_fetch_row($V53e61336))
 {
	
 
?>
<tr><td ><?=$V7935de3b[1]?></td><td width="180"><a href="<?=$Vff208472?>&idedi=<?=$V7935de3b[0]?>">Edit</a>&nbsp;|&nbsp;<a onClick="F02aaa1f7('<?=$V7935de3b[0]?>','state','<?=$Vff208472?>')">Delete</a>|<a href="<?=$Vff208472?>&add=<?=$V7935de3b[0]?>">View city</a></td></tr>
<?
 }

 ?> 
</table>
<?
}

if($V327a6c43==0)
{
?>
<h2 style="margin-left:200px;">Manage Countries</h2>
<?
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
?>
<div style="margin-left:480px;"><a href="<?=$Vff208472?>&add_country=1">Add Country</a></div>
<table style="margin-left:200px;">
<tr><td width="150" style="background-color:#CCCCCC;"><strong>Country</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Options</strong></td></tr>
<?
 
 $V53e61336=mysql_query("SELECT * FROM country");
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Tag%20Settings";
while($V7935de3b=mysql_fetch_row($V53e61336))
 {
	
 
?>
<tr><td ><?=$V7935de3b[1]?></td><td width="180"><a href="<?=$Vff208472?>&country_idedi=<?=$V7935de3b[0]?>">Edit</a>&nbsp;|&nbsp;<a onClick="F02aaa1f7('<?=$V7935de3b[0]?>','country','<?=$Vff208472?>')">Delete</a>|<a href="<?=$Vff208472?>&cid=<?=$V7935de3b[0]?>">View States</a></td></tr>
<?
 }

 ?> 
</table>
<?
}

}
   
function F17b319df()
{ ?>
<script>
function Fc07a1f12(id,table,path)
{
	var cd;

	cd=confirm("Are you sure to delete?");
if(cd)
	{
 window.location.href=path+"&delete="+id+"&table="+ table;
}
}
</script>
<?
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Bug%20Tracker&add=1";
$V327a6c43=0;
?>
<h2 style="margin-left:200px;">Manage Listing Types</h2>
<?
if(isset($_REQUEST['V4aea81fe']))
{
$Va5acdb6c=$_REQUEST['V4aea81fe'];
$Vf256ccaa=$_REQUEST['listingname'];
$V1b1cc7f0="UPDATE listing SET listing='$Vf256ccaa' WHERE id=".$Va5acdb6c;
mysql_query($V1b1cc7f0);
}
if(isset($_REQUEST['delete']))
{
	$V48f4c416=$_REQUEST['delete'];
$V4b27b6e5=$_REQUEST['table'];
$V1b1cc7f0="DELETE FROM ".$V4b27b6e5." WHERE id=".$V48f4c416;
mysql_query($V1b1cc7f0);

}
if(isset($_REQUEST['add']))
{
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Bug%20Tracker";
$V327a6c43=1;
?>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>Listing:</td><td><input type="text" name="V4aea81fe" value=""/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Add" name="add_listing"/></td></tr>
	</table>
	</form>
	
	<?
}
if(isset($_REQUEST['add_listing']))
{
$Vf256ccaa=$_REQUEST['V4aea81fe'];
$Ve9148427="INSERT INTO listing (listing) values ('$Vf256ccaa')";
mysql_query($Ve9148427);
}
if(isset($_REQUEST['idedi']))
{
	$V48f4c416=$_REQUEST['idedi'];
$V1b1cc7f0="SELECT * FROM listing WHERE id=".$V48f4c416;
$Vb4a88417=mysql_query($V1b1cc7f0);
$V7935de3b=mysql_fetch_row($Vb4a88417);	
	$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Bug%20Tracker";
$V327a6c43=1;
?>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>Listing:</td><td><input type="text" name="listingname" value="<?=$V7935de3b[1]?>"/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Edit" name="V4aea81fe"/></td></tr>
	<input type="hidden" value="<?=$V48f4c416?>" name="V4aea81fe"/>
	</table>
	</form>
	
	<?
	
}
if($V327a6c43==0)
{
?>
<div style="margin-left:380px;"><a href="<?=$Vff208472?>">Add listing</a></div>
<table style="margin-left:200px;">
<tr><td width="150" style="background-color:#CCCCCC;"><strong>Listing</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Options</strong></td></tr>
<?
 
 $V53e61336=mysql_query("SELECT * FROM listing");
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=Bug%20Tracker";
while($V7935de3b=mysql_fetch_row($V53e61336))
 {
?>
<tr><td ><?=$V7935de3b[1]?></td><td><a href="<?=$Vff208472?>&idedi=<?=$V7935de3b[0]?>">Edit</a>&nbsp;|&nbsp;<a onClick="Fc07a1f12('<?=$V7935de3b[0]?>','listing','<?=$Vff208472?>')">Delete</a></td></tr>
<?
 }
?> 
</table>
<?
}
}
   
function F6470f080()
{ ?>
<script>
function Fe2132f3f(id,table,path)
{
	var cd;

	cd=confirm("Are you sure to delete?");
if(cd)
	{
 window.location.href =path+"&delete="+id+"&table="+ table;
}
}
</script> 
<?
$Vff208472 =get_settings('home')."/wp-admin/admin.php?page=propery&add=1";
$V327a6c43=0;
?>
<h2 style="margin-left:200px;">Manage Property Types</h2>
<?
if(isset($_REQUEST['V1a8db4c9']))
{
$V6bb837ff=$_REQUEST['V6bb837ff'];
$Vc4820b0d=$_REQUEST['propertyname'];
$V1b1cc7f0="UPDATE property SET property_type='$Vc4820b0d' WHERE id=".$V6bb837ff;
mysql_query($V1b1cc7f0);
}
if(isset($_REQUEST['delete']))
{
	$V48f4c416=$_REQUEST['delete'];
$V4b27b6e5=$_REQUEST['table'];
$V1b1cc7f0="delete from ".$V4b27b6e5." where id='$V48f4c416'";
mysql_query($V1b1cc7f0);

}
if(isset($_REQUEST['add']))
{
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=propery";
$V327a6c43=1;
?>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>Property</td><td><input type="text" name="propertynam" value=""/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Add" name="add_property"/></td></tr>
	</table>
	</form>
	
	<?
}
if(isset($_REQUEST['add_property']))
{
$Vc4820b0d=$_REQUEST['propertynam'];
$Ve9148427="INSERT INTO property (property_type) values ('$Vc4820b0d')";
mysql_query($Ve9148427);
}
if(isset($_REQUEST['idedi']))
{
	$V48f4c416=$_REQUEST['idedi'];
$V1b1cc7f0="SELECT * FROM property WHERE id=".$V48f4c416;
$Vb4a88417=mysql_query($V1b1cc7f0);
$V7935de3b=mysql_fetch_row($Vb4a88417);	
	$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=propery";
$V327a6c43=1;
?>
	<form name="" action="<?=$Vff208472 ?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>Property:</td><td><input type="text" name="propertyname" value="<?=$V7935de3b[1]?>"/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Edit" name="V1a8db4c9"/></td></tr>
	<input type="hidden" value="<?=$V48f4c416?>" name="V6bb837ff"/>
	</table>
	</form>
	
	<?
	
}
if($V327a6c43==0)
{
$Vff208472 =get_settings('home')."/wp-admin/admin.php?page=propery&add=1";
?>
<div style="margin-left:380px;"><a href="<?=$Vff208472?>">Add property</a></div>
<table style="margin-left:200px;">
<tr><td width="150" style="background-color:#CCCCCC;"><strong>Property</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Options</strong></td></tr>
<?
 
 $V53e61336=mysql_query("SELECT * FROM property");
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=propery"; 
 while($V7935de3b=mysql_fetch_row($V53e61336))
 {
 
?>
<tr><td s><?=$V7935de3b[1]?></td><td><a href="<?=$Vff208472?>&idedi=<?=$V7935de3b[0]?>">Edit</a>&nbsp;|&nbsp;<a onClick="Fe2132f3f('<?=$V7935de3b[0]?>','property','<?=$Vff208472?>')">Delete</a></td></tr>
<?
 }

 ?> 
</table>
<?
}
}
   
function F1810d3a8()
{ ?>
<script>
function F322bb617(id,table,path)
{
	var cd;

	cd=confirm("Are you sure to delete?");
if(cd)
	{
 window.location.href =path+"&delete="+id+"&table="+ table;
}
}
</script>
<?
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=distance&add=1"; 
$V327a6c43=0; 
?>
<h2 style="margin-left:200px;">Manage Distance</h2>
<?
if(isset($_REQUEST['Va74ec9c5']))
{
$Va5eacca9=$_REQUEST['Va5eacca9'];
$Vc744269d=$_REQUEST['distancename'];
$V1b1cc7f0="update distance set distance='$Vc744269d' where id='$Va5eacca9'";
mysql_query($V1b1cc7f0);
}
if(isset($_REQUEST['delete']))
{
	$V48f4c416=$_REQUEST['delete'];
$V4b27b6e5=$_REQUEST['table'];
$V1b1cc7f0="delete from ".$V4b27b6e5." where id='$V48f4c416'";
mysql_query($V1b1cc7f0);

}
if(isset($_REQUEST['add']))
{
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=distance";
$V327a6c43=1; 
?>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px;">
	<tr><td>Distance</td><td><input type="text" name="distancenam" value=""/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Add" name="add_distance"/></td></tr>
	</table>
	</form>
	
	<?
}
if(isset($_REQUEST['add_distance']))
{
$Vc744269d=$_REQUEST['distancenam'];
$Ve9148427="INSERT INTO distance (distance) values ('$Vc744269d')";
mysql_query($Ve9148427);
}
if(isset($_REQUEST['idedi']))
{
	$V48f4c416=$_REQUEST['idedi'];
$V1b1cc7f0="SELECT * FROM distance WHERE id=".$V48f4c416;
$Vb4a88417=mysql_query($V1b1cc7f0);
$V7935de3b=mysql_fetch_row($Vb4a88417);	
	$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=distance"; 
	$V327a6c43=1;
?>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px;">
	<tr><td>Distance</td><td><input type="text" name="distancename" value="<?=$V7935de3b[1]?>"/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Edit" name="Va74ec9c5"/></td></tr>
	<input type="hidden" value="<?=$V48f4c416?>" name="Va5eacca9"/>
	</table>
	</form>
	
	<?
	
}
if($V327a6c43==0)
{
?>
<div style="margin-left:380px;"><a href="<?=$Vff208472?>">Add distance</a></div>
<table style="margin-left:200px;">
<tr><td width="150" style="background-color:#CCCCCC;"><strong>Distance</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Options</strong></td></tr>
<?
 
 $V53e61336=mysql_query("SELECT * FROM distance");
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=distance"; 
 while($V7935de3b=mysql_fetch_row($V53e61336))
 {
?>
<tr><td ><?=$V7935de3b[1]?></td><td><a href="<?=$Vff208472 ?>&idedi=<?=$V7935de3b[0]?>">Edit</a>&nbsp;|&nbsp;<a onClick="F322bb617('<?=$V7935de3b[0]?>','distance','<?=$Vff208472?>')">Delete</a></td></tr>
<?
 }

 ?> 
</table>
<?
}
}
   
function F2318162d()
{
 $Vff208472 = get_settings('home')."/wp-admin/admin.php?page=settings"; 
?>
<h2 style="margin-left:200px;">Default Settings</h2>
<form name="form1" method="post" action="<?=$Vff208472 ?>">
<table style="margin-left:200px;">
<tr>
<td>City/State</td>
<td>
<select name="V4ed5d2ea">
<option value="<?=get_option('city')?>" selected="selected"><?=get_option('city')?></option>
<? 
$V53e61336=mysql_query("SELECT * FROM city");
while($V7935de3b=mysql_fetch_row($V53e61336))
 {
?>
<option value="<?=$V7935de3b[1]?>"><?=$V7935de3b[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Distance:</td>
<td>
<select name="Va74ec9c5" >
<option value="<?=get_option('distance')?>"><?=get_option('distance')?></option>
<? 
$V4ce95997=mysql_query("SELECT * FROM distance");
while($V05bcfd32=mysql_fetch_row($V4ce95997))
 {
?>
<option value="<?=$V05bcfd32[1]?>"><?=$V05bcfd32[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Listing:</td>
<td>
<select name="V4aea81fe">
<option value="<?=get_option('listing')?>"><?=get_option('listing')?></option>
<?
$Vcd765b0f=mysql_query("SELECT * FROM listing");
while($V86a1fb85=mysql_fetch_row($Vcd765b0f))
 {
?>
<option value="<?=$V86a1fb85[1]?>"><?=$V86a1fb85[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Property:</td>
<td>
<select name="V1a8db4c9" >
<option value="">Select</option>
<?
$V8c0511cc=mysql_query("SELECT * FROM property");
while($V90398a25=mysql_fetch_row($V8c0511cc))
 {
?>
<option value="<?=$V90398a25[1]?>"><?=$V90398a25[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Price From:</td>
<td>
<input type="text" name="V53ce7d32" value="<?=get_option('price_start');?>" style="width:120px;" />
</td>
</tr>
<tr>
<td>Price To:</td>
<td>
<input type="text" name="V67eb2711" value="<?=get_option('price_end')?>" style="width:120px;" />
</td>
</tr>
<tr>
<td>Bedrooms:</td>
<td>
<input type="text" name="V001cbc05" value="<?=get_option('bed')?>" style="width:120px;"/>
</td>
</tr>
<tr>
<td>Bathrooms:</td>
<td>
<input type="text" name="Vd01befa8" value="<?=get_option('bath')?>" style="width:120px;"/>
</td>
</tr>
<tr>
<td>Keywords:</td>
<td>
<input type="text" style="width:120px;" value="<?=get_option('keyword')?>" name="Vd7df5b64"/>
</td>
</tr>
<tr>
<td>Sort by:</td>
<td>Ascending<input type="radio" name="Vcadc8c8d" value="ascending" checked="checked" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>descending<input type="radio" name="Vcadc8c8d" value="descending"/></td>
</tr>
<td></td>
<td>
<input type="submit" name="subb" value="Submit"/>
</td>
</tr>
</table>
</form>
	
<?
if(isset($_REQUEST['subb']))
{
?>
<br/>
<p><strong>Successfully Submitted</strong></p>
<?
 $V16f709d3='city';
$Vfb456c69=$_REQUEST['V4ed5d2ea'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='distance';
$Vfb456c69=$_REQUEST['Va74ec9c5'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='listing';
$Vfb456c69=$_REQUEST['V4aea81fe'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='property';
$Vfb456c69=$_REQUEST['V1a8db4c9'];
if ($_GET['pt']!="")
{
	$Vfb456c69=str_replace('-','%20',$_GET['pt']);
}
update_option( $V16f709d3,$Vfb456c69);


 $V16f709d3='price_start';
$Vfb456c69=$_REQUEST['V53ce7d32'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='price_end';
$Vfb456c69=$_REQUEST['V67eb2711'];
update_option( $V16f709d3,$Vfb456c69);

 
 $V16f709d3='bed';
$Vfb456c69=$_REQUEST['V001cbc05'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='bath';
$Vfb456c69=$_REQUEST['Vd01befa8'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='keyword';
$Vfb456c69=$_REQUEST['Vd7df5b64'];
update_option( $V16f709d3,$Vfb456c69);

 
}
}
///////////Installation Instructions////////////////
function install_instruct()
{
?>
<h2 style="margin-left:200px;">Installation Instructions</h2>
<iframe src="http://www.realshout.com/realshout-wordpress-plugin-readme.php" frameborder="0" height="650" width="900" scrolling="auto"></iframe>
<?
}
  
function F5e61c0fb()
{
 $Vff208472 = get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/jscolor/jscolor.js"; 
 $V9ef10d3b = get_settings('home')."/wp-admin/admin.php?page=color"; 
?>
<script language="javascript" src="<?=$Vff208472?>">
</script>
<?
if(isset($_REQUEST['save']))
{
 $V16f709d3='first_color';
$Vfb456c69=$_REQUEST['b_color'];
update_option( $V16f709d3,$Vfb456c69);

 
 $V16f709d3='second_color';
$Vfb456c69=$_REQUEST['second'];
update_option( $V16f709d3,$Vfb456c69);

 $V16f709d3='hover';
$Vfb456c69=$_REQUEST['hover'];
update_option( $V16f709d3,$Vfb456c69);

}
?>
<h2 style="margin-left:200px;">Display settings</h2>
<form name="" action="<?=$V9ef10d3b?>" method="POST">
<table style="margin-left:200px;">
<tr><td>First row alternate color</td><td><input type="text" value="<?=get_option('first_color')?>" name="b_color" id="h_color" class="colors" style="width:80px;"/></td></tr>
<tr><td>Second row alternate color</td><td><input type="text" name="second" value="<?=get_option('second_color')?>" id="h_color" class="colors" style="width:80px;"/></td></tr>
<tr><td>Hover background Color</td><td><input type="text" name="hover" value="<?=get_option('hover')?>" id="h_color" class="colors" style="width:80px;"/></td></tr>
<tr><td>&nbsp;</td><td><input type="Submit" Value="Save" name="save"/></td></tr>
</table>
</form>
<?
}
   
function Fe467abc1()
{ ?>
<script>
function F4159486c(id,table,path)
{
	var cd;

	cd=confirm("Are you sure to delete?");
if(cd)
	{
 window.Vd5189de0.href =path+"&delete="+id+"&table="+ table;
}
}
</script> 
<?
$V327a6c43=0;
?>
<h2 style="margin-left:320px;">Manage User</h2>
<?
if(isset($_REQUEST['user']))
{ 
	$Ve8701ad4=$_REQUEST['us'];
$Vda984e42 = $_REQUEST['Vda984e42'];
$Vb83a886a=$_REQUEST['Vb83a886a'];
$V1a1dc91c=$_REQUEST['V1a1dc91c'];
$Vb068931c=$_REQUEST['user_edit'];
$V1b1cc7f0="update user set name='$Vb068931c',pass='$V1a1dc91c',ph='$Vda984e42',email='$Vb83a886a' where id='$Ve8701ad4'"; 

mysql_query($V1b1cc7f0);
}
if(isset($_REQUEST['delete']))
{
	$V48f4c416=$_REQUEST['delete'];
$V4b27b6e5=$_REQUEST['table'];
$V1b1cc7f0="delete from ".$V4b27b6e5." where id='$V48f4c416'";
mysql_query($V1b1cc7f0);

}
if(isset($_REQUEST['add']))
{
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=user";
$V327a6c43=1;
?>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>User name</td><td><input type="text" name="user" value=""/></td></tr>
	<tr><td>Password</td><td><input type="text" name="V1a1dc91c" value=""/></td></tr>
	<tr><td>Email</td><td><input type="text" name="Vb83a886a" value=""/></td></tr>
	<tr><td>Phone</td><td><input type="text" name="Vda984e42" value=""/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Add" name="add_user"/></td></tr>
	</table>
	</form>
	
	<?
}
 
if(isset($_REQUEST['idedi']))
{
	$V48f4c416=$_REQUEST['idedi'];
$V1b1cc7f0="SELECT * FROM user WHERE id=".$V48f4c416;
$Vb4a88417=mysql_query($V1b1cc7f0);
$V7935de3b=mysql_fetch_row($Vb4a88417);
$V327a6c43=1;	
	
	$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=user";
?>
	<form name="" action="<?=$Vff208472?>" method="post">
	<table width="500" style="margin-bottom:10px; margin-left:200px;">
	<tr><td>User name</td><td><input type="text" name="user_edit" value="<?=$V7935de3b[2]?>"/></td></tr>
	<tr><td>Password</td><td><input type="password" name="V1a1dc91c" value="<?=$V7935de3b[1]?>"/></td></tr>
	<tr><td>Email</td><td><input type="text" name="Vb83a886a" value="<?=$V7935de3b[4]?>"/></td></tr>
	<tr><td>Phone</td><td><input type="text" name="Vda984e42" value="<?=$V7935de3b[3]?>"/></td></tr>
	<tr><td>&nbsp;</td></tr><tr><td><input type="submit" value="Edit" name="user"/></td></tr>
	<input type="hidden" value="<?=$V48f4c416?>" name="us"/>
	</table>
	</form>
	
	<?
	
}
if($V327a6c43==0)
{
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=user&add=1";
?>
<table style="margin-left:100px;">
<tr><td width="150" style="background-color:#CCCCCC;"><strong>User Name</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Email</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Phone</strong></td><td width="100" style="background-color:#CCCCCC;"><strong>Option</strong></td></tr>
<?
 
 $V53e61336=mysql_query("SELECT * FROM user");
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=user";
while($V7935de3b=mysql_fetch_row($V53e61336))
 {
?>
<tr><td ><?=$V7935de3b[2]?></td><td ><?=$V7935de3b[4]?></td><td ><?=$V7935de3b[3]?></td><td><a href="<?=$Vff208472?>&idedi=<?=$V7935de3b[0]?>">Edit</a>|<a onClick="F4159486c('<?=$V7935de3b[0]?>','user','<?=$Vff208472?>')">Delete</a></td></tr>
<?
 }

 ?> 
</table>
<?
}
}
function Fb1c84e4c(){
?>
<script language="javascript">
function F1fc0d1dc(id,path)
{
	var cd;

	cd=confirm("Are you sure to delete?");
if(cd)
	{
 window.location.href =path+"&Vb80bb774="+id+"&delete=1";
}
}
</script>
<?
$V327a6c43=1;
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=User Contacts";

 if(isset($_REQUEST['send_mail']))
 {
 
$V327a6c43=0;
$Vb80bb774=$_REQUEST['Vb80bb774'];
$V1758a244=mysql_query("SELECT * FROM contact_us where id=".$Vb80bb774);
$Vf5b9a10a=mysql_fetch_row($V1758a244);
$Vb5e0aaf3=$Vf5b9a10a[4];
$Vb5e3374e=$Vf5b9a10a[1];
$Vb068931c=$Vf5b9a10a[3];
$loc=$Vf5b9a10a[5];
$origmsg="\n\n". "\n\n"."<br /><br /> --ORIGINAL INQUIRY-- <br />" ."\n".$Vf5b9a10a[2];

if(isset($_REQUEST['submit']))
	{
 $V327a6c43=0;
$Vb80bb774=$_REQUEST['Vb80bb774'];
$Vb5e3374e = $_REQUEST['Vb5e3374e'];

 
$V78e73102= 'Subject : '.$_REQUEST['Vb5e3374e'].'<br/>';
//$V78e73102.= 'Country : '.$_REQUEST['select_country'].'<br/>';
//$V78e73102.='<br/>';
$V78e73102.=$_REQUEST['enquiry'];
$V78e73102.='<br/>';
$V78e73102.='Property Location : '.$_REQUEST['Vb83a886a'];	

$toemail=$_REQUEST['email'];
$toname=$_REQUEST['email'];
$fromemail=get_option('email');
$fromname=get_option('email');
$V099fb995 = "From: $fromname <$fromemail>\n" . "MIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1\n" . "Return-Path: <$fromemail>";

$V1758a244=mysql_query("SELECT * FROM contact_us WHERE id=".$Vb80bb774);
$Vf5b9a10a=mysql_fetch_row($V1758a244);
$Vb5e0aaf3=$Vf5b9a10a[5];

$V01b6e203=$_REQUEST['Vb83a886a'];
mail($toemail,$Vb5e3374e,$V78e73102,$V099fb995);
echo '<h2>Your email was successfully sent</h2><p><a href="?page=User Contacts">Go Back To User Contacts</a></p>';
$showleadform="not";	
	}

 ?>
 
 <script>
 function F658dd5a4(){
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

if (document.contact.Vb5e3374e.value == ""){
 alert('Please enter subject');
document.contact.subject.focus();
return false;
}
if (document.contact.enquiry.value == ""){
 alert('Please enter comments');
document.contact.enquiry.focus();
return false;
} 
 }
</script>
 <br/>
 <br/>
 <br/>
 <?
 if ($showleadform!="not")
 {
 ?>
 <form name="contact" action="" method="post" enctype="multipart/form-data">
 <h2 style="padding-left:200px">Email Lead</h2>
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
 <tr>
 <td align="right" valign="top" class="normtxt" width="22%">Name</td>
 <td align="center" valign="top" class="normtxt" width="1%"><strong>:</strong></td>
 <td align="left" valign="top" class="normtxt"><input type="text" name="Vb068931c" id="name" value="<?=$Vb068931c?>" /></td>
 <td align="left" valign="top" class="normtxt" width="28%">*</td>
 </tr>
 <tr>
 <td align="right" valign="top" class="normtxt">Email</td>
 <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
 <td align="left" valign="top" class="normtxt"><input type="text" name="email" id="email" value="<?=$Vb5e0aaf3?>" /></td>
 <td align="left" valign="top" class="normtxt">*</td>
 </tr>
 <tr>
 <td align="right" valign="top" class="normtxt">Subject</td>
 <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
 <td align="left" valign="top" class="normtxt"><input type="text" name="Vb5e3374e" id="subject" value="RE: Property Inquiry" /></td>
 <td align="left" valign="top" class="normtxt">*</td>
 </tr>
 <tr>
 <td align="right" valign="top" class="normtxt">Message</td>
 <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
 <td align="left" valign="top" class="normtxt"><textarea name="enquiry" id="enquiry" cols="40" rows="7" ><? echo $origmsg; ?></textarea>
      </td>
 <td align="left" valign="top" class="normtxt">*</td>
 </tr>
  <tr>
 <td align="right" valign="top" class="normtxt"> Property Location</td>
 <td align="center" valign="top" class="normtxt"><strong>:</strong></td>
      <td align="left" valign="top" class="normtxt"><input type="text" value="<?=$loc?>" name="Vb83a886a" size="50" /></td>
 <td align="left" valign="top" class="normtxt">*</td>
 </tr>
 <tr>
 <td align="right" valign="top" class="">&nbsp;</td>
 <td align="center" valign="top" class="">&nbsp;</td>
 <td align="center" valign="top" class=""><input type="submit" name="submit" class="normtxt" value="Send" onClick="return F658dd5a4();" /></td>
 <td align="left" valign="top" class="">
 <input type="hidden" value="<?=$Vb80bb774?>" name="Vb80bb774" />
 </td>
 </tr>
 </table>
 </form>
 <? } ?>
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
 
 <?
 }

if(isset($_REQUEST['delete']))
{
	
	$V327a6c43=0;
$Vb80bb774=$_REQUEST['Vb80bb774'];
mysql_query("DELETE FROM contact_us WHERE id=".$Vb80bb774);
$Vd6fe1d0b=get_settings('home')."/wp-admin/admin.php?page=User Contacts";
header("location:$Vd6fe1d0b");
$V327a6c43=0;
} 
 
 if(isset($_REQUEST['detail']))
 {
 
	
	$V327a6c43=0;
$Vb80bb774=$_REQUEST['id'];
$Vdd76269e=mysql_query("SELECT * FROM contact_us WHERE id=".$Vb80bb774);
$V6dfac566=mysql_fetch_row($Vdd76269e);
$Vd5189de0 = $V6dfac566[5];

	?>
	<table style="margin-left:100px;">
	<tr><td><strong>Name</strong></td><td><p><?=$V6dfac566[3]?></p></td></tr>
	<tr><td><strong>Subject</strong></td><td><p><?=$V6dfac566[1]?></p></td></tr>
	<tr><td valign="top"><strong>Message</strong></td><td><p><? echo nl2br($V6dfac566[2]);?></p></td></tr>
	</table>
<style type="text/css">
div#popup {
background:#EFEFEF;
border:1px solid #F0F0F0;
margin:0px;
padding:7px;
width:270px;
}
div#popup1 {
background:#EFEFEF;
border:1px solid #F0F0F0;
margin:0px;
padding:7px;
width:270px;
}
</style>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=get_option('google_api')?>" type="text/javascript"></script>
<script type="text/javascript">
  
window.onload = load;
function load()
{
 F327c1288();
}
</script>
<?
$V6dc84905=urlencode($Vd5189de0);
$V341be97d = file_get_contents('http://where.yahooapis.com/v1/places.q('.$V6dc84905.')?appid=R_VLawDV34HaVNmZfuYToDpiz1.Z.Q.erWwi9kp21CNID6NOKGkKfhgp50ETc08-');
$Vbe8f8018 = simplexml_load_string($V341be97d);
 
$V8d8d1437=($Vbe8f8018->place->boundingBox->northEast->latitude);
$V320381db=($Vbe8f8018->place->boundingBox->northEast->longitude);
?>
<script type="text/javascript">
var data = [{name:'',img:'',title:'<?=$Vd5189de0?>',date:'',lat:'<?=$V8d8d1437?>',lng:'<?=$V320381db?>',seeall:''}];
var map, route;
var points = [];
var gmarkers = [];
var count =0;
var stopClick = false;
function Ff33bf54e(he)
{
	window.location.href=he;
 
	window.close;
}
function Fd0f1f5e1(icon) { 
 icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
icon.iconSize = new GSize(32, 32);
icon.shadowSize = new GSize(37, 34);
icon.iconAnchor = new GPoint(15, 34);
icon.infoWindowAnchor = new GPoint(19, 2);
icon.infoShadowAnchor = new GPoint(18, 25);
}
function F1c0c7177(marker) { 
 GEvent.addListener(marker, "click", function() {
 marker.openInfoWindowHtml(marker.content);
 
 count = marker.nr;
stopClick = true;
});
return marker;
}
function F327c1288() {
 if(GBrowserIsCompatible()) {
 map = new GMap2(document.getElementById("map2"));
//map.setCenter(new GLatLng(25.5422,50.385799), 10);
map.setCenter(new GLatLng(<?=$V8d8d1437?>,<?=$V320381db?>), 10);
map.addControl(new GSmallMapControl());
 
 map.addControl(new GMapTypeControl());
 
 var icon = new GIcon();
icon.image = "http://www.google.com/intl/en_de/mapfiles/ms/icons/ltblue-dot.png";
Fd0f1f5e1(icon);
for(var i = 0; i < data.length; i++) {
 points[i] = new GLatLng(parseFloat(data[i].lat), parseFloat(data[i].lng));
gmarkers[i] = new GMarker(points[i], icon);
 
var html='<table width=\"250\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\"><tr><td rowspan=\"3\"valign=\"top\" width=\"25\">'+data[i].img+'</td><td valign=\"top\" align=\"left\">'+data[i].name+'</td></tr><tr><td valign=\"top\" align=\"left\">'+data[i].title+'</td></tr><tr><td align=\"left\">'+data[i].date+'</td></tr><tr><td colspan=\"2\" align=\"right\">'+data[i].seeall+'</td></tr></table>';

 
 
 
  
 gmarkers[i].content = html;
gmarkers[i].nr = i;
F1c0c7177(gmarkers[i]);
map.addOverlay(gmarkers[i]);
}
 
 var poly= new GPolyline(points, "#003355", 3, 0);
map.addOverlay(poly);
 
 gmarkers[0].openInfoWindowHtml( gmarkers[0].content);
route =setTimeout("F39977da6()", 90000);
}
} 
function F94ad92fe() {
 if(route) {
 clearTimeout(route);
stopClick = true;
}
}
function Fa3f79478() {
 if(stopClick == true) F39977da6();
stopClick = false;
}
function F39977da6() {
 count++;
if(count < points.length) {
 
 map.panTo(points[count]);
gmarkers[count].openInfoWindowHtml( gmarkers[count].content);
var delay = 3400;
if((count+1) != points.length)
 var dist = points[count].distanceFrom(points[count+1]);
 
 if( dist < 10000 ) {
 delay = 2000;
}
if( dist > 80000 ) {
 delay = 4200;
}
route = setTimeout("F39977da6()", delay);
}
else {
 clearTimeout(route);
count = 0;
route = false;
Fc5d15520();
}
}
function Fc5d15520() {
 GUnload();
if(route) clearTimeout(route);
stopClick = false;
count = 0;
F327c1288();
}
 
</script>
<div id="map2" style="height:300px;width:95%;vertical-align:middle;border:1px solid gray;margin-top:05px;margin-left:0px; margin-bottom:0px;"></div>
 <div id="side_bar"></div>	
<?	
 }
if($V327a6c43==1)
 {
 $Vff208472 = get_settings('home')."/wp-admin/admin.php?page=User Contacts";
?>
<h2 style="margin-left:320px;">User Contacts</h2>
 <table style="margin-left:50px;">
<tr><td width="200" style="background-color:#CCCCCC;"><strong>Name</strong></td><td width="300" style="background-color:#CCCCCC;"><strong>Email</strong></td><td width="300" style="background-color:#CCCCCC;"><strong>Subject</strong></td><td width="200" style="background-color:#CCCCCC;"><strong>Options</strong></td></tr>
<?
 
 $V53e61336=mysql_query("SELECT * FROM contact_us");
$Vff208472 = get_settings('home')."/wp-admin/admin.php?page=User Contacts";
while($V7935de3b=mysql_fetch_row($V53e61336))
 {
?>
<tr><td ><?=$V7935de3b[3]?></td><td ><?=$V7935de3b[4]?></td><td ><?=$V7935de3b[1]?></td><td width="300" ><a href="<?=$Vff208472?>&id=<?=$V7935de3b[0]?>&detail=1&d_=<?=$V4c68e0c8?>">Detail</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=$Vff208472?>&Vb80bb774=<?=$V7935de3b[0]?>&send_mail=1">Send Email</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onClick="F1fc0d1dc('<?=$V7935de3b[0]?>','<?=$Vff208472?>')">Delete</a></td></tr>
<? 
 }
}
?> 
</table>
<? 
}
   
function F53cf3274()
{
	?>
	<script language="javascript">
	
	function Fbe6565d8()
	{
 if(document.map_api.key.value=="")
 {
 alert("please enter google map key.");
document.map_api.key.focus();
return false;

 }

	}
</script>
	<?
	
	$V84e0d901=get_settings('home')."/wp-admin/admin.php?page=MAP API";

	if(isset($_REQUEST['save']))
	{
 $V16f709d3='google_api';
$Vfb456c69=$_REQUEST['key'];
update_option( $V16f709d3,$Vfb456c69);
echo "<p><strong>Successfully save.<strong></p>";
}

	$V327a6c43=0;
if($V327a6c43==0)
	{
	?>
	<form name="map_api" method="post" action="<?=$V84e0d901?>">
	
	<table>
	<tr><td colspan="2" align="center"><h2>Google Map Key</h2>
	</td></tr>
	<tr><td>
	<table align="center">
	<tr><td>Google Map key</td><td><textarea cols="45" rows="2" name="key"><?=get_option('google_api')?></textarea></td></tr>
	<tr><td>&nbsp;</td><td><input type="Submit" name="save" value="Save" onClick="return Fbe6565d8();"/></td></tr>
	</table>
	</td></tr>
	</table> 
	</form>
	<?
	}

}
 
function F229c90b7() { if (function_exists('add_options_page'))
 { 
 if (function_exists('add_menu_page')) { 
 
 
 
 if(F30d38403('city', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE city (
 state_id int(11) NOT NULL,
 id int(11) NOT NULL auto_increment,
 city varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
  
if(F30d38403('state', '')) 
{
 $rs_field = mysql_query('DESC state');
 $flag = 0;
 while ($row_field = mysql_fetch_array($rs_field)){
   if($row_field['Field'] == 'country_id'){
    $flag = 1;
   }  
 }
 
 if ($flag == 0){
  mysql_query("ALTER TABLE `state` ADD `country_id` INT( 11 ) NOT NULL AFTER `state` ;"); 
 } 
 
}
else
 {
 
 
 mysql_query("CREATE TABLE state (
 id int(11) NOT NULL auto_increment,
 state varchar(50) NOT NULL,
 country_id int(11) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}

if(F30d38403('country', '')) 
{
 
}
else
 { 
 mysql_query("CREATE TABLE country (
 id int(11) NOT NULL auto_increment,
 country varchar(255) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
  
if(F30d38403('listing', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE listing (
 id int(11) NOT NULL auto_increment,
 listing varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(F30d38403('property', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE property (
 id int(11) NOT NULL auto_increment,
 property_type varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(F30d38403('distance', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE distance (
 id int(11) NOT NULL auto_increment,
 distance varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
  
if(F30d38403('contact_us', '')) 
{
 
}
else
 {
 mysql_query("CREATE TABLE contact_us (
 id int(11) NOT NULL auto_increment, 
 `subject` varchar(255) NOT NULL,
 message text NOT NULL, 
 from_name text NOT NULL,
 from_email varchar(255) NOT NULL,
 location varchar(255) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(F30d38403('user', '')) 
{
 
}
else
 {
 
 
 mysql_query("CREATE TABLE `user` (
 id int(11) NOT NULL auto_increment,
 pass varchar(30) NOT NULL,
 `name` varchar(50) NOT NULL,
 ph varchar(50) NOT NULL,
 email varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
  
if(F30d38403('user_listing', '')) 
{
 $rs_field = mysql_query('DESC user_listing');
 $flag_ = 0;
 while ($row_field = mysql_fetch_array($rs_field)){
   if($row_field['Field'] == 'listing_id'){
    $flag_ = 1;
   }  
 }
 
 if ($flag_ == 0){
  mysql_query("ALTER TABLE `user_listing` ADD `listing_id` varchar( 255 ) NOT NULL AFTER `user_id` ;"); 
 } 
}
else
 {
 
 
 mysql_query("CREATE TABLE user_listing (
 id int(11) NOT NULL auto_increment,
 user_id int(11) NOT NULL, 
 listing_id varchar(255) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
if(F30d38403('temp_listing', '')) 
{
 $rs_field = mysql_query('DESC temp_listing');
 $flag__ = 0;
 while ($row_field = mysql_fetch_array($rs_field)){
   if($row_field['Field'] == 'listing_id'){
    $flag__ = 1;
   }  
 }
 
 if ($flag__ == 0){
  mysql_query("ALTER TABLE `temp_listing` ADD `listing_id` varchar( 255 ) NOT NULL AFTER `id` ;"); 
 }
}
else
 {
 
 
 mysql_query("CREATE TABLE temp_listing (
 id int(11) NOT NULL auto_increment,
 listing_id varchar(255) NOT NULL,
 date_in int(11) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}
 
 
 
 
add_menu_page('GoogleBaesPlugin', 'Default Search', 8, __FILE__, 'F2f011a0c','');
add_submenu_page(__FILE__, 'Install Instructions', 'Install Instructions', 9, 'Installation', 'install_instruct');
add_submenu_page(__FILE__, 'State/City Management', 'State/City Management', 9, 'Tag Settings', 'Fbc2cbb55');
add_submenu_page(__FILE__, 'Listing Management', 'Listing Types', 10, 'Bug Tracker', 'F17b319df');
add_submenu_page(__FILE__, 'Property Management', 'Property Types', 10, 'propery', 'F6470f080');
add_submenu_page(__FILE__, 'Distance Management', 'Distance Management', 10, 'distance', 'F1810d3a8');
 
add_submenu_page(__FILE__, 'Display Settings', 'Display Settings', 10, 'color', 'F5e61c0fb');
add_submenu_page(__FILE__, 'User Managment', 'User Managment', 10, 'user', 'Fe467abc1');
add_submenu_page(__FILE__, 'User Contacts', 'User Contacts', 10, 'User Contacts', 'Fb1c84e4c');
add_submenu_page(__FILE__, 'Google Map API Key', 'Google Map API Key', 10, 'MAP API', 'F53cf3274');
add_submenu_page(__FILE__, '.htaccess for SEO URL', '.htaccess for SEO URLS', 9, '.htaccess', 'Fdc1f12c9');

 }
} } 
add_action('admin_menu','F229c90b7');
class Fc2e928db {
 function Ffc5364bf(){
 $V8d777f38 = get_option('ebaywidget_title');

 ?>
 <p><label>Title :<input name="ebaywidget_title"
type="text" value="<?php echo $V8d777f38; ?>" /></label></p>
 
 <?php
 if (isset($_POST['ebaywidget_title'])){
 update_option('ebaywidget_title', $_POST['ebaywidget_title']);

 }
}
function F9d2b1ad5($Va956af09){
 
 $Vd5d3db17 = get_option('ebaywidget_title');
echo $Va956af09['before_widget'];
echo $Va956af09['before_title'] . $Vd5d3db17 . $Va956af09['after_title'];

 if (get_option('amazon_cs_page') == ""){
 $V418c5509 = get_settings('home')."/index.php";
} else {
 $V418c5509 = get_settings('home')."/index.php?page_id=".get_option('amazon_cs_page');
}

	
	?>
<div id="ad_search" style="display:<? if( isset($_REQUEST['sub_name']) || (get_option('gb_search_option') == 'advanced' && !isset($_REQUEST['cs_search']))) { ?>block; <? } else { ?>none;<? } ?>">
<?
 if ( isset($_SESSION['V9ed39e2e']) ){ 
 $country = $_SESSION['country']; 
 $V9a39e702 = $_SESSION['V9ed39e2e'];
$V794a5805 = $_SESSION['V4ed5d2ea'];
$Va95347ba = $_SESSION['Va74ec9c5'];
$V27a2de35 = $_SESSION['V4aea81fe'];
if ($_GET['pt']!="")
{
	$_SESSION['V1a8db4c9']=str_replace('-','%20',$_GET['pt']);
}
$V74d052a1 = $_SESSION['V1a8db4c9'];
$V9bae3263 = $_SESSION['V53ce7d32'];
$V759d544f = $_SESSION['V67eb2711'];
$V904606a6 = $_SESSION['V001cbc05'];
$Vbe7e4c5d = $_SESSION['Vd01befa8'];
$V5cae3b4c = $_SESSION['Vcadc8c8d'];
$V2fa1be64 = $_SESSION['Vda3ad3b4'];
} else {
$country = get_option('country');
 $V9a39e702 = get_option('state');
$V794a5805 = get_option('city');
$Va95347ba = get_option('distance');
$V27a2de35 = get_option('listing');
$V74d052a1 = get_option('property');
$V9bae3263 = get_option('price_start');
$V759d544f = get_option('price_end');
$V904606a6 = get_option('bed');
$Vbe7e4c5d = get_option('bath');
$V5cae3b4c = get_option('sort_by');
$V2fa1be64 = get_option('order_by');
}
?>
<form name="form1" method="post" action="<?=$V418c5509?>">
<table>
<tr><td></td><td><?=get_option('login')?><a name="advanced"></a></td></tr>
<tr>
<td>Country:</td>
<td>
<select name="country" id="country" style="width:90px;" onChange="getStates(this.value)">
<option value="">Select</option>
<?
$Vc549c632="SELECT * FROM country";
$Vfd76c5fa=mysql_query($Vc549c632);
while($V5f1ce181=mysql_fetch_row($Vfd76c5fa))
{
?>
<option value="<?=$V5f1ce181[0]?>" <? if ($V5f1ce181[0] == $country ) { ?> selected="selected" <? } ?> ><?=$V5f1ce181[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>State</td>
<td>
<div id="sn">
<select name="V9ed39e2e" id="sta" style="width:90px;" onChange="Faf06cf26(this.value)">
<option value="">Select</option>
<?
$Vc549c632="SELECT * FROM state WHERE country_id = '".$country."'";
$Vfd76c5fa=mysql_query($Vc549c632);
while($V5f1ce181=mysql_fetch_row($Vfd76c5fa))
{
?>
<option value="<?=$V5f1ce181[0]?>" <? if($V5f1ce181[0] == $V9a39e702 ) { ?> selected="selected" <? } ?> ><?=$V5f1ce181[1]?></option>
<?
}
?>
</select>
</div>
</td>
</tr>
<tr>
<td>City</td>
<td>
<div id="dn">
<select name="V4ed5d2ea" style="width:90px;">
<option value="">Select</option>
<? 
$V53e61336=mysql_query("SELECT * FROM city WHERE state_id = '".$V9a39e702."'");
while($V7935de3b=mysql_fetch_row($V53e61336))
 { 
?>
<option value="<?=$V7935de3b[2]?>" <? if($V7935de3b[2] == $V794a5805 ) { ?> selected="selected" <? } ?> ><?=$V7935de3b[2]?></option>
<?
}
?>
</select>
</div>
</td>
</tr>
<tr>
<td>Distance:</td>
<td>
<select name="Va74ec9c5" style="width:90px;" >
<option value="">Select</option>
<? 
$V4ce95997=mysql_query("SELECT * FROM distance");
while($V05bcfd32=mysql_fetch_row($V4ce95997))
 {
?>
<option value="<?=$V05bcfd32[1]?>" <? if($V05bcfd32[1] == $Va95347ba ) { ?> selected="selected" <? } ?>><?=$V05bcfd32[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
 <td>Type:</td>
<td>
<select name="V4aea81fe" style="width:90px;">
<option value="">Select</option>
<?
$Vcd765b0f=mysql_query("SELECT * FROM listing");
while($V86a1fb85=mysql_fetch_row($Vcd765b0f))
 {
?>
<option value="<?=$V86a1fb85[1]?>" <? if($V86a1fb85[1] == $V27a2de35 ) { ?> selected="selected" <? } ?>><?=$V86a1fb85[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Property:</td>
<td>
<select name="V1a8db4c9" style="width:90px;" >
<option value="">Select</option>
<?
$V8c0511cc=mysql_query("SELECT * FROM property");
while($V90398a25=mysql_fetch_row($V8c0511cc))
 {
?>
<option value="<?=$V90398a25[1]?>" <? if($V90398a25[1] == $V74d052a1 ) { ?> selected="selected" <? } ?>><?=$V90398a25[1]?></option>
<?
}
?>
</select>
</td>
</tr>
<tr>
<td>Price From:</td>
<td>
<input type="text" name="V53ce7d32" size="10" value="<?=$V9bae3263?>" />
</td>
</tr>
<tr>
<td>Price To:</td>
<td>
<input type="text" name="V67eb2711" size="10" value="<?=$V759d544f?>" />
</td>
</tr>
<tr>
<td>Bedrooms:</td>
<td>
<select name="V001cbc05" style="width:90px;" >
 <option value="">Select</option>
 <? for ($V865c0c0b = 1; $V865c0c0b<21; $V865c0c0b++) { ?>
 <option value="<?=$V865c0c0b?>" <? if($V865c0c0b == $V904606a6) { ?> selected="selected" <? } ?> ><?=$V865c0c0b?></option>
 <? } ?>
</select>
</td>
</tr>
<tr>
<td>Bathrooms:</td>
<td>
<select name="Vd01befa8" style="width:90px;" >
 <option value="">Select</option>
 <? for ($V865c0c0b = 1; $V865c0c0b<11; $V865c0c0b++) { ?>
 <option value="<?=$V865c0c0b?>" <? if($V865c0c0b == $Vbe7e4c5d) { ?> selected="selected" <? } ?> ><?=$V865c0c0b?></option>
 <? } ?>
</select>
</td>
</tr>
<!--<tr>
<td>Keywords:</td>
<td>
<input type="text" name="Vd7df5b64" size="10" value="<?=$_REQUEST['Vd7df5b64']?>" />
</td>
</tr>-->
<tr>
 <td>Order By: </td>
 <td><select name="Vda3ad3b4" style="width:90px;" >
 <option value="">Select</option>
 <option value="br" <? if ($V2fa1be64 == 'br') { ?> selected="selected" <? } ?> >Bedrooms</option>
 <option value="ba" <? if ($V2fa1be64 == 'ba') { ?> selected="selected" <? } ?> >Bathrooms</option> 
 <option value="pr" <? if ($V2fa1be64 == 'pr') { ?> selected="selected" <? } ?> >Price</option> 
 </select> 
 </td>
</tr>
<tr>
<td>Sort by:</td>
<td>Ascending<input type="radio" name="Vcadc8c8d" value="ascending" <? if ($V5cae3b4c == 'ascending') { ?> checked="checked" <? } ?> /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>Descending<input type="radio" name="Vcadc8c8d" value="descending" <? if ($V5cae3b4c == 'descending') { ?> checked="checked" <? } ?>/></td>
</tr>
<td></td>
<td>
<input type="submit" name="sub_name" value="Submit" onClick="return Ff9ab0545();"/>
</td>
</tr>
<tr>
 <td colspan="2"></td>
</tr>
<tr>
 <td align="right" colspan="2"><a style="cursor:pointer;" onClick="document.getElementById('ad_search').style.display = 'none';document.getElementById('si_search').style.display = 'block';">Simple Search</a></td>
</tr>
</table>
</form>
</div>
<div id="si_search" style="display:<? if(isset($_REQUEST['cs_search']) || (get_option('gb_search_option') == 'simple' && !isset($_REQUEST['sub_name']))) { ?>block; <? } else { ?>none;<? } ?>">
<?

 $Vdf500166 = mysql_query("SELECT c.id as cid, c.city, s.id as sid, s.state FROM city c, state s WHERE c.state_id = s.id ORDER BY c.city ASC");
?>
<table border="0" width="100%" cellpadding="1" cellspacing="1">
 <? 
 $Vaf86566b = 0;

 if (get_option('amazon_cs_page') == ""){
 $Ve2742836 = "?";
} else {
 $Ve2742836 = "?page_id=".get_option('amazon_cs_page')."&";
}
while ($V0979aa3d = mysql_fetch_array($Vdf500166)) { $Vaf86566b++; ?>
 <tr>
 <td>
 <a href="<?=get_settings('home')?>/<?=$Ve2742836?>cs_search=1&c_=<?=str_replace(' ', '-',$V0979aa3d[1])?>&s_=<?=$V0979aa3d[3]?>"><?=$V0979aa3d[1].', '.$V0979aa3d[3]?></a>
 <? 
 if ($V0979aa3d[1]==str_replace('-', ' ',$_GET['c_']))
 {
 	$qry1 = mysql_query("SELECT * FROM property");
	while($rsprop=mysql_fetch_row($qry1))
    {
		$prop_types.='<li style="list-style-type: square;list-style-position: inside;padding-left:20px;padding-bottom:0px;margin-bottom:0px"><a href="'.get_settings('home').'/'.$Ve2742836.'sub_name=Submit&V4aea81fe=for+sale&V9ed39e2e=1&V4ed5d2ea='.str_replace(' ', '-',$V0979aa3d[1]).'&s_='.$V0979aa3d[3].'&Vcadc8c8d=ascending&V1a8db4c9='.$rsprop[1].'" title="'.$V0979aa3d[3].' '.str_replace('-', ' ',$V0979aa3d[1]).' '.$rsprop[1].'">'.$rsprop[1].'</a></li>';
	}
	$prop_types='<ul>'.$prop_types.'</ul>';
	echo $prop_types;
 }
 ?>
 </td>
 </tr>
<? } ?> 
 <tr><td>&nbsp;</td></tr>
 <tr><td align="right"><a style="cursor:pointer;" onClick="document.getElementById('ad_search').style.display = 'block';document.getElementById('si_search').style.display = 'none';">Advanced Search</a></td></tr>
</table>
</div>
<table width="100%">
<?
$Vb83a886a=get_option('email');
$Vff208472 = get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php";
?>
<tr>
<td colspan="2" align="right" >
<?
echo $contact = "<div class='simple_overlay' id='mies_simple'><div class='close'></div>
	<iframe src='".get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/contact_us.php' width='525' frameborder='0' height='490'></iframe>
 </div><span id='triggers'><a><img alt='Contact Us' rel='#mies_simple'></a></span>";
?>
</td>
</tr>
<tr>
<td colspan="2" align="right" >
<script type="text/javascript">
addthis_pub = '[ACCOUNT-ID]';
</script><a href="http://www.addthis.com/bookmark.php" onMouseOver="return addthis_open(this, '', '[V572d4e42]', '[TITLE]')" onMouseOut="addthis_close()" onClick="return addthis_sendto()"><img src="http://s9.addthis.com/button1-bm.gif" width="125" height="16" border="0" alt="" /></a><script type="text/javascript" src="http://s7.addthis.com/js/152/addthis_widget.js"></script>
</td>
</tr>
</table>
<? 
if( (isset($_SESSION['Ve8701ad4'])) && (trim($_SESSION['Ve8701ad4']) != ''))
{
?>
<table width="100%">
<tr>
<td colspan="2" align="right" >
<p style="font-size:12px; color:#990000;">Welcome <?=$_SESSION['username']?></p>
</td>
</tr>
<?
$Vff208472 = get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/myaccout.php";
 
if (get_option('amazon_cs_page') == ""){
 $Vff208472=get_settings('home')."?show=1";
} else {
 $Vff208472=get_settings('home')."/?page_id=".get_option('amazon_cs_page')."&show=1";
}

 if (get_option('amazon_cs_page') == ""){
 $Vb57791e9=get_settings('home')."?signout=1";
} else {
 $Vb57791e9=get_settings('home')."/?page_id=".get_option('amazon_cs_page')."&signout=1";
}

 $V5e0bf013 = get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/myaccout.php";
?>
<tr><td height="21" colspan="2" align="right" ><a href="<?=$Vff208472?>">My listing</a>&nbsp;&nbsp;|&nbsp;&nbsp;<? echo "<a style='cursor:pointer;' href='#' onClick='window.open(\"$V5e0bf013?user=1\",\"window1\",\"menubar=no,width=500,height=400,toolbar=no,scrollbars=yes\")'>Edit profile</a>";?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=$Vb57791e9?>">Sign out</a></td></tr>
</table>
<?
}
else
{
 if (get_option('amazon_cs_page') == ""){
 $V418c5509 = get_settings('home');
} else {
 $V418c5509 = get_settings('home')."/index.php?page_id=".get_option('amazon_cs_page');
}
?>
<form name="login" id="login" method="post" action="<?=$V418c5509?>"><a name="login"></a>
<table> 
<tr>
<td><a name="login"></a>Email</td><td><input type="text" value="" name="user_email"size="10"/></td></tr>
<tr><td>Password</td><td><input type="password" value="" name="V186bca78" size="10"/></td></tr>
<?
$Vff208472=get_settings('home')."/wp-content/plugins/realshout-real-estate-property-search/sign_in.php";
?>
<tr><td>&nbsp;</td><td align="left"><input type="Submit" name="lgin" value="Login" onClick="return F2c4f30a8()"/>|<a 
OnClick="window.open('<?=$Vff208472?>','window1','menubar=no,width=500,height=400,toolbar=no,scrollbars=yes')">Sign up</a></td></tr>
</table>
</form>
<?
} 
 echo $Va956af09['after_widget'];
}
function F9de4a974(){
 register_sidebar_widget('mywidget', array('Fc2e928db', 'F9d2b1ad5'));
register_widget_control('mywidget', array('Fc2e928db', 'Ffc5364bf')); 
 }
}
include('navigation.php');
?>