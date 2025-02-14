<?php



use App\https\HttpRequest;


class Request {

    private $path;
    private $action;
    private $params=[];
    private $request;



    public function __construct(string $path, string $action)
    {
        
        $this->request = new HttpRequest();


        $this->path= trim($path,'/');
        $this->action = $action;
        
    }

    public function getPath(){

        return $this->path;
    }

    public function match($url){
       
        $path = preg_replace('#({[\w]+})#','([^/])',$this->path);
        
        $pathToMatch = "#^$path$#";

        

        if(preg_match($pathToMatch,$url,$results)){
            
            array_shift($results);
            $this->params = $results;
            

            return true;
        }else{
            return false;
        }
        
    }

    public function execute(){
       
        


        $action = explode('@',$this->action);
        $controller = $action[0];
        $controller = new $controller();
        
        
        $method = $action[1];

        if($_SERVER['REQUEST_METHOD']==='GET'){
            
            return isset($this->params) ? $controller->$method(implode($this->params)) : $controller->$method();
        }else
        {
            return isset($this->params) ? $controller->$method($this->request,implode($this->params)) : $controller->$method($this->request);

        }

    }


}