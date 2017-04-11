<?php

namespace yeesoft\multilingual\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\UnknownPropertyException;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\validators\Validator;
use yeesoft\multilingual\helpers\MultilingualHelper;

class MultilingualBehavior extends Behavior
{

    /**
     * List of multilingual attributes.
     * 
     * @var array
     */
    public $attributes;

    /**
     * Available languages. It can be a simple array ['en-US', 'es'] or an associative 
     * array ['en-US' => 'English', 'es' => 'Español'].
     * 
     * @var array
     */
    public $languages;

    /**
     * @var string the name of the translation table.
     */
    public $tableName;

    /**
     * @var string translation table name suffix. If `tableName` is not set
     * $this->owner->tableName() . $this->tableNameSuffix will be used as `tableName`.
     */
    public $tableNameSuffix = '_lang';

    /**
     * @var string the name of the lang field of the translation table. Default to 'language'.
     */
    public $languageField = 'language';

    /**
     * @var string the name of the foreign key field of the translation table related to base model table.
     */
    public $languageForeignKey = 'owner_id';

    /**
     * The name of translation model class. If this value is empty translation model 
     * class will be dynamically created using of the `eval()` function. No additional 
     * files are required.
     * 
     * @var string 
     */
    public $translationClassName;

    /**
     * @var string if $translationClassName is not set, it will be assumed that $translationClassName is
     * get_class($this->owner) . $this->translationClassNameSuffix
     */
    public $translationClassNameSuffix = 'Lang';

    /**
     * @var boolean if this property is set to true required validators will be applied to all translation models.
     * Default to false.
     */
    public $requireTranslations = true;

    /**
     * Multilingual attribute label pattern. Available variables: `{label}` - original 
     * attribute label, `{language}` - language name, `{code}` - language code.
     * 
     * @var string 
     */
    public $attributeLabelPattern = '{label} [{language}]';

    /**
     * @var string the prefix of the localized attributes in the language table. Here to avoid collisions in queries.
     * In the translation table, the columns corresponding to the localized attributes have to be name like this: 'prefix_[name of the attribute]'
     * and the id column (primary key) like this : 'prefix_id'
     * Default to ''.
     */
    public $localizedPrefix = '';

    /**
     * @var boolean whether to use force deletion of the associated translations when a base model is deleted.
     * Not needed if using foreign key with 'on delete cascade'.
     * Default to true.
     */
    public $forceDelete = true;

    /**
     * @var string current language.
     */
    protected $_currentLanguage;

    /**
     * @var array language keys.
     */
    private $_languageKeys;

    /**
     * @var string Owner model class name
     */
    private $_ownerClassName;

    /**
     * @var string owner model primary key
     */
    private $_ownerPrimaryKey;

    /**
     * @var array multilingual attributes
     */
    private $_multilingualAttributes = [];

    /**
     * @var array excluded validators
     */
    private $_excludedValidators = ['unique'];

    /**
     * @var array multilingual attribute labels
     */
    private $_attributeLabels;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        /* @var $owner ActiveRecord */
        parent::attach($owner);

        $this->_currentLanguage = Yii::$app->language;

        if (empty($this->attributes) || !is_array($this->attributes)) {
            throw new InvalidConfigException('Please specify multilingual attributes for the ' . get_class($this) . ' in the ' . get_class($this->owner));
        }

        $this->languages = MultilingualHelper::getLanguages($this->languages, $this);

        if (array_values($this->languages) !== $this->languages) { //associative array
            $this->_languageKeys = array_keys($this->languages);
        } else {
            $this->_languageKeys = $this->languages;
            $this->languages = array_combine($this->languages, $this->languages);
        }

        if (!$this->tableName) {
            $this->tableName = $this->generateTableName($owner->tableName(), $this->tableNameSuffix);
        }

        if (!$this->translationClassName) {
            $this->translationClassName = get_class($this->owner) . $this->translationClassNameSuffix;
        }

        $this->initTranslationClass();

        $this->_ownerClassName = get_class($this->owner);

        /* @var $className ActiveRecord */
        $className = $this->_ownerClassName;
        $this->_ownerPrimaryKey = $className::primaryKey()[0];

        if (!isset($this->languageForeignKey)) {
            throw new InvalidConfigException('Please specify languageForeignKey for the ' . get_class($this) . ' in the ' . get_class($this->owner));
        }

        $rules = $owner->rules();
        $validators = $owner->getValidators();

