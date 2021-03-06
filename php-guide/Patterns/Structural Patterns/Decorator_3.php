<?php

class RequestHelper{}

abstract class ProcessRequest {
    abstract function process( RequestHelper $req );
}

class MainProcess extends ProcessRequest {
    function process( RequestHelper $req ) {
        print __CLASS__.": doing something useful with request\n";
    }
}

/////////////////////////////// Декораторы /////////////////////////////////////
abstract class DecorateProcess extends ProcessRequest {
    protected $processrequest;
    function __construct( ProcessRequest $pr ) {
        $this->processrequest = $pr;
    }
}

class LogRequest extends DecorateProcess {
    function process( RequestHelper $req ) {
        print __CLASS__.": logging request<br>";
        $this->processrequest->process( $req );
    }
}

class AuthenticateRequest extends DecorateProcess {
    function process( RequestHelper $req ) {
        print __CLASS__.": authenticating request<br>";
        $this->processrequest->process( $req );
    }
}

class StructureRequest extends DecorateProcess {
    function process( RequestHelper $req ) {
        print __CLASS__.": structuring request data<br>";
        $this->processrequest->process( $req );
    }
}

///////////////////////////////// Client /////////////////////////////////////

// матрёшка объектов
$process = new AuthenticateRequest(new StructureRequest(new LogRequest (new MainProcess())));
// вызов метода объектов матрёшки
$process->process( new RequestHelper() );
?>
