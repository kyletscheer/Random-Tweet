# Random-Tweet
Get a random tweet based on a set keyword
HERE IS THE WAY EASIER WAY, FOR SIMPLE QUERIES —
Just use my Twitter API testing page for all your searching needs. Go to tweet.kylescheer.com/index.php and get started. Here are the instructions on how to customize it to your query:
Make the URL “tweet.kylescheer.com/index.php?keyword=[whatever keyword you want searched]” and it will search that. The keyword is “random” by default. Use “%20” for spaces, or “%23” for a hashtag. For example, you can do “tweet.kylescheer.com/index.php?keyword=harry%20potter” to get a random tweet containing “ harry potter”.
Note: Keywords aren’t case sensitive, so a keyword of “harry potter” will also bring up tweets with “Harry Potter”, “Harry potter”, “HARRY POTTER”, etc…
Introduction:
In 14 steps, this tutorial will teach you how to retrieve a “random” tweet from the Twitter Search API using your own specified parameters.
Step 1) Go into your specified directory and create two new files:
index.php
TwitterAPIExchange.php
Step 2) Open both in a text editor.
Step 3) In TwitterAPIExchange.php, place the following text exactly:
Step 4) Save the TwitterAPIExchange.php file and close it.
Step 5) Go to the below website and go through steps 1–4 to retrieve your Twitter API access tokens.
https://www.slickremix.com/docs/how-to-get-api-keys-and-tokens-for-twitter/
Step 6) In index.php, place the following text:
Step 7) Replace the access token placeholder text (all the xxxxxx’s) on lines 5–8 in index.php with the tokens you received from Step 5.
Step 8) Save the index.php file. Go to the index.php file in a web browser to check that the API tokens are working.
If you did receive an output, you are all good to continue to step 9. If you didn’t receive an output, recheck your tokens and consumer keys and make sure there are no spaces inside the quotation marks.
Step 9) Right now, we are running a search which finds tweets with the exact keyword “random” and are excluding retweets. This is shown on line 12 in the $searchfield variable.
NOTE: THE BELOW DOESN’T SEEM TO BE WORKING PROPERLY ANYMORE. YOU’LL NEED TO PLAY AROUND WITH THE QUERY A BIT TO MAKE IT WORK PROPERLY.
To create your own search, go to https://twitter.com/search-home and create your query. Once you’ve run the search, copy the end section of the URL in the browsers search bar. Only copy the section including and after the “?”. Do NOT include the “ https://twitter.com/search” text. Replace the current $searchfield value with this text.
Step 10) Go to the index.php file in a web browser to check that the query is working.
Step 11) If you look at line 13, the $getfield variable, I’ve specified certain parameters that should be looked at. More information here –
https://developer.twitter.com/en/docs/tweets/search/api-reference/get-search-tweets
&count=100
Currently, the Twitter Standard Search API only allows for a maximum of 100 tweets to be pulled for a query. Including this text ensures we get as many tweets as possible, rather than the default 15.
&result_type=mixed
The result_type parameter specifies which results to get. Because we can only get 100 results, we need to specify which results we want. There are 3 types of result_type option available: popular, recent, and mixed. Popular gets the most popular tweets that fit the criteria specified. Recent get the most recent tweets that fit the criteria specified. Mixed gets a mixture of popular and recent tweets that fit the criteria specified. For this example, I’ve chosen to use the mixed option.
tweet_mode=extended”
Now that Twitter allows people to post 280 characters instead of 140 characters, this parameter specifies that we want to see the entire tweet rather than a parsed 140 character version. Keeping this parameter is highly recommended.
Step 12) Making the results randomized and returning 1 result required 4 lines of code.
Line 19: shuffle($string[‘statuses’]); randomized the 100 results.
Line 20: $i=1 specifies a variable to count with.
Line 44: $i++ increments $i by 1.
Line 45: if($i == 2) break; specifies how many times we want the foreach loop to occur before it ends. If you want 1 result, it should be 2. If you want 5 results, it should be 4.
Step 13) Lines 21–43 specify which variables we want to echo and how we want to see them.
https://developer.twitter.com/en/docs/tweets/data-dictionary/overview/user-object has a full list of variables available to view.
Here is where you can remove variables, add new variables, and customize the HTML and CSS to make the variables look better on the page.
Step 14) You can change the button text at the very bottom. The button functions to reload the page and restarts the query, so you get a new set of tweets and in a different order.
Conclusion: You have now created a webpage to get a random tweet. Congratulations! Let me know if you have any questions.
