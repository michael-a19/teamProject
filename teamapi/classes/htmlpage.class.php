<?php
/**
 * php class for defining and instantiating a webpage
 */
class HTMLPage {
    //private $recordSet; 
    private $type = 'HTML';
    private $page;
    private $pageContent; 
    private $pageStart;
    private $pageEnd;
    private $main; 
    protected $header; 
    private $css; 
    private $footer; 
    private $title;
    private $navBar;
    private $navItems;

    #define the top and bottom of the page
    #use case statement to check the page and define the content 

    /**
     * Constructor for the HTMLPage class
     * Takes the path and path array as arguements then decides on what page content to use based on the passed path
     * @param $path String : the path from the url after the basepath has been removed 
     * @param $pathArr array of String : an array of any additional path items after the path has been removed
     */
    public function __construct($path, $pathArr) {

        $navList = array('main'=>'main/', 'docs'=>'docs/', 'credits'=>'credits/');

        #must check the second arguement of pathArr the first is possibly the same as teh first
        #'''switch pageContent = ''';
        #chekc if the second element in the pathArr is empty, if so set path to main, if not set it to the value of pathArr[1]
        //$path; = (empty($pathArr[1])) ? "main" : $pathArr[1];
        #check if the third element in pathArr is set, if not set path to path if so join path and second element
       $path = (!empty($pathArr[1])) ? $path . '/' . $pathArr[1] : $path; // if 3rdelement is not empty join to first element if not just set it to itself 

       #decide what page content to load based on the value of $path
        switch ($path){
            case 'main':
                $this->mainPage();
                $this->title = "main";
                break;
            case 'docs':
                $this->docsPage();
                $this->title = "documentation";
                break;
            case 'credits':
                $this->creditsPage();
                $this->title = "credits";
                break;
            default: 
                $this->errorPage();
                $this->title = 'error';
                break; 
        }

        #make top page 
        
        $this->setCss();
        $this->createPageStart();
        $this->createNav($navList);
        $this->createMain();
        $this->createFooter(); 
        $this->createPageEnd();
        $this->createPage(); 

    }

    // need an add to body method when seperating out into classes

    private function createNav($navItems) {
        $this->setNav($navItems);
        $this->navBar = <<<NAV
        <nav>
            <ul> $this->navItems </ul>
        </nav>
NAV;
}
    private function setNav($navItems){
        $nav = "";
        foreach($navItems as $key => $value){
            $nav .= "<li><a href='".BASEPATH."$value'>$key</a><li>";
        }
        $this->navItems = $nav; 
        
    }


    
    private function createPageStart() {
        $this->pageStart = <<<START
        <!DOCTYPE html>
        <html lan="en">
            <header>
                <meta charset="utf-8" />
                <link rel="stylesheet" href="$this->css">
                <link rel="preconnect" href="https://fonts.gstatic.com">
                <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
                <title>$this->title</title>
            </header>
            <body>
                <div id='page_container'>
START;
}
    /**
     * Create the end of a html page
     */
    private function createPageEnd() {
        $this->pageEnd = <<<END
                </div>
            </body>
        </html>
END;
}

    private function createFooter() {
        $this->footer = <<<FOOTER
        <footer>
            <p> some text stuff here </p>
        </footer>
FOOTER;
}

    private function setCss() {
        $this->css = BASEPATH.CSSPATH; #global files
      }

    /**
     * Add the main content for the page
     */
    private function createPage() {
        $this->page .= $this->pageStart.$this->navBar.$this->pageContent.$this->footer.$this->pageEnd;

    }
    
    private function createMain() {
        $this->pageContent = <<<MAINCONTENT
        <main>
            $this->main
        </main>
MAINCONTENT;
}



    public function getType() {
        return $this->type;
    }

    public function getPage() {
        return $this->page; #make page
    }
    #needs moved into its own class
    private function mainPage(){
        $this->main = <<<MAIN
        <h1>Team Project and Professionalism</h1>
        <p>This website is part of the assessment for the team project and professional module at Northumbria University. This
        website implemenst a RESTful API and acts as the backend to the rest of our project.</p>
        <div class='devs'>
            <p>Developers:</p>
            <ul>
                <li>Cameron Brogden</li>
                <li>Katherine Boyfield</li>
                <li>Jack Crosthwaite</li>
                <li>Michael Anderson</li>
            </ul>
        </div>
MAIN;
       
}
    #needs moved into its own class
    private function docsPage(){
        $this->main = <<<DOCS
        <h1>API Documentation</h1>
        <p>This page describes the endpoints that this API makes available</p>
        <div id='endpoint'>
            <h2>www.website.com/api/</h2>
            <p>Description: endpoint description</p>
            <p>Type: POST</p>
            <p>Authentication Required</p>
            <p>Example</p>
            <ul>
                <li>www.website.com/api/</li>
            </ul>
        </div>
DOCS;
}
    private function creditsPage() {
        $this->main = <<<CREDITS
        <h1>Credits</h1>
         <p>write credit things here and stuff</p>
CREDITS;
}
    private function errorPage(){
       $this->main = <<<ERROR
       <h1>ERROR 404</h1>
        <p>Page not found</p>
ERROR;
}
}


?>