<?php include 'header.php'; ?>
</head>
<body>
<?php include 'nav.php'; ?>
  <div class="container">
  <form action='index.php' method='post'>
  Keyword: <input type="text" name="keyword">
  <input type="submit">
  </form>
  <br><br>
  <button type="button" class="switcher">Toggle Between Tweet Style Or Raw Data</button>

  <?php
  error_reporting(0);
ini_set('display_errors', 0);
	//check if keyword input has been filled out. If not, set to "random
	if (!empty($_POST["keyword"])){
		$inputkeyword = $_POST["keyword"];
	}
	else {
	$inputkeyword = "random";
	}
	echo "<br><br><h5>The keyword is '" . $inputkeyword . "'.</h5>";
	//define connection to Twitter API
	require_once('TwitterAPIExchange.php');
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$OAUTH_ACCESS_TOKEN = '769463491-e9Z80XPolJ8QprsqSAXhqWdfcZ2McHpbzjBB3pO1';
	$OAUTH_ACCESS_TOKEN_SECRET = 'cHJauzfo6HQc9XCsU0hvkD58PYu879XLFCzr8G0qnWRu9';
	$CONSUMER_TOKEN = 'gs3RIhkllY5zha7g3OcG1VJQJ';
	$CONSUMER_TOKEN_SECRET ='6vTowFChGyMx5QAS6e4HksYcn82TqWLqscbuuqNIHTxt7UDrtQ';
	$settings = array(
		'oauth_access_token' => "$OAUTH_ACCESS_TOKEN",
		'oauth_access_token_secret' => "$OAUTH_ACCESS_TOKEN_SECRET",
		'consumer_key' => "$CONSUMER_TOKEN",
		'consumer_secret' => "$CONSUMER_TOKEN_SECRET"
	);
	$url = "https://api.twitter.com/1.1/search/tweets.json";
	$requestMethod = "GET";
	//specify search criteria
	$searchfield = "?f=tweets&vertical=default&q=’" . $inputkeyword . "’ AND -filter:retweets &src=typd";
	$getfield = $searchfield . "&count=100&result_type=mixed&tweet_mode=extended";
	//connect to Twitter API
	$twitter = new TwitterAPIExchange($settings);
	//get the requested tweets from the Twitter API search
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),true);
	if($string["errors"][0]["message"] != "") {
		echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";
		exit();
	}
	//randomly select one of the tweets
	shuffle($string['statuses']);
	$i = 1;
	//print out the tweet information
	foreach($string['statuses'] as $tweets) {
		echo "<div class='basic'>";
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
		echo "<b>Listed: </b>". $listed ."<br /><hr /></div>";
		echo "<div class='stylized'>";
		?>
		<blockquote class="twitter-tweet">
  <a href="https://twitter.com/x/status/<?php echo $id?>"></a> 
</blockquote>
	<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> 

		</div>
		<?php
		$i++;
		if($i == 2) break;  
	}
	?>
	<?php
	//display the button to get a new random tweet
	echo "<button type='button' class='btn btn-primary' onClick='window.location.reload()'>Another Tweet</button>";
	?>
	<br><br><br><br><a href="https://kyletscheer.medium.com/getting-a-random-tweet-using-the-twitter-search-api-and-php-c7546c8fa080" target="_blank">How this was made.</a>
	<script>
		$('.basic').hide();
		$('.switcher').click(function(){
			$('.stylized,.basic').toggle();
		});
	</script>

</div>
<?php include 'footer.php'; ?>
</body>
</html>
