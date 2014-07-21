<?
include('../../../wp-config.php');
if(isset($_REQUEST['id']))
{
$id=$_REQUEST['id'];
?>
<select name="V4ed5d2ea" style="width:90px;">
<option value="">Select</option>
<?
$query="select * from city where state_id='$id'";
$result=mysql_query($query);
while($result_row=mysql_fetch_row($result))
{
?>
<option value="<?=$result_row[2]?>"><?=$result_row[2]?></option>
<?
}
?>
</select>
<?
}
?>