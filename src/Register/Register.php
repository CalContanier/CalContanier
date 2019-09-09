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
    public function contact()
    {
        return $this->contact;
    }
    
    /**
     * @return RegisterBind
     */
    public function bind()
    {
        return $this->bind;
    }
    
    
}