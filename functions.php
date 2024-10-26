<?php
//Creates a link to the given URL, with the frame target and content information specified in the current layout's vars.php
// under $target, with the given title and arguments. (get-vars)
function make_link ($url, $target, $text, $arguments = array(), $hilight = false, $picture = null, $anametarget = null, $title = null, $stdout = true) {
	global $layout_vars;
	$output = "";
	$firstarg = true;
	$output .= "<a href=\"".$url;
	foreach ($arguments as $arg => $value) {
		if (is_array ($value)) {
			foreach ($value as $kkey => $vval) {
				$output .= ($firstarg ? "?" : "&amp;");
				$output .= $arg."[".$kkey."]=".$vval;
				$firstart = false;
			}
		} else {
			$output .= ($firstarg ? "?" : "&amp;");
			$output .= $arg."=".$value;
			$firstarg = false;
		}
	}
	if ($target != "") {
		if ($target == "_top") {
			if ($anametarget != null)
				$output .= "#".$anametarget;
			$output .= "\" target=\"_top";
		} elseif ($target == "_blank") {
			if ($anametarget != null)
				$output .= "#".$anametarget;
			$output .= "\" target=\"_blank";
		} elseif (array_key_exists($target, $layout_vars["default_targets"])) {
			if ($firstarg) {
				$output .= "?";
				$firstarg = false;
			} else {
				$output .= "&amp;";
			}
			$output .= "content=".$layout_vars["default_targets"][$target]["content"];
			if ($anametarget != null)
				$output .= "#".$anametarget;
			$output .= "\"";
			if ($layout_vars["frames"] == true) $output .= " target=\"".$layout_vars["default_targets"][$target]["frame"];
		}
	} else {
		if ($anametarget != null)
			$output .= "#".$anametarget;
	}
	if ($hilight == true)
		$output .= "\" class=\"hilight";
	if ($title != null)
		$output .= "\" title=\"".$title;
	if ($picture != null) {
		$output .= "\"><img src=\"".$picture."\" alt=\"".$text."\" /></a>";
	} else {
		$output .= "\">".$text."</a>";
	}
	if ($stdout == true)
		echo $output;
	else
		return $output;
}

//Creates entry fields for updating non-boolean configuration values, or for entering streams and such.
function make_form ($url, $target, $vars = array(), $method = "post", $submitcaption = "Submit", $intable = true, $trcount = 0) {
	global $layout_vars;
	if (count ($vars) > 0) {
		echo "<form action=\"".$url."\" method=\"".$method;
		if ($target == "_top") {
			echo "\" target=\"_top\">\n";
		} elseif ($target == "_blank") {
			echo "\" target=\"_blank\">\n";
		} else {
			echo "\" target=\"".$target."\">\n<input type=\"hidden\" name=\"content\" value=\"".$layout_vars["default_targets"][$target]["content"]."\" />\n";
		}
		if ($intable == true) {
			$counte = $trcount;
			foreach ($vars as $variable => $value) {
				echo "<input type=\"hidden\" name=\"form_vars[".$counte."]\" value=\"".$variable."\" />";
				echo "<tr".($counte % 2 != 1 ? " class=\"alt\"" : "").">\n";
				if (is_array ($value)) {
					echo "<td>".str_replace ('_', ' ', $variable)."</td>\n";
					$first = true;
					foreach ($value as $val => $isselected) {
						if (!$first) {
							//$counte++;
							echo "<tr".($counte % 2 != 1 ? " class=\"alt\"" : "")."><td></td>";
						}
						echo "<td><label><input type=\"radio\" name=\"".$variable."\" value=\"".$val."\" ".($isselected == true ? "checked " : "")." class=\"textbox\" />".str_replace ('_', ' ', $val)."</label></td></tr>\n";
						$first = false;
					}
				} elseif (is_bool ($value)) {
						echo "<td><label for=\"".$variable."\">".str_replace ('_', ' ', $variable)."</label></td><td><input type=\"checkbox\" name=\"".$variable."\" value=\"true\" ".($value == true ? "checked " : "")." id=\"".$variable."\" class=\"textbox\" />enable</td>\n";
				} else {
					echo "<td><label>".str_replace('_', ' ', $variable)."</label></td><td><input type=\"text\" class=\"textbox\" name=\"".$variable."\" value=\"".$value."\" id=\"".$variable."\" /></td>";
				}
				echo "</tr>\n";
				$counte++;
			}
			if ($intable) echo "<tr><td><input type=\"submit\" value=\"".$submitcaption."\" class=\"button\" /></td><td> </td></tr>";
		} else {
			foreach ($vars as $variable => $value) {
				if (is_array ($value)) {
					echo str_replace ('_', ' ', $variable).":\n";
					foreach ($value as $val => $isselected) {
						echo "<label><input type=\"radio\" name=\"".$variable."\" value=\"".$val."\" ".($isselected == true ? "checked " : "")."/>".str_replace ('_', ' ', $value)."</label><br />/n";
					}
				} elseif (is_bool ($value)) {
						echo "<label><input type=\"checkbox\" name=\"".$variable."\" value=\"true\" ".($value == true ? "checked " : "")."/>".str_replace ('_', ' ', $variable)."</label>\n";
				} else {
					echo "<label>".str_replace('_', ' ', $variable).":</label> <input type=\"text\" name=\"".$variable."\" value=\"".$value."\" id=\"".$variable."\" />";
				}
				echo "<br />\n";
			}
			echo "<input type=\"submit\" value=\"".$submitcaption."\" class=\"button\" />";
		}
		echo "\n</form>\n";
	}
}

