<?php
declare(strict_types=1);

namespace DmiRud\SeoRobots\Plugin\Framework\View\Page\Config\Renderer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Config\Renderer;

/**
 * Overrides robots meta tag content's value
 */
class OverrideMetaRobotsContent
{
    /**
     * config path
     */
    private const XML_PATH_OVERRIDE_RULES = 'dmirud_seo/meta/robots_meta_override';

    /**
     * @var Config $pageConfig
     */
    private Config $pageConfig;

    /**
     * @var Http $request
     */
    private Http $request;

    /**
     * @var SerializerInterface $serializer
     */
    private SerializerInterface $serializer;

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param Config $pageConfig
     * @param Http $request
     * @param SerializerInterface $serializer
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Config               $pageConfig,
        Http                 $request,
        SerializerInterface  $serializer,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->pageConfig = $pageConfig;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Before Render function
     *
     * @param Renderer $subject
     */
    public function beforeRenderMetadata(Renderer $subject): void
    {
        foreach ($this->getRobotsOverrideRules() as $urlPath => $content) {
            if (str_starts_with($this->request->getOriginalPathInfo(), $urlPath)) {
                $this->pageConfig->setMetadata('robots', $content);
                break;
            }
        }
    }

    /**
     * Get robots override rules
     *
     * @return array
     */
    private function getRobotsOverrideRules(): array
    {
        $result = [];
        try {
            $value = $this->scopeConfig->getValue(self::XML_PATH_OVERRIDE_RULES);
            foreach ($this->serializer->unserialize($value) as $rule) {
                $result[$rule['url_path']] = $rule['content'];
            }
        } catch (\InvalidArgumentException) {
        }

        return $result;
    }
}