<?php
if (!array_key_exists ("browser", $configuration))
	$configuration["browser"] = $configuration["default_browser"];
switch ($configuration["browser"]) {
case "directory":
	echo "<div id=\"directory\">\n";
	$dirlinks = dir_links ($configuration["directory"], false);
	$dirs = get_directories ($connection, $configuration["directory"]);
	create_browser_table ($configuration["columns"]["directories"], $dirs["directory"], "directory", "directories", "Directories in ".$dirlinks, "No directories found in ".$dirlinks, true, array ("command" => "directory", "arg" => $configuration["directory"]), true, null, null, null, null, true, array ("directory"));
	echo "</div>\n<div id=\"files\">\n";
	$files = get_files ($connection, "directory", $configuration["directory"]);
	create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files in ".$dirlinks, "No files found in ".$dirlinks, true, array ("command" => "directory", "arg" => $configuration["directory"]), true, null, null, null, null, true);
	echo "</div>\n<div id=\"playlists\">\n";
	$pls = get_playlists ($connection, $configuration["directory"]);
	create_browser_table ($configuration["columns"]["playlists"], $pls["playlist"], "playlist", "playlists", "Playlists in ".$dirlinks, "No playlists found in ".$dirlinks, true, array (), false, null, null, null, null, true);
	echo "</div>\n";
	include("streams.php");
	break;
case "search":
	include ("search.php");
	if (array_key_exists ("tag", $configuration) && array_key_exists ("arg", $configuration)) {
		echo "<div id=\"files\">\n";
		$files = get_files ($connection, $configuration["tag"], $configuration["arg"], false);
		create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files with ".$configuration["tag"]." \"".$configuration["arg"]."\"", "No files found!", true, array ("command" => "directory", "arg" => $configuration["directory"]), true, null, null, null, null, true);
		echo "</div>\n";
	}
	break;
case "album":
	if (array_key_exists ("album", $configuration)) {
		echo "<div id=\"files\">\n";
		$files = get_files ($connection, "album", $configuration["album"]);
		create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files in album \"".stripslashes ($configuration["album"])."\"", "No files found!", true, array ("command" => "album", "arg" => $configuration["album"]), true, null, null, null, null, true);
		echo "</div>\n";
	} else if (array_key_exists ("artist", $configuration)) {
		echo "<div id=\"files\">\n";
		$files = get_files ($connection, "artist", $configuration["artist"]);
		create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files from artist \"".stripslashes ($configuration["artist"])."\"", "No files found!", true, array ("command" => "artist", "arg" => $configuration["artist"]), true, null, null, null, null, true);
		echo "</div>\n";
	}
	if (array_key_exists ("artist", $configuration)) {
		$albums = get_albums ($connection, $configuration["artist"]);
	} else {
		$albums = get_albums ($connection);
	}
	echo "<div id=\"album\">\n";
	create_browser_table ($configuration["columns"]["albums"], $albums["Album"], "Album", "albums", "Albums", "No albums found!", true, array (), false, null, null, null, null, true);
	echo "</div>\n";
	break;
case "artist":
case "title":
case "genre":
	$tag = $configuration["browser"];
	if (array_key_exists ($tag, $configuration)) {
		echo "<div id=\"files\">\n";
		$files = get_files ($connection, $tag, $configuration[$tag]);
		create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files with ".$tag." \"".stripslashes ($configuration[$tag])."\"", "No files found!", true, array ("command" => $tag, "arg" => $configuration[$tag]), true, null, null, null, null, true);
		echo "</div>\n";
	}
	echo "<div id=\"".$tag."\">\n";
	$tags = get_tag ($connection, $tag);
	create_browser_table ($configuration["columns"][$tag."s"], $tags[ucwords ($tag)], ucwords ($tag), $tag."s", ucwords ($tag)."s", "No ".$tag."s found!", true, array (), false, null, null, null, null, true);
	echo "</div>\n";
	break;
}
?>
