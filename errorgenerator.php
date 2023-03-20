<?php
/**
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}
class ErrorGenerator extends Module
{
    public function __construct()
    {
        $this->name = 'errorgenerator';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Fabien PrestaShop';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = false;

        parent::__construct();

        $this->displayName = $this->l('Error Generator');
        $this->description = $this->l('Generate errors within your backoffice');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() &&
            $this->installTabs() &&
            $this->registerHook('displayBackOfficeHeader');
    }

    private function installTabs()
    {
        foreach ([
             [
                 'class_name' => 'AdminErrorGeneratorController',
                 'route_name' => 'errorgenerator_throw_error',
                 'name' => 'Générer 500',
                 'icon' => 'dangerous',
                 'parent' => 'CONFIGURE',
                 'position' => 1,
                 'active' => true,
                 'enabled' => true,
             ],
             [
                 'class_name' => 'AdminErrorGeneratorController',
                 'route_name' => 'errorgenerator_throw_twig_error',
                 'name' => 'Générer 500 twig',
                 'icon' => 'dangerous',
                 'parent' => 'CONFIGURE',
                 'position' => 2,
                 'active' => true,
                 'enabled' => true,
             ]
         ] as $tabItem) {
            $tab = new Tab();
            $tab->id_parent = $tabItem['parent'] ? Tab::getIdFromClassName($tabItem['parent']) : 0;
            $tab->module = $this->name;
            $tab->class_name = $tabItem['class_name'];
            $tab->route_name = $tabItem['route_name'];
            $tab->icon = $tabItem['icon'];
            $tab->active = $tabItem['active'];
            $tab->enabled = $tabItem['enabled'];
            foreach (Language::getLanguages() as $lang) {
                $tab->name[$lang['id_lang']] = $tabItem['name'];
            }
            $tab->add();

            if (isset($tabItem['position'])) {
                $tab->updatePosition(false, $tabItem['position']);
            }
        }

        return true;
    }
    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('die')) {
            throw new Exception('Exception thrown by hookDisplayBackOfficeHeader 💀');
        }
    }
}
