<?php

require 'apiInterface.php';

/**
* 
*/
class Api implements ApiInterface
{

    public $url;

    private $token;

    public $data;

    private $res;
    
    function __construct($url,$data)
    {
        $this->url = $url;
        $this->data = $data;
        $this->preSubmit();
        $this->submitPayload();
    }


    public function submitPayload()
    {

        try{
            $context_options = array (
            'http' => array (
                'method' => 'POST',
                'header'=> "Content-type: application/json\r\n"
                    //. "Content-Length: " . strlen($data) . "\n"
                    //. "Accept: application/x-www-form-urlencoded\r\n"
                    . "Authorization: Bearer ".$this->token . "\r\n",
                'content' => $this->data
                )
            );

            $context = stream_context_create($context_options); 
            $this->res = file_get_contents($this->url, false, $context);
            echo $this->res;
        }
        catch (\Exception $t) {
            //To catch any error, assuming on php7
            echo $t->getMessage()."\n";

        }
        
    }

    public function preSubmit()
    {
        $context_options = array (
        'http' => array (
            'method' => 'OPTIONS'
            )
        );

        $context = stream_context_create($context_options); 
        $this->token = file_get_contents($this->url, false, $context);
    }
}


if(isset($_REQUEST) && $_REQUEST['submitted'] == 'submitted'){
    $url = $_REQUEST['url'];
    $data = $_REQUEST['payload'];
    $cls = new Api($url, $data);
}



?>

<!DOCTYPE html>
    <html>

    <body>
    <center>
    <h2>API</h2>
    
        <form action="/api.php">
          <label for="url">URL:</label><br>
          <input type="text" id="url" name="url" value="https://www.coredna.com/assessment-endpoint.php"><br><br>
          <label for="payload">Payload:</label><br>
          <textarea id="payload" name="payload" ></textarea> <br><br>
          <input type="hidden" name="submitted" value="submitted">
          <input type="submit" value="Submit">
        </form> 
    </center>

    

    </body>
    </html>