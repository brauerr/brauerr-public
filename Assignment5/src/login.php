//login.php

/*
login.php should have a form where a user can enter a username. 
It should have a button that says "Login". 
Upon clicking the login button the page should POST the username to the page content.php. 
The username should be posted via a parameter called username. 
If the username is an empty string or null, content.php should display the message "A username must be entered. 
Click here to return to the login screen.". 
The text 'here' should be a link that links back to login.php. 
If the username is any other string it should display the text 
"Hello [username] you have visited this page [n] times before. Click here to logout.". 
n should display 0 on the first visit, 1 on the 2nd and so on. 
The text 'here' should log the user out, destroying the session, and return them to the login screen.

If a user tries to access the page without going through the login page 
(and did not previously go through the login page to start a session) 
content.php should simply redirect them back to login.php. 
There are different ways to accomplish this. S
One might be to set a variable when a session is started the 'correct' way and check if that variable exists when loading the page.
*/