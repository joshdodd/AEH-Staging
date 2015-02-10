<?php
// ----- Get Tweets - Research -----
// Session start
	session_start();

	// Set timezone. (Modify to match your timezone) If you need help with this, you can find it here. (http://php.net/manual/en/timezones.php)
	date_default_timezone_set('America/Kentucky/Monticello');

	// Require TwitterOAuth files. (Downloadable from : https://github.com/abraham/twitteroauth)
	require_once("twitteroauth/twitteroauth/twitteroauth.php");

	// Function to authenticate app with Twitter.
	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	  return $connection;
	}

	// Function to display the latest tweets.
	function display_latest_tweets(
		// Function parameters.
		$twitter_user_id,
		$twitter_list_id,
		$tweets_to_display,
		//$cache_file          = './tweets.txt',  // Change this to the path of your cache file. (Default : ./tweets.txt)
		$ignore_replies      = false,           // Ignore replies from the timeline. (Default : false)
		$include_rts         = false,           // Include retweets. (Default : false)
		$twitter_wrap_open   = '<div class="home-tweets">',
		$twitter_wrap_close  = '</div>',
		$tweet_wrap_open     = '<li><p class="home-tweet-tweet">',
		$meta_wrap_open      = '<span class="home-tweet-date">',
		$meta_wrap_close     = '</span>',
		$tweet_wrap_close    = '</p></li>',
		$date_format         = 'g:i A M jS',    // Date formatting. (http://php.net/manual/en/function.date.php)
		$twitter_style_dates = true){           // Twitter style days. [about an hour ago] (Default : true)

		// Twitter keys (You'll need to visit https://dev.twitter.com and register to get these.
		$consumerkey         = "U3l7Ag4z3sF377eyYcozw";
		$consumersecret      = "Epn2PNk6WnjF4aBIkL7SLb3d7P1t2L8pAp5sJJAb2hc";
		$accesstoken         = "182446074-rzuCpzcugm25xMB7trSocVLZVxzxSyalUadNGR1z";
		$accesstokensecret   = "JmfOTp3GfdN5DMKJWMTt7GeYAeumvJ5IQekLpBOPTCC06";

		// Seconds to cache feed (Default : 1 minute).
		$cachetime           = 60*3;

		// Time that the cache was last updtaed.
		$cache_file_created  = ((file_exists($cache_file))) ? filemtime($cache_file) : 0;

		// A flag so we know if the feed was successfully parsed.
		$tweet_found         = false;

		// Show cached version of tweets, if it's less than $cachetime.
		if (time() - $cachetime < $cache_file_created) {
	 		$tweet_found = true;
			// Display tweets from the cache.
			readfile($cache_file);
		} else {
		// Cache file not found, or old. Authenticae app.
		$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

			if($connection){
				// Get the latest tweets from Twitter
 				$get_tweets = $connection->get("https://api.twitter.com/1.1/lists/statuses.json?slug=".$twitter_list_id."&owner_screen_name=".$twitter_user_id."&count=".$tweets_to_display."&include_rts=".$include_rts);

				// Error check: Make sure there is at least one item.
				if (count($get_tweets)) {
					// Define tweet_count as zero
					$tweet_count = 0;

					// Start output buffering.
					ob_start();

					// Open the twitter wrapping element.
					$twitter_html = $twitter_wrap_open;

					// Iterate over tweets.
					foreach($get_tweets as $tweet) {
							$tweet_found = true;
							$tweet_count++;
 							$tweet_desc = $tweet->text;
 							$tweet_ava  = $tweet->user->profile_image_url;
 							$user_name  = $tweet->user->name;
 							$tweet_handle = $tweet->user->screen_name;

							// Add hyperlink html tags to any urls, twitter ids or hashtags in the tweet.
							$tweet_desc = preg_replace("/((http)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $tweet_desc );
							$tweet_desc = preg_replace("/[@]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">\\0</a>", $tweet_desc );
							$tweet_desc = preg_replace("/[#]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/search?q=%23\\1\" target=\"_blank\">\\0</a>", $tweet_desc );

 							// Convert Tweet display time to a UNIX timestamp. Twitter timestamps are in UTC/GMT time.
							$tweet_time = strtotime($tweet->created_at);
 							if ($twitter_style_dates){
								// Current UNIX timestamp.
								$current_time = time();
								$time_diff = abs($current_time - $tweet_time);
								switch ($time_diff)
								{
									case ($time_diff < 60):
										$display_time = $time_diff.' seconds ago';
										break;
									case ($time_diff >= 60 && $time_diff < 3600):
										$min = floor($time_diff/60);
										$display_time = $min.' minutes ago';
										break;
									case ($time_diff >= 3600 && $time_diff < 86400):
										$hour = floor($time_diff/3600);
										$display_time = 'about '.$hour.' hour';
										if ($hour > 1){ $display_time .= 's'; }
										$display_time .= ' ago';
										break;
									default:
										$display_time = date($date_format,$tweet_time);
										break;
								}
 							} else {
 								$display_time = date($date_format,$tweet_time);
 							}
							// Render the tweet.
							$twitter_html .= '<div class="tweet-wrap">
												<div class="gutter clearfix">
													<div class="tweet-img">
														<a target="_blank" href="http://www.twitter.com/'.$tweet_handle.'"><img src="'.$tweet_ava.'" /></a>
													</div>
													<div class="tweet-content">
														<span class="tweet-name">'.$user_name.'</span>
														<span class="tweet-handle">@'.$tweet_handle.'</span>
														<span class="tweet-content">'.html_entity_decode($tweet_desc).'</span>
														<span class="tweet-datetime">'.$display_time.'</span>
													</div>
												</div>
											  </div>';
						// If we have processed enough tweets, stop.
						if ($tweet_count >= $tweets_to_display){
							break;
						}
					}
					// Close the twitter wrapping element.
					$twitter_html .= $twitter_wrap_close;
					echo $twitter_html;
				}
			}
		}
	}


// ----- Get Tweets - User -----
// Function to display the latest tweets.
	function display_user_tweets(
		// Function parameters.
		$twitter_user_id,
		$tweets_to_display,
		//$cache_file          = './tweets.txt',  // Change this to the path of your cache file. (Default : ./tweets.txt)
		$ignore_replies      = false,           // Ignore replies from the timeline. (Default : false)
		$include_rts         = false,           // Include retweets. (Default : false)
		$twitter_wrap_open   = '<div class="home-tweets">',
		$twitter_wrap_close  = '</div>',
		$tweet_wrap_open     = '<li><p class="home-tweet-tweet">',
		$meta_wrap_open      = '<span class="home-tweet-date">',
		$meta_wrap_close     = '</span>',
		$tweet_wrap_close    = '</p></li>',
		$date_format         = 'g:i A M jS',    // Date formatting. (http://php.net/manual/en/function.date.php)
		$twitter_style_dates = true){           // Twitter style days. [about an hour ago] (Default : true)

		// Twitter keys (You'll need to visit https://dev.twitter.com and register to get these.
		$consumerkey         = "U3l7Ag4z3sF377eyYcozw";
		$consumersecret      = "Epn2PNk6WnjF4aBIkL7SLb3d7P1t2L8pAp5sJJAb2hc";
		$accesstoken         = "182446074-rzuCpzcugm25xMB7trSocVLZVxzxSyalUadNGR1z";
		$accesstokensecret   = "JmfOTp3GfdN5DMKJWMTt7GeYAeumvJ5IQekLpBOPTCC06";

		// Seconds to cache feed (Default : 1 minute).
		$cachetime           = 60*3;

		// Time that the cache was last updtaed.
		$cache_file_created  = ((file_exists($cache_file))) ? filemtime($cache_file) : 0;

		// A flag so we know if the feed was successfully parsed.
		$tweet_found         = false;

		// Show cached version of tweets, if it's less than $cachetime.
		if (time() - $cachetime < $cache_file_created) {
	 		$tweet_found = true;
			// Display tweets from the cache.
			readfile($cache_file);
		} else {
		// Cache file not found, or old. Authenticae app.
		$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

			if($connection){
				// Get the latest tweets from Twitter
 				$get_tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitter_user_id."&count=".$tweets_to_display);



				// Error check: Make sure there is at least one item.
				if (count($get_tweets)) {
					// Define tweet_count as zero
					$tweet_count = 0;

					// Start output buffering.
					ob_start();

					// Open the twitter wrapping element.
					$twitter_html = $twitter_wrap_open;

					// Iterate over tweets.
					foreach($get_tweets as $tweet) {
							$tweet_found = true;
							$tweet_count++;
 							$tweet_desc = $tweet->text;
 							$tweet_ava  = $tweet->user->profile_image_url;
 							$user_name  = $tweet->user->name;
 							$tweet_handle = $tweet->user->screen_name;

							// Add hyperlink html tags to any urls, twitter ids or hashtags in the tweet.
							$tweet_desc = preg_replace("/((http)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $tweet_desc );
							$tweet_desc = preg_replace("/[@]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">\\0</a>", $tweet_desc );
							$tweet_desc = preg_replace("/[#]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/search?q=%23\\1\" target=\"_blank\">\\0</a>", $tweet_desc );

 							// Convert Tweet display time to a UNIX timestamp. Twitter timestamps are in UTC/GMT time.
							$tweet_time = strtotime($tweet->created_at);
 							if ($twitter_style_dates){
								// Current UNIX timestamp.
								$current_time = time();
								$time_diff = abs($current_time - $tweet_time);
								switch ($time_diff)
								{
									case ($time_diff < 60):
										$display_time = $time_diff.' seconds ago';
										break;
									case ($time_diff >= 60 && $time_diff < 3600):
										$min = floor($time_diff/60);
										$display_time = $min.' minutes ago';
										break;
									case ($time_diff >= 3600 && $time_diff < 86400):
										$hour = floor($time_diff/3600);
										$display_time = 'about '.$hour.' hour';
										if ($hour > 1){ $display_time .= 's'; }
										$display_time .= ' ago';
										break;
									default:
										$display_time = date($date_format,$tweet_time);
										break;
								}
 							} else {
 								$display_time = date($date_format,$tweet_time);
 							}
							// Render the tweet.
							$twitter_html .= '<div class="tweet-container"><div class="tweet-img"><a href="http://www.twitter.com/'.$tweet_handle.'"><img src="'.$tweet_ava.'" /></a></div>
								<div class="tweet-user"><span class="twitred">'.$user_name.' </span> @'.$tweet_handle.'</div>
								<div class="tweet-content">'.html_entity_decode($tweet_desc).'</div></div>';


						// If we have processed enough tweets, stop.
						if ($tweet_count >= $tweets_to_display){
							break;
						}
					}
					// Close the twitter wrapping element.
					$twitter_html .= $twitter_wrap_close;
					echo $twitter_html;
				}
			}
		}
	}