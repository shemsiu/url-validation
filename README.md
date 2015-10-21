## Overview

I have made my own URL validator and it seems to work pretty good. The var_filter("url", FILTER_VALIDATE_URL) only validate if the url syntax is correct.

My URL validator also checks wether top level domain is correct, have the user remembered to enter www and if you required then it checks wether the user have typed https:// instead http://.

Have fun.

## Example
You don't need to configurate anything in the URL Class - just work with it.

Remember that the URL class is in the namespace "Shemsiu" and you need to use it like this: "use Shemsiu\URL;".


When you are going to run the validator just type this in one line:

```php
if(!(new URL("http://www.google.com", false, true, true))->validate()) {
	//Error
} else {
	//Passed
}
```

## Parameters
First parameter: The URL to validate

Second parameter: If true: then check if the user type https://. If false -> skip it.

Third parameter: If true: Check if the user type www after http:// or after https://. If false -> skip it.

Fourth parameter: If true: Check if the user has typed corretcly top level domain like .com. If false -> skip it.


## Default parameter value:

Second parameter: false

Third parameter: true

Fourth parameter: true


## Retrieve error messages

You can retrieve an array with error messages:
```php
foreach(URL::$error_message as $error) print $error;
```