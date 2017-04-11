<?php

namespace yeesoft\multilingual\db;

/**
 * Multilingual attribute labels trait.
 * 
 * Modifies `getAttributeLabel` method to support multilingual attribute labels.
 */
trait MultilingualLabelsTrait
{

    /**
     * 
     * @inheritdoc
     */
    public function getAttributeLabel($attribute)
    {
        if ($this->isAttributeMultilingual($attribute)) {
            return $this->getMultilingualAttributeLabel($attribute);
        } else {
            return parent::getAttributeLabel($attribute);
        }
    }

}
