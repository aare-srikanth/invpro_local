<?php
defined('_JEXEC') or die( 'Restricted access' );

class JTableEmail extends JTable
{
	function __construct( &$_db )
	{
		parent::__construct( '#__angkor_emails', 'id', $_db);		
	}	
}
?>
