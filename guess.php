<!DOCTYPE html>
<html>
    <head>	
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        
        <meta name="authors" content="Kirtana Pathak, Vinh Do, Liem Budzien">
        <meta name="description" content="Guessing page">
        <meta name="keywords" content="drawing, telestrations">
           
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="styles/custom.css">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:100,300,400">
        
        <!--icon on tab of page-->
        <link rel="shortcut icon" href="images/favicon.png" type="image/ico" />
        <title>Telestrations</title>
    
    </head>
    
    <body>
    <?php session_start();
        ob_start();
        if(isset($_SESSION['username']))
        {  
    ?>
        <div id ="heading">
            <h3>
                Guess the drawing!
            </h3>
        </div>
        <div class="row">
            <div class="col-md-2">
                <h3>Time left:
                    <br><span id="time">1:00</span>
                </h3>
            </div>
            <div class="col-md-8">
                <div class="text-center">
                    <canvas id="myCanvas" width = "1000%" height="485%" style="border:0.01rem solid #000000;background-color: white;text-align:center">
                        Sorry, your browser does not support the canvas.
                    </canvas>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                    <input name="guess" class="form-control form-control-lg guessBox" type="text" placeholder="Enter your guess" autofocus>
            </div>    
            <div class="col-md-4"></div>
        </div>

        <div text-align="center">
            <button class="btn btn-success btn-game" onclick="window.location.href='results.php'">Done</button>    
        </form>
        <button class="btn btn-danger btn-game" onclick="window.location.href='lobby.php'">Exit Game</button>    
        </div>
        
        <?php 
            }
            else
                header('Location: login.php');
        ?>

        <?php
            if(isset($_POST['guess']))
            {
                $yourprompt = $_POST['guess'];
                $_SESSION['guess'] = $yourprompt;
                
                require('connect-db.php');
                          
                $query = "INSERT INTO drawings (imgURL, username, prompt, guess) VALUES (:imgURL, :username, :prompt, :guess)";
                $statement = $db->prepare($query);
                $statement->bindValue(':imgURL', $_SESSION["imgURL"]);
                $statement->bindValue(':username', $_SESSION["username"]);	
                $statement->bindValue(':prompt', $_SESSION["prompt"]);	
                $statement->bindValue(':guess', $_SESSION["guess"]);	
                $statement->execute();
                $statement->closeCursor();

                header('Location: results.php');
            }
        ?>
    </body>
    <script>
        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                timer--;
                if (timer == -1) {
                    window.location.href='results.php'
                }
            }, 1000);
        }

        window.onload = function () {
            var time = 59;
            display = document.querySelector('#time');
            startTimer(time, display);

            var ctx = document.getElementById('myCanvas').getContext('2d');
            var img = new Image();
            img.onload = function() {
                ctx.drawImage(img, 0, 0);
            };
            img.src='<?php echo $_SESSION["imgURL"]; ?>';
        };
    </script>
</html>