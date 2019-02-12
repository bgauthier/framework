<?php 
namespace Framework\Modules\UI;

interface IJavascriptEvents {
	
	const EVENT_ONCLICK = "onClick";
	const EVENT_ONBLUR = "onBlur";
	const EVENT_ONCHANGE = "onChange";
	const EVENT_ONSELECT = "onSelect";
	const EVENT_ONDBLCLICK = "onDblClick";
	const EVENT_ONFOCUS = "onFocus";
	const EVENT_ONFOCUSIN = "onFocusIn";
	const EVENT_ONFOCUSOUT = "onFocusOut";
	const EVENT_ONHOVER = "onHover";
	const EVENT_ONKEYDOWN = "onKeyDown";
	const EVENT_ONKEYPRESS = "onKeyPress";
	const EVENT_ONKEYUP = "onKeyUp";
	const EVENT_ONMOUSEDOWN = "onMouseDown";
	const EVENT_ONMOUSEENTER = "onMouseEnter";
	const EVENT_ONMOUSELEAVE = "onMouseLeave";
	const EVENT_ONMOUSEMOVE = "onMouseMove";
	const EVENT_ONMOUSEOUT = "onMouseOut";
	const EVENT_ONMOUSEUP = "onMouseUp";
	const EVENT_ONRESIZE = "onResize";
	const EVENT_ONSCROLL = "onScroll";
	const EVENT_ONSUBMIT = "onSubmit";
	const EVENT_ONUNLOAD = "onUnload";
	const EVENT_ONLOAD = "onLoad";
	const EVENT_ONBODYCLICK = "onBodyClick";
	
		
	/**
	 * Returns onClick event javascript code
	 */
	public function getOnClick();
	public function setOnClick($sJsCode, $bRenderEventInTag = FALSE);
	/**
	 * Returns onBlur event javascrip code
	 */
	public function getOnBlur();
	public function setOnBlur($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * Returns onChange event javascript code
	 */
	public function getOnChange();
	public function setOnChange($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnSelect();
	public function setOnSelect($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnDblClick();
	public function setOnDblClick($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnFocus();
	public function setOnFocus($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnFocusIn();
	public function setOnFocusIn($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnFocusOut();
	public function setOnFocusOut($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnHover();
	public function setOnHover($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnKeyDown();
	public function setOnKeyDown($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnKeyPress();
	public function setOnKeyPress($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnKeyUp();
	public function setOnKeyUp($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnMouseDown();
	public function setOnMouseDown($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnMouseEnter();
	public function setOnMouseEnter($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnMouseLeave();
	public function setOnMouseLeave($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnMouseMove();
	public function setOnMouseMove($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnMouseOut();
	public function setOnMouseOut($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnMouseUp();
	public function setOnMouseUp($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnResize();
	public function setOnResize($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnScroll();
	public function setOnScroll($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnSubmit();
	public function setOnSubmit($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnUnload();
	public function setOnUnload($sJSCode, $bRenderEventInTag = FALSE);
	/**
	 * 
	 */
	public function getOnLoad();
	public function setOnLoad($sJSCode, $bRenderEventInTag = FALSE);
	
	
	/**
	 * Returns event based on event name
	 * @param unknown $sEventName
	 */
	public function getEventCode($sEventName);
	/**
	 * Sets onClick event Javascript code
	 * @param unknown $sJsCode
	 */
	public function setEventCode($sEventName, $sJSCode, $bRenderEventInTag = FALSE);
	
}

?>