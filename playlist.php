<div id="playlist">
<?php
echo "<h2><a name=\"playlist\"></a>Current Playlist</h2>\n";
echo "<form action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\"";

if (array_key_exists("playlists", $layout_vars["default_targets"])) {
	if ($layout_vars["frames"] == true) echo " target=\"".$layout_vars["default_targets"]["playlists"]["frame"]."\"";
	echo ">\n<input type=\"hidden\" name=\"content\" value=\"".$layout_vars["default_targets"]["playlists"]["content"]."\" />\n<table cellspacing=\"0\">\n";
} else {
	echo "><table cellspacing=\"0\">\n";
}

echo "<tr>\n<td class=\"nobg\">";
make_link("", "playlist", "Shuffle", array("command" => "shuffle"));
echo "</td>\n";
echo "<td class=\"nobg\"><input type=\"text\" name=\"arg\" style=\"width: 100%\" class=\"textbox\" /></td>\n";
echo "<td class=\"nobg\"><input type=\"submit\" name=\"command\" value=\"save\" class=\"button\" /></td>\n";
echo "<td class=\"nobg\">";
make_link("", "playlist", "Clear", array("command" => "clear"));
echo "</td>\n";
echo "</tr></table>\n</form>\n";

if($configuration["playlist_lines"] >= $mpd_status["playlistlength"]) {
	$playlist_start = 0;
	$playlist_end = $mpd_status["playlistlength"];
} else {
	$playlist_start = $configuration["playlist_focus"] - round($configuration["playlist_lines"] / 2 - .1);
	$playlist_end = $configuration["playlist_focus"] + round($configuration["playlist_lines"] / 2);
	if($playlist_start < 0) {
		$playlist_end -= $playlist_start;
		$configuration["playlist_focus"] = $configuration["playlist_lines"];
		$playlist_start = 0;
	}
	if($playlist_end > $mpd_status["playlistlength"]) {
		$playlist_end = $mpd_status["playlistlength"];
	}
}

if($configuration["playlist_lines"] < $mpd_status["playlistlength"] && $playlist_start > 0) {
	echo "<table cellspacing=\"0\" class=\"nostyle\" style=\"text-align: center\"><tr>";
	echo "<td class=\"nobg\">";
	make_link("", "playlist", "prev", $arguments = array("playlist_focus" => ($configuration["playlist_focus"] > $configuration["playlist_lines"] ? strval($configuration["playlist_focus"] - $configuration["playlist_lines"]) : "0" )));
	echo "</td>";
	echo "</tr></table>";
}

$pl_letters = array();
$pl = get_playlist ($connection);
create_browser_table ($configuration["columns"]["playlist"], $pl["file"], "file", "playlist", "", "No songs in playlist!", $pl_letters, array(), false, $playlist_start, $playlist_end, "Id", $mpd_status["songid"]);

if($configuration["playlist_lines"] < $mpd_status["playlistlength"] && $playlist_end < $mpd_status["playlistlength"]) {
	echo "<table cellspacing=\"0\" class=\"nostyle\" style=\"text-align: center\"><tr>";
	echo "<td class=\"nobg\">";
	make_link("", "playlist", "next", $arguments = array("playlist_focus" => ($configuration["playlist_focus"] < $mpd_status["playlistlength"] - $configuration["playlist_lines"] ? strval($configuration["playlist_focus"] + $configuration["playlist_lines"]) : $mpd_status["playlistlength"] )));
	echo "</td>";
	echo "</tr></table>";
}
?>
</div>
