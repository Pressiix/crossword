<html>

<head>
    <script src="asset/bootstrap/jquery.min.js"></script>
</head>

<body>
    <h1 id="toptitle"></h1>
    <script>
        <?php session_start(); ?>
        $("#toptitle").text("Your score : <?= $_SESSION["score"] ?>");
        <?php
            $_SESSION["score"] = 0;
        ?>
    </script>
    <button onclick="location.href='index.php';">New Game</button>
</body>

</html>