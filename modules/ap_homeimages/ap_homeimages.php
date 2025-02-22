<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class ap_homeimages extends Module
{
    const TABLE_IMAGES = _DB_PREFIX_ . 'images_homepage';
    const TABLE_IMAGES_NO_PREFIX = 'images_homepage';

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
        $this->bootstrap = true;

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
        $imageList = Db::getInstance()->executeS('SELECT * FROM ' . self::TABLE_IMAGES . ' ORDER BY position ASC');

        if (empty($imageList)) {
            return '';
        }

        $this->context->smarty->assign([
            'imageList' => $imageList,
        ]);

        return $this->context->smarty->fetch(
            'module:ap_homeimages/views/templates/hook/displayHome.tpl'
        );
    }

    public function getContent()
    {
        if (Tools::getValue('action') === 'save') {
            $this->save();
        }

        if (Tools::getValue('action') === 'new') {
            return $this->new();
        }

        if (Tools::getValue('action') === 'edit') {
            return $this->edit();
        }

        if (Tools::getValue('action') === 'remove') {
            $this->delete();
        }

        $imageList = Db::getInstance()->executeS('SELECT * FROM ' . self::TABLE_IMAGES . ' ORDER BY position ASC');

        $this->context->smarty->assign([
            'controllerUrl' => $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name,
            'imageList' => $imageList,
        ]);

        return $this->context->smarty->fetch(
            'module:ap_homeimages/views/templates/admin/configure.tpl'
        );
    }

    public function install()
    {
        $query = ' CREATE TABLE IF NOT EXISTS ' . self::TABLE_IMAGES . ' (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            image VARCHAR(510) NOT NULL,
            cta_text VARCHAR(510) NOT NULL,
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

    private function delete()
    {
        $id = Tools::getValue('id');

        if (empty($id)) {
            return;
        }

        Db::getInstance()->delete(self::TABLE_IMAGES_NO_PREFIX, 'id = ' . $id);

        $this->context->controller->confirmations[] = $this->l('Image deleted successfully.', 'Admin.Homeimages.Success');
    }

    private function edit()
    {
        $this->context->smarty->assign([
            'controllerUrl' => $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name,
            'image' => Db::getInstance()->getRow('SELECT * FROM ' . self::TABLE_IMAGES . ' WHERE id = ' . Tools::getValue('id')),
        ]);

        return $this->context->smarty->fetch(
            'module:ap_homeimages/views/templates/admin/new.tpl'
        );
    }

    private function new()
    {
        $nextPosition = Db::getInstance()->getValue('SELECT MAX(position) + 1 FROM ' . self::TABLE_IMAGES);

        if (empty($nextPosition)) {
            $nextPosition = 1;
        }

        $this->context->smarty->assign([
            'controllerUrl' => $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name,
            'nextPosition' => $nextPosition,
        ]);

        return $this->context->smarty->fetch(
            'module:ap_homeimages/views/templates/admin/new.tpl'
        );
    }

    private function save()
    {
        $data = Tools::getValue('data');
        $saveImage = null;
        
        if (!empty($_FILES['data']['name']['image'])) {
            $saveImage = $this->saveImage($_FILES['data']);
        }

        if (false === $saveImage) {
            $this->context->controller->errors[] = $this->l('An error occurred while uploading the image.', 'Admin.Homeimages.Error');
        }

        if (false !== $saveImage) {
            $entity = Db::getInstance()->getValue('SELECT id FROM ' . self::TABLE_IMAGES . ' WHERE id = ' . $data['id']);

            if (null !== $saveImage) {
                $data['image'] = $saveImage;
            }

            if (empty($entity)) {
                Db::getInstance()->insert(self::TABLE_IMAGES_NO_PREFIX, $data);
            } else {
                Db::getInstance()->update(self::TABLE_IMAGES_NO_PREFIX, $data, 'id = ' . $data['id']);
            }
        }

        $this->context->controller->confirmations[] = $this->l('Image saved successfully.', 'Admin.Homeimages.Success');
    }

    private function saveImage($image)
    {
        $uploadDir = _PS_IMG_DIR_ . 'homepage_images/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true); 
        }

        $allowedTypes = ['image/webp', 'image/svg+xml', 'image/jpg', 'image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($image['type']['image'], $allowedTypes)) {
            return false;
        }

        $imagePath = $uploadDir . basename($image['name']['image']);

        if (@move_uploaded_file($image['tmp_name']['image'], $imagePath)) {
            return '/img/homepage_images/' . basename($image['name']['image']);
        } 

        return false;
    }
}