//Creates links to a series of directories, breaking at each "/".
function dir_links ($dir, $stdout = true) {
	$output = "";
	if (strlen($dir) > 0) {
		$output .= make_link ("", "browser", "Music", array ("directory" => ""), false, null, null, null, false);
		$bit = strtok ($dir, "/");
		$cdir = $bit;
		while ($bit) {
			$output .= " / ";
			$output .= make_link ("", "browser", $bit, array ("directory" => $cdir), false, null, null, null, false);
			$bit = strtok ("/");
			$cdir = $cdir."/".$bit;
		}
	} else {
		$output .= make_link ("", "browser", "Music", array ("directory" => ""), false, null, null, null, false);
	}
	if ($stdout == true)
		echo $output;
	else
		return $output;
}

//Loads configuration variables from cookies.
function load_config_cookies($cookies) {
	global $configuration;
	foreach($cookies as $key => $value) {
		if(strncmp($key, "phpMp_config_", strlen("phpMp_config_")) == 0) {
			if($value == "true")
				$configuration[substr($key, strlen("phpMp_config_"))] = true;
			elseif($value == "false")
				$configuration[substr($key, strlen("phpMp_config_"))] = false;
			else
				$configuration[substr($key, strlen("phpMp_config_"))] = $value;
		}
	}
}

//Creates a cookie holding a configuration value.
function make_config_cookie($key, $value) {
	if (is_array ($value)) {
		foreach ($value as $valkey => $valval) {
			setcookie("phpMp_config_".$key."[".$valkey."]", $valval);
		}
	} else {
		setcookie("phpMp_config_".$key, $value);
	}
}

//Destroys a configuration cookie.
function eat_config_cookie($key) {
	setcookie("phpMp_config_".$key, "", time() - 3600);
}

//Parses information returned by mpd.
function parse_mpd_var($in_str) {
	global $mpd_version;
	$got = trim($in_str);
	if(!isset($got))
		return null;
	switch(strtok($got, " ")) {
	case "OK":
		if(strtok(" ") == "MPD")
			$mpd_version = strtok(" ");
		return true;
	case "ACK":
		str_replace("\n", "\n<br />", $got);
		print $got."<br />";
		return true;
	default:
		$key = trim(strtok($got, ":"));
		$val = trim(strtok("\0"));
		return array(0 => $key, 1 => $val);
	}
}

//Sends a command to mpd and parses the results.
function do_mpd_command($conn, $command, $varname = null, $return_array = false, $groupbycase = false) {
	$retarr = array();
	global $tags;
	global $letters_tags;
	fputs($conn, $command."\n");
	while(!feof($conn)) {
		$var = parse_mpd_var(fgets($conn, 1024));
		if(isset($var)){
			if($var === true) {
				if (count($retarr) == 0)
					return true;
				else
					break;
			}
			if(isset($varname) && strcmp($var[0], $varname)) {
				return $var[1];
			} elseif($return_array == true) {
				if($groupbycase == true) {
					if(substr($var[0], 0, 1) == strtolower(substr($var[0], 0, 1))) {
						if(array_key_exists($var[0], $retarr)) {
							$retarr[($var[0])][($var[1])] = array();
						} else {
							$retarr[($var[0])] = array($var[1] => array());
						}
						$lastgroupname = $var[0];
						$lastitemname = $var[1];
					} else {
						$retarr[$lastgroupname][$lastitemname][($var[0])] = $var[1];
					}
				} else {
					if(array_key_exists($var[0], $retarr)) {
						if(is_array($retarr[($var[0])])) {
							array_push($retarr[($var[0])], $var[1]);
						} else {
							$tmp = $retarr[($var[0])];
							$retarr[($var[0])] = array($tmp, $var[1]);
						}
					} else {
						$retarr[($var[0])] = $var[1];
					}
				}
			}
		}
	}
	return $retarr;
}

