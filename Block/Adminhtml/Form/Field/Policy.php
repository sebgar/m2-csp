<?php
namespace Sga\Csp\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Csp\Api\PolicyCollectorInterface;

class Policy extends Select
{
    protected $_collector;

    public function __construct(
        Context $context,
        PolicyCollectorInterface $collector,
        array $data = []
    ){
        $this->_collector = $collector;

        parent::__construct($context, $data);

        $this->loadOptions();
    }

    protected function _toHtml()
    {
        $column = $this->getColumn();
        if (isset($column['style']) && (string)$column['style'] !== '') {
            $this->setExtraParams('style="'.$column['style'].'"');
        }

        return parent::_toHtml();
    }

    protected function loadOptions()
    {
        $policies = $this->_collector->collect();
        foreach ($policies as $policy) {
            $this->addOption($policy->getId(), $policy->getId());
        }
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }
}
