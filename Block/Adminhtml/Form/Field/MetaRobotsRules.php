<?php
declare(strict_types=1);

namespace DmiRud\SeoRobots\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class MetaRobotsRules extends AbstractFieldArray
{

    /**
     * @var BlockInterface[]
     */
    private array $renderers = [];

    /**
     * Prepare to render
     *
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(
            'url_path',
            [
                'label' => __('Url path'),
                'class' => 'required-entry validate-text validate-no-html-tags admin__control-text'
            ]
        );
        $this->addColumn(
            'content',
            [
                'label' => __('Content'),
                'renderer' => $this->getRenderer(MetaRobotsContent::class)
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        if ($urlPath = $row->getData('content')) {
            $options['option_' .  $this->getRenderer(MetaRobotsContent::class)->calcOptionHash($urlPath)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }


    /**
     * Provides renderer block
     *
     * @param string $class
     * @return AbstractBlock
     * @throws LocalizedException
     */
    protected function getRenderer(string $class): BlockInterface
    {
        if (!array_key_exists($class, $this->renderers)) {
            $this->renderers[$class] = $this->getLayout()->createBlock(
                $class,
                '',
                ['data' => ['value' => $this->getValue(), 'is_render_to_js_template' => true]]
            );
        }

        return $this->renderers[$class];
    }
}
