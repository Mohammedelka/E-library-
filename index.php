<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-library</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="books.css">
    <script src="index.js" defer></script>
    <style>
    @import url("https://fonts.googleapis.com/css?family=Josefin+Sans:400,400i,600,600i");

    .homediv {
        grid-area: 2/1/3/3;
        justify-content: center;
        display: flex;
        align-items: center;
        flex-direction: column;

    }

    .homecontent {
        width: 50%;
        height: 100px;
        border-radius: 20px;
        background: rgba(27, 32, 48, 1);
        color: white;
        justify-content: center;
        display: flex;
        align-items: center;
        font-size: 50px;
        margin: 30px;
        font-family: "Josefin Sans", sans-serif;


    }

    #liblogo {
        margin: 0;
        padding: 0;
        overflow: hidden;
        width: 60px;
        margin-left: 10px;
        font-family: sans-serif;
    }

    .secondhome {
        width: 35%;
        transition: all 0.3s ease-out;
    }

    .secondhome:hover {
        color: rgba(27, 32, 48, 1);
        background: white;
        transform: scale(1.1);
        cursor: pointer;
    }
    </style>
</head>

<body>
    <div class="header">
        <div class="sides">
            <a href="" id="liblogo" class="item"><img src="./images/lib.jpg" width="100%" height="150%"></a>
            <a href="#" class="item">BLOG</a>
            <a href="#" class="item">BLOG</a>
            <a href="#" class="item">BLOG</a>
            <a href="#" class="item">BLOG</a>
            <a href="#" class="item">BLOG</a>
        </div>
    </div>
    <div class="homediv">
        <div class="homecontent"> Welcome to Our E-Library</div>
        <a href="login_signup.php" class="homecontent secondhome">
            <div> Login/Sign Up</div>
        </a>

    </div>
</body>

</html>