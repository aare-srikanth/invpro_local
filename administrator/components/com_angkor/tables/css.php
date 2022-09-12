<?php
defined('_JEXEC') or die( 'Restricted access' );

class JTableCSS extends JTable
{
	function __construct( &$_db )
	{
		parent::__construct( '#__angkor_css', 'id', $_db);		
	}	
}