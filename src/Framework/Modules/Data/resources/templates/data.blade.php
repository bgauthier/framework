{!! $PHP_OPEN_TAG !!}
namespace {!! $NAMESPACE !!};

{!! $USE !!}

/**
 *	{!! $DESCRIPTION !!}
 * 	{!! $CLASSNAME !!}Data
 *
 *	LICENSE: This source file is subject to the LogikBuild license
 * 	that is available at the following URI: LICENSE.md.
 * 	If you did not receive a copy of
 * 	the LogikBuild License and are unable to obtain it through the web, please
 * 	send a note to support@intelogie.com so we can mail you a copy immediately.
 *
 *	@package 	LogikSuite
 * 	@author 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	Benoit Gauthier bgauthier@intelogie.com
 * 	@copyright 	INTELOGIE.COM INC.
 * 	@license 	LICENSE.md
 *  @abstract
 */
abstract class {!! $CLASSNAME !!}Data extends Model {
		
{!! $CONSTANTS !!}
	
{!! $VARIABLES !!}
		
	/**
	 *	Class constructor
	 */
	public function __construct() {
		
		parent::__construct();
		
		$this->setTable($this->MODEL_TABLE);			
		{!! $DATABASENAMESET !!}		
		{!! $SETOBJECTMODULE !!}
{!! $DATAFIELDS !!}		
		{!! $TRANSLATIONTABLE !!}			
	}
	
{!! $GETTERS_AND_SETTERS !!}
	
	public function beforeSave() {
{!! $BEFORESAVE !!}
		return TRUE;
	}
	
	/**
	 * After the main object is saved call this function
	 */
	public function afterSave() {
{!! $AFTERSAVE !!}
		return TRUE;
	}
	
	/**
	 *	Executes before the object is deleted
	 */
	public function beforeDelete() {
{!! $BEFOREDELETE !!}
		return TRUE;
	}
	
	/**
	 * V A L I D A T I O N S  
	 */
	 
{!! $VALIDATIONS !!}
	
	/**
	 * R E L A T I O N S H I P S  
	 */
	 
{!! $RELATIONSHIPCODE !!}	
	
}

{!! $PHP_CLOSE_TAG !!}