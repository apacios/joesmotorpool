<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class ap_homeimages extends Module
{
    const TABLE_IMAGES = _DB_PREFIX_ . 'images_homepage';

    const HOOK_LIST = [
        'header',
        'displayHome',
    ];

    public $cssPath;

    public function __construct()
    {
        $this->name = 'ap_homeimages';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'apacios';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->trans('Homepage Images', [], 'Modules.ApHomeimages.Admin');
        $this->description = $this->trans('Shows 3 images on homepage', [], 'Modules.ApHomeimages.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall this module?', [], 'Modules.ApHomeimages.Admin');
        $this->ps_versions_compliancy = [
            'min' => '1.7.0',
            'max' => _PS_VERSION_,
        ];

        $this->cssPath = $this->getPathUri() . '/views/assets/css';
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path. '/views/css/front.css');
    }

    public function hookDisplayHome()
    {
        $images = Db::getInstance()->executeS('SELECT * FROM ' . self::TABLE_IMAGES . ' ORDER BY position ASC');

        if (empty($images)) {
            return '';
        }

        $this->context->smarty->assign([
            'images' => $images,
        ]);

        return $this->context->smarty->fetch(
            'module:ap_homeimages/views/templates/hook/displayHome.tpl'
        );
    }

    public function install()
    {
        $query = ' CREATE TABLE IF NOT EXISTS ' . self::TABLE_IMAGES . ' (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            image VARCHAR(510) NOT NULL,
            cta_text int(10) unsigned NOT NULL,
            cta_link VARCHAR(510) NOT NULL,
            position int(10) unsigned NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        return parent::install() 
            && Db::getInstance()->execute($query)
            && $this->registerHook(self::HOOK_LIST)
        ;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}