        foreach ($rules as $rule) {
            if (in_array($rule[1], $this->_excludedValidators)) {
                continue;
            }

            $ruleAttributes = is_array($rule[0]) ? $rule[0] : [$rule[0]];
            $attributes = array_intersect($this->attributes, $ruleAttributes);

            if (empty($attributes)) {
                continue;
            }

            $multilingualAttributes = [];
            foreach ($attributes as $key => $attribute) {
                foreach ($this->_languageKeys as $language)
                    $multilingualAttributes[] = $this->getAttributeName($attribute, $language);
            }

            if (isset($rule['skipOnEmpty']) && !$rule['skipOnEmpty'])
                $rule['skipOnEmpty'] = !$this->requireTranslations;

            $params = array_slice($rule, 2);

            if ($rule[1] !== 'required' || $this->requireTranslations) {
                $validators[] = Validator::createValidator($rule[1], $owner, $multilingualAttributes, $params);
            } elseif ($rule[1] === 'required') {
                $validators[] = Validator::createValidator('safe', $owner, $multilingualAttributes, $params);
            }
        }

        $translation = new $this->translationClassName;
        foreach ($this->_languageKeys as $language) {
            foreach ($this->attributes as $attribute) {
                $attributeName = $this->localizedPrefix . $attribute;
                $this->setMultilingualAttribute($this->getAttributeName($attribute, $language), $translation->{$attributeName});
            }
        }
    }

    /**
     * Generate translation table name.
     * 
     * @param ActiveRecord $ownerTableName
     * @param string $tableNameSuffix
     * @return string
     */
    public function generateTableName($ownerTableName, $tableNameSuffix)
    {
        if (preg_match('/{{%(\w+)}}$/', $ownerTableName, $matches)) {
            $ownerTableName = $matches[1];
        }

        return '{{%' . $ownerTableName . $tableNameSuffix . '}}';
    }

    /**
     * Relation to model translations
     * @return ActiveQuery
     */
    public function getTranslations()
    {
        return $this->owner->hasMany($this->translationClassName, [$this->languageForeignKey => $this->_ownerPrimaryKey]);
    }

    /**
     * Relation to model translation
     * @param $language
     * @return ActiveQuery
     */
    public function getTranslation($language = null)
    {
        $language = $language ?: $this->_currentLanguage;
        return $this->owner->hasOne($this->translationClassName, [$this->languageForeignKey => $this->_ownerPrimaryKey])
                        ->where([$this->languageField => $language]);
    }

    /**
     * Handle 'beforeValidate' event of the owner.
     */
    public function beforeValidate()
    {
        foreach ($this->attributes as $attribute) {
            $this->setMultilingualAttribute($this->getAttributeName($attribute, $this->_currentLanguage), $this->getMultilingualAttribute($attribute));
        }
    }

    /**
     * Handle 'afterFind' event of the owner.
     */
    public function afterFind()
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;

        if ($owner->isRelationPopulated('translations') && $related = $owner->getRelatedRecords()['translations']) {
            $translations = $this->indexByLanguage($related);
            foreach ($this->_languageKeys as $language) {
                foreach ($this->attributes as $attribute) {
                    foreach ($translations as $translation) {
                        if ($translation->{$this->languageField} == $language) {
                            $attributeName = $this->localizedPrefix . $attribute;
                            $this->setMultilingualAttribute($this->getAttributeName($attribute, $language), $translation->{$attributeName});
                        }
                    }
                }
            }
        } else {
            if (!$owner->isRelationPopulated('translation')) {
                $owner->translation;
            }

            $translation = $owner->getRelatedRecords()['translation'];
            if ($translation) {
                foreach ($this->attributes as $attribute) {
                    $attribute_name = $this->localizedPrefix . $attribute;
                    $owner->setMultilingualAttribute($attribute, $translation->$attribute_name);
                }
            }
        }

        foreach ($this->attributes as $attribute) {
            if ($owner->hasAttribute($attribute) && $this->getMultilingualAttribute($attribute)) {
                $owner->setAttribute($attribute, $this->getMultilingualAttribute($attribute));
            }
        }
    }

    /**
     * Handle 'afterInsert' event of the owner.
     */
    public function afterInsert()
    {
        $this->saveTranslations();
    }

    /**
     * Handle 'afterUpdate' event of the owner.
     */
    public function afterUpdate()
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;

        if ($owner->isRelationPopulated('translations')) {
            $translations = $this->indexByLanguage($owner->getRelatedRecords()['translations']);
            $this->saveTranslations($translations);
        }
    }

    /**
     * Handle 'afterDelete' event of the owner.
     */
    public function afterDelete()
    {
        if ($this->forceDelete) {
            /** @var ActiveRecord $owner */
            $owner = $this->owner;
            $owner->unlinkAll('translations', true);
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name) || $this->hasMultilingualAttribute($name);
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return $this->hasMultilingualAttribute($name);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (UnknownPropertyException $e) {
            if ($this->hasMultilingualAttribute($name)) {
                return $this->getMultilingualAttribute($name);
            }
            // @codeCoverageIgnoreStart
            else {
                throw $e;
            }
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (UnknownPropertyException $e) {
            if ($this->hasMultilingualAttribute($name)) {
                $this->setMultilingualAttribute($name, $value);
            }
            // @codeCoverageIgnoreStart
            else {
                throw $e;
            }
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function __isset($name)
    {
        if (!parent::__isset($name)) {
            return $this->hasMultilingualAttribute($name);
        } else {
            return true;
        }
    }

    /**
     * Whether is attribute multilingual.
     * 
     * @param string $attribute
     * @return bool
     */
    public function isAttributeMultilingual($attribute)
    {
        return array_key_exists($attribute, $this->_multilingualAttributes);
    }

    /**
     * Whether an attribute exists
     * @param string $name the name of the attribute
     * @return boolean
     */
    public function hasMultilingualAttribute($name)
    {
        return in_array($name, $this->attributes) || array_key_exists($name, $this->_multilingualAttributes);
    }

    /**
     * @param string $name the name of the attribute
     * @return string the attribute value
     */
    public function getMultilingualAttribute($name)
    {
        if (array_key_exists($name, $this->_multilingualAttributes)) {
            return $this->_multilingualAttributes[$name];
        } elseif (in_array($name, $this->attributes)) {
            $name = $this->getAttributeName($name, $this->_currentLanguage);
            return $this->_multilingualAttributes[$name];
        }
        return null;
    }

    /**
     * @param string $name the name of the attribute
     * @param string $value the value of the attribute
     */
    public function setMultilingualAttribute($name, $value)
    {
        if (in_array($name, $this->attributes)) {
            $name = $this->getAttributeName($name, $this->_currentLanguage);
        }

        $this->_multilingualAttributes[$name] = $value;
    }

    /**
     * Return multilingual attribute label.
     * 
     * @param string $attribute
     * @return string
     */
    public function getMultilingualAttributeLabel($attribute)
    {
        $attributeLabels = $this->getMultilingualAttributeLabels();
        return isset($attributeLabels[$attribute]) ? $attributeLabels[$attribute] : $attribute;
    }

    /**
     * @return array generate multilingual attribute labels.
     */
    protected function getMultilingualAttributeLabels()
    {
        if (!$this->_attributeLabels) {
            foreach ($this->attributes as $attribute) {
                foreach ($this->languages as $code => $language) {
                    $title = $this->owner->getAttributeLabel($attribute);
                    $attributeName = $this->getAttributeName($attribute, $code);

                    $this->_attributeLabels[$attributeName] = $this->generateAttributeLabel($title, $code, $language);
                }
            }
        }

        return $this->_attributeLabels;
    }

    protected function generateAttributeLabel($label, $code, $language)
    {
        return strtr($this->attributeLabelPattern, [
            '{code}' => $code,
            '{label}' => $label,
            '{language}' => $language,
        ]);
    }

    /**
     * Create dynamic translation model.
     */
    protected function initTranslationClass()
    {
        if (!class_exists($this->translationClassName)) {
            $namespace = substr($this->translationClassName, 0, strrpos($this->translationClassName, '\\'));
            $translationClassName = $this->getShortClassName($this->translationClassName);

            eval('
            namespace ' . $namespace . ';
            use yii\db\ActiveRecord;
            class ' . $translationClassName . ' extends ActiveRecord
            {
                public static function tableName()
                {
                    return \'' . $this->tableName . '\';
                }
            }');
        }
    }

    /**
     * @param array $translations
     */
    protected function saveTranslations($translations = [])
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;

        foreach ($this->_languageKeys as $language) {
            if (!isset($translations[$language])) {
                /** @var ActiveRecord $translation */
                $translation = new $this->translationClassName;
                $translation->{$this->languageField} = $language;
                $translation->{$this->languageForeignKey} = $owner->getPrimaryKey();
            } else {
                $translation = $translations[$language];
            }

            $save = false;
            foreach ($this->attributes as $attribute) {
                $value = $this->getMultilingualAttribute($this->getAttributeName($attribute, $language));

                if ($value !== null) {
                    $field = $this->localizedPrefix . $attribute;
                    $translation->$field = $value;
                    $save = true;
                }
            }

            if ($translation->isNewRecord && !$save) {
                continue;
            }

            $translation->save();
        }
    }

    /**
     * @param $records
     * @return array
     */
    protected function indexByLanguage($records)
    {
        $sorted = array();
        foreach ($records as $record) {
            $sorted[$record->{$this->languageField}] = $record;
        }
        unset($records);
        return $sorted;
    }

    /**
     * @param string $className
     * @return string
     */
    protected function getShortClassName($className)
    {
        return substr($className, strrpos($className, '\\') + 1);
    }

    /**
     * @param string $attribute
     * @param string $language
     * @return string
     */
    protected function getAttributeName($attribute, $language)
    {
        return MultilingualHelper::getAttributeName($attribute, $language);
    }

}
