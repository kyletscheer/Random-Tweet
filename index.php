<html>
<head>
</head>
<body>
  <form action='index.php' method='post'>
  Keyword: <input type="text" name="keyword">
  <input type="submit">
  </form>
  <?
		if (!empty($_POST["keyword"])){
			$inputkeyword = $_POST["keyword"];
		}
		else {
		$inputkeyword = "random";
		}
		echo "<h5>The keyword is '" . $inputkeyword . "'.</h5>";
		require_once('TwitterAPIExchange.php');
		/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
		$settings = array(
			'oauth_access_token' => "PLACE OAUTH ACCESS TOKEN HERE",
			'oauth_access_token_secret' => "PLACE OAUTH ACCESS TOKEN SECRET HERE",
			'consumer_key' => "PLACE CONSUMER KEY HERE",
			'consumer_secret' => "PLACE CONSUMER SECRET HERE"
		);
		$url = "https://api.twitter.com/1.1/search/tweets.json";
		$requestMethod = "GET";
		$searchfield = "?f=tweets&vertical=default&q=’" . $inputkeyword . "’ AND -filter:retweets &src=typd";
		$getfield = $searchfield . "&count=100&result_type=mixed&tweet_mode=extended";
		$twitter = new TwitterAPIExchange($settings);
		$string = json_decode($twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest(),true);
		if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}
		shuffle($string['statuses']);
		$i = 1;
		foreach($string['statuses'] as $tweets) {
		 $time = $tweets['created_at'];
		 $id = $tweets['id'];
		 $source = $tweets['source'];
		 $tweet = $tweets['full_text'];
		 $name = $tweets['user']['name'];
		 $user = $tweets['user']['screen_name'];
		 $profile_image = $tweets['user']['profile_image_url'];
		 $followers = $tweets['user']['followers_count'];
		 $friends = $tweets['user']['friends_count'];
		 $listed = $tweets['user']['listed_count'];
		echo "<b>Time and Date of Tweet: </b>" . $time ."<br />";
		  echo "<b>ID of Tweet: </b>" . $id . "<br />";
		  echo "<b>Source of Tweet: </b>" . $source . "<br />";
				echo "<b>Tweet: </b>". $tweet ."<br />";
				echo "<b>Tweeted by: </b>". $name ."<br />";
				echo "<b>Screen name: </b>". $user ."<br />";
		  echo "<a href=\"http://twitter.com/$user\" target=\"_blank\">@$user</a><br />";
		  echo "<img src=\"".$profile_image."\" width=\"100px\" height=\"100px\" /><br />";
				echo "<b>Followers: </b>". $followers ."<br />";
				echo "<b>Following: </b>". $friends ."<br />";
				echo "<b>Listed: </b>". $listed ."<br /><hr />";
		 $i++;
		   if($i == 2) break;
		}
		echo "<button type='button' class='btn btn-primary' onClick='window.location.reload()'>Another Tweet</button>";
		?>
</body>
</html>
