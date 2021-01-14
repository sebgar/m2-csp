<?php
namespace Sga\Csp\Block\Adminhtml\Config;

use Magento\Framework\DataObject;
use Sga\Csp\Block\Adminhtml\Form\Field\Policy as PolicyField;

class Rules extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    protected $_policyRenderer;

    public function _prepareToRender()
    {
        $this->addColumn(
            'policy',
            [
                'label' => __('Policy'),
                'renderer'  => $this->getPolicyRenderer(),
                'style' => 'width:150px',
            ]
        );
        $this->addColumn(
            'host',
            [
                'label' => __('Host'),
                'style' => 'width:300px',
            ]
        );
    }

    public function getArrayRows()
    {
        $this->_fixMissingColumn();
        return parent::getArrayRows();
    }

    protected function _fixMissingColumn()
    {
        $element = $this->getElement();
        $values = $element->getValue();
        if ($values && is_array($values)) {
            foreach ($values as $rowId => $row) {
                foreach ($this->getColumns() as $key => $column) {
                    if (!isset($row[$key])) {
                        $values[$rowId][$key] = '';
                    }
                }
            }
            $element->setValue($values);
        }
    }

    protected function getPolicyRenderer()
    {
        if (!$this->_policyRenderer) {
            $this->_policyRenderer = $this->getLayout()->createBlock(
                PolicyField::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->_policyRenderer;
    }

    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];

        $value = $row->getPolicy();
        if ($value) {
            $options['option_' . $this->getPolicyRenderer()->calcOptionHash($value)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}
