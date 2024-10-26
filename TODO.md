- Change do_mpd_browse_command to return a bare indexed array instead of a weirdly nested associative array:
	```
	// Current:
	array(
		"file" => array(
			"foo/bar.mp3" => array(
				"file" => "foo/bar.mp3",
				//...
			),
			"baz/bloop.mp3" => array(
				"file" => "baz/bloop.mp3",
				//...
			),
		),
	)
	// Proposed:
	array(
		array(
			"file" => "foo/bar.mp3",
			//...
		),
		array(
			"file" => "baz/bloop.mp3",
			//...
		),
	)
	```
- Add playlist sorting
- Album art?
- Use song IDs instead of playlist positions for manipulating songs in the playlist.
- Maybe rework layouts to use modern CSS instead of a table-based layout?
