<?php
/**
 * RestClient class
 *
 * @package default
 * @author quangnx
 **/
class RestClient{

    var $_url,
        $_user_agent,
        $_req_headers,
        $_res_headers,
        $_res_body,
        $_http_status;

    var $_options = array(
            'url' => 'http://api.example.com',
            'proxy' => '',
            'method' => 'GET',
            'headers' => [],
            'agent' => 'PHP RestClient 1.0',
            'data' => ''
        );

    function __construct($options = array()) {
        if (is_string($options)) {
            $this->_url = $options;
        }else{
            foreach ($options as $key => $value) {
                $this->_options[$key] = $value;
            }
            $this->parseOptions($this->_options);
        }
    }

    protected function parseOptions($options){
        $this->_url = $options["url"];
        $this->setHeaders($options["headers"]);
        $this->_user_agent = $options["agent"];
    }

    function execute($value=''){
        $this->request($this->_options["method"],$value?$value:$this->_options['data']);
    }

    function get($value=''){
        $this->request();
    }

    function post($value=''){
        $this->request("POST", $value?$value:$this->_options['data']);
    }

    function put($value=''){
        $this->request("PUT", $value?$value:$this->_options['data']);
    }

    function delete($value=''){
        $this->request("DELETE", $value?$value:$this->_options['data']);
    }

    function options($value=''){
        $this->request("OPTIONS", $value?$value:$this->_options['data']);
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
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_user_agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $this->_http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        list($this->_res_headers, $this->_res_body) = explode("\r\n\r\n", $output, 2);
    }

    function getHttpStatus(){
        return $this->_http_status;
    }

    function getResHeaders(){
        return $this->_res_headers;
    }

    function getResBody(){
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

    function setURL($url){
        $this->_options["url"] = $url;
    }

    function setReqData($data){
        $this->_options["data"] = $data;
    }

    function setUserAgent($value){
        $this->_user_agent = $value;
    }

    function setMethod($method="GET"){
        $this->_options["method"] = $method;
    }

} // END class RestClient

?>