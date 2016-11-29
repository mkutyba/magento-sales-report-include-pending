<?php

class KutybaIt_SalesReportIncludePending_Block_Sales_Adminhtml_Report_Filter_Form extends Mage_Adminhtml_Block_Report_Filter_Form
{
    /**
     * Add fields to base fieldset which are general to sales reports
     *
     * @return Mage_Sales_Block_Adminhtml_Report_Filter_Form
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $form = $this->getForm();
        $htmlIdPrefix = $form->getHtmlIdPrefix();
        /** @var Varien_Data_Form_Element_Fieldset $fieldset */
        $fieldset = $this->getForm()->getElement('base_fieldset');

        if (is_object($fieldset) && $fieldset instanceof Varien_Data_Form_Element_Fieldset) {

            $statuses = Mage::getModel('sales/order_config')->getStatuses();
            $values = array();
            foreach ($statuses as $code => $label) {
                $values[] = array(
                    'label' => Mage::helper('reports')->__($label),
                    'value' => $code
                );
            }

            $fieldset->addField('show_order_statuses', 'select', array(
                'name'      => 'show_order_statuses',
                'label'     => Mage::helper('reports')->__('Order Status'),
                'options'   => array(
                        '0' => Mage::helper('reports')->__('Any'),
                        '1' => Mage::helper('reports')->__('Specified'),
                    ),
                'note'      => Mage::helper('reports')->__('Applies to Any of the Specified Order Statuses'),
            ), 'to');

            $fieldset->addField('order_statuses', 'multiselect', array(
                'name'      => 'order_statuses',
                'values'    => $values,
                'display'   => 'none'
            ), 'show_order_statuses');

            // define field dependencies
            if ($this->getFieldVisibility('show_order_statuses') && $this->getFieldVisibility('order_statuses')) {
                $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                    ->addFieldMap("{$htmlIdPrefix}show_order_statuses", 'show_order_statuses')
                    ->addFieldMap("{$htmlIdPrefix}order_statuses", 'order_statuses')
                    ->addFieldDependence('order_statuses', 'show_order_statuses', '1')
                );
            }
        }

        return $this;
    }
}