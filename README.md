WritePath Translation API (PHP, Java)
==============================

With the WritePath Translation API and Proofreading API, developers can easily access over 10.000 translators & editors and more than 120 different language pairs and integrate into their own system on demand. For details check the <a href="https://www.writepath.co/en/developers">PHP translation API</a> documentation.
It takes care of authorization, JSON encoding and decoding and it can do a few more very convenient things.

New: A Java translation API client is available upon request

<h2>Getting started:</h2>
<a href="https://www.writepath.co/en/signup">Sign up</a> at writepath.co and request your API keys.

<h2>Methods:</h2>
<ul>
  <li>Get word count of plain text (POST)</li>
  <li>Get word count of document (POST)</li>
  <li>Post a job with plain text (POST)</li>
  <li>Post a job with document (POST)</li>
  <li>Fetch job status (POST)</li>
  <li>Fetch current word balance of your account (POST)</li>
  <li>Post a comment (POST)</li>
</ul>

A list of supported languages and categories is available <a href="https://www.writepath.co/en/developers/languages">here</a>.

<h2>Callbacks:</h2>

Callbacks are automatic notifications which are sent to your notification URL (which you specify with your post job or post plain text method). Callbacks are available for the following events:

<ul>
  <li>When a job has been finished</li>
  <li>When translator or editor sends a comment</li>
</ul>

<h2>Example:</h2>

Include the file WritePathClient.php, set your API keys and call the respective method:

<pre>
include 'WritePathClient.php';

$api_key = '12345678';
$private_key = 'ABCDEFGH';

$service = '2'; // i.e. Standard Translation
$langID = '5'; // i.e. English - Chinese (Simplified)
$text = 'My very long text';

$WritePathClient = new WritePathClient($api_key, $private_key);
$reply = $WritePathClient->getWordCountPlainText($service, $langID, $text);
</pre>

We use JSON. Every response looks like this:

<pre>
{
  "response": {

  },
  "opStatus": "ok"
}
</pre>


If something has gone wrong, the response looks like this:

<pre>
{
  "response": {
    "errorCode": "4001",
    "errorMessage": "Something has gone wrong"
  },
  "opStatus": "error"
}
</pre>

Detailed documentation for all methods and callbacks is available <a href="https://www.writepath.co/en/developers">here</a>.
