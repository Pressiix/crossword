<html>

<head>
    <link rel="stylesheet" href="asset/bootstrap/bootstrap.min.css">
    <script src="asset/bootstrap/jquery.min.js"></script>
    <script src="asset/bootstrap/popper.min.js"></script>
    <script src="asset/bootstrap/bootstrap.min.js"></script>
</head>

<body>
    <div class="container-fluid text-center">
    <br/><h1 id="toptitle"></h1><br/>
    <script>
        <?php session_start(); ?>
        $("#toptitle").text("Your score : <?= $_SESSION["score"] ?>");
        <?php
            $_SESSION["score"] = 0;
        ?>
    </script>
    <button class="btn btn-primary" onclick="location.href='index.php';">New Game</button>
    </div>
</body>

</html>