<?php namespace Cores;

class Exception extends \Exception{
    private $customMess = '';
    private $mess = '';

    public function __construct($message, $code = 0, Exception $previous = null, $customMess = '')
    {
        $this->customMess = $customMess;
        $this->mess = $message;
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function detailMessage($message)
    {
        return '<h1>' . $message . '</h1><p>Code ' . $this->getCode() . ': ' . $this->customMess . '</p>';
    }

    public function getStyle()
    {
        return '<style type="text/css">a,h1{background-color:transparent;font-weight:400}#container,code{border:1px solid #D0D0D0}::selection{background-color:#E13300;color:#fff}::-moz-selection{background-color:#E13300;color:#fff}::-webkit-selection{background-color:#E13300;color:#fff}body{background-color:#fff;margin:40px;font:13px/20px normal Helvetica,Arial,sans-serif;color:#4F5155}a{color:#039}h1{color:#444;border-bottom:1px solid #D0D0D0;font-size:19px;margin:0 0 14px;padding:14px 15px 10px}code{font-family:Consolas,Monaco,Courier New,Courier,monospace;font-size:12px;background-color:#f9f9f9;color:#002166;display:block;margin:14px 0;padding:12px 10px}#container{margin:10px;box-shadow:0 0 8px #D0D0D0}p{margin:12px 15px}</style>';
    }

    public function getErrorPage()
    {
        http_response_code(400);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>' . $this->mess . '</title></head>';
        echo $this->getStyle();
        echo '<div id="container">';
        echo $this->detailMessage($this->mess);
        echo '<p>Line ' . $this->getLine() . ': ' . $this->getFile() . '</p>';
        echo '</div>';
        echo '</html>';
    }

    public static function routeNotFound(string $route) {
        try {
            throw new Exception("Không tìm thấy Route!",
                100,
                null,
                "Vui lòng kiểm tra lại Route \" <strong style='color: red'>$route</strong> \" và phương thức <strong style='color: red'>{$_SERVER['REQUEST_METHOD']}</strong> có được sử dụng không?");
        } catch (Exception $ex) {
            $ex->getErrorPage();
        }
    }
    
    
}