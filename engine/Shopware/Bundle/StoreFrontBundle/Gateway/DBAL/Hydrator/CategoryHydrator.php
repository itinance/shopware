<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Bundle\StoreFrontBundle\Gateway\DBAL\Hydrator;

use Shopware\Bundle\StoreFrontBundle\Struct;

/**
 * @category  Shopware
 * @package   Shopware\Bundle\StoreFrontBundle\Gateway\DBAL\Hydrator
 * @copyright Copyright (c) shopware AG (http://www.shopware.de)
 */
class CategoryHydrator extends Hydrator
{
    /**
     * @var AttributeHydrator
     */
    private $attributeHydrator;

    /**
     * @var MediaHydrator
     */
    private $mediaHydrator;

    /**
     * @param AttributeHydrator $attributeHydrator
     * @param MediaHydrator $mediaHydrator
     */
    public function __construct(
        AttributeHydrator $attributeHydrator,
        MediaHydrator $mediaHydrator
    ) {
        $this->attributeHydrator = $attributeHydrator;
        $this->mediaHydrator = $mediaHydrator;
    }

    /**
     * @param array $data
     * @return Struct\Category
     */
    public function hydrate(array $data)
    {
        $category = new Struct\Category();

        $this->assignCategoryData($category, $data);

        if ($data['__media_id']) {
            $category->setMedia(
                $this->mediaHydrator->hydrate($data)
            );
        }

        if ($data['__categoryAttribute_id']) {
            $attribute = $this->extractFields('__categoryAttribute_', $data);
            $category->addAttribute('core', $this->attributeHydrator->hydrate($attribute));
        }

        return $category;
    }

    /**
     * @param Struct\Category $category
     * @param array $data
     */
    private function assignCategoryData(Struct\Category $category, array $data)
    {
        if (isset($data['__category_id'])) {
            $category->setId((int) $data['__category_id']);
        }

        if (isset($data['__category_path'])) {
            $path = ltrim($data['__category_path'], '|');
            $path = rtrim($path, '|');

            $path = explode('|', $path);

            $category->setPath(array_reverse($path));
        }

        if (isset($data['__category_description'])) {
            $category->setName($data['__category_description']);
        }

        if (isset($data['__category_metakeywords'])) {
            $category->setMetaKeywords($data['__category_metakeywords']);
        }

        if (isset($data['__category_metadescription'])) {
            $category->setMetaDescription($data['__category_metadescription']);
        }

        if (isset($data['__category_cmsheadline'])) {
            $category->setCmsHeadline($data['__category_cmsheadline']);
        }

        if (isset($data['__category_cmstext'])) {
            $category->setCmsText($data['__category_cmstext']);
        }

        if (isset($data['__category_template'])) {
            $category->setTemplate($data['__category_template']);
        }

        if (isset($data['__category_noviewselect'])) {
            $category->setAllowViewSelect((bool) !$data['__category_noviewselect']);
        }

        if (isset($data['__category_blog'])) {
            $category->setBlog((bool) $data['__category_blog']);
        }

        if (isset($data['__category_showfiltergroups'])) {
            $category->setDisplayPropertySets((bool) $data['__category_showfiltergroups']);
        }

        if (isset($data['__category_external'])) {
            $category->setExternalLink($data['__category_external']);
        }

        if (isset($data['__category_hidefilter'])) {
            $category->setDisplayFacets((bool) !$data['__category_hidefilter']);
        }

        if (isset($data['__category_hidetop'])) {
            $category->setDisplayInNavigation((bool) !$data['__category_hidetop']);
        }

        if (isset($data['__category_customer_groups'])) {
            $category->setBlockedCustomerGroupIds(
                explode('|', $data['__category_customer_groups'])
            );
        }
    }

}