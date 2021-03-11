<?php
/**
* Router class to determine the type of page based on an HTTP request 
* 
*
*/
class Router {
    private $page; 
    private $type;

    public function __construct($recordSet) {
        //echo("working");

        #get the current url 
        $url = $_SERVER["REQUEST_URI"];
        $path = parse_url($url)['path']; #parse url splits the url up into an array of its different parts
        
        #remove the basepath from the url leaving just the path e.g. www.example.com/students will be /students as www.example.com is the basepath defined in the config.ini file and the constant in config.php
        $path = str_replace(BASEPATH, "", $path); 
        #array of path seperate based on the / so after removing basepath we will have /students we split this based on the / leaving just students, if the left url was /students/classes 
        #the resulting array here will be 'students' and 'classes' 
        $pathArr = explode('/', $path);
        #if pathArr first element is empty set path to main, if not set path equal to the first element in patharr 
        $path = (empty($pathArr[0])) ? "main" : $pathArr[0]; 

        //when requesting data from the api the path will be www.example.com/api/whateverDataWeWant so the pathArr[0] will be 'api' we check for that here and call the appropriate method 
        ($path == 'api') ? $this->apiRoute($pathArr, $recordSet) : $this->htmlRoute($path, $pathArr); #if path is api then call apiRoute else call htmlRoute
        //if the first one is not api then go to html so pass path and patharr to html
        #don't need to pass $path to html
    }

    /**
     * Passes the path array to create a new jsonpage
     */
    public function apiRoute($pathArr, $recordSet) {
        $this->page = new JSONPage($pathArr, $recordSet);
        #$this->type = 'JSON';
       //echo("json called");
    }

    public function htmlRoute($path, $pathArr){
        //todo create the part to create an html page, will be important to create some api documentation. 
        #$ini['routes'] = parse_ini_file("config/routes.ini", true); #get the stuff in that ini file (page routes) 
  # $pageInfo = isset($path, $ini['routes'][$path]) #check if both set
  # ? $ini['routes'][$path] #if path is set and that exists in the array of route shit from ini file then set to that path 
   #: $ini['routes']['error']; #if not set to error, means no empty handling any more? 
 
#bottom part only 
        $this->page = new HTMLPage($path, $pathArr);

       # $this->page = new WebPageWithNav($pageInfo['title'], $pageInfo['heading1'], $pageInfo['footer']);
  #$this->page->addToBody($pageInfo['text']);
    }

    /**
     * return the type of the defined page, either json or html S
     */
    public function getType() {
        return $this->page->getType();
    }
    //return the page data from the created page
    public function getPage() {
        return $this->page->getPage(); 
    }
}
    
?>