//Sends a command list to mpd and returns true if it was successful.
function do_mpd_command_list($conn, $command, $arglist) {
	fputs($conn, "command_list_begin\n");
	foreach($arglist as $key => $arg) {
		fputs($conn, $command." ".$arg."\n");
	}
	fputs($conn, "command_list_end\n");
	while(!feof($conn)) {
		$var = parse_mpd_var(fgets($conn, 1024));
		if(isset($var)){
			if($var === true)
				return true;
		}
	}
	return false;
}

//Creates a slider of length $length that can vary $var from $min to $max, hilighted up to the point of $current.
// If $intonly is true, it rounds off each segment to the nearest integer.
function create_slider($current, $min, $max, $length, $var, $intonly = false) {
	global $configuration, $layout_vars;
	if($configuration["graphical_sliders"] == true && style_slider_image() == true && function_exists("imagecreatetruecolor")) {
		echo "<form method=\"get\">\n";
		echo "<input type=\"hidden\" name=\"sliderlength\" value=\"".$length."\" />\n";
		echo "<input type=\"hidden\" name=\"slidermin\" value=\"".$min."\" />\n";
		echo "<input type=\"hidden\" name=\"slidermax\" value=\"".$max."\" />\n";
		echo "<input type=\"hidden\" name=\"slidercommand\" value=\"".$var."\" />\n";
		echo "<input type=\"hidden\" name=\"sliderintonly\" value=\"".($intonly ? "true" : "false")."\" />\n";
		if (array_key_exists ("content", $_REQUEST)) echo "<input type=\"hidden\" name=\"content\" value=\"".$_REQUEST["content"]."\" />\n";
		echo "<input type=\"image\" alt=\"[slider]\" src=\"slider.php?imagedark=".style_slider_image("dark")."&amp;imagelight=".style_slider_image("light")."&amp;length=".$length."&amp;value=".$current."&amp;min=".$min."&amp;max=".$max."\" name=\"slider\">\n</form>";
	} else {
		echo "<div style=\"width: ".($length * 4 + 4)."px\">";
		for($segment = 0; $segment <= intval($length/4); $segment++) {
			$pos = $segment * (($max - $min) / $length);
			echo "<a href=\"?command=".$var."&amp;arg=".($intonly ? intval($pos) : $pos).(array_key_exists("content", $_REQUEST) ? "&content=".$_REQUEST["content"] : "")."\" title=\"".($intonly ? intval($pos) : $pos)."\" class=\"".($pos <= $current ? "sliderlite" : "sliderdark")."\"> </a>\n";
		}
		echo "</div>";
	}
}

//Formats a time given in seconds.
function format_time($seconds) {
	$hrs = intval($seconds/(60*60));
	$min = intval($seconds/60) - ($hrs * 60);
	$sec = $seconds - ($min * 60) - ($hrs * 60 * 60);
	return ($hrs > 0 ? strval($hrs).":".str_pad(strval($min), 2, "0", STR_PAD_LEFT) : strval($min)).":".str_pad(strval($sec), 2, "0", STR_PAD_LEFT);
}

//Returns a song title string formatted according to $format (see config.php) derived from the information in $songinfo.
function format_song_title($format, $songinfo, $number = null, $strict = false) {
	global $configuration;
	$output = $format;
	$tags = explode("[", $output);
	if($tags) {
		foreach($tags as $key => $tag_raw) {
			$tag_list = substr($tag_raw, 0, strpos($tag_raw, "]"));
			if(strlen($tag_list) > 0) {
				$replace = null;
				foreach(explode("|", $tag_list) as $key => $tag) {
					$replace = get_songinfo_tag($tag, $songinfo, $number);
					if($replace !== null)
						break;
				}
				if($replace !== null)
					$output = str_replace("[".$tag_list."]", $replace, $output);
				else if ($strict == true)
					return false;
				else
					$output = str_replace ("[".$tag_list."]", $configuration["unknown_string"], $output);
			}
		}
	}
	return $output;
}

