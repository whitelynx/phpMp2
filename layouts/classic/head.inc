<body>

<div id="topbar">
<table class="anotfull">
<tr>
<td style="text-align: right">
<?php
foreach ($configuration["browsers"] as $browser => $name ) {
	echo "[";
	make_link("", "browser", $name, array("browser" => $browser), false, null, $browser);
	echo "]\n";
} ?>
[<?php make_link ("", "options", "Options") ?>]
[<?php make_link ("", "files", "Update", array("command" => "update")) ?>]
</td>
</tr>
</table>
</div>

