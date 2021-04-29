<?php

    //delete these 
    //have to change it so can set own stlye sheets 
    function startPage($title){
        //? put into its own file or not??? just focus on styling of that section should be fine. 
        $pageStart  = <<<PAGE
            <!DOCTYPE HTML> 
            <html>
                <head>
                    <title>$title</title>
                    <link href='/login/chat/styles/style.css' rel='stylesheet'>
                    <link href='/login/styles/style.css' rel='stylesheet'>
                    <link rel="preconnect" href="https://fonts.gstatic.com">
                <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
                </head>
            <body>
PAGE;
        return $pageStart;
    }
    function endPage(){
        $pageEnd = <<<END
                </body>
            </html>
END;
        return $pageEnd;
    }

?>