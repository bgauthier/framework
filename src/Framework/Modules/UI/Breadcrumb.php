<?php 
namespace Framework\Modules\UI;

/**
 *
 *	LICENSE: This source file is subject to the LogikSuite Framework license
 * 	that is available at the following file: LICENSE.md
 * 	If you did not receive a copy of the LogikSuite Framework License and
 * 	are unable to obtain it through the web, please send a note to
 * 	support@intelogie.com so we can mail you a copy immediately.
 *
 *	@package 	LogikSuite
 * 	@author 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	INTELOGIE.COM INC.
 * 	@license 	LICENSE.md
 */
class Breadcrumb {

    protected $_sText = NULL;
    protected $_sClasses = NULL;
    protected $_sLink = NULL;

    public function __construct($sText = NULL, $sLink = NULL, $sClasses = NULL) {
        $this->setText($sText);
        $this->setLink($sLink);
        $this->setClasses($sClasses);
    }
    

    /**
     * Get the value of _sText
     */ 
    public function getText()
    {
        return $this->_sText;
    }

    /**
     * Set the value of _sText
     *
     * @return  self
     */ 
    public function setText($sText)
    {
        $this->_sText = $sText;
        return $this;
    }

    /**
     * Get the value of _sClasses
     */ 
    public function getClasses()
    {
        return $this->_sClasses;
    }

    /**
     * Set the value of Classes
     *
     * @return  self
     */ 
    public function setClasses($sClasses)
    {
        $this->_sClasses = $sClasses;
        return $this;
    }

    /**
     * Get the value of _sLink
     */ 
    public function getLink()
    {
        return $this->_sLink;
    }

    /**
     * Set the value of _sLink
     *
     * @return  self
     */ 
    public function setLink($sLink)
    {
        $this->_sLink = $sLink;

        return $this;
    }
}

?>