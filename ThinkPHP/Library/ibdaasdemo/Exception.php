<?php
class Yunfeng_Exception extends Exception{
    /**
     * Yunfeng_Exception constructor
     *
     * @param string $code
     *            service error code.
     * @param string $message
     *            detailed information for the exception.
     */
    public function __construct($code, $message) {
        parent::__construct($message);
        $this->code = $code;
        $this->message = $message;
    }
    
    /**
     * The __toString() method allows a class to decide how it will react when
     * it is treated like a string.
     *
     * @return string
     */
    public function __toString() {
        return json_encode(array(
            "code" => $this->code,
            "message" => $this->message
        ),320);
    }
    
    /**
     * Get Yunfeng_Exception error code.
     *
     * @return string
     */
    public function getErrorCode() {
        return $this->code;
    }
    
    /**
     * Get Yunfeng_Exception error message.
     *
     * @return string
     */
    public function getErrorMessage() {
        return $this->message;
    }

}

