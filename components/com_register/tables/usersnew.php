<?php
/**
 * @version		$Id: message.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.database.table');

class TableUsersnew extends JTable
{
	
	/**
	 * Primary Key
	 * id
	 *
	 * @access	public
	 * @var		int
	 */
	var $id	= 0;	
	
	/**
	 * name
	 *
	 * @access	public
	 * @var		varchar
	 */
	var $name	= null;

	/**
	 * username
	 *
	 * @access	public
	 * @var		varchar
	 */
	var $username		= null;

	/**
	 * email
	 * 
	 * @access	public
	 * @var		varchar
	 */
	var $email			= null;

	/**
	 * password
	 *
	 * @access	public
	 * @var		varchar
	 */
	var $password		= null;

	/**
	 * usertype
	 *
	 * @access	public
	 * @var		varchar
	 	var $usertype				= null;

	 */

	/**
	 * block
	 *
	 * @access	public
	 * @var		tinyint
	 */
	var $block			= null;

	/**
	 * The message sendEmail
	 *
	 * @access	public
	 * @var		tinyint
	 */
	var $sendEmail			= null;

   
	/**
	 * The registerDate
	 *
	 * @access	public
	 * @var		datetime
	 */
	var $registerDate			= null;
    
	/**
	 * The lastvisitDate
	 *
	 * @access	public
	 * @var		datetime
	 */
	var $lastvisitDate			= null;
	
	/**
	 * The activation
	 *
	 * @access	public
	 * @var		varchar
	 */
	var $activation			= null;
	
	/**
	 * The params
	 *
	 * @access	public
	 * @var		text
	 */
	var $params			= null;
	

	/**
	 * The lastResetTime
	 *
	 * @access	public
	 * @var		lastResetTime
	 */
	var $lastResetTime = null;
	
	/**
	 * The resetCount
	 *
	 * @access	public
	 * @var		resetCount
	 */
	var $resetCount = null;  
	 
	/**
	 * The resetCount
	 *
	 * @access	public
	 * @var		resetCount
	 */
	var $webserviceid = 0;  


	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param database A database connector object
	 */
	function __construct(& $db)
	{
		parent::__construct('#__users', 'id', $db);
	}

	

	
}
