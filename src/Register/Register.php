<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer\Register;

use CalContainer\Contracts\AbsSingleton;

class Register extends AbsSingleton
{
    /**
     * @var RegisterBind
     */
    protected $bind;
    
    /**
     * @var RegisterContact
     */
    protected $contact;
    
    /**
     * delay to bind
     * @var array
     */
    protected $delay = [];
    
    // init
    protected function init()
    {
        $this->contact = new RegisterContact();
        $this->bind = new RegisterBind();
    }
    
    /**
     * @return RegisterContact
     */
    public static function contact()
    {
        return static::getInstance()->contact;
    }
    
    /**
     * @return RegisterBind
     */
    public static function bind()
    {
        return static::getInstance()->bind;
    }
    
    
}