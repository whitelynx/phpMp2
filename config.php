<?php
$configuration = array(
// NEED TO SET THESE!
	"mpd_host" => "127.0.0.1", //The host where mpd is being run.
	"mpd_port" => 6600, //The port which mpd is listening on.

// GENERAL
	"time_elapsed" => true, //Set to true to display elapsed time, false to display remaining time.
	"page_title" => "phpMp2", //Title of the webpage.
	"font_size" => "12pt", //Font size.
	"auto_refresh" => true, //Set to true to cause the status page to automatically refresh every [refresh_freq] seconds.
	"refresh_freq" => 60, //Number of seconds between auto-refreshes.
	"use_cookies" => true, //Pretty much has to be true to function correctly. (Don't complain if you get problems setting this to false)
	"default_browser" => "directory", //The default browser, used when none is specified. (see below for a list of browsers)

// VISUAL STYLE
	"layout" => "classic", //A directory in the layouts/ directory.
	"style" => "classic", //A directory in the styles/ directory.
	"playlist_lines" => 21, //Number of lines shown in the playlist.
	"reset_on_next_song" => true, //Automatically resets the playlist view to center on the current song when the next song starts.

// OUTPUT FORMATTING
	"filenames_only" => false, //Show only the filename of the current song.
	"filenames_strip_directory" => true, //Strip the directory from the output when displaying a filename.
	"filenames_replace_underscores" => true, //Replace '_' with ' ' in filenames.
	"song_display_format" => "[Number]. [Artist] - [Titlefile] ([Album])", //Format for the song status display.
//	Available tags for song_display_format and the columns structure:
//		[Number] - Current song's number in the playlist. (only available in the "playlist" column definition)
//		[Track] - The current song's track number.
//		[Title] - The current song's title.
//		[Artist] - The current song's artist.
//		[Album] - The album that the current song is part of.
//		[Genre] - The current song's genre.
//		[Time] - The total time of the current song, in seconds.
//		[file] - The filename of the current song.
//		[directory] - The name of the current directory. (only available in the "directories" column definition)
//		[playlist] - The name of the current playlist. (only available in the "playlists" column definition)
	"sort" => array ("Title","Artist","Album","Track"), //Sort songs according to these fields.
	// Valid fields for "sort" are any of the tags in the above list that begin with a capital letter.

	"unknown_string" => "~", //String to show when a column's value is unknown for a certain song/directory.
	"show_dotdot" => false, //Show the ".." entry in the directory browser.

// SLIDER OPTIONS
	"combined_slider" => true, //Set to true to combine volume and crossfade into one line, toggleing the slider between the two.
	"combined_slider_control" => "seek", //Set to the parameter to control with the combined slider. One of "volume", "xfade", "seek".
	"display_volume" => true, //Set to true to show the volume slider, if combined_slider != true.
	"display_crossfade" => true, //Set to true to show the volume slider, if combined_slider != true.
	"slider_width" => 240, //Width of the volume and crossfade sliders.
	"graphical_sliders" => true,

//////// Don't worry about editing anything below this line unless you know what you're doing. ////////
	"browsers" => array(
		"directory" => "Browse by Directory",
		"artist" => "Browse by Artist",
		"album" => "Browse by Album",
		"title" => "Browse by Title",
		"genre" => "Browse by Genre",
		"search" => "Search database"
	), //Contains all of the metadata tags that phpmp2 will allow you to browse through.  Special browsers include Directory, which browses through the directory structure of the music database, and Search, which allows you to search the database based on given search terms.
	"search_fields" => array ("artist", "album", "title", "genre", "directory", "filename"),

	"columns" => array(
		"files" => array(
			"Add" => array(
				"text" => "add",
				"command" => "add",
				"arg" => "[fileliteral]",
				"shrink" => true
			),
			"Track" => array(
				"text" => "[Track]"
			),
			"Title" => array(
				"text" => "[Titlefile]",
				"command" => "title",
				"arg" => "[Title]"
			),
			"Artist" => array(
				"text" => "[Artist]",
				"command" => "artist",
				"arg" => "[Artist]"
			),
			"Album" => array(
				"text" => "[Album]",
				"command" => "album",
				"arg" => "[Album]"
			),
			"Time" => array(
				"text" => "[Time]",
			)
		),
		"directories" => array(
			"Add" => array(
				"text" => "add",
				"command" => "add",
				"arg" => "[fileliteral]",
				"shrink" => true
			),
			"Update" => array(
				"text" => "update",
				"command" => "update",
				"arg" => "[fileliteral]",
				"shrink" => true
			),
			"Directory" => array(
				"text" => "[directory]",
				"command" => "directory",
				"arg" => "[directoryliteral]"
			)
		),
		"artists" => array(
			"Artist" => array(
				"text" => "[Artist]",
				"command" => "artist",
				"arg" => "[Artist]"
			)
		),
		"albums" => array(
			"Album" => array(
				"text" => "[Album]",
				"command" => "album",
				"arg" => "[Album]"
			)
		),
		"genres" => array(
			"Genre" => array(
				"text" => "[Genre]",
				"command" => "genre",
				"arg" => "[Genre]"
			)
		),
		"titles" => array(
			"Title" => array(
				"text" => "[Title]",
				"command" => "title",
				"arg" => "[Title]"
			)
		),
		"playlists" => array(
			"Delete" => array(
				"text" => "del",
				"command" => "rm",
				"arg" => "[playlistliteral]",
				"shrink" => true
			),
			"Name" => array(
				"text" => "[playlist]",
				"command" => "load",
				"arg" => "[playlistliteral]"
			)
		),
		"playlist" => array(
			"d" => array(
				"text" => "d",
				"command" => "deleteid",
				"arg" => "[Id]",
				"tooltip" => "Delete",
				"shrink" => true
			),
			"Song" => array(
				"text" => "[Number]. [Artist] - [Titlefile] ([Album])",
				"command" => "playid",
				"arg" => "[Id]"
			),
			"Time" => array(
				"text" => "[Time]"
			)
		)
	)
)
?>
