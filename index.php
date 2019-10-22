<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Crossword</title>
    <link rel="stylesheet" href="asset/css/main.css">
    <link rel="stylesheet" href="asset/bootstrap/bootstrap.min.css">
    <script src="asset/bootstrap/jquery.min.js"></script>
    <script src="asset/bootstrap/popper.min.js"></script>
    <script src="asset/bootstrap/bootstrap.min.js"></script>
    <script>
        window.console = window.console || function(t) {};
    </script>
    <script>
        if (document.location.search.match(/type=embed/gi)) {
            window.parent.postMessage("resize", "*");
        }
    </script>
</head>

<body translate="no">
    <div class="container-fluid ">
        <h1>Crossword </h1>
        <div class="timer">
            <label style="font-weight:bold;">score : </label>
            <label id="score"></label> &nbsp <label style="font-weight:bold;">Timer </label>
            <span id="time"></span>
        </div>
        <div class="row">
            <div id="crossword" class="col-md-5 d-flex justify-content-center"></div>

            <div class="col-md-7" id="right-box">
                <table id="clues">
                    <thead>
                        <tr>
                            <th>Horizontal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <ul id="across"></ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table id="clues">
                    <thead>
                        <tr>
                            <th>Vertical</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <ul id="down"></ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!--<button id="check-answer" class="btn btn-success" onclick="checkAnswer()">Check answer</button>-->
                <button id="reset" class="btn btn-primary" onclick="location.reload()">New Game</button>
                <button id="show-answer" class="btn btn-warning" onclick="showAnswer()">Show</button>
            </div>
        </div>
        <div id='div_session_write'> </div>

        <script src="asset/js/stopExecutionOnTimeout.js"></script>
        <script src="asset/js/crossword-gen.js?v=0.5"></script>
        <script id="rendered-js">
            /** WORDS */
            var words = ["sushi", "html", "javascript", "flash", "css", "puzzle", "cat", "elephant"];
            /** MEANINGS */
            var meannings = [
                "A Japanese dish consisting of small balls or rolls of vinegar-flavored cold cooked rice served with a garnish of raw fish, vegetables, or egg.",
                "The standard markup language for Web pages",
                "A scripting languages, primarily used on the Web. It is used to enhance HTML pages and is commonly found embedded in HTML code",
                "A sudden burst of light or of something shiny or bright. ",
                "Defining the style of Web pages",
                "A game, problem, or toy that tests a person's ingenuity or knowledge.",
                "A small domesticated carnivorous mammal with soft fur and short snout",
                "A heavy plant-eating mammal with a prehensile trunk"
            ];
            var cw = new Crossword(words, meannings);
            var tries = 15;
            var grid = cw.getSquareGrid(tries);
            
            var current_score = 0;
            var max_score = gridCount(grid);
            var Minutes = 1; //Set a default minute
            
            

            /**
             *   Action when a screen loads
             */
            $(document).ready(function(){
                /**
                 *   Countdown Timer
                 ***************************************/
                
                startTimer(Minutes)

                setTimeout(function(Minutes) {
                    checkAnswer();
                    location.href = "show_score.php";
                }, Minutes * 60000); //countdown with millisecond
                /**************************************/

                var data = [];
                var grid_attr = [];
                var grid_root = '';
                var grid_char = '';

                // report a problem with the words in the crossword
                if (grid == null) {
                    var bad_words = cw.getBadWords();
                    var str = [];
                    for (var i = 0; i < bad_words.length; i++) {
                        if (window.CP.shouldStopExecution(0)) break;
                        str.push(bad_words[i].word);
                    }
                    window.CP.exitedLoop(0);
                    alert("A grid could not be created with these words:\n" + str.join("\n") + '. Please change it.');
                    return;
                }

                /** 
                 * Show current score and maximum score
                 * gridCount() function will return the maximum score 
                 */
                $("#score").text(current_score + "/" + max_score);

                // turn the crossword grid into HTML
                var show_answers = false;
                document.getElementById("crossword").innerHTML = CrosswordUtils.toHtml(grid, show_answers);

                // make a nice legend for the clues
                var legend = cw.getLegend(grid);
                addLegendToPage(legend);
            });

            function gridCount(grid) {
                var arr_count = 0;
                for (var i = 0; i < grid.length; i++) {
                    for (var j = 0; j < grid[i].length; j++) {
                        if (grid[i][j] !== null) {
                            arr_count++;
                        }
                    }
                }
                return arr_count;
            }


            function addLegendToPage(groups) {
                for (var k in groups) {
                    var html = [];
                    for (var i = 0; i < groups[k].length; i++) {
                        if (window.CP.shouldStopExecution(1)) break;
                        html.push("<strong>(" + groups[k][i]['position'] + ")</strong> " + groups[k][i]['clue'] + "<br/><br/>");
                    }
                    window.CP.exitedLoop(1);
                    document.getElementById(k).innerHTML = html.join("\n");
                }
            }

            function checkAnswer() {
                var answers = document.getElementsByTagName('input');
                var data = [];
                var data_answer = '';
                var position = '';
                for (var i = 0; i < answers.length; i++) {

                    if (window.CP.shouldStopExecution(2)) {
                        break;
                    }

                    data_answer = answers[i].getAttribute("data-answer").toLowerCase();

                    if (answers[i].value.toLowerCase() !== data_answer) {
                        answers[i].parentElement.classList.remove("passed");
                        answers[i].parentElement.className += ' error';
                    } else {
                        answers[i].parentElement.classList.remove("error");
                        answers[i].parentElement.className += ' passed';
                        current_score++;
                    }
                }
                window.CP.exitedLoop(2);
                $("#score").text(current_score + "/" + max_score);
                $('#div_session_write').load("_session.php?last_score="+current_score);
                current_score = 0;
            }

            //Show Answer Button
            function showAnswer() {
                var answers = document.getElementsByTagName('input');
                var inputVal = '';
                var position = '';
                for (var i = 0; i < answers.length; i++) {

                    if (window.CP.shouldStopExecution(2)) {
                        break;
                    }

                    inputVal = answers[i].getAttribute("data-answer").toUpperCase();
                    position = answers[i].getAttribute("position");
                    $("input[position='" + position + "']").attr("placeholder",inputVal);
                    $("input[position='" + position + "']").attr("disabled", "disabled");
                    $("td[title='" + position + "']").css("border", "solid grey");
                    //$("button[id='check-answer']").attr("disabled", "disabled");
                    $("button[id='show-answer']").attr("disabled", "disabled");
                    answers[i].parentElement.classList.remove("error");
                    answers[i].parentElement.classList.remove("passed");
                    answers[i].parentElement.className += ' show-answer';
                }
                window.CP.exitedLoop(2);
                checkAnswer();
                $("#score").text(current_score + "/" + max_score);
                $('#div_session_write').load("_session.php?last_score=0");
            }

            function startTimer(duration) {
                var start_minutes = duration, //Show timer = 30 Minutes
                    display = $('#time');
                //display.text(String("0" + start_minutes).slice(-2) + ":00");
                start_minutes = start_minutes * 60;

                var timer = start_minutes,
                    minutes, seconds;
                setInterval(function() {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.text(minutes + ":" + seconds);

                    if (--timer < 0) {
                        timer = start_minutes;
                    }
                }, 1000);
            }

        </script>
    </div>
</body>

</html>