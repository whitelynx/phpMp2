<body>

<div id="topbar">
<table class="nostyle">
<tr>
<?php
foreach ($configuration["browsers"] as $browser => $name ) {
	echo "<td style=\"width: 9%\">";
	make_link("", "browser", $name, array("browser" => $browser), false, null, $browser);
	echo " </td>";
} ?>
<td style="width: 9%"><?php make_link("", "options", "phpMp2 Options") ?></td>
<td style="width: 9%"><?php make_link ("", "files", "Update", array("command" => "update")) ?></td>
</tr>
</table>
</div>

