# Random Tweet Generator

The Random Tweet Generator is a PHP script to get a random tweet based on a designated keyword.

## Demo

If you don't need to customize the layout of the tweets for nice viewing, just use my Twitter API testing page for all your searching needs. Go to [tweet.kylescheer.com/index.php](http://tweet.kylescheer.com/index.php) and get started.

## Guide

The only code you'll need for this is the TwitterSearchAPI.php and index.php files.

In 10 steps, this tutorial will teach you how to retrieve a “random” tweet from the Twitter Search API using your own specified parameters.

### Step 1)

Download the index.php and TwitterAPIExchange.php files and place them into your directory

### Step 2)

Open index.php in a text editor.

### Step 3)

Go to the below website and go through steps 1–4 to retrieve your Twitter API access tokens.

https://www.slickremix.com/docs/how-to-get-api-keys-and-tokens-for-twitter/

### Step 4)

```php
$settings = array(
		'oauth_access_token' => "PLACE OAUTH ACCESS TOKEN HERE",
		'oauth_access_token_secret' => "PLACE OAUTH ACCESS TOKEN SECRET HERE",
		'consumer_key' => "PLACE CONSUMER KEY HERE",
		'consumer_secret' => "PLACE CONSUMER SECRET HERE"
	);
```

Replace the access token placeholder text with the tokens you received from Step 3.

### Step 5)

Save the index.php file. Go to the index.php file in a web browser to check that the API tokens are working.

If you did receive an output, you are all good to continue to step 9. If you didn’t receive an output, recheck your tokens and consumer keys and make sure there are no spaces inside the quotation marks.

### Step 6)
Right now, we are running a search which finds tweets with the exact keyword “random”, or otherwise set, and are excluding retweets. This is shown in the **$searchfield** variable.

You can specify the **$inputkeyword** as you choose, in the code. Use the [Twitter Standard Operator guide](https://developer.twitter.com/en/docs/twitter-api/v1/tweets/search/guides/standard-operators) for information on how to search hashtags and more.

### Step 7)
Go to the index.php file in a web browser to check that the query is working.

### Step 8)
Customize the $getfield variable as you see fit. [Here is an explainer](https://developer.twitter.com/en/docs/tweets/search/api-reference/get-search-tweets) on the existing $getfield parameters:

```php
&count=100
```
Currently, the Twitter Standard Search API only allows for a maximum of 100 tweets to be pulled for a query. Including this text ensures we get as many tweets as possible, rather than the default 15.

```php+HTML
&result_type=mixed
```
The result_type parameter specifies which results to get. Because we can only get 100 results, we need to specify which results we want. There are 3 types of result_type option available: popular, recent, and mixed. Popular gets the most popular tweets that fit the criteria specified. Recent get the most recent tweets that fit the criteria specified. Mixed gets a mixture of popular and recent tweets that fit the criteria specified. For this example, I’ve chosen to use the mixed option.

```php+HTML
tweet_mode=extended”
```
Now that Twitter allows people to post 280 characters instead of 140 characters, this parameter specifies that we want to see the entire tweet rather than a parsed 140 character version. Keeping this parameter is *highly recommended*.

### Step 9)
The lines under *"//print out the tweet information"* specifies which variables we want to echo and how we want to see them.

https://developer.twitter.com/en/docs/tweets/data-dictionary/overview/user-object has a full list of variables available to view.

Here is where you can remove variables, add new variables, and customize the HTML and CSS to make the variables look better on the page.

### Step 10)

You can change the button text at the very bottom. The button functions to reload the page and restarts the query, so you get a new set of tweets and in a different order.

## Notes

1. Keywords aren’t case sensitive, so a keyword of “harry potter” will also bring up tweets with “Harry Potter”, “Harry potter”, “HARRY POTTER”, etc…

## Contributions

The entire TwitterAPIExchange.php code is thanks to J7mbo - https://github.com/J7mbo/twitter-api-php
