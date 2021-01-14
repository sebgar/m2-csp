<?php
namespace Sga\Csp\Plugin;

use Magento\Csp\Api\Data\PolicyInterface;
use Magento\Csp\Model\CspRenderer as Subject;
use Magento\Framework\App\Response\HttpInterface as HttpResponse;
use Sga\Csp\Helper\Config as ConfigHelper;

class CspRenderer
{
    protected $_configHelper;

    public function __construct(
        ConfigHelper $configHelper
    ) {
        $this->_configHelper = $configHelper;
    }

    public function afterRender(
        Subject $subject,
        $result,
        HttpResponse $response
    ) {
        if ($this->_configHelper->isEnable()) {
            $list = [
                'Content-Security-Policy-Report-Only',
                'Content-Security-Policy',
            ];

            foreach ($list as $l) {
                $header = $response->getHeader($l);
                if ($header !== false) {
                    $headers = explode(';', $header->getFieldValue());

                    $rules = $this->_configHelper->getRules();
                    if (is_array($rules) && count($rules) > 0) {
                        foreach ($headers as $k => $headerStr) {
                            $headerParts = explode(' ', trim($headerStr));

                            foreach ($rules as $rule) {
                                if ($rule['policy'] === $headerParts[0]) {
                                    $headerParts[] = $rule['host'];
                                }
                            }
                            $headers[$k] = implode(' ', $headerParts);
                        }
                    }

                    $response->setHeader('Content-Security-Policy-Report-Only', implode('; ', $headers));
                }
            }
        }

        return $result;
    }
}