// Get the data for a given tag from the given $songinfo.
function get_songinfo_tag($tag, $songinfo, $number = null) {
	global $configuration;
	if ($tag == "Number") {
		if($number != null) {
			return $number;
		} else {
			return "";
		}
	} else if (array_key_exists ($tag, $songinfo)) {
		switch($tag) {
		case "Title":
			return htmlspecialchars($songinfo[$tag]);
		case "Time":
			return format_time ($songinfo[$tag]);
		case "directory":
		case "playlist":
		case "file":
			$replace = htmlspecialchars ($songinfo[$tag]);
			if ($configuration["filenames_strip_directory"] == true)
				$replace = basename ($replace);
			if ($configuration["filenames_replace_underscores"] == true)
				$replace = str_replace ('_',' ', $replace);
			return $replace;
		default:
			return htmlspecialchars($songinfo[$tag]);
		}
	} else if (substr ($tag, -7) == "literal") {
		$base_tag = substr ($tag, 0, -7);
		if (array_key_exists ($base_tag, $songinfo)) {
			return htmlspecialchars ($songinfo[$base_tag]);
		}
		return null;
	} else if ($tag == "Titlefile") {
		if (array_key_exists ("Title", $songinfo)) {
			return htmlspecialchars($songinfo["Title"]);
		} else {
			$replace = htmlspecialchars ($songinfo["file"]);
			if ($configuration["filenames_strip_directory"] == true)
				$replace = basename ($replace);
			if ($configuration["filenames_replace_underscores"] == true)
				$replace = str_replace ('_',' ', $replace);
			return $replace;
		}
	}
	return null;
}

//Makes a table of index letters.
function make_index_table($letters_arr, $prefix, $make_table = true) {
	if(count($letters_arr) > 0) {
		$letters = array_keys($letters_arr);
		natcasesort($letters);
		$letters = array_flip($letters);
		if ($make_table)
			echo "<table cellspacing=\"0\"><tr class=\"nobg\">\n";
		else
			echo "[";
		foreach($letters as $letter => $truth) {
			if ($make_table)
				echo "<td class=\"nobg\" style=\"width: 2em\">";
			echo "<a href=\"#".$prefix.$letter."\">".$letter."</a>";
			if ($make_table)
				echo "</td>\n";
		}
		if ($make_table)
			echo "<td class=\"nobg\" style=\"width: 100%\"></td></tr></table>\n";
		else
			echo "]";
	}
}

//Returns the first character of a multibyte string.
function mbFirstChar($str) {
	if (strlen ($str) > 0) {
		$i = 1;
		$ret = "$str[0]";
		while($i < strlen($str) && ord($str[$i]) >= 128  && ord($str[$i]) < 192) {
			$ret.=$str[$i];
			$i++;
		}
		return $ret;
	} else {
		return "";
	}
}

//Sorts two given songs according to the sortorder given in $configuration.
function sort_song ($a, $b) {
	global $configuration;
	return sort_songinfo ($a, $b, isset($configuration["sort"]) ? $configuration["sort"] : array("file"), 0);
}

//Helper function for sort_song.
function sort_songinfo ($a, $b, $sort_history, $depth) {
	if ($a == $b)
		return 0;
	if ($depth >= count($sort_history)) return strnatcasecmp ($a["file"], $b["file"]);
	$sortname = $sort_history[$depth];
	if (array_key_exists ($sortname, $a)) {
		if (array_key_exists ($sortname, $b)) {
			return strnatcasecmp ($a[$sortname], $b[$sortname]);
		} else {
			return 1;
		}
	} else {
		if (array_key_exists ($sortname, $b)) {
			return -1;
		} else {
			return sort_songinfo ($a, $b, $sort_history, $depth + 1);
		}
	}
}

//Returns the first value in the sort order that is set in $info.
function get_songinfo_first ($info, $sort_history, $depth) {
	if (count ($sort_history) > $depth) {
		$sortname = $sort_history[$depth];
		if (array_key_exists ($sortname, $info)) {
			return $info[$sortname];
		} else {
			return get_songinfo_first ($info, $sort_history, $depth + 1);
		}
	} else {
		return "";
	}
}

