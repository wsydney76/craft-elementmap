<?php
/**
 * Element Map plugin for Craft 3.0
 *
 * @copyright Copyright Charlie Development
 */

namespace wsydney76\elementmap;

use Craft;
use craft\base\Element;
use craft\base\Plugin;
use craft\commerce\elements\Product;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use craft\events\RegisterElementTableAttributesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\SetElementTableAttributeHtmlEvent;
use craft\web\UrlManager;
use yii\base\Event;

/**
 * The main Craft plugin class.
 */
class ElementMap extends Plugin
{
    /**
     * @inheritdoc
     * @see craft\base\Plugin
     */
    public function init()
    {

        parent::init();

        $this->setComponents([
            'renderer' => \wsydney76\elementmap\services\Renderer::class,
        ]);

        // Set routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules['elementmap/<site:.*>/<class:.*>/<id:[\d]+>'] = 'elementmap/map/map';
        });

        // Render element maps within the appropriate template hooks.
        Craft::$app->getView()->hook('cp.entries.edit.meta', [$this, 'renderEntryElementMap']);
        Craft::$app->getView()->hook('cp.assets.edit.meta', [$this, 'renderAssetElementMap']);
        Craft::$app->getView()->hook('cp.categories.edit.meta', [$this, 'renderCategoryElementMap']);
        Craft::$app->getView()->hook('cp.users.edit.meta', [$this, 'renderUserElementMap']);
        Craft::$app->getView()->hook('cp.globals.edit.content', [$this, 'renderGlobalsElementMap']);
        Craft::$app->getView()->hook('cp.commerce.product.edit.details', [$this, 'renderProductElementMap']);

        // Allow some elements to have map data shown in their overview tables.
        Event::on(Asset::class, Element::EVENT_REGISTER_TABLE_ATTRIBUTES, [$this, 'registerTableAttributes']);
        Event::on(Asset::class, Element::EVENT_SET_TABLE_ATTRIBUTE_HTML, [$this, 'getTableAttributeHtml']);
        Event::on(Category::class, Element::EVENT_REGISTER_TABLE_ATTRIBUTES, [$this, 'registerTableAttributes']);
        Event::on(Category::class, Element::EVENT_SET_TABLE_ATTRIBUTE_HTML, [$this, 'getTableAttributeHtml']);
        Event::on(Entry::class, Element::EVENT_REGISTER_TABLE_ATTRIBUTES, [$this, 'registerTableAttributes']);
        Event::on(Entry::class, Element::EVENT_SET_TABLE_ATTRIBUTE_HTML, [$this, 'getTableAttributeHtml']);
        Event::on(User::class, Element::EVENT_REGISTER_TABLE_ATTRIBUTES, [$this, 'registerTableAttributes']);
        Event::on(User::class, Element::EVENT_SET_TABLE_ATTRIBUTE_HTML, [$this, 'getTableAttributeHtml']);
        Event::on(Product::class, Element::EVENT_REGISTER_TABLE_ATTRIBUTES, [$this, 'registerTableAttributes']);
        Event::on(Product::class, Element::EVENT_SET_TABLE_ATTRIBUTE_HTML, [$this, 'getTableAttributeHtml']);
    }

    /**
     * Handler for the Element::EVENT_REGISTER_TABLE_ATTRIBUTES event.
     */
    public function registerTableAttributes(RegisterElementTableAttributesEvent $event)
    {
        $event->tableAttributes['elementMap_incomingReferenceCount'] = ['label' => Craft::t('elementmap', 'References From (Count)')];
        $event->tableAttributes['elementMap_outgoingReferenceCount'] = ['label' => Craft::t('elementmap', 'References To (Count)')];
        $event->tableAttributes['elementMap_incomingReferences'] = ['label' => Craft::t('elementmap', 'References From')];
        $event->tableAttributes['elementMap_outgoingReferences'] = ['label' => Craft::t('elementmap', 'References To')];
    }

    /**
     * Handler for the Element::EVENT_SET_TABLE_ATTRIBUTE_HTML event.
     */
    public function getTableAttributeHtml(SetElementTableAttributeHtmlEvent $event)
    {
        if ($event->attribute == 'elementMap_incomingReferenceCount') {
            $event->handled = true;
            $entry = $event->sender;
            $elements = $this->renderer->getIncomingElements($entry, $entry->site->id);
            $event->html = Craft::$app->view->renderTemplate('elementmap/_table', ['elements' => count($elements)]);
        } else if ($event->attribute == 'elementMap_outgoingReferenceCount') {
            $event->handled = true;
            $entry = $event->sender;
            $elements = $this->renderer->getOutgoingElements($entry, $entry->site->id);
            $event->html = Craft::$app->view->renderTemplate('elementmap/_table', ['elements' => count($elements)]);
        } else if ($event->attribute == 'elementMap_incomingReferences') {
            $event->handled = true;
            $entry = $event->sender;
            $elements = $this->renderer->getIncomingElements($entry, $entry->site->id);
            $event->html = Craft::$app->view->renderTemplate('elementmap/_table', ['elements' => $elements]);
        } else if ($event->attribute == 'elementMap_outgoingReferences') {
            $event->handled = true;
            $entry = $event->sender;
            $elements = $this->renderer->getOutgoingElements($entry, $entry->site->id);
            $event->html = Craft::$app->view->renderTemplate('elementmap/_table', ['elements' => $elements]);
        }
    }

    /**
     * Renders the element map for an entry within the entry editor, given the current Twig context.
     *
     * @param array $context The incoming Twig context.
     */
    public function renderEntryElementMap(array &$context)
    {
        return $this->renderMap($context['entry'], 'entry');
    }

    /**
     * Renders an underlying incoming/outgoing element map.
     *
     * @param array $map The map data to render.
     */
    public function renderMap($element, $class)
    {
        return Craft::$app->view->renderTemplate('elementmap/_map', ['element' => $element, 'class' => $class]);
    }

    /**
     * Renders the element map for an entry within the entry editor, given the current Twig context.
     *
     * @param array $context The incoming Twig context.
     */
    public function renderAssetElementMap(array &$context)
    {
        return $this->renderMap($context['element'], 'asset');
    }

    /**
     * Renders the element map for a category within the category editor, given the current Twig context.
     *
     * @param array $context The incoming Twig context.
     */
    public function renderCategoryElementMap(array &$context)
    {
        return $this->renderMap($context['category'], 'category');
    }

    /**
     * Renders the element map for a user within the user editor, given the current Twig context.
     *
     * @param array $context The incoming Twig context.
     */
    public function renderUserElementMap(array &$context)
    {
        return $this->renderMap($context['user'], 'user');
    }

    public function renderGlobalsElementMap(array &$context)
    {
        return $this->renderMap($context['globalSet'], 'globalset');
    }

    /**
     * Renders the element map for a product within the product editor, given the current Twig context.
     *
     * @param array $context The incoming Twig context.
     */
    public function renderProductElementMap(array &$context)
    {
        return $this->renderMap($context['product'], 'product');
    }

}
