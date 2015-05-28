## Pathway PHP web application

##Files Structure:

#Pathway
->app
   ->Http
      ->Controllers
          ->Auth
              AuthController #PHP code to handle user authontication
              PasswordController #PHP code for password management

          ->Requests
               #php code for form validation

            AnalysisController #PHP code for analysis part for all 4 analysis
            GuestController    #PHP code for GUEST PAGE
            HomeController     #PHP code for Home page of user
            PreviousAnalysis   #PHP code for user history analysis listing
            ProfileController  #PHP code to get the user details
            WelcomeController  #PHP code for Welcome page  "/"(default) page

        Routes.php  #Routing for pages URL to PHP controller binding is done here

->bootstrap

->config

->database

->public    #Contans all public file here images, CSS, Javascript and R file
    ->all   #contains the analysis generated files and image
    ->css   #style sheets for web
    ->fonts #fonts files
    ->image
    ->js    #javascript file for the web

      biocStatsimport.sh       #script for importing the data from bioc page for the pathway library analysis
      my_Rscript.R             #contains R code
      dump.sql                 #sql dump of the table scheama and sample data to start the applicaiton

->resources
    -> views
        ->analysis
             example_one   #html/php for example one analysis
             example_two   #html/php for example two analysis
             example_three #html/php for example three analysis
             NewAnalysis   #html/php for NewAnalysis analysis
             Result        #html/php for Result analysis
             viewer        #html/php for view the image generated upon analysis
        ->auth  #authorization related php/html code
        ->emails
        ->profile
            anal_hist     #user analysis histroy page
            user          #user home page
            user_anal     #user analysis listing page
            user_edit     #profile edit page
        ->vendor
        about     #about page
        app       #header page for each page in the app
        footer    #footer generic
        guest     #guest page
        home      #guest home page
        navigation #profile left side navigation generic
        tutorial   #help page
        welcome    #welcome page
->storage  #temperory laravel folder for storing the logs and other temperory files
->tests    #laravel test cases writing and asserting
->vendor   #laravel base
