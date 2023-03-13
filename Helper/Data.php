<?php

namespace Magestar\NewArrivals\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const CONFIG_SECTION = 'newproducts';

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
    ) {   
        parent::__construct($context); 
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->localeDate = $localeDate;
    }

    /**
     * Get settings
     *
     * @return string
     */
    public function getCfg($optionString)
    {
        return $this->scopeConfig->getValue(self::CONFIG_SECTION . '/' . $optionString, ScopeInterface::SCOPE_STORE);
    }

    public function isNewProduct($product){
        $newsFromDate = $product->getNewsFromDate();
        $newsToDate = $product->getNewsToDate();
        if (!$newsFromDate && !$newsToDate) {
            return false;
        }

        return $this->localeDate->isScopeDateInInterval(
            $product->getStore(),
            $newsFromDate,
            $newsToDate
        );
    }

}
