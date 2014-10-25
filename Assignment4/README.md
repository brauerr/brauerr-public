Ajax-Assignment
===============
In this assignment you will do two things. The first is to make a simple Ajax and Local Storage library that can easily be tested the other is to implement a custom weather display page that can remember the users configuration between visits.

General Information
-------------------

Your code should have the following directory structure. You will only put code in the src folder. When we write tests, you will put them in your tests folder. You will have more files than `studentlibrary.js` howevever, that is the file the autmotated tests will run on. It should have the functions from the `Library` section alond with any needed helper functions. All other code for the `Weather` portion also will go in the src directory, but will be put in files your create.

- Repository root
  - Assignment4
    - src
      - studentlibrary.js
    - tests

We will test using gjslint however, the following changes will be made. We will use the `--nojsdoc` flag so you will not get warnings about using JSDoc formatting to document your code (but it is good practice, so feel free to do so if you want to).

Google Style Guide conflicts with JSLint on one point. JSLint wants a space between `function` and `()` when declaring anonymous functions. Google Style Guide does not permit that space. Last week we ignored this difference. For this week there can be no space betwen them. This is correct `function(foo, bar)`. This is not `function (foo, bar)`.

When you are done, the final commit should have the exact commit message 'Assignment 4 final commit.'. We will look for the code assoicated with this commit when grading. The code provided in that commit must exactly match the public hosted code that students will use to test your weather application.

Getting an A
------------
To be eligible to get an A you must complete the tests of 3 other students pages. It will not get you any extra points to do so, but if you do not do so your grade will be capped at a B for the assignment.

Library
-------
The main Ajax function in your library should be a function called `ajaxRequest(URL, Type, Parameters)`. The URL is the base URL that the request will be made to (eg. http://foo.com/page.php). Type will be either the string 'POST' or 'GET' depending on if it is a POST or GET request. Parameters will be an object containing pairs of strings that are key value pairs. So the object literal `{'name':'sally','profession':'doctor'}` would be a valid input parameter `{'doStuff':function (){...}}` would not be legal because a function is not a string. Likewise `{'item':'book','count':50}` would not be legal because 50 is not a string.

This function should return an object of the following format:
```
{
'success': boolean,
'code': string,
'codeDetail': string,
'response': string
}
```
The `success` property should be true if the response had a code corresponding with [success](http://en.wikipedia.org/wiki/List_of_HTTP_status_codes#2xx_Success) and false otherwise. `code` should hold the response code. `codeDetail` should hold the text associated with the particular status code. `response` should be the string representation of the response received from the server.

If the type is a `POST` request the data should be sent as 'application/x-www-form-urlencoded'. This involves setting the content type in the header. An example is given in the Getting Started with Ajax reading on [developer.mozilla.org](https://developer.mozilla.org/en-US/docs/AJAX/Getting_Started#Step_5_.E2.80.93_Working_with_data).

If the type is a `GET` then the proper URL string should be created and used within your function. The key value pairings coming from the URL and Parameter inputs.

The local storage portion of the library will be a simple test to determine if local storage works. This should be a function called `localStorageExists()`. It will return a boolean. If you are able to write and read from local storage, it should return true, otherwise it should return false.

Automated tests will follow later in the week.

Weather
-------
This portion of the assignment is less specific in its requirements. It will use [OpenWeatherMap](http://openweathermap.org/api) as a source of data. Your requirement is to let the user input a city and state as well as select from a number of weather measurements to display. Users can save their settings and when they return they should be automatically loaded. It will be graded using a manual testing protocol by 3 of your classmates. (So that means you will also be manually testing 3 other students sites) Refer to the testing protocol below to see what exact functionality you need.

Weather Testing Protocol
------------------------
- Does the page have a text input for a city name?
- Does the page have a drop down input to select a state?
- Does the drop down have at least the states WA, OR and CA?
- Does the page have the following check boxes?
  - Wind
  - Max Temperature
  - Min Temperature
  - Current temperature
- Does the page have a button labeled 'Save Settings'?
- Does the page have a button labeled 'Get Weather'?
- Input Corvallis into the city text box and select OR from the state drop down.
- Check the wind, current temperature, max temperature and min temperature boxes.
- Click save settings.
- Open a new tab in your browser and navigate back to the page.
- Is the City field populated with Corvallis?
- Is the State field populated with OR?
- Are all the check boxes still checked?
- Uncheck max temperature
- Click 'Save Settings'
- Open a new tab and navigate back to the page
- Are all the settings as before the same except that max temperature is still unchecked?
- Click 'Get Weather'
- Does a list of weather appear on the page containing the following information?
  - City
  - State
  - Current wind direction and speed
  - Minimum temperature
  - Current temperature
- Open (OpenWeatherMap Corvallis)[http://openweathermap.org/city/5720727]
  - Does the weather listed seem to match the information provided on OpenWeatherMap?
- Input 'Seattle' into the city selection.
- Select 'WA' from the drop down.
  - Uncheck everything but wind
- Click 'Get Weather'
  - Has the list been replaced by a new list?
  - Does the new list contain the following?
    - City
    - State
    - Current wind direction and speed
  - Do those values seem to match the values at (OpenWeatherMap Seattle)[http://openweathermap.org/city/5809844]?
