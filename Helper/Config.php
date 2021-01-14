<?php
namespace Sga\Csp\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XML_PATH_ENABLE = 'sga_csp/general/enable';
    const XML_PATH_RULES = 'sga_csp/general/rules';

    public function isEnable($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getRules($store = null)
    {
        $str = $this->scopeConfig->getValue(
            self::XML_PATH_RULES,
            ScopeInterface::SCOPE_STORE,
            $store
        );
        return json_decode($str, true);
    }
}