//Performs an MPD command and parses the output as a browsing response.
function do_mpd_browse_command ($connection, $command, $arguments, $filter_group = "") {
	$retarr = array();
	if (is_array ($arguments)) {
		$args = $arguments;
	} else {
		$args = array ($arguments);
	}
	foreach ($args as $arg) {
		$skip_group = false;
		if (is_null ($arg)) {
			fputs($connection, $command."\n");
		} else {
			fputs($connection, $command." \"".$arg."\"\n");
		}
		$first_tag = null;
		while(!feof($connection)) {
			$var = parse_mpd_var(fgets($connection, 1024));
			if(isset($var)){
				if($first_tag === null) {
					$first_tag = $var[0];
				}
				if($var === true) {
					if (count($retarr) == 0)
						return true;
					else
						break;
				}
				if($var[0] == $first_tag) {
					//tag is the same as the first received tag; this is the start of a new response group.
					$groupname = $var[0];
					$itemname = $var[1];
					if ($filter_group == "" || $groupname == $filter_group) {
						$skip_group = false;
						if (array_key_exists ($groupname, $retarr)) {
							$retarr[$groupname][$itemname] = array ();
						} else {
							$retarr[$groupname] = array ($itemname => array ());
						}
					} else {
						$skip_group = true;
					}
				}
				if ($skip_group != true) {
					//add this to the current response group.
					$retarr[$groupname][$itemname][($var[0])] = $var[1];
				}
			}
		}
	}
	return $retarr;
}

//Builds an array of index letters from the given data.
function make_index ($data, $dataname) {
	$letters = array();
	foreach ($data as $name => $info) {
		if (is_array ($info))
			$inf = array_merge (array ($dataname => $name), $info);
		else
			$inf = array ($dataname => $info);
		$letter = strtoupper (mbFirstChar (get_songinfo_first ($inf, isset($configuration["sort"]) ? array_merge ($configuration["sort"], array ($dataname)) : array ($dataname), 0)));
		if (!isset ($letters[$letter]))
			$letters[$letter] = true;
	}
	return $letters;
}

//Converts an array from the format returned by do_mpd_command's return_array mode to the format returned by do_mpd_browse_command.
function convert_mpd_return ($data, $dataname) {
	$retarr = array ();
	foreach ($data as $name) {
		$retarr[$name] = array ($dataname => $name);
	}
	return $retarr;
}

//Ensure that we return an array - if we received a `true` response, return an empty array.
function ensure_array($value, $name) {
	if ($value === true) {
		return array ($name => array ());
	}
	return $value;
}

//Returns an array of files matching the given search within the given field.
// Valid values for $field:
//  "directory" - return all files within the directory given in $search.
//  "files" - return all files listed within $search (passed as a comma-separated list) that exist in the database.
//  "artist" - return all files matching the artist given in $search.
//  "album" - return all files matching the album given in $search.
//  "title" - return all files matching the title given in $search.
// Set $exact to true if the search should only return exact matches. (only applies to artist and album)
function get_files ($connection, $field, $search, $exact = true) {
	switch ($field) {
	case "directory":
		return ensure_array(do_mpd_browse_command ($connection, "lsinfo", $search, "file"), "file");
	case "files":
	case "filename":
		$filenames = explode (",", $search);
		return ensure_array(do_mpd_browse_command ($connection, "search filename", $filenames, "file"), "file");
	case "artist":
	case "album":
	case "title":
	case "genre":
		if ($exact == true) {
			return ensure_array(do_mpd_browse_command ($connection, "find ".$field, $search, "file"), "file");
		}
		return ensure_array(do_mpd_browse_command ($connection, "search ".$field, $search, "file"), "file");
	default:
		return array ();
	}
}

//Returns a list of subdirectories of the given directory. The directory defaults to the root of the database.
function get_directories ($connection, $parent = "") {
	return ensure_array(do_mpd_browse_command ($connection, "lsinfo", $parent, "directory"), "directory");
}

//Returns an array of available playlists in the given directory. The directory defaults to the root of the database.
function get_playlists ($connection, $parent = "") {
	return ensure_array(do_mpd_browse_command ($connection, "lsinfo", $parent, "playlist"), "playlist");
}

//Returns an array of instances of the given tag in the database.
function get_tag ($connection, $tag) {
	$tmp = do_mpd_command ($connection, "list ".$tag, null, true);
	return array (ucwords ($tag) => convert_mpd_return ($tmp[ucwords ($tag)], ucwords ($tag)));
}

