<?
include('../../../wp-config.php');
if(isset($_REQUEST['id']))
{
$id=$_REQUEST['id'];
?>
<select name="V9ed39e2e" id="sta" style="width:90px;" onChange="Faf06cf26(this.value)">
<option value="">Select</option>
<?
$Vc549c632="SELECT * FROM state WHERE country_id = '$id'";
$Vfd76c5fa=mysql_query($Vc549c632);
while($V5f1ce181=mysql_fetch_row($Vfd76c5fa))
{
?>
<option value="<?=$V5f1ce181[0]?>" <? if ($V5f1ce181[0] == get_option('state') ) { ?> selected="selected" <? } ?> ><?=$V5f1ce181[1]?></option>
<?
}
?>
</select>
<?
}
?>