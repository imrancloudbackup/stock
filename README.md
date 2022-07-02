Good Morning YOKO and Team,

This project is created by using the CORE PHP.

So Before running the project make sure you are installed the below softwares on your system:

1. WAMP OR XAMPP SERVER

CONFIGURATION
=============

1. UNZIP THE PROJECT FILE AND COPY THE PROJECT FOLDER AND PASTE IT ON THE ROOT FOLDER EX:WAMP/WWW/ --> PASTE HERE
2. CREATE A NEW DATABASE WITH THE NAME OF test_market in PHPMYADMIN
3. FIND THE .SQL FILE FROM THE PROJECT DIRECTORY AND IMPORT IT ON THE test_market DATABASE
4. CHANGE YOUR USERNAME , PASSWORD, DATABASE NAME, BASEURL IN THE dbconfig.php file
5. NOW TURN ON THE WAMP SERVER IN YOUR PC AND RUN THE PROJECT EX: LOCALHOST/STOCK
6. CREATE A NEW ACCOUNT BY USING SIGNUP LINK
7. LOGIN YOURSELF WITH THE USERNAME AND PASSWORD
8. FOR NOW THE ROLE WILL BE AUTOMATICALLY ALLOCATED WHILE CREATING THE ACCOUNT
9. MAY BE IN FUTURE IF WE NEED ROLE ALLOCATION THEN WE WILL ADD.
10. IF LOGIN IS SUCCESS THEN THE PAGE WILL REDIRECT YOU TO THE DASHBOARD PAGE(STOCK LIST).
11. GO TO THE UPLOAD STOCK PAGE AND UPLOAD YOUR CSV FILE
12. ONCE UPLOADED THE STOCK LIST WILL START VISIBLE ON THE DASHBOARD PAGE
13  CHECK THE MEAN STOCK AND STANDARD DEVIATION PAGES
14. BUY AND SELL STOCKS BY USING THE BUY AND SELL BUTTON
15. ONLY ONE PURCHASE IS ALLOWED PER DAY EITHER BUY OR SELL
16. THE BUY AND SELL STOCK SHOW YOU THE BALANCE STOCK AND BALANCE MONEY YOU HAVE IN YOUR ACCOUNT
17. IF YOU ARE NOT ABLE TO BUY BECAUSE OF LOW BALANCE THEN GO TO THE TOP RIGHT AND CLICK THE PROFILE ICON AND CLICK MY PROFILE 
18. IN MY PROFILE YOU ARE ABLE TO SEE YOUR ACCOUNT DETAILS, YOUR PURCHASE HISTORY, YOUR SELL HISTORY, YOUR PROFIT AND WALLET.
19. IN WALLET YOU CAN ADD AMOUNT AND AFTER ADDING THE AMOUNT YOU CAN START PURCHASE THE STOCKS.
20. IN THE DASHBOARD USING THE FILTERS TO GET THE BEST DATE OF BUY AND SELL STOCKS TO MINIMIZE YOUR LOSS OR MAXIMIZE YOUR PROFIT
21. FIND THE RESET_CRON.PHP FILE IN DIRECTORY AND PLACE IT ON YOUR WAMP. PLEASE CHECK THE FOLLOWING LINK: https://magento.stackexchange.com/questions/222766/how-can-i-setup-cron-job-in-wamp-windows
22. THIS CRON WILL HELP YOU TO RESET THE TODAY PURCHASE. SO THAT YOU ARE ABLE TO BUY OR SELL THE STOCK ON NEXT DAY.



==================================================================OR============================================================

IF YOU ARE USING LIVE SERVER

1. UNZIP THE PROJECT FILE AND COPY THE PROJECT FOLDER AND PASTE IT ON THE ROOT FOLDER 
2. CREATE A NEW DATABASE WITH THE NAME OF test_market in PHPMYADMIN
3. FIND THE .SQL FILE FROM THE PROJECT DIRECTORY AND IMPORT IT ON THE test_market DATABASE
4. CHANGE YOUR USERNAME , PASSWORD, DATABASE NAME, BASEURL IN THE dbconfig.php file
5. NOW RUN THE PROJECT IN THE BROWSER 
6. CREATE A NEW ACCOUNT BY USING SIGNUP LINK
7. LOGIN YOURSELF WITH THE USERNAME AND PASSWORD
8. FOR NOW THE ROLE WILL BE AUTOMATICALLY ALLOCATED WHILE CREATING THE ACCOUNT
9. MAY BE IN FUTURE IF WE NEED ROLE ALLOCATION THEN WE WILL ADD.
10. IF LOGIN IS SUCCESS THEN THE PAGE WILL REDIRECT YOU TO THE DASHBOARD PAGE(STOCK LIST).
11. GO TO THE UPLOAD STOCK PAGE AND UPLOAD YOUR CSV FILE
12. ONCE UPLOADED THE STOCK LIST WILL START VISIBLE ON THE DASHBOARD PAGE
13  CHECK THE MEAN STOCK AND STANDARD DEVIATION PAGES
14. BUY AND SELL STOCKS BY USING THE BUY AND SELL BUTTON
15. ONLY ONE PURCHASE IS ALLOWED PER DAY EITHER BUY OR SELL
16. THE BUY AND SELL STOCK SHOW YOU THE BALANCE STOCK AND BALANCE MONEY YOU HAVE IN YOUR ACCOUNT
17. IF YOU ARE NOT ABLE TO BUY BECAUSE OF LOW BALANCE THEN GO TO THE TOP RIGHT AND CLICK THE PROFILE ICON AND CLICK MY PROFILE 
18. IN MY PROFILE YOU ARE ABLE TO SEE YOUR ACCOUNT DETAILS, YOUR PURCHASE HISTORY, YOUR SELL HISTORY, YOUR PROFIT AND WALLET.
19. IN WALLET YOU CAN ADD AMOUNT AND AFTER ADDING THE AMOUNT YOU CAN START PURCHASE THE STOCKS.
20. IN THE DASHBOARD USING THE FILTERS TO GET THE BEST DATE OF BUY AND SELL STOCKS TO MINIMIZE YOUR LOSS OR MAXIMIZE YOUR PROFIT
21. FIND THE RESET_CRON.PHP FILE IN DIRECTORY AND PLACE IT ON YOUR CRON JOB. 
22. THIS CRON WILL HELP YOU TO RESET THE TODAY PURCHASE. SO THAT YOU ARE ABLE TO BUY OR SELL THE STOCK ON NEXT DAY. 

================================================================================================================================

NEW UPDATE 02 JULY 2022

=======================

SHOWING BEST DATE TO BUY AND SELL IS UPDATED.

CHECKSTOCKS.PHP HAVING DETAILED CODE.

CHECKSTOCKS1.PHP HAVING LESS CODE.

IF YOU WANT TO USE THE CHECKSTOCKS1.PHP THEN CHANGE THE PHPFILES/CHECKSTOCKS.PHP TO PHPFILES/CHECKSTOCK1.PHP IN FILTERFORM ON SUBMIT FUNCTION IN THE DASHBOARD PAGE.

WHEN YOU ENTER THE FIRST TIME TO THIS PROJECT THAT TIME IT WILL SHOW THE MESSAGE "NO STOCK AVAILABLE. PLEASE UPLOAD THE STOCK (CLICK THIS TO REDIRECT TO UPLOAD STOCK PAGE)".






PLEASE LET ME KNOW IF YOU ARE HAVING ANY TROUBLE WHILE RUNNING THE PROJECT


HAVE A NICE DAY

THANK YOU