//Returns an array of albums in the database, optionally looking only for albums matching a given artist.
function get_albums ($connection, $artist = "") {
	$tmp = do_mpd_command ($connection, "list album ".$artist, null, true);
	return array ("Album" => convert_mpd_return ($tmp["Album"], "Album"));
}

//Returns an array containing all the songs in the current playlist.
function get_playlist ($connection) {
	return ensure_array(do_mpd_browse_command ($connection, "playlistinfo", null, "file"), "file");
}

//Creates a table for the browser based on a given column definition and dataset.
function create_browser_table ($columns, $data, $dataname, $name, $title, $nonefoundmessage, $makeindextable = false, $searchterms = array(), $addalllink = false, $startindex = null, $stopindex = null, $hilighttag = null, $hilightmatch = null, $sortdata = false, $customsort = null) {
	global $configuration;
	if (is_array ($data) && count ($data) > 0) {
		if ($sortdata == true) {
			$sorttmp = $configuration["sort"];
			if (is_array ($customsort))
				$configuration["sort"] = $customsort;
			else if ($customsort !== null)
				$configuration["sort"] = array($customsort);
			else
				array_merge (array($dataname), $configuration["sort"]);
			uasort ($data, 'sort_song');
			$configuration["sort"] = $sorttmp;
		}

		if ($title != "") {
			echo "<h2><a name=\"".$name."\"></a>".$title;
			if ($addalllink == true) {
				echo " <small>[";
				make_link ("", "playlist", "add all", array("command" => "addall", "tag" => $searchterms["command"], "arg" => $searchterms["arg"]));
				echo "] ";
				if ($makeindextable == true) {
					make_index_table (make_index ($data, $dataname), $name."_", false);
				}
				echo "</small>";
			} else {
				if ($makeindextable == true) {
					echo " <small>";
					make_index_table (make_index ($data, $dataname), $name."_", false);
					echo "</small>";
				}
			}
			echo "</h2>\n";
		}

		echo "<table cellspacing=\"0\">\n<thead><tr>";
		foreach ($columns as $coltitle => $coldef) {
			echo "<td".(array_key_exists ("shrink", $coldef) && $coldef["shrink"] ? " style=\"width: ".strlen ($coltitle)."em\"" : "").">";
			if (array_key_exists ("sort", $coldef))
				make_link ("", $name, $coltitle, array ("sort" => $coldef["sort"]));
			else
				echo $coltitle;
			echo "</td>";
		}
		echo "</tr></thead>\n<tbody>";

		$rowcount = 0;

		foreach ($data as $dbname => $dblock) {
			if ($startindex == null || ($startindex <= $rowcount && $rowcount <= $stopindex)) {
				if (is_array ($dblock)) {
					$dblockmerged = array_merge (array ($dataname => $dbname, "index" => $rowcount), $dblock);
				} else {
					$dblockmerged = array ($dataname => $dblock, "index" => $rowcount);
				}
				echo "<tr";
				if ($hilighttag != null && $dblockmerged[$hilighttag] == $hilightmatch)
					echo " class=\"hilight\"";
				else if ($rowcount % 2 != 1)
					echo " class=\"alt\"";
				echo ">";
				foreach ($columns as $coltitle => $coldef) {
					echo "<td>";
					if (array_key_exists ("command", $coldef)) {
						$target = "";
						switch ($coldef["command"]) {
						case "play":
						case "playid":
							$target = "status";
							break;
						case "add":
						case "addall":
						case "addall_recursive":
						case "addlist":
						case "upload_playlist":
						case "delete":
						case "deleteid":
						case "load":
							$target = "playlist";
							break;
						case "directory":
						case "artist":
						case "album":
						case "title":
						case "genre":
						case "search":
						case "rm":
						case "update":
							$target = "browser";
						}
						$cmdarg = format_song_title ($coldef["arg"], $dblockmerged, strval ($rowcount), true);
						if ($cmdarg !== false)
							make_link ("", $target, format_song_title ($coldef["text"], $dblockmerged, strval ($rowcount)), array ("command" => $coldef["command"], "arg" => $cmdarg), false, null, null, (array_key_exists ("tooltip", $coldef) ? $coldef["tooltip"] : null));
						else
							echo $configuration["unknown_string"];
					} else {
						echo format_song_title ($coldef["text"], $dblockmerged, strval ($rowcount));
					}
					echo "</td>\n";
				}
				echo "</tr>\n";
			}
			$rowcount++;
		}
		echo "</tbody>\n</table>\n";
	} else {
		echo $nonefoundmessage."\n";
	}
}
?>
