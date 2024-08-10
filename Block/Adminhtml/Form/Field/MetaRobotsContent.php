<?php
declare(strict_types=1);

namespace DmiRud\SeoRobots\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

class MetaRobotsContent extends Select
{
    /**
     * All content combination values
     */
    public const META_ROBOTS_CONTENT_OPTIONS = [
        'INDEX, FOLLOW',
        'NOINDEX, FOLLOW',
        'INDEX, NOFOLLOW',
        'NOINDEX, NOFOLLOW'
    ];

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {

            foreach (self::META_ROBOTS_CONTENT_OPTIONS as $option) {
                $this->addOption($option, __($option));
            }
        }

        return parent::_toHtml();
    }
}