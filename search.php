<div id="search">
<h2><a name="search"></a>Find songs</h2>
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<?php
if(array_key_exists("content", $_REQUEST))
	echo "<input type=\"hidden\" name=\"content\" value=\"".$_REQUEST["content"]."\" />";
?>
<input type="hidden" name="command" value="search" />
<table<?php if (array_key_exists ("multiline_search", $layout_vars) && $layout_vars["multiline_search"] == true) echo " style=\"border-top: 0\""; ?> cellspacing="0">
<tr>
<td><label accesskey="f"><b>F</b>ind:</label></td>
<td><input type="text" name="arg" style="width: 100%" class="textbox" /></td>
<td><input type="submit" name="search" value="Go!" class="button" /></td>
<td><input type="submit" name="clearsearch" value="Clear" class="button" /></td>
<?php
if (array_key_exists ("multiline_search", $layout_vars) && $layout_vars["multiline_search"] == true)
	echo "</tr></table>\n<table style=\"border-top: 0\" cellspacing=0><tr>";
?>
<td><select name="tag">
<?php
foreach ($configuration["search_fields"] as $field) {
	echo "<option value=\"".$field;
	if ($field == "title")
		echo "\" selected=\"selected";
	echo "\">".ucwords ($field)."</option>\n";
}
?>
</select></td>
</tr>
</table>
</form>
</div>
