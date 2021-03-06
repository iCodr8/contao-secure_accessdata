<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2015 Leo Feyer
 *
 * @package    photoalbums2
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @license    LGPL
 * @copyright  Daniel Kiesel 2011-2015
 */

/**
 * Namespace
 */
namespace SecureAccessdata;

/**
 * Class SecureAccessdataRunonce
 *
 * @copyright  Daniel Kiesel 2011-2015
 * @author     Daniel Kiesel <daniel@craffft.de>
 * @package    secure-accessdata
 */
class SecureAccessdataRunonce extends \Controller
{
    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('Database');
    }

    /**
     * Execute all runonce files in module config directories
     */
    public function run()
    {
        $this->upgrade_to_1_1_0();
    }

    /**
     * upgrade_to_1_1_0 function.
     *
     * @access private
     * @return void
     */
    private function upgrade_to_1_1_0()
    {
        if ($this->Database->tableExists('tl_secure_accessdata')) {
            // Read fields decrypt them an save them
            $objData = \SecureAccessdataModel::findAll();

            if ($objData !== null) {
                while ($objData->next()) {
                    $arrSet = array();

                    // Set vars for update
                    $objData->access_title = (\Encryption::decrypt($objData->access_title) == '') ? $objData->access_title : \Encryption::decrypt($objData->access_title);
                    $objData->author = (\Encryption::decrypt($objData->author) == '') ? $objData->author : \Encryption::decrypt($objData->author);

                    // Save
                    $objData->save();
                }
            }
        }
    }
}

/**
 * Instantiate controller
 */
$objSecureAccessdataRunonce = new SecureAccessdataRunonce();
$objSecureAccessdataRunonce->run();
