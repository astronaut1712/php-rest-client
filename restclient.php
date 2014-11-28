<?php
/**
 * RestClient class
 *
 * @package default
 * @author
 **/
class RestClient{

    var $_url,
        $_user_agent,
        $_req_headers,
        $_res_headers,
        $_res_body,
        $_http_status;

    var $_options = array(
            'url' => 'http://nxquang.com',
            'proxy' => '',
            'method' => 'GET',
            'headers' => [],
            'data' => ''
        );

    function __construct($options = array()) {
        foreach ($options as $key => $value) {
            $this->_options[$key] = $value;
        }
        $this->parseOptions($this->_options);
    }

    protected function parseOptions($options){
        $this->_url = $options["url"];
        $this->setHeaders($options["headers"]);
    }

    function execute($value=''){
        $this->request($this->_options["method"],$value|$this->_options['data']);
    }

    function get($value=''){
        $this->request();
    }

    function post($value=''){
        $this->request("POST", $value|$this->_options['data']);
    }

    function put($value=''){
        $this->request("PUT");
    }

    function delete($value=''){
        $this->request("DELETE");
    }

    function options($value=''){
        $this->request("OPTIONS");
    }

    protected function request($method = 'GET', $data=''){
        $ch = curl_init($this->_url);
        if (strtoupper($method) != "GET") {
            switch (strtoupper($method)) {
                case 'POST':
                    curl_setopt($ch, CURLOPT_POST, 1);
                    break;
                case 'PUT':
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($ch, CURLOPT_PUT, 1);
                    break;
                default:
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                    #$this->putHeader("X-HTTP-Method-Override: ".$method);
                    break;
            }
            if($data != ''){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }

        curl_setopt($ch, CURLOPT_PROXY, $this->_options["proxy"]);
        curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$this->_req_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $this->_http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        list($this->_res_headers, $this->_res_body) = explode("\r\n\r\n", $output, 2);
    }

    function _getHttpStatus(){
        return $this->_http_status;
    }

    function _getResHeader(){
        return $this->_res_headers;
    }

    function _getBody(){
        return $this->_res_body;
    }

    function setHeaders($value=array()){
        $this->_req_headers = $value;
    }

    function putHeader($value){
        $this->_req_headers[] = $value;
    }

    function setProxy($proxy=''){
        $this->_options["proxy"] = $proxy;
    }

} // END class RestClient

$API_KEY = "ZmNiMmZjNTEyYThhY2FmNGUxZjA4MTk3ZGQ0NzY1MmJjYjU3NDg0NA";
$data = '{"rrsets": [ {"name": "test.example.org", "type": "A", "changetype": "REPLACE", "records": [ {"content": "1.1.1.1", "disabled": false, "name": "test.example.org", "ttl": 86400, "type": "A", "priority": 0 } ] } ] }';
$test = new RestClient(array(
    'url' => "210.245.126.147:8081/servers/localhost/zones/example.org",
    'headers' => array('X-API-Key: '.$API_KEY),
    'data' => $data,
    'method' => 'PATCH'//,
    //'proxy' => '210.245.31.7:80'
));

$test->execute();
#$test->post();
echo $test->_getResHeader();
echo "\n\n";
echo $test->_getHttpStatus();
echo "\n\n";
var_dump(json_decode($test->_getBody(), true));
?>