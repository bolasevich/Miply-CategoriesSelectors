MPower_CategoriesSelector Extension
=====================

Description
-----------
Ads dependable drop-downs to select following categories levels. 

Requirements
------------
- PHP >= 7.0
- Magento ~2.0
- jQuery ~1.5
- [Composer](https://getcomposer.org/)
- [magento-hackathon/magento-composer-installer](https://github.com/Cotya/magento-composer-installer.git)

Installation Instructions
-------------------------
1. Add _git_ repository address to your _composer.json_  

        {
            "type": "git",
            "url": "https://git.miply.no/miply_module-categories-selectors"
        }
1. Install the extension via Composer  
        $ composer require "miply/module-categories-selectors" -vvv
1. Enable the extension   
        $ bin/magento module:enable Miply_CategoriesSelectors
1. Enable the extension   
        $ bin/magento module:enable Miply_CategoriesSelectors
1. Add block to allowed blocks on frontend  
_System > Permissions > Blocks > Add Block_:  
    * **Block Name**: miply_categoriesselector/selectors  
    * **Allowed**:    Yes
1. Add block with drop-downs to:
    * layout of your choice, e.g. _cms_index_index.xml_
      `<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" layout="1column" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">
          <body>
              <referenceContainer name="content">
                  <block class="Miply\CategoriesSelectors\Block\Selectors" name="miply_categoriesselector.selectors" template="Miply_CategoriesSelectors::selectors.phtml" />
              </referenceContainer>
          </body>
      </page>`
    * your custom layout using one of predefined handles:  
        1. `<update handle="miply_categoriesselector_selectors_root" />`
        1. `<update handle="miply_categoriesselector_selectors_content" />`
    * ...many other ways
1. Add number of drop-downs to display to configuration (table _core_config_data_, default value: 3)
    * _path_: `miply_categoriesselector/categories/levels`, e.g. _value_: "5"
1. Add default text for each drop-down to configuration (table _core_config_data_)
    * _path_: `miply_categoriesselector/drop_down/default_text_0`, e.g. _value_: "Select Make"
    * _path_: `miply_categoriesselector/drop_down/default_text_1`, e.g. _value_: "Select Model"
    * _path_: `miply_categoriesselector/drop_down/default_text_2`, e.g. _value_: "Select Year"
    * ...
    * _path_: `miply_categoriesselector/drop_down/default_text_x`, e.g. _value_: "Select X"
1. Clear the cache, logout from the admin panel and then login again.


Support
-------
If you have any issues with this extension, notify author. 


Developer
---------

Miply Patryk Makowski  
[https://miply.no](https://miply.no)  
[magento@miply.no](magento@miply.no)
 
[GitHub](github.com/p-makowski)  
[LinkedIn](https://www.linkedin.com/in/patryk-makowski)

Licence
-------
See LICENSE.md

Copyright
---------
(c) 2018 Miply Patryk Makowski [https://miply.no](https://miply.no)
