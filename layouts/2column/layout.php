<body>

<div id="topbar">
<table class="nostyle">
<tr>
<td style="width: 10%"><?php make_link("", "playlist", "Current&nbsp;Playlist", array(), false, null, "playlist") ?></td>
<?php
foreach ($configuration["browsers"] as $browser => $name ) {
	echo "<td style=\"width: 10%\">";
	make_link("", "browser", $name, array("browser" => $browser), false, null, $browser);
	echo " </td>";
} ?>
<td style="width: 10%"><?php make_link ("", "files", "Update", array("command" => "update")) ?></td>
</tr>
</table>
</div>

<div style="float: right; width: 34%">
<?php include("status.php"); ?>
<?php include("control.php"); ?>
<?php include("auth.php"); ?>
<?php include("playlist.php"); ?>
<?php include("options.php"); ?>
</div>

<div style="float: left; width: 64%">
<?php include("browse.php"); ?>
</div>

</body>
