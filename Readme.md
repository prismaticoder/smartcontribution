# THE PAYDAY APP

The PayDay App is a web application designed for use in small scale cooperative societies in keeping track of records and accounts.

### STEPS FOR REPRODUCING THIS PROJECT LOCALLY

1. ###### DOWNLOAD XAMPP
  - If you haven't already, download and install the latest version of [XAMPP](https://www.apachefriends.org/download.html), then proceed to start your local server from the XAMPP Control Panel.
  - After cloning this directory, place it in the `C://xampp/htdocs/` directory on your local PC

2. ###### IMPORT THE SQL FILE
  - Go to `localhost/phpmyadmin` on your browser, create an empty database with the name `smart_contribution` and import the `smart_contribution.sql` file into the database.
  - The database should contain the following tables:
  `````
  contributions
  loans
  logs
  main_customers
  months
  transactions
  roles
  users
  years
  zone
  `````
3. ###### RUN THE APPLICATION
  - Go to `localhost/smartcontribution` on your browser and you can log in with `user1` as your username and `superadmin` as your password.
  - Dummy customers and users have been added to the database, so you could play around with them and remove them later if you so choose
4. ###### HAVE FUN!
  Have fun working with the app, it's also available for all contributions and improvements
  
Happy Coding,
 
Jesutomiwa :)
