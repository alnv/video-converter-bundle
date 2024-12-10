<?php

namespace Alnv\VideoConverterBundle\Backend;

use Contao\Database;
use Contao\FrontendTemplate;
use Contao\StringUtil;

class ConvertView extends \Backend
{

    protected function compile()
    {

        $strCurrentVideoConvertId = \Input::get('id');
        $objSettings = Database::getInstance()->prepare('SELECT * FROM tl_video_converting WHERE id=?')->limit(1)->execute($strCurrentVideoConvertId);

        if (!$objSettings->numRows) {
            return '';
        }

        $objTemplate = new FrontendTemplate('be_video_converter');
        $objTemplate->setData([
            'id' => $objSettings->id,
            'outputs' => StringUtil::deserialize($objSettings->outputs, true)
        ]);

        return $objTemplate->parse();
    }
}