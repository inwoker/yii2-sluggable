<?php

namespace inwoker\translateable;

use Yii;
use yii\behaviors\SluggableBehavior as CoreSluggableBehavior;
use yii\helpers\Inflector;

class SluggableBehavior extends CoreSluggableBehavior
{
    public $attributeLanguage;

    /**
     * @param array $slugParts
     * @return string
     */
    protected function generateSlug($slugParts)
    {
        $language = $this->determineLanguage();

        // function to get list of transliteration ids
        // print_r(transliterator_list_ids());

        if ($language === 'uk') {
            Inflector::$transliterator = 'Ukrainian-Latin/BGN; Latin-ASCII; [\u0080-\uffff] remove';
        } elseif ($language === 'ru') {
            Inflector::$transliterator = 'Russian-Latin/BGN; Latin-ASCII; [\u0080-\uffff] remove';
        }

        return Inflector::slug(implode('-', $slugParts));
    }

    /**
     * @return string
     */
    private function determineLanguage()
    {
        if (empty($this->attributeLanguage)) {
            if (!empty($this->owner->language)) {
                //in case we have language property in model
                $language = $this->owner->language;
            }
        } elseif (!empty($this->attributeLanguage)) {
            //in case we want to define language in Behavior config
            $language = $this->attributeLanguage;
        }
        if (empty($language)) {
            //else - app language
            $language = Yii::$app->language;
        }

        return $language;
    }
}