<?php
switch ($configuration["browser"]) {
case "directory":
	echo "<div id=\"directories\">\n";
	$dirs_letters = array();
	$dirs = get_directories ($connection, $configuration["directory"]);
	create_browser_table ($configuration["columns"]["directories"], $dirs["directory"], "directory", "directories", "Directories in ".dir_links ($configuration["directory"], false), "No directories found!", $dirs_letters, array ("command" => "directory", "arg" => $configuration["directory"]), true);
	echo "</div>\n<div id=\"files\">\n";
	$files_letters = array();
	$files = get_files ($connection, "directory", $configuration["directory"]);
	create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files in ".dir_links ($configuration["directory"], false), "No files found!", $files_letters, array ("command" => "directory", "arg" => $configuration["directory"]), true);
	echo "</div>\n<div id=\"playlists\">\n";
	$pls_letters = array();
	$pls = get_playlists ($connection, $configuration["directory"]);
	create_browser_table ($configuration["columns"]["playlists"], $pls["playlist"], "playlist", "playlists", "Playlists in ".dir_links ($configuration["directory"], false), "No playlists found!", $pls_letters);
	echo "</div>\n";
	include("streams.php");
	break;
case "search":
	include ("search.php");
	//if (searched) {
		echo "<div id=\"files\">\n";
		$files_letters = array();
		//@@@FIXME: change to use search instead of directory.
		$files = get_files ($connection, "directory", $configuration["directory"]);
		create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files with ".$tag." \"".dir_links ($configuration["directory"]."\"", false), "No files found!", $files_letters, array ("command" => "directory", "arg" => $configuration["directory"]), true);
		echo "</div>\n";
	//}
	break;
case "album":
	if (array_key_exists ("album", $configuration)) {
		echo "<div id=\"files\">\n";
		$files_letters = array();
		$files = get_files ($connection, "album", $configuration["album"]);
		create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files in album \"".stripslashes ($configuration["album"])."\"", "No files found!", $files_letters, array ("command" => "album", "arg" => $configuration["album"]), true);
		echo "</div>\n";
	} else if (array_key_exists ("artist", $configuration)) {
		echo "<div id=\"files\">\n";
		$files_letters = array();
		$files = get_files ($connection, "artist", $configuration["artist"]);
		create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files from artist \"".stripslashes ($configuration["artist"])."\"", "No files found!", $files_letters, array ("command" => "artist", "arg" => $configuration["artist"]), true);
		echo "</div>\n";
	}
	if (array_key_exists ("artist", $configuration)) {
		$albums_letters = array();
		$albums = get_albums ($connection, $configuration["artist"]);
	} else {
		$albums_letters = array();
		$albums = get_albums ($connection);
	}
	echo "<div id=\"albums\">\n";
	create_browser_table ($configuration["columns"]["albums"], $albums["Album"], "Album", "albums", "Albums", "No albums found!", $albums_letters);
	echo "</div>\n";
	break;
case "artist":
case "title":
case "genre":
	$tag = $configuration["browser"];
	if (array_key_exists ($tag, $configuration)) {
		echo "<div id=\"files\">\n";
		$files_letters = array();
		$files = get_files ($connection, $tag, $configuration[$tag]);
		create_browser_table ($configuration["columns"]["files"], $files["file"], "file", "files", "Files with ".$tag." \"".stripslashes ($configuration[$tag])."\"", "No files found!", $files_letters, array ("command" => $tag, "arg" => $configuration[$tag]), true);
		echo "</div>\n";
	}
	echo "<div id=\"".$tag."\">\n";
	$tags_letters = array();
	$tags = get_tag ($connection, $tag);
	create_browser_table ($configuration["columns"][$tag."s"], $tags[ucwords ($tag)], ucwords ($tag), $tag."s", ucwords ($tag)."s", "No ".$tag."s found!", $tags_letters);
	echo "</div>\n";
	break;
}
?>
