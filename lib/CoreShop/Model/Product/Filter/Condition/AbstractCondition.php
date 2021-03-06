<?php
/**
 * CoreShop.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Model\Product\Filter\Condition;

use CoreShop\Model\Product\Filter;
use CoreShop\Model\Product\Listing;
use Pimcore\View;

/**
 * Class AbstractCondition
 * @package CoreShop\Model\Product\Filter\Condition
 */
abstract class AbstractCondition
{
    /**
     * @var string
     */
    public static $type = '';

    /**
     * @var mixed
     */
    public $field;

    /**
     * @var string
     */
    public $label;

    /**
     * @var mixed
     */
    public $preSelect;

    /**
     * @var string
     */
    public $quantityUnit;

    /**
     *  @var View
     */
    protected $view;

    /**
     * @return string
     */
    public static function getType()
    {
        return static::$type;
    }

    /**
     * @param $language
     *
     * @return View
     */
    public function getView($language = null)
    {
        if (!$language) {
            $language = \CoreShop::getTools()->getLocale();
        }

        if (!$this->view) {
            $this->view = new View();
        }

        $this->view->language = (string) $language;
        $this->view->brick = $this;

        $class = get_class($this);
        $class = explode('\\', $class);
        $class = array_pop($class);

        $this->view->setScriptPath(
            [
                CORESHOP_TEMPLATE_BASE.'/scripts/coreshop/product/filter',
                CORESHOP_TEMPLATE_BASE.'/scripts/coreshop/product/filter/'.strtolower($class),
                CORESHOP_TEMPLATE_PATH.'/scripts/coreshop/product/filter',
                CORESHOP_TEMPLATE_PATH.'/scripts/coreshop/product/filter/'.strtolower($class),
                PIMCORE_WEBSITE_PATH.'/views/scripts/coreshop/' . strtolower($class),
            ]
        );

        return $this->view;
    }

    /**
     * add Condition to Productlist.
     *
     * @param Filter  $filter
     * @param Listing $list
     * @param $currentFilter
     * @param $params
     * @param bool $isPrecondition
     *
     * @return array $currentFilter
     */
    abstract public function addCondition(Filter $filter, Listing $list, $currentFilter, $params, $isPrecondition = false);

    /**
     * render HTML for filter.
     *
     * @param Filter  $filter
     * @param Listing $list
     * @param $currentFilter
     *
     * @return mixed
     */
    public function render(Filter $filter, Listing $list, $currentFilter)
    {
        $rawValues = $list->getGroupByValues($this->getField(), true);
        $script = $this->getViewScript($filter, $list, $currentFilter);

        return $this->getView()->partial($script, [
            'label' => $this->getLabel(),
            'currentValue' => $currentFilter[$this->getField()],
            'values' => array_values($rawValues),
            'fieldname' => $this->getField(),
            'quantityUnit' => $this->getQuantityUnit()
        ]);
    }

    /**
     * @param Filter  $filter
     * @param Listing $list
     * @param $currentFilter
     *
     * @return string
     */
    protected function getViewScript(Filter $filter, Listing $list, $currentFilter)
    {
        $script = static::getType().'.php';

        if ($this->getView()->getScriptPath($this->getField().'.php')) {
            $script = $this->getField().'.php';
        }

        return $script;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values)
    {
        foreach ($values as $key => $value) {
            if ($key == 'type') {
                continue;
            }

            $setter = 'set'.ucfirst($key);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getPreSelect()
    {
        return $this->preSelect;
    }

    /**
     * @param mixed $preSelect
     */
    public function setPreSelect($preSelect)
    {
        $this->preSelect = $preSelect;
    }

    /**
     * @return string
     */
    public function getQuantityUnit()
    {
        return $this->quantityUnit;
    }

    /**
     * @param string $quantityUnit
     */
    public function setQuantityUnit($quantityUnit)
    {
        $this->quantityUnit = $quantityUnit;
    }
}